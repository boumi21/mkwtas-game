<?php

class Player {
    public int $id;
    public string $name;
    public string $country;
    public int $nbrRecords;
    public int $nbrCollabs;
    public int $firstRecordYear;
    public array $lastTracks;

    public function __construct(int $id, string $name, string $country, int $nbrRecords, int $nbrCollabs, int $firstRecordYear, array $lastTracks) {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->nbrRecords = $nbrRecords;
        $this->nbrCollabs = $nbrCollabs;
        $this->firstRecordYear = $firstRecordYear;
        $this->lastTracks = $lastTracks;
    }
}