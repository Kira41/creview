<?php
/**
 * Endpoint to return casino games in JSON format.
 */

require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT id, name, game_type, rating FROM games ORDER BY id");
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['games' => $games]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
