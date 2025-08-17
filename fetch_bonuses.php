<?php
/**
 * Endpoint to return active casino bonuses in JSON format.
 */

require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

try {
    $db = Database::getInstance()->getConnection();

    $stmt = $db->query("SELECT b.id, b.type, b.title, b.bonus_amount, b.bonus_percentage, b.free_spins, b.wagering_requirement, b.bonus_code, b.valid_until, c.logo, c.rating FROM bonuses b JOIN casinos c ON b.casino_id = c.id WHERE b.status = 'active' ORDER BY b.id");
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

    echo json_encode(['bonuses' => $bonuses]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
