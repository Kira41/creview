<?php
// Database configuration placeholders
define('DB_HOST', 'localhost');
define('DB_NAME', 'casino_reviews');
define('DB_USER', 'username');
define('DB_PASS', 'password');

// Enable debug mode during development
define('DEBUG_MODE', true);
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
