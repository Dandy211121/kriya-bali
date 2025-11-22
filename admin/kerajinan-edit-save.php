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

if ($id <= 0) {
    redirect_with_msg("{$BASE_URL}admin/kerajinan-list.php", 'error', 'ID tidak valid.');
}

$title       = trim($_POST['title'] ?? '');
$region_id   = intval($_POST['region_id'] ?? 0);
$category_id = intval($_POST['category_id'] ?? 0);
$artisan_id  = intval($_POST['artisan_id'] ?? 0);
$price       = intval($_POST['price'] ?? 0);
$desc        = trim($_POST['description'] ?? '');

// Ambil data lama
$old = db_fetch("SELECT * FROM crafts WHERE id = :id", ['id' => $id]);

if (!$old) {
    redirect_with_msg("{$BASE_URL}admin/kerajinan-list.php", 'error', 'Data tidak ditemukan.');
}

// Upload foto baru
$image_path = $old['image_path'];

if (!empty($_FILES['image']['name'])) {

    $error = validate_image_upload($_FILES['image']);
    if ($error !== null) {
        redirect_with_msg("{$BASE_URL}admin/kerajinan-edit.php?id={$id}", 'error', $error);
    }

    $folder = __DIR__ . "/../public/uploads/crafts/";
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $newName = time() . "-" . rand(1000, 9999) . "." . $ext;

    $destination = $folder . $newName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {

        // Hapus foto lama
        if (!empty($old['image_path'])) {
            $oldFile = __DIR__ . "/../" . $old['image_path'];
            if (file_exists($oldFile)) unlink($oldFile);
        }

        $image_path = "public/uploads/crafts/" . $newName;
    } else {
        redirect_with_msg("{$BASE_URL}admin/kerajinan-edit.php?id={$id}", 'error', 'Gagal mengunggah gambar.');
    }
}

// Update database
db_exec("
    UPDATE crafts
    SET title = :t,
        region_id = :r,
        category_id = :c,
        artisan_id = :a,
        price = :p,
        description = :d,
        image_path = :img
    WHERE id = :id
", [
    't'   => $title,
    'r'   => $region_id,
    'c'   => $category_id,
    'a'   => $artisan_id,
    'p'   => $price,
    'd'   => $desc,
    'img' => $image_path,
    'id'  => $id
]);

redirect_with_msg("{$BASE_URL}admin/kerajinan-list.php", 'success', 'Kerajinan berhasil diperbarui.');
