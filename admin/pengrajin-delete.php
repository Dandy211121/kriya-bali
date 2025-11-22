<?php
require_once __DIR__ . '/../config/db.php';
require_admin();
require_csrf();

// Ambil ID
$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    die("ID tidak valid.");
}

// Ambil data untuk cek foto lama
$data = db_fetch("SELECT * FROM artisans WHERE id = :id", ['id' => $id]);

if (!$data) {
    die("Data pengrajin tidak ditemukan.");
}

// Hapus foto jika ada
if (!empty($data['photo_path'])) {
    $path = __DIR__ . '/../' . $data['photo_path'];

    if (file_exists($path)) {
        unlink($path);
    }
}

// Hapus dari database
db_exec("DELETE FROM artisans WHERE id = :id", ['id' => $id]);

// (Opsional) Simpan log untuk superadmin
/*
db_exec("
    INSERT INTO deletes_log (item_type, item_id, description, deleted_at)
    VALUES ('artisan', :id, :desc, NOW())
", [
    'id' => $id,
    'desc' => 'Pengrajin bernama ' . ($data['name'] ?? '')
]);
*/

// Redirect kembali ke list
header("Location: {$BASE_URL}admin/pengrajin-list.php?msg=deleted");
exit;

