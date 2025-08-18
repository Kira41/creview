<?php
/**
 * Simple endpoint that returns casinos and games in JSON format.
 */

require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

try {
    $db = Database::getInstance()->getConnection();

    // Pagination parameters
    $offset = isset($_GET['offset']) ? max(0, intval($_GET['offset'])) : 0;
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : CASINOS_PER_PAGE;

    // Sorting parameter
    $sort = $_GET['sort'] ?? 'id';
    $allowedSorts = [
        'id' => 'id',
        'rating' => 'rating DESC',
        'name' => 'name',
        'bonus' => 'bonus'
    ];
    $orderBy = $allowedSorts[$sort] ?? $allowedSorts['id'];

    // Fetch total active casinos count
    $countStmt = $db->query("SELECT COUNT(*) FROM casinos WHERE status = 'active'");
    $totalCount = (int) $countStmt->fetchColumn();

    // Fetch active casinos with pagination
    $casinoStmt = $db->prepare("SELECT id, name, casino_type AS type, rating, bonus, features, description, logo, payment_methods FROM casinos WHERE status = 'active' ORDER BY $orderBy LIMIT :limit OFFSET :offset");
    $casinoStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $casinoStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $casinoStmt->execute();
    $casinos = $casinoStmt->fetchAll(PDO::FETCH_ASSOC);

    // Decode JSON fields to proper PHP arrays
    foreach ($casinos as &$casino) {
        if (isset($casino['features'])) {
            $decoded = json_decode($casino['features'], true);
            $casino['features'] = is_array($decoded) ? $decoded : [];
        } else {
            $casino['features'] = [];
        }

        if (isset($casino['payment_methods'])) {
            $decoded = json_decode($casino['payment_methods'], true);
            $casino['payment_methods'] = is_array($decoded) ? $decoded : [];
        } else {
            $casino['payment_methods'] = [];
        }
    }
    unset($casino);

    // Fetch games for returned casinos
    $games = [];
    if (!empty($casinos)) {
        $ids = array_column($casinos, 'id');
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $gameStmt = $db->prepare("SELECT casino_id, name FROM games WHERE casino_id IN ($placeholders) ORDER BY id");
        $gameStmt->execute($ids);
        $games = $gameStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode([
        'casinos' => $casinos,
        'games' => $games,
        'totalCount' => $totalCount,
        'hasMore' => ($offset + count($casinos) < $totalCount),
        'perPage' => $limit
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

