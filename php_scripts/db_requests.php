<?php

require_once __DIR__ . '/../php_includes/constants.php';

class DatabaseRequests
{
    private $bdd;

    public function __construct($bdd)
    {
        $this->bdd = $bdd;
    }





    
    /* PLAYER REQUESTS */

    // Get all players
    public function getAllPlayers()
    {
        $query = "SELECT * FROM player ORDER BY name_player";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get specific players
    public function getPlayer(int $idPlayer)
    {
        $query = "SELECT * FROM player WHERE id_player = ?";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute([$idPlayer]);
        return $stmt->fetch();
    }

     // Get player country
     public function getPlayerCountry(int $idPlayer)
     {
         $query = "SELECT country FROM player WHERE id_player = ?";
         $stmt = $this->bdd->prepare($query);
         $stmt->execute([$idPlayer]);
         return $stmt->fetchColumn();
     }
 
     // Get player number of records
     public function getPlayerIdsRecords(int $idPlayer)
     {
         $query = "SELECT id_record FROM record_with_players WHERE id_player = ?";
         $stmt = $this->bdd->prepare($query);
         $stmt->execute([$idPlayer]);
         return $stmt->fetchAll(PDO::FETCH_COLUMN);
     }
 
     // Get number of collabs form an array of records
     public function getNbrCollabsFromRecords(array $idsRecords)
     {
         $query = "SELECT
                     COUNT(sub.nbr_player)
                 FROM
                     (
                     SELECT
                         COUNT(id_player) AS 'nbr_player'
                     FROM
                         record_with_players
                     WHERE
                         id_record IN(" . implode(',', $idsRecords) . ")
                     GROUP BY
                         id_record
                 ) sub
                 WHERE
                     sub.nbr_player > 1;";
         $stmt = $this->bdd->prepare($query);
         $stmt->execute();
         return $stmt->fetchColumn();
     }
 
     // Get year's first record from player
     public function getPlayerFirstRecordYear(int $idPlayer)
     {
         $query = "SELECT
                     MIN(YEAR(r.date_record)) AS 'year'
                 FROM
                     record r
                 JOIN record_with_players rwp ON
                     r.id_record = rwp.id_record
                 WHERE
                     id_player = ?;";
         $stmt = $this->bdd->prepare($query);
         $stmt->execute([$idPlayer]);
         return $stmt->fetchColumn();
     }
 
     // Get last 3 tracks from player
     public function getPlayerLastTracks(int $idPlayer)
     {
         $query = "SELECT
                     r.id_track
                 FROM
                     record r
                 JOIN record_with_players rwp ON
                     r.id_record = rwp.id_record
                 WHERE
                     rwp.id_player = ?
                 ORDER BY
                     r.date_record DESC
                 LIMIT 3;";
         $stmt = $this->bdd->prepare($query);
         $stmt->execute([$idPlayer]);
         return $stmt->fetchAll(PDO::FETCH_COLUMN);
     }





    /* GAME REQUESTS */

    // Verify if at least one game exists
    public function getNbrOfGames()
    {
        $query = "SELECT COUNT(*) FROM `game_details`";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Verify number of current games
    public function getNbrOfCurrentGames()
    {
        $query = "SELECT COUNT(*) FROM `game_details` gd
            JOIN game_status gs ON gd.id_status = gs.id_status
            WHERE gs.name = 'current'";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Get current game
    public function getCurrentGame()
    {
        $query = "SELECT gd.* FROM `game_details` gd
            JOIN game_status gs ON gd.id_status = gs.id_status
            WHERE gs.name = 'current'";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Get last X games
    public function getLastXPlayers()
    {
        $query = "SELECT
            sub.id_player
        FROM
            (
            SELECT
                gd.*
            FROM
                game_details gd
            JOIN game_status gs ON
                gd.id_status = gs.id_status
            WHERE
                gs.name = 'passed' OR
                gs.name = 'current'
            ORDER BY
                id_game
            DESC
        LIMIT ?
        ) AS sub";

        $stmt = $this->bdd->prepare($query);
        $stmt->execute([NBR_PLAYERS_DRAW]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Get next game
    // Next game if current game is found
    // First future game if no current game is found (first game ever)
    public function getNextGame()
    {
        $query = "SELECT
                    *
                FROM
                    game_details
                WHERE
                    id_game =(
                    SELECT
                        IFNULL(
                            (
                            SELECT
                                id_game
                            FROM
                                `game_details`
                            WHERE
                                id_game >(
                                SELECT
                                    gd.id_game
                                FROM
                                    `game_details` gd
                                JOIN game_status gs ON
                                    gd.id_status = gs.id_status
                                WHERE
                                    gs.name = 'current'
                            )
                        ORDER BY
                            id_game ASC
                        LIMIT 1
                        ),
                        (
                        SELECT
                            gd.id_game
                        FROM
                            game_details gd
                        JOIN game_status gs ON
                            gd.id_status = gs.id_status
                        WHERE
                            gs.name = 'future'
                        ORDER BY
                            gd.id_game ASC
                        LIMIT 1
                    )
                        )
                );";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }


    // Insert new future games after draw
    public function insertNextGames(array $idsPlayers)
    {
        $query = "INSERT INTO `game_details` (id_player, id_status) VALUES ";
        $query .= implode(',', array_map(function ($idPlayer, int $idStatus = Status::FUTURE->value) {
            return "($idPlayer, $idStatus)";
        }, $idsPlayers));
        $stmt = $this->bdd->prepare($query);
        $stmt->execute();
    }

    // Move current cursor to next game and add date of game
    public function goNextGame(int $idNextGame)
    {
        try {
            $this->bdd->beginTransaction();
            $queryAddPassedStatus = "UPDATE `game_details` gd
                JOIN game_status gs ON gd.id_status = gs.id_status
                SET gd.id_status = ?
                WHERE gs.name = 'current'";
            $stmt = $this->bdd->prepare($queryAddPassedStatus);
            $stmt->execute([Status::PASSED->value]);

            $queryAddCurrentStatus = "UPDATE `game_details` SET id_status = ?, `date` = CURDATE() WHERE id_game = ?";
            $stmt = $this->bdd->prepare($queryAddCurrentStatus);
            $stmt->execute([Status::CURRENT->value, $idNextGame]);
            $this->bdd->commit();
        } catch (Exception $e) {
            $this->bdd->rollBack();
            throw $e;
        }
    }





    /* METHODS IF WE STORE PLAYERS DETAILS IN GAME_DETAILS TABLE */

    // Insert next player details TODO ;)
    public function insertNextPlayerDetails(array $player)
    {
        $query = "INSERT INTO game_details(
                    `date`,
                    player_name,
                    player_country,
                    player_nbr_record,
                    player_nbr_collabs,
                    player_first_record_year,
                    player_last_tracks
                )
                VALUES(CURDATE(), ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
