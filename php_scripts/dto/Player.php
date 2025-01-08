<?php

class Player {
    private int $id;
    private string $name;
    private string $country;
    private int $nbrRecords;
    private int $nbrCollabs;
    private int $firstRecordYear;
    private array $lastTracks;

    public function __construct(int $id, string $name, string $country, int $nbrRecords, int $nbrCollabs, int $firstRecordYear, array $lastTracks) {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->nbrRecords = $nbrRecords;
        $this->nbrCollabs = $nbrCollabs;
        $this->firstRecordYear = $firstRecordYear;
        $this->lastTracks = $lastTracks;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCountry(): string {
        return $this->country;
    }

    public function getNbrRecords(): int {
        return $this->nbrRecords;
    }

    public function getNbrCollabs(): int {
        return $this->nbrCollabs;
    }

    public function getFirstRecordYear(): int {
        return $this->firstRecordYear;
    }

    public function getLastTracks(): array {
        return $this->lastTracks;
    }

    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setCountry(string $country): void {
        $this->country = $country;
    }

    public function setNbrRecords(int $nbrRecords): void {
        $this->nbrRecords = $nbrRecords;
    }

    public function setNbrCollabs(int $nbrCollabs): void {
        $this->nbrCollabs = $nbrCollabs;
    }

    public function setFirstRecordYear(int $firstRecordYear): void {
        $this->firstRecordYear = $firstRecordYear;
    }

    public function setLastTracks(array $lastTracks): void {
        $this->lastTracks = $lastTracks;
    }
}