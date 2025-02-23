<?php

// File called via a cron job every day

require '../php_includes/db_connect.php';
require_once '../php_scripts/services/GameService.php';
require_once '../php_includes/utils.php';


/**
 * Handles the game update process
 */
class GameUpdateHandler 
{
    private GameService $gameService;

    public function __construct(PDO $bdd) 
    {
        $this->gameService = new GameService($bdd);
    }

    /**
     * Main update process
     */
    public function update() 
    {
        $nbrOfCurrentGames = $this->gameService->getNbrOfCurrentGames();

        try {
            match ($nbrOfCurrentGames) {
                0 => $this->handleNoCurrentGame(),
                1 => $this->handleExistingGame(),
                default => $this->logError(ERROR_MESSAGES['MULTIPLE_GAMES'])
            };
        } catch (Exception $e) {
            $this->logError("Update failed: " . $e->getMessage());
        }
    }

    /**
     * Handle case when no current game exists
     */
    private function handleNoCurrentGame() 
    {
        if ($this->gameService->doesGameExist()) {
            $this->logError(ERROR_MESSAGES['NO_CURRENT_GAME']);
            return;
        }

        $this->createAndStartNewGame();
    }

    /**
     * Handle case when current game exists
     */
    private function handleExistingGame() 
    {
        if (!$this->moveToNextGame()) {
            $this->createAndStartNewGame();
        }
    }

    /**
     * Create and start a new game
     */
    private function createAndStartNewGame() 
    {
        $this->gameService->newDraw();
        $nextGame = $this->gameService->getNextGame();
        
        if ($nextGame === null) {
            $this->logError(ERROR_MESSAGES['NO_NEXT_GAME']);
            return;
        }

        $this->gameService->goNextGame($nextGame['id_game']);
    }

    /**
     * Move to the next game if available
     */
    private function moveToNextGame() 
    {
        $nextGame = $this->gameService->getNextGame();
        if (!isset($nextGame['id_game'])) {
            return false;
        }

        $this->gameService->goNextGame($nextGame['id_game']);
        return true;
    }

    /**
     * Log error message
     */
    private function logError(string $message) 
    {
        error_log("[ERROR] " . date('Y-m-d H:i:s') . " - " . $message);
        print($message . PHP_EOL);
    }
}



// Execute the update
try {
    $gameUpdateHandler = new GameUpdateHandler($bdd);
    $gameUpdateHandler->update();
} catch (Exception $e) {
    error_log("[FATAL] " . date('Y-m-d H:i:s') . " - Update script failed: " . $e->getMessage());
    exit(1);
}