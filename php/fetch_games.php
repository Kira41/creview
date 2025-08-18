<?php
/**
 * Endpoint to return casino games in JSON format.
 */

require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

try {
    $db = Database::getInstance()->getConnection();
    // Pagination parameters
    $offset = isset($_GET['offset']) ? max(0, intval($_GET['offset'])) : 0;
    $limit  = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : CASINOS_PER_PAGE;
    $free   = isset($_GET['free']) ? intval($_GET['free']) : null;

    if ($free !== null) {
        // Fetch total count for free games
        $countStmt = $db->prepare("SELECT COUNT(*) FROM games WHERE free = :free");
        $countStmt->bindValue(':free', $free, PDO::PARAM_INT);
        $countStmt->execute();
        $totalCount = (int) $countStmt->fetchColumn();

        // Fetch free games with pagination
        $stmt = $db->prepare("SELECT id, name, game_type, rating FROM games WHERE free = :free ORDER BY id LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':free', $free, PDO::PARAM_INT);
    } else {
        // Fetch total games count
        $countStmt = $db->query("SELECT COUNT(*) FROM games");
        $totalCount = (int) $countStmt->fetchColumn();

        // Fetch games with pagination
        $stmt = $db->prepare("SELECT id, name, game_type, rating FROM games ORDER BY id LIMIT :limit OFFSET :offset");
    }

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'games'      => $games,
        'totalCount' => $totalCount,
        'hasMore'    => ($offset + count($games) < $totalCount),
        'perPage'    => $limit
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
