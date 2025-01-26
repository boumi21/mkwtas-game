<?php

require '../php_includes/db_connect.php';
require_once '../php_scripts/services/GameService.php';

$gameService = new GameService($bdd);


//$guessData = json_decode(file_get_contents("php://input"));



$pageInfos = $gameService->getGameInfos();

echo json_encode($pageInfos);
