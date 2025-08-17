<?php
/**
 * Simple endpoint that returns casinos and games in JSON format.
 */

require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

try {
    $db = Database::getInstance()->getConnection();

    // Fetch active casinos
    $casinoStmt = $db->query("SELECT id, name, casino_type AS type, rating, bonus, features, description, logo FROM casinos WHERE status = 'active'");
    $casinos = $casinoStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch games for all casinos
    $gameStmt = $db->query("SELECT casino_id, name FROM games ORDER BY id");
    $games = $gameStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'casinos' => $casinos,
        'games' => $games,
        'totalCount' => count($casinos)
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

