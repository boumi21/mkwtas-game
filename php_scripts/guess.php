<?php

require '../php_includes/db_connect.php';
require_once '../php_scripts/services/GameService.php';

$guessData = json_decode(file_get_contents("php://input"));



$playerService = new PlayerService($bdd);

if (isset($guessData)) {
    echo json_encode($guessData);
    //1. get player guessed details
    $guessedPlayer = $playerService->getPlayerInfos($guessData->idPlayer);
    //2. get player to guess details (check if id_player exists)
    $currentPlayer = $playerService->getPlayerInfos($gameService->getCurrentGame()['id_player']);
    //3. compare the two
} else {
    http_response_code(400); // Set the HTTP response status code to 400
    echo json_encode(["error" => "Invalid input"]);
}
