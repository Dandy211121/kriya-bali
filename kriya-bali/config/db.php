<?php
// ---------------------------------------------------------------------
// Debug mode (nyalakan hanya di development)
// ---------------------------------------------------------------------
// Gunakan environment variable APP_ENV untuk menentukan mode
$env = getenv('APP_ENV') ?: 'production';
if ($env === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// ---------------------------------------------------------------------
// Session
// ---------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| BASE URL (KARENA DOMAIN: kriya-bali.test/)
|--------------------------------------------------------------------------
| Website berada di root domain, jadi gunakan '/'
*/
$BASE_URL = '/';

/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/
$DB_HOST = 'localhost';
$DB_NAME = 'kriya_bali';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    echo "Koneksi database gagal: " . htmlspecialchars($e->getMessage());
    exit;
}

/*
|--------------------------------------------------------------------------
| DB HELPERS (AMAN)
|--------------------------------------------------------------------------
*/
function db_fetch_all($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function db_fetch($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

function db_exec($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

/*
|--------------------------------------------------------------------------
| AUTH HELPERS
|--------------------------------------------------------------------------
*/

function is_logged_in() {
    return isset($_SESSION['user']);
}

function is_admin() {
    return is_logged_in() && $_SESSION['user']['role'] === 'admin';
}

function is_superadmin() {
    return is_logged_in() && $_SESSION['user']['role'] === 'superadmin';
}

function require_login() {
    global $BASE_URL;
    if (!is_logged_in()) {
        header("Location: {$BASE_URL}login-select.php");
        exit;
    }
}

function require_admin() {
    global $BASE_URL;
    if (!is_admin() && !is_superadmin()) {
        header("Location: {$BASE_URL}login-select.php");
        exit;
    }
}

function require_superadmin() {
    global $BASE_URL;
    if (!is_superadmin()) {
        header("Location: {$BASE_URL}login-select.php");
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| ASSET HELPER (NORMALISASI PATH)
|--------------------------------------------------------------------------
| Mencegah URL ganda, misalnya //public/css/style.css
*/
function asset($path) {
    global $BASE_URL;

    $base = rtrim($BASE_URL, '/');     // remove trailing slash
    $p    = '/' . ltrim($path, '/');   // pastikan path selalu diawali '/'

    return $base . $p;
}

/*
|--------------------------------------------------------------------------
| CSRF HELPERS
|--------------------------------------------------------------------------
*/
function csrf_token() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        try {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } catch (Exception $e) {
            // fallback
            $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
        }
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function validate_csrf($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($token) || empty($_SESSION['csrf_token'])) return false;
    return hash_equals($_SESSION['csrf_token'], $token);
}

function require_csrf() {
    $token = $_POST['csrf'] ?? $_GET['csrf'] ?? '';
    if (!validate_csrf($token)) {
        http_response_code(403);
        die('Invalid CSRF token.');
    }
}

/*
|--------------------------------------------------------------------------
| SETTINGS table helper (AMANKAN row null)
|--------------------------------------------------------------------------
*/
function get_setting($key) {
    $row = db_fetch(
        "SELECT setting_value FROM settings WHERE setting_key = :k",
        ['k' => $key]
    );

    return ($row && isset($row['setting_value']))
        ? $row['setting_value']
        : null;
}

function set_setting($key, $value) {
    $exists = db_fetch(
        "SELECT id FROM settings WHERE setting_key = :k",
        ['k' => $key]
    );

    if ($exists) {
        db_exec(
            "UPDATE settings SET setting_value = :v WHERE setting_key = :k",
            ['v' => $value, 'k' => $key]
        );
    } else {
        db_exec(
            "INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v)",
            ['k' => $key, 'v' => $value]
        );
    }
}
