<?php
require_once __DIR__ . '/../config/db.php';
require_admin();
require_csrf();

// Helper function for redirect with message
function redirect_with_msg($url, $msg_type, $msg) {
    $_SESSION[$msg_type] = $msg;
    header("Location: $url");
    exit;
}

// Validate image file
function validate_image_upload($file) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "Upload error code " . $file['error'];
    }
    if (!in_array(mime_content_type($file['tmp_name']), $allowed_types)) {
        return "Tipe file tidak diizinkan. Hanya JPG, PNG, GIF yang diperbolehkan.";
    }
    if ($file['size'] > $max_size) {
        return "Ukuran file melebihi batas maksimum 2MB.";
    }
    return null;
}

$id = intval($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$region_id = intval($_POST['region_id'] ?? 0);
$desc = trim($_POST['description'] ?? '');

if ($id <= 0 || $name === '' || $region_id <= 0) {
    redirect_with_msg("{$BASE_URL}admin/pengrajin-edit.php?id={$id}", 'error', 'Input tidak valid.');
}


// Ambil data lama
$old = db_fetch("SELECT * FROM artisans WHERE id = :id", ['id' => $id]);
if (!$old) {
    redirect_with_msg("{$BASE_URL}admin/pengrajin-list.php", 'error', 'Pengrajin tidak ditemukan.');
}

$photo_path = $old['photo_path'];

// Jika upload file baru
if (!empty($_FILES['image']['name'])) {

    $error = validate_image_upload($_FILES['image']);
    if ($error !== null) {
        redirect_with_msg("{$BASE_URL}admin/pengrajin-edit.php?id={$id}", 'error', $error);
    }

    $dir = __DIR__ . '/../public/uploads/artisans/';

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $newName = time() . '-' . rand(1000, 9999) . '.' . $ext;

    $target = $dir . $newName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {

        // Hapus foto lama jika ada
        if (!empty($old['photo_path'])) {
            $oldFile = __DIR__ . '/../' . $old['photo_path'];
            if (file_exists($oldFile)) unlink($oldFile);
        }

        $photo_path = 'public/uploads/artisans/' . $newName;
    } else {
        redirect_with_msg("{$BASE_URL}admin/pengrajin-edit.php?id={$id}", 'error', 'Gagal mengunggah gambar.');
    }
}

try {
    // Update database
    db_exec("
        UPDATE artisans
        SET name = :n,
            region_id = :r,
            description = :d,
            photo_path = :p
        WHERE id = :id
    ", [
        'n'  => $name,
        'r'  => $region_id,
        'd'  => $desc,
        'p'  => $photo_path,
        'id' => $id
    ]);
    redirect_with_msg("{$BASE_URL}admin/pengrajin-list.php", 'success', 'Pengrajin berhasil diperbarui.');
} catch (PDOException $e) {
    error_log("Error updating artisan id=$id: " . $e->getMessage());
    redirect_with_msg("{$BASE_URL}admin/pengrajin-edit.php?id={$id}", 'error', 'Gagal memperbarui pengrajin. Silakan coba lagi.');
}
