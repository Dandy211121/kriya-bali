<?php
require_once __DIR__ . '/../config/db.php';

// Hanya admin & superadmin yang boleh masuk
require_admin();

// Redirect langsung ke dashboard
header("Location: {$BASE_URL}admin/dashboard.php");
exit;
