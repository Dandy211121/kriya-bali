<?php
require_once __DIR__ . '/config/db.php';

session_start();

// simpan role sebelum logout
$role = $_SESSION['user']['role'] ?? 'user';

// hancurkan semua data session
$_SESSION = [];
session_unset();
session_destroy();

// hapus session cookie (lebih aman)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// regenerate ID baru setelah logout
session_start();
session_regenerate_id(true);

// redirect ke halaman pemilihan login
header("Location: {$BASE_URL}login-select.php?logout={$role}");
exit;
