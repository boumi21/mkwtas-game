<?php

require_once dirname(__DIR__, 1) . '/db_requests.php';

class TrackService
{

    private DatabaseRequests $dbRequester;

    public function __construct(PDO $bdd)
    {
        $this->dbRequester = new DatabaseRequests($bdd);
    }

    // Get Tracks from Ids
    public function getTracksFromIds(array $idsTracks)
    {
        return $this->dbRequester->getTracksFromIds($idsTracks);
    }
}
