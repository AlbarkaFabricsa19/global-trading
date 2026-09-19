<?php
// config/db.php - Global Trading Database Connection & Helper Functions
require_once __DIR__ . '/env.php';

if (!defined('DB_HOST')) define('DB_HOST', env('DB_HOST', 'localhost'));
if (!defined('DB_PORT')) define('DB_PORT', env('DB_PORT', '3306'));
if (!defined('DB_USER')) define('DB_USER', env('DB_USER', 'root'));
if (!defined('DB_PASS')) define('DB_PASS', env('DB_PASS', ''));
if (!defined('DB_NAME')) define('DB_NAME', env('DB_NAME', 'global_trading'));

function getDBConnection() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            // Try connecting to MySQL using .env configuration
            $port = DB_PORT ? ';port=' . DB_PORT : '';
            $dsn = "mysql:host=" . DB_HOST . $port . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            // Fallback to SQLite if MySQL database does not exist or MySQL server is unavailable
            $sqlitePath = __DIR__ . '/../global_trading.sqlite';
            try {
                $pdo = new PDO("sqlite:" . $sqlitePath, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $ex) {
                die("Database Connection Error: " . $ex->getMessage());
            }
        }
    }
    return $pdo;
}

function sanitizeInput($data) {
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

function getBaseUrl() {
    $envUrl = env('APP_URL', null);
    if (!empty($envUrl)) {
        return rtrim($envUrl, '/\\');
    }
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    return rtrim($protocol . '://' . $host . $scriptDir, '/\\');
}
?>
