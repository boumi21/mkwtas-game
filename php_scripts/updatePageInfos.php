<?php

require dirname(__DIR__, 1) . '/php_includes/db_connect.php';
require_once dirname(__DIR__, 1) . '/php_scripts/services/GameService.php';

$gameService = new GameService($bdd);

// Update the page infos
$pageInfos = $gameService->getGameInfos();
echo json_encode($pageInfos);
