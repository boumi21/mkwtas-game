<?php
class DatabaseRequests {
    private $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    // Get calendar entries by year
    public function getAllPlayers() {
        $query = "SELECT name_player, id_player, country FROM player ORDER BY name_player";
        $stmt = $this->bdd->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

// Usage:
// try {
//     $bdd = new PDO(
//         "mysql:host=localhost;dbname=db_mkwtas;charset=utf8",
//         "username",
//         "password",
//         [
//             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
//         ]
//     );

//     $calendar = new Calendar($bdd);
    
//     // Example calls:
//     $yearData = $calendar->getByYear(2013);
//     $monthData = $calendar->getByMonth(2013, 1);
//     $quarterData = $calendar->getByQuarter(2013, 1);
//     $dateData = $calendar->getByDate('2013-01-01');

//     // Return as JSON
//     header('Content-Type: application/json');
//     echo json_encode([
//         'success' => true,
//         'data' => $yearData // or any other data you want to return
//     ]);

// } catch(PDOException $e) {
//     header('Content-Type: application/json');
//     echo json_encode([
//         'success' => false,
//         'error' => $e->getMessage()
//     ]);
// }

?>