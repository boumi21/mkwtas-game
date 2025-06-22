<?php

// All Database requests

require_once dirname(__DIR__, 1) . '/php_includes/utils.php';

class DatabaseRequests
{
    private $bdd;

    public function __construct($bdd)
    {
        $this->bdd = $bdd;
    }


    /* PLAYER REQUESTS */

    // Get all players with at least one record
    public function getAllPlayers()
    {
        $query = "SELECT DISTINCT p.* FROM player p
                    LEFT JOIN record_with_players rwp ON p.id_player = rwp.id_player
                    WHERE rwp.id_record is not null
                    ORDER BY p.name_player";
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
        $query = "SELECT country AS 'country' FROM player WHERE id_player = ?";
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


    // GetTracksFromIds
    public function getTracksFromIds(array $idsTracks)
    {
        $query = "SELECT t.* FROM (" .
            implode(" UNION ALL ", array_map(function ($id) {
                return "SELECT * FROM track WHERE id_track = $id";
            }, $idsTracks)) .
            ") t";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
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
        $query .= implode(',', array_map(function ($idPlayer, int $idStatus = GameStatus::FUTURE->value) {
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
            $stmt->execute([GameStatus::PASSED->value]);

            $queryAddCurrentStatus = "UPDATE `game_details` SET id_status = ?, `date` = CURDATE() WHERE id_game = ?";
            $stmt = $this->bdd->prepare($queryAddCurrentStatus);
            $stmt->execute([GameStatus::CURRENT->value, $idNextGame]);
            $this->bdd->commit();
        } catch (Exception $e) {
            $this->bdd->rollBack();
            throw $e;
        }
    }

    // Add win to win history
    public function addWinToHistory(int $nbrTries)
    {
        $query = "INSERT INTO game_win_history(id_game, nbr_tries, win_date)
                    VALUES(
                        (
                        SELECT
                            gd.id_game
                        FROM
                            `game_details` gd
                        JOIN game_status gs ON
                            gd.id_status = gs.id_status
                        WHERE
                            gs.name = 'current'
                    ),
                    ?,
                    NOW());";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute([$nbrTries]);
    }

    // Number of persons that guessed the correct player
    public function getNbrCorrectGuessesFromCurrentGame()
    {
        $query = "SELECT
                    gd.id_game,
                    COALESCE(COUNT(gwh.id_game),
                    0) AS 'nbr_correct_guesses'
                FROM
                    game_details gd
                JOIN game_status gs ON
                    gd.id_status = gs.id_status
                LEFT JOIN game_win_history gwh ON
                    gd.id_game = gwh.id_game
                WHERE
                    gs.name = 'current';";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }
}
