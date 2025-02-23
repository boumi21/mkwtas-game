<?php

// Number of players per draw
const NBR_PLAYERS_DRAW = 10;


enum GameStatus: int
{
    case PASSED = 1;
    case CURRENT = 2;
    case FUTURE = 3;
}


enum GuessStatus: int
{
    case CORRECT = 1;
    case INCORRECT = 2;
    case PRESENT = 3;
    case LESS = 4;
    case MORE = 5;
}

/**
 * Constants for game status messages
 */
const ERROR_MESSAGES = [
    'NO_CURRENT_GAME' => 'Error: No game with status current',
    'NO_NEXT_GAME' => 'Error: No next game found after new draw',
    'MULTIPLE_GAMES' => 'Error: Multiple current games found'
];

/**
 * Returns the json file with country codes to their names as an array
 */
function getCodeToCountryArray(){
    $json = file_get_contents(__DIR__ . '/../assets/countries.json');
    return json_decode($json);
}

/**
 * Retrieve the country name matching the country code
 */
function getCountryNameFromCode($countryCode){
    foreach (getCodeToCountryArray() as $key => $value) {
        if ($key == $countryCode) {
            return $value;
        }
    }
}