<?php

require '../php_includes/db_connect.php';
require_once '../php_scripts/services/GameService.php';

$gameService = new GameService($bdd);


$nbrOfCurrentGames = $gameService->getNbrOfCurrentGames();

// Verify number of current game (1 expected or 0 if it's the first game)
if ($nbrOfCurrentGames == 0) {
    // If no current game exists, we verify if at least one game exists
    if ($gameService->doesGameExist()) {
        print_r("Error : No game with status current");
    } else {
        // Start a new game
        print_r("No game, need to start one");
        $gameService->newDraw();
        $nextGame = $gameService->getNextGame();
        if ($nextGame != null) {
            // Move current cursor
            $gameService->goNextGame($nextGame['id_game']);
        } else {
            print("Error : No next game found after new draw");
        }
    }
} elseif ($nbrOfCurrentGames == 1) {
    print_r("Current game found");

    $nextGame = $gameService->getNextGame();
    print_r($nextGame);
    if ($nextGame != null) {
        // Move current cursor
        $gameService->goNextGame($nextGame['id_game']);

        /**
         * If we store players details in game_details table
         */
        /*
        // get next player
        $nextPlayer = $gameService->getPlayer($nextGame['id_player']);
        // insert player details in database
        $gameService->insertNextPlayerDetails($nextPlayer);
        // move current cursor
        */
    } else {
        $gameService->newDraw();
        $nextGame = $gameService->getNextGame();
        if ($nextGame != null) {
            // Move current cursor
            $gameService->goNextGame($nextGame['id_game']);
        } else {
            print("Error : No next game found after new draw");
        }
    }
} else {
    print_r("Error : Multiple current games found");
}
