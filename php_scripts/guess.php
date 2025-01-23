<?php

require '../php_includes/db_connect.php';
require_once '../php_scripts/services/PlayerService.php';
require_once '../php_scripts/services/GameService.php';

$playerService = new PlayerService($bdd);
$gameService = new GameService($bdd);

//$compare = $playerService->comparePlayers(2,23);
//echo json_encode($compare);

$guessData = json_decode(file_get_contents("php://input"));



if (isset($guessData)) {
    //1. get player guessed details
    //$guessedPlayer = $playerService->getPlayerInfos($guessData->idPlayer);
    //2. get player to guess details (check if id_player exists)
    //$currentPlayer = $playerService->getPlayerInfos($gameService->getCurrentGame()['id_player']);
    //3. compare the two
    
    $compare = $playerService->comparePlayers($guessData->idGuessedPlayer, $gameService->getCurrentGame()['id_player']);
    // If the guess is correct
    if ($guessData->idGuessedPlayer == $gameService->getCurrentGame()['id_player']) {
        // We add it to win history
        $gameService->addWinToHistory($guessData->nbrTries);
    }
    echo json_encode($compare);
    
} else {
    http_response_code(400); // Set the HTTP response status code to 400
    echo json_encode(["error" => "Invalid input"]);
}
