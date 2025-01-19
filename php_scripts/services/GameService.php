<?php

require_once 'db_requests.php';

class GameService
{

    private DatabaseRequests $dbRequester;

    public function __construct(PDO $bdd)
    {
        $this->dbRequester = new DatabaseRequests($bdd);
    }


    // Verify if at least one game exists
    public function doesGameExist()
    {
        return $this->dbRequester->getNbrOfGames() == 0 ? false : true;
    }

    // Get number of current games
    public function getNbrOfCurrentGames()
    {
        return $this->dbRequester->getNbrOfCurrentGames();
    }
    
    // Get last draw players in an array
    public function getLastDrawPlayers(){
        return $this->dbRequester->getLastXPlayers();
    }

    // Get current game
    // Returns null if no game is found
    public function getCurrentGame()
    {
        return $this->dbRequester->getCurrentGame();
    }

    // Get next game after current one
    // Returns null if no game is found
    public function getNextGame(){
        return $this->dbRequester->getNextGame();
    }

    // Add new draw as futures games
    public function insertNextGames(array $idsPlayerForNextDraw){
        $this->dbRequester->insertNextGames($idsPlayerForNextDraw);

    }
    
    // Move current cursor to next game + add cirrent date
    public function goNextGame(int $idNextGame){
        $this->dbRequester->goNextGame($idNextGame);
    }

    // Create a new draw (when no future game is available)
    public function newDraw(){
        // Get all players ID
        $allPlayersArray = $this->dbRequester->getAllPlayers();
        $idAllPlayersArray = array_column($allPlayersArray, 'id_player');

        // Get players ID from last draw
        $idLastDrawPlayers = $this->getLastDrawPlayers();

        // Get players ID not in previous draw
        $idPlayersNotInPreviousDraw = array_diff($idAllPlayersArray, $idLastDrawPlayers);

        // Select X random players ID that are not in the previous draw (X = NBR_PLAYERS_DRAW or the maximum if there are less players available)
        shuffle($idPlayersNotInPreviousDraw);
        $idPlayersForNextGames = array_slice($idPlayersNotInPreviousDraw, 0, NBR_PLAYERS_DRAW > count($idPlayersNotInPreviousDraw) ? count($idPlayersNotInPreviousDraw) : NBR_PLAYERS_DRAW);
        
        // Insert new draw
        $this->insertNextGames($idPlayersForNextGames);
    }


    /*
        Methods if we store players details in game_details table
    */
    public function insertNextPlayerDetails(string $name, string $country, int $nbrRecords, int $nbrCollabs, int $firstRecordYear, string $lastTracks){
        $this->dbRequester->insertNextPlayerDetails($name, $country, $nbrRecords, $nbrCollabs, $firstRecordYear, $lastTracks);
    }


}