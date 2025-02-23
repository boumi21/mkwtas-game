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