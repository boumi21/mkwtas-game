<?php

require_once 'db_requests.php';
require_once 'dto/Player.php';

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
    public function getPlayerCountry(int $idPlayer){
        return $this->dbRequester->getPlayerCountry($idPlayer);
    }

    // Get all IDs of records for a player
    public function getPlayerIdsRecords(int $idPlayer){
        return $this->dbRequester->getPlayerIdsRecords($idPlayer);
    }

    // Get number of records for a player
    public function getPlayerNbrRecords(int $idPlayer){
        $idsRecords = $this->getPlayerIdsRecords($idPlayer);
        return count($idsRecords);
    }

    // Get number of collaborations from an array of records
    public function getNbrCollabsFromRecords(array $idsRecords){
        return $this->dbRequester->getNbrCollabsFromRecords($idsRecords);
    }

    // Get the year of the first record for a player
    public function getPlayerFirstRecordYear(int $idPlayer){
        return $this->dbRequester->getPlayerFirstRecordYear($idPlayer);
    }

    // Get the 3 last tracks from a player
    public function getPlayerLastTracks(int $idPlayer){

        return $this->dbRequester->getPlayerLastTracks($idPlayer);
    }


    
    public function getPlayerInfos(int $idPlayer){

        $playerLight = $this->getPlayerFromDb($idPlayer);
        $playerIdsRecords = $this->getPlayerIdsRecords($idPlayer);

        $name = $playerLight['name_player'];
        $country = $playerLight['country'];
        $nbrRecords = count($playerIdsRecords);
        $nbrCollabs = $this->getNbrCollabsFromRecords($playerIdsRecords);
        $firstYearRecord = $this->getPlayerFirstRecordYear($idPlayer);
        $lastTracks = $this->getPlayerLastTracks($idPlayer);

        $playerDetails = new Player($idPlayer, $name, $country, $nbrRecords, $nbrCollabs, $firstYearRecord, $lastTracks);



        return array('name' => $name, 'country' => $country, 'nbrRecords' => $nbrRecords, 'nbrCollabs' => $nbrCollabs, 'firstYearRecord' => $firstYearRecord, 'lastTracks' => $lastTracks);
    }

}