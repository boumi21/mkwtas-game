<?php

// Number of players per draw
define('NBR_PLAYERS_DRAW', 10);


enum Status: int
{
    case PASSED = 1;
    case CURRENT = 2;
    case FUTURE = 3;
}