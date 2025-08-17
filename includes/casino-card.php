<?php
/**
 * Casino Card Component
 * Generates HTML for individual casino cards
 */

function renderCasinoCard($casino) {
    $rating = formatRating($casino['rating']);
    $ratingClass = getRatingClass($casino['rating']);
    $ratingLabel = getRatingLabel($casino['rating']);
    $casinoName = sanitizeInput($casino['name']);
    $casinoType = sanitizeInput($casino['type']);
    $bonus = sanitizeInput($casino['bonus']);
    $logoUrl = !empty($casino['logo']) ? $casino['logo'] : "https://via.placeholder.com/120x60/4CAF50/white?text=" . urlencode($casinoName);
    
    // Parse features from JSON or array
    $features = [];
    if (is_string($casino['features'])) {
        $features = json_decode($casino['features'], true) ?: [];
    } elseif (is_array($casino['features'])) {
        $features = $casino['features'];
    }
    
    ob_start();
    ?>
    <div class="casino-card" data-rating="<?php echo $rating; ?>" data-type="<?php echo $casinoType; ?>" data-name="<?php echo $casinoName; ?>" data-casino-id="<?php echo $casino['id']; ?>">
        <div class="casino-header">
            <div class="casino-logo">
                <img src="<?php echo $logoUrl; ?>" alt="<?php echo $casinoName; ?>" loading="lazy">
            </div>
            <div class="casino-rating">
                <span class="rating-badge <?php echo $ratingClass; ?>"><?php echo $rating; ?></span>
                <span class="rating-label"><?php echo $ratingLabel; ?></span>
            </div>
        </div>
        
        <div class="casino-content">
            <h3 class="casino-name"><?php echo $casinoName; ?></h3>
            
            <?php if (!empty($bonus)): ?>
            <div class="casino-bonus">
                <span class="bonus-text"><?php echo $bonus; ?></span>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($features)): ?>
            <ul class="casino-features">
                <?php foreach (array_slice($features, 0, 3) as $feature): ?>
                <li><i class="fas fa-check"></i> <?php echo sanitizeInput($feature); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            
            <div class="casino-actions">
                <a href="casino-detail.php?id=<?php echo $casino['id']; ?>" class="btn btn-primary">Read Review</a>
                <a href="<?php echo !empty($casino['affiliate_url']) ? $casino['affiliate_url'] : '#'; ?>" 
                   class="btn btn-secondary" 
                   <?php echo !empty($casino['affiliate_url']) ? 'target="_blank" rel="noopener"' : ''; ?>>
                   Visit Site
                </a>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function getCasinos($filters = [], $limit = CASINOS_PER_PAGE, $offset = 0) {
    $db = Database::getInstance();
    
    $where = ['status = :status'];
    $params = ['status' => 'active'];
    
    // Apply filters
    if (!empty($filters['rating'])) {
        $where[] = 'rating >= :rating';
        $params['rating'] = $filters['rating'];
    }
    
    if (!empty($filters['type']) && $filters['type'] !== 'all') {
        $where[] = 'type = :type';
        $params['type'] = $filters['type'];
    }
    
    if (!empty($filters['search'])) {
        $where[] = '(name LIKE :search OR description LIKE :search)';
        $params['search'] = '%' . $filters['search'] . '%';
    }
    
    // Build ORDER BY clause
    $orderBy = 'rating DESC, created_at DESC';
    if (!empty($filters['sort'])) {
        switch ($filters['sort']) {
            case 'name':
                $orderBy = 'name ASC';
                break;
            case 'newest':
                $orderBy = 'created_at DESC';
                break;
            case 'bonus':
                $orderBy = 'bonus_value DESC, rating DESC';
                break;
            default:
                $orderBy = 'rating DESC, created_at DESC';
        }
    }
    
    $whereClause = implode(' AND ', $where);
    $sql = "SELECT * FROM casinos WHERE {$whereClause} ORDER BY {$orderBy} LIMIT :limit OFFSET :offset";
    
    $params['limit'] = $limit;
    $params['offset'] = $offset;
    
    return $db->fetchAll($sql, $params);
}

function getCasinoCount($filters = []) {
    $db = Database::getInstance();
    
    $where = ['status = :status'];
    $params = ['status' => 'active'];
    
    // Apply same filters as getCasinos
    if (!empty($filters['rating'])) {
        $where[] = 'rating >= :rating';
        $params['rating'] = $filters['rating'];
    }
    
    if (!empty($filters['type']) && $filters['type'] !== 'all') {
        $where[] = 'type = :type';
        $params['type'] = $filters['type'];
    }
    
    if (!empty($filters['search'])) {
        $where[] = '(name LIKE :search OR description LIKE :search)';
        $params['search'] = '%' . $filters['search'] . '%';
    }
    
    $whereClause = implode(' AND ', $where);
    $sql = "SELECT COUNT(*) as count FROM casinos WHERE {$whereClause}";
    
    $result = $db->fetchOne($sql, $params);
    return $result['count'];
}

function getCasinoById($id) {
    $db = Database::getInstance();
    $sql = "SELECT * FROM casinos WHERE id = :id AND status = 'active'";
    return $db->fetchOne($sql, ['id' => $id]);
}

function updateCasinoRating($id, $rating) {
    $db = Database::getInstance();
    $sql = "UPDATE casinos SET rating = :rating, updated_at = NOW() WHERE id = :id";
    return $db->query($sql, ['rating' => $rating, 'id' => $id]);
}

function addCasino($data) {
    $db = Database::getInstance();
    
    $casinoData = [
        'name' => $data['name'],
        'type' => $data['type'],
        'rating' => $data['rating'],
        'bonus' => $data['bonus'],
        'features' => json_encode($data['features']),
        'description' => $data['description'],
        'logo' => $data['logo'] ?? null,
        'affiliate_url' => $data['affiliate_url'] ?? null,
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    $sql = "INSERT INTO casinos (name, type, rating, bonus, features, description, logo, affiliate_url, status, created_at, updated_at) 
            VALUES (:name, :type, :rating, :bonus, :features, :description, :logo, :affiliate_url, :status, :created_at, :updated_at)";
    
    $db->query($sql, $casinoData);
    return $db->getConnection()->lastInsertId();
}

function deleteCasino($id) {
    $db = Database::getInstance();
    $sql = "UPDATE casinos SET status = 'deleted', updated_at = NOW() WHERE id = :id";
    return $db->query($sql, ['id' => $id]);
}

// AJAX endpoint for loading more casinos
if (isset($_GET['action']) && $_GET['action'] === 'load_more') {
    header('Content-Type: application/json');
    
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * CASINOS_PER_PAGE;
    
    $filters = [
        'rating' => $_GET['rating'] ?? '',
        'type' => $_GET['type'] ?? '',
        'search' => $_GET['search'] ?? '',
        'sort' => $_GET['sort'] ?? ''
    ];
    
    $casinos = getCasinos($filters, CASINOS_PER_PAGE, $offset);
    $totalCount = getCasinoCount($filters);
    
    $html = '';
    foreach ($casinos as $casino) {
        $html .= renderCasinoCard($casino);
    }
    
    echo json_encode([
        'html' => $html,
        'hasMore' => ($offset + CASINOS_PER_PAGE) < $totalCount,
        'totalCount' => $totalCount
    ]);
    exit;
}
?>

