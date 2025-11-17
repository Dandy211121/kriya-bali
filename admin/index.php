<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

// Redirect ke dashboard admin
header("Location: " . $BASE_URL . "admin/dashboard.php");
exit;
