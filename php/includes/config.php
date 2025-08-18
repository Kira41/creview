<?php
/**
 * Casino Review Site Configuration
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'casino_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_CHARSET', 'utf8mb4');

// Environment Configuration
// Set to 0 for local XAMPP connection (no username/password)
// Set to 1 for online connection using DB_USER and DB_PASS
$online = 0;

// Site Configuration
define('SITE_NAME', 'CasinoReviews');
define('SITE_URL', 'https://yoursite.com');
define('CASINOS_PER_PAGE', 9);

// Database Connection Class
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            global $online;
            if ($online === 1) {
                $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            } else {
                // Default XAMPP credentials (root with no password)
                $this->connection = new PDO($dsn, 'root', '', $options);
            }
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function fetchAll($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function fetchOne($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }
}

// Utility Functions
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function formatRating($rating) {
    return number_format($rating, 1);
}

function getRatingClass($rating) {
    if ($rating >= 4.5) return 'excellent';
    if ($rating >= 4.0) return 'good';
    return 'fair';
}

function getRatingLabel($rating) {
    if ($rating >= 4.5) return 'Excellent';
    if ($rating >= 4.0) return 'Good';
    return 'Fair';
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

