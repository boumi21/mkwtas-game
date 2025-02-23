<?php

// File called at each guess

require dirname(__DIR__, 1) . '/php_includes/db_connect.php';
require_once dirname(__DIR__, 1) . '/php_scripts/services/PlayerService.php';
require_once dirname(__DIR__, 1) . '/php_scripts/services/GameService.php';

$playerService = new PlayerService($bdd);
$gameService = new GameService($bdd);

$guessData = json_decode(file_get_contents("php://input"));



if (isset($guessData)) {

    // If the game id is set, we check if it matches the current game
    if (isset($guessData->idGame) && $guessData->idGame != "") {
        if ($guessData->idGame != $gameService->getCurrentGame()['id_game']) {
            http_response_code(205); // Tell the view to reload the page, a new game is available
            return;
        }
    }

    $idPlayerCurrentGame = $gameService->getCurrentGame()['id_player'];
    
    $compare = $playerService->comparePlayers($guessData->idGuessedPlayer, $idPlayerCurrentGame);
    // If the guess is correct
    if ($guessData->idGuessedPlayer == $idPlayerCurrentGame) {
        // We add it to win history
        $gameService->addWinToHistory($guessData->nbrTries);
    }
    echo json_encode($compare); // Return the comparison result to the view
    
} else {
    http_response_code(400); // Set the HTTP response status code to 400
    echo json_encode(["error" => "Invalid input"]);
}
