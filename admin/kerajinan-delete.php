<?php
require_once __DIR__ . '/../config/db.php';
require_admin();
require_csrf();

// --- Validasi ID ---
$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    die("ID tidak valid.");
}

// --- Ambil data lama ---
$data = db_fetch("
    SELECT * FROM crafts WHERE id = :id
", ['id' => $id]);

if (!$data) {
    die("Data kerajinan tidak ditemukan.");
}

// --- Hapus foto kerajinan ---
if (!empty($data['image_path'])) {

    $filePath = __DIR__ . '/../' . $data['image_path'];

    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

// --- Hapus data dari database ---
db_exec("DELETE FROM crafts WHERE id = :id", ['id' => $id]);

// (OPSIONAL) Simpan log penghapusan
/*
db_exec("
    INSERT INTO deletes_log (item_type, item_id, description, deleted_at)
    VALUES ('craft', :id, :desc, NOW())
", [
    'id' => $id,
    'desc' => 'Kerajinan: ' . ($data['title'] ?? '')
]);
*/

// --- Redirect kembali ke list ---
header("Location: {$BASE_URL}admin/kerajinan-list.php?msg=deleted");
exit;
