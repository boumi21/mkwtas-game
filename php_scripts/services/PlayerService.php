<?php

require_once 'db_requests.php';

class PlayerService
{

    private DatabaseRequests $dbRequester;

    public function __construct(PDO $bdd)
    {
        $this->dbRequester = new DatabaseRequests($bdd);
    }
    

    // Get specific player
    public function getPlayer(int $idPlayer)
    {
        return $this->dbRequester->getPlayer($idPlayer);
    }
    

    // Get player's country (ISO fromat)
    public function getPlayerCountry(int $idPlayer){
        return $this->dbRequester->getPlayerCountry($idPlayer);
    }

    // Get total number of records for a player
    public function getPlayerNbrRecords(int $idPlayer){
        return $this->dbRequester->getPlayerNbrRecords($idPlayer);
    }

    // Get number of collaborations for a player
    public function getNbrCollabsFromRecords(int $idPlayer){
        return $this->dbRequester->getNbrCollabsFromRecords($idPlayer);
    }

    // Get the year of the first record for a player
    public function getPlayerFirstRecordYear(int $idPlayer){
        return $this->dbRequester->getPlayerFirstRecordYear($idPlayer);
    }

    // Get the 3 last tracks from a player
    // Returns the 3 last tracks with this format : 1_2_3
    public function getPlayerLastTracks(int $idPlayer){
        return $this->dbRequester->getPlayerLastTracks($idPlayer);
    }


    
    public function getNextPLayerDetails(int $idPlayer){
        $name = $this->getPlayer($idPlayer)['name_player'];
        $country = $this->getPlayerCountry($idPlayer);
        $nbrRecords = $this->getPlayerNbrRecords($idPlayer);
        $nbrCollabs = $this->getNbrCollabsFromRecords($idPlayer);
        $firstYearRecord = $this->getPlayerFirstRecordYear($idPlayer);
        $lastTracks = $this->getPlayerLastTracks($idPlayer);

        return array('name' => $name, 'country' => $country, 'nbrRecords' => $nbrRecords, 'nbrCollabs' => $nbrCollabs, 'firstYearRecord' => $firstYearRecord, 'lastTracks' => $lastTracks);
    }

}