<?php

require_once dirname(__DIR__, 1) . '/db_requests.php';
require_once dirname(__DIR__, 1) . '/dto/Player.php';
require_once dirname(__DIR__, 2) . '/php_includes/utils.php';
require_once __DIR__ . '/TrackService.php';

class PlayerService
{

    private DatabaseRequests $dbRequester;
    private TrackService $trackService;

    public function __construct(PDO $bdd)
    {
        $this->dbRequester = new DatabaseRequests($bdd);
        $this->trackService = new TrackService($bdd);
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

    /**
     * Retrieves player information based on the provided player ID.
     *
     * @param int $idPlayer The ID of the player whose information is to be retrieved.
     * @return array An associative array containing player information.
     */
    public function getPlayerInfos(int $idPlayer)
    {

        $playerLight = $this->getPlayerFromDb($idPlayer);
        $playerIdsRecords = $this->getPlayerIdsRecords($idPlayer);

        return new Player(
            $idPlayer,
            $playerLight['name_player'],
            $playerLight['country'],
            count($playerIdsRecords),
            $this->getNbrCollabsFromRecords($playerIdsRecords),
            $this->getPlayerFirstRecordYear($idPlayer),
            $this->getPlayerLastTracks($idPlayer)
        );
    }

    
    /**
     * Compares two players based on their IDs.
     *
     * @param int $idGuessedPlayer The ID of the guessed player.
     * @param int $idCurrentPlayer The ID of the current player.
     * @return array An array that acts as a view model.
     */
    public function comparePlayers(int $idGuessedPlayer, int $idCurrentPlayer)
    {
        //TODO : Verify if players have at least one record

        $guessedPlayer = $this->getPlayerInfos($idGuessedPlayer);
        $currentPlayer = $this->getPlayerInfos($idCurrentPlayer);

        $name = $this->compareName($guessedPlayer, $currentPlayer);
        $country = $this->compareCountry($guessedPlayer, $currentPlayer);
        $firstRecordYear = $this->compareFirstRecordYear($guessedPlayer, $currentPlayer);
        $nbrRecords = $this->compareNbrRecords($guessedPlayer, $currentPlayer);
        $nbrCollabs = $this->compareNbrCollabs($guessedPlayer, $currentPlayer);
        $lastTracks = $this->compareLastTracks($guessedPlayer, $currentPlayer);

        return array(
            'guessedPlayer' => array(
                'name' => $name,
                'country' => $country,
                'firstRecordYear' => $firstRecordYear,
                'nbrRecords' => $nbrRecords,
                'nbrCollabs' => $nbrCollabs,
                'lastTracks' => $lastTracks
            )
        );
    }

    private function compareName($guessedPlayer, $currentPlayer)
    {
        return array(
            'value' => $guessedPlayer->name,
            'status' => ($guessedPlayer->name == $currentPlayer->name ? GuessStatus::CORRECT->value : GuessStatus::INCORRECT->value)
        );
    }

    private function compareCountry($guessedPlayer, $currentPlayer)
    {
        return array(
            'value' => getCountryNameFromCode($guessedPlayer->country),
            'status' => ($guessedPlayer->country == $currentPlayer->country ? GuessStatus::CORRECT->value : GuessStatus::INCORRECT->value)
        );
    }

    private function compareFirstRecordYear($guessedPlayer, $currentPlayer)
    {
        return array(
            'value' => $guessedPlayer->firstRecordYear,
            'status' => ($guessedPlayer->firstRecordYear == $currentPlayer->firstRecordYear ? GuessStatus::CORRECT->value : GuessStatus::INCORRECT->value)
        );
    }

    private function compareNbrRecords($guessedPlayer, $currentPlayer)
    {
        if ($guessedPlayer->nbrRecords == $currentPlayer->nbrRecords){
            $statusNbrRecords = GuessStatus::CORRECT->value;
        } else {
            $statusNbrRecords = $guessedPlayer->nbrRecords < $currentPlayer->nbrRecords ? GuessStatus::MORE->value : GuessStatus::LESS->value;
        }
        return array(
            'value' => $guessedPlayer->nbrRecords,
            'status' => $statusNbrRecords
        );
    }

    private function compareNbrCollabs($guessedPlayer, $currentPlayer)
    {
        if ($guessedPlayer->nbrCollabs == $currentPlayer->nbrCollabs){
            $statusNbrCollabs = GuessStatus::CORRECT->value;
        } else {
            $statusNbrCollabs = $guessedPlayer->nbrCollabs < $currentPlayer->nbrCollabs ? GuessStatus::MORE->value : GuessStatus::LESS->value;
        }
        return array(
            'value' => $guessedPlayer->nbrCollabs,
            'status' => $statusNbrCollabs
        );
    }

    private function compareLastTracks($guessedPlayer, $currentPlayer)
    {
        $lastTracks = array();
        $lastTracksInfos = $this->trackService->getTracksFromIds($guessedPlayer->lastTracks);

        foreach ($lastTracksInfos as $key => $track) {
            if(array_key_exists($key, $currentPlayer->lastTracks)){
                if($track['id_track'] == $currentPlayer->lastTracks[$key]){
                    $statusTrack = GuessStatus::CORRECT->value;
                } else {
                    if(in_array($track['id_track'], $currentPlayer->lastTracks)){
                        $statusTrack = GuessStatus::PRESENT->value;
                    } else {
                        $statusTrack = GuessStatus::INCORRECT->value;
                    }
                }
                $lastTracks[$key] = array(
                    'value' => $track['name_track'],
                    'status' => $statusTrack
                );
            }
        }
        return $lastTracks;
    }
}
