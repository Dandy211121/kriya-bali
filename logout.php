<?php
require_once __DIR__ . '/config/db.php';

session_start();

$role = $_SESSION['user']['role'] ?? 'user';

// hapus session
session_destroy();

// Redirect sesuai role
header("Location: {$BASE_URL}login-select.php?logout=" . $role);
exit;
