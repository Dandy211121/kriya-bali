<?php
require_once __DIR__ . '/../config/db.php';
require_admin(); // wajib admin

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

// CSRF check (token passed in POST)
require_csrf();

// Validasi ID dari POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    die("<p style='color:red;'>ID tidak valid.</p>");
}

// Cek apakah data ada
$cek = db_fetch("SELECT id FROM crafts WHERE id = :id", ['id' => $id]);
if (!$cek) {
    die("<p style='color:red;'>Data kerajinan tidak ditemukan.</p>");
}

// Catat log penghapusan (asumsikan tabel dibuat melalui migration/schema.sql)
$userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
$userName = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : null;
$ip = $_SERVER['REMOTE_ADDR'] ?? null;
db_exec("INSERT INTO deletes_log (table_name, deleted_id, user_id, deleted_by_name, ip_address) VALUES (:t, :did, :uid, :name, :ip)", [
    't' => 'crafts',
    'did' => $id,
    'uid' => $userId,
    'name' => $userName,
    'ip' => $ip
]);

// Hapus data
db_exec("DELETE FROM crafts WHERE id = :id", ['id' => $id]);

// Redirect
header("Location: " . $BASE_URL . "admin/kerajinan-list.php");
exit;
