<?php

// Number of players per draw
define('NBR_PLAYERS_DRAW', 10);


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