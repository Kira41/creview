<?php
/**
 * Endpoint to return active casino bonuses in JSON format.
 */

require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

try {
    $db = Database::getInstance()->getConnection();
    // Pagination parameters
    $offset = isset($_GET['offset']) ? max(0, intval($_GET['offset'])) : 0;
    $limit  = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : CASINOS_PER_PAGE;

    // Fetch total active bonuses count
    $countStmt = $db->query("SELECT COUNT(*) FROM bonuses WHERE status = 'active'");
    $totalCount = (int) $countStmt->fetchColumn();

    // Fetch active bonuses with pagination
    $stmt = $db->prepare("SELECT b.id, b.type, b.title, b.bonus_amount, b.bonus_percentage, b.free_spins, b.wagering_requirement, b.bonus_code, b.valid_until, c.logo, c.rating FROM bonuses b JOIN casinos c ON b.casino_id = c.id WHERE b.status = 'active' ORDER BY b.id LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $bonuses = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $parts = [];
        if (!empty($row['bonus_percentage']) && $row['bonus_percentage'] > 0) {
            $parts[] = $row['bonus_percentage'] . '%';
        }
        if (!empty($row['bonus_amount']) && $row['bonus_amount'] > 0) {
            $parts[] = 'up to $' . rtrim(rtrim($row['bonus_amount'], '0'), '.');
        }
        if (!empty($row['free_spins']) && $row['free_spins'] > 0) {
            $parts[] = $row['free_spins'] . ' Free Spins';
        }
        $row['bonus_text'] = implode(' ', $parts);
        $bonuses[] = $row;
    }

    echo json_encode([
        'bonuses'   => $bonuses,
        'totalCount'=> $totalCount,
        'hasMore'   => ($offset + count($bonuses) < $totalCount),
        'perPage'   => $limit
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
