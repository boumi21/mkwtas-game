<?php

require_once 'db_requests.php';
require_once 'dto/Player.php';
require_once '../php_includes/constants.php';

class PlayerService
{

    private DatabaseRequests $dbRequester;

    public function __construct(PDO $bdd)
    {
        $this->dbRequester = new DatabaseRequests($bdd);
    }


    // Get specific player
    public function getPlayerFromDb(int $idPlayer)
    {
        return $this->dbRequester->getPlayer($idPlayer);
    }

    // Get player's country (ISO fromat)
    public function getPlayerCountry(int $idPlayer)
    {
        return $this->dbRequester->getPlayerCountry($idPlayer);
    }

    // Get all IDs of records for a player
    public function getPlayerIdsRecords(int $idPlayer)
    {
        return $this->dbRequester->getPlayerIdsRecords($idPlayer);
    }

    // Get number of records for a player
    public function getPlayerNbrRecords(int $idPlayer)
    {
        $idsRecords = $this->getPlayerIdsRecords($idPlayer);
        return count($idsRecords);
    }

    // Get number of collaborations from an array of records
    public function getNbrCollabsFromRecords(array $idsRecords)
    {
        return $this->dbRequester->getNbrCollabsFromRecords($idsRecords);
    }

    // Get the year of the first record for a player
    public function getPlayerFirstRecordYear(int $idPlayer)
    {
        return $this->dbRequester->getPlayerFirstRecordYear($idPlayer);
    }

    // Get the 3 last tracks from a player
    public function getPlayerLastTracks(int $idPlayer)
    {

        return $this->dbRequester->getPlayerLastTracks($idPlayer);
    }

    // Get all details for a player
    public function getPlayerInfos(int $idPlayer)
    {

        $playerLight = $this->getPlayerFromDb($idPlayer);
        $playerIdsRecords = $this->getPlayerIdsRecords($idPlayer);

        $name = $playerLight['name_player'];
        $country = $playerLight['country'];
        $nbrRecords = count($playerIdsRecords);
        $nbrCollabs = $this->getNbrCollabsFromRecords($playerIdsRecords);
        $firstYearRecord = $this->getPlayerFirstRecordYear($idPlayer);
        $lastTracks = $this->getPlayerLastTracks($idPlayer);

        $playerDetails = new Player($idPlayer, $name, $country, $nbrRecords, $nbrCollabs, $firstYearRecord, $lastTracks);

        return $playerDetails;
    }

    // Compare two players
    public function comparePlayers(int $idGuessedPlayer, int $idCurrentPlayer)
    {
        $guessedPlayer = $this->getPlayerInfos($idGuessedPlayer);
        $currentPlayer = $this->getPlayerInfos($idCurrentPlayer);

        //name
        $name = array(
            'value' => $guessedPlayer->name,
            'status' => ($guessedPlayer->name == $currentPlayer->name ? GuessStatus::CORRECT->value : GuessStatus::INCORRECT->value)
        );

        $country = array(
            'value' => $guessedPlayer->country,
            'status' => ($guessedPlayer->country == $currentPlayer->country ? GuessStatus::CORRECT->value : GuessStatus::INCORRECT->value)
        );

        $firstRecordYear = array(
            'value' => $guessedPlayer->firstRecordYear,
            'status' => ($guessedPlayer->firstRecordYear == $currentPlayer->firstRecordYear ? GuessStatus::CORRECT->value : GuessStatus::INCORRECT->value)
        );

        if ($guessedPlayer->nbrRecords == $currentPlayer->nbrRecords){
            $statusNbrRecords = GuessStatus::CORRECT->value;
        } else {
            $statusNbrRecords = $guessedPlayer->nbrRecords < $currentPlayer->nbrRecords ? GuessStatus::MORE->value : GuessStatus::LESS->value;
        }
        $nbrRecords = array(
            'value' => $guessedPlayer->nbrRecords,
            'status' => $statusNbrRecords
        );

        if ($guessedPlayer->nbrCollabs == $currentPlayer->nbrCollabs){
            $statusNbrCollabs = GuessStatus::CORRECT->value;
        } else {
            $statusNbrCollabs = $guessedPlayer->nbrCollabs < $currentPlayer->nbrCollabs ? GuessStatus::MORE->value : GuessStatus::LESS->value;
        }
        $nbrCollabs = array(
            'value' => $guessedPlayer->nbrCollabs,
            'status' => $statusNbrCollabs
        );

        foreach ($guessedPlayer->lastTracks as $key => $track) {

            if(array_key_exists($key, $currentPlayer->lastTracks)){
                if($track == $currentPlayer->lastTracks[$key]){
                    $statusTrack = GuessStatus::CORRECT->value;
                } else {
                    if(in_array($track, $currentPlayer->lastTracks)){
                        $statusTrack = GuessStatus::PRESENT->value;
                    } else {
                        $statusTrack = GuessStatus::INCORRECT->value;
                    }
                }
    
                $lastTracks[$key] = array(
                    'value' => $track,
                    'status' => $statusTrack
                );
            }
        }

        $totalArray = array(
            'guessedPlayer' => array(
                'name' => $name,
                'country' => $country,
                'firstRecordYear' => $firstRecordYear,
                'nbrRecords' => $nbrRecords,
                'nbrCollabs' => $nbrCollabs,
                'lastTracks' => $lastTracks
            )
        );

        return $totalArray;
    }
}
