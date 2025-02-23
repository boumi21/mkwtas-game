<?php

require '../php_includes/db_connect.php';
require_once '../php_scripts/services/GameService.php';

$gameService = new GameService($bdd);

// Update the page infos
$pageInfos = $gameService->getGameInfos();
echo json_encode($pageInfos);
