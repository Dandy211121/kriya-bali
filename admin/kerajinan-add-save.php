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

// Ambil data
$title       = trim($_POST['title'] ?? '');
$region_id   = intval($_POST['region_id'] ?? 0);
$category_id = intval($_POST['category_id'] ?? 0);
$artisan_id  = intval($_POST['artisan_id'] ?? 0);
$price       = intval($_POST['price'] ?? 0);
$desc        = trim($_POST['description'] ?? '');

// Validasi dasar
if ($title === '' || !$region_id || !$category_id || !$artisan_id || $price <= 0) {
    redirect_with_msg("{$BASE_URL}admin/kerajinan-add.php", 'error', 'Input tidak valid.');
}

// Upload foto
$image_path = null;

if (!empty($_FILES['image']['name'])) {

    $error = validate_image_upload($_FILES['image']);
    if ($error !== null) {
        redirect_with_msg("{$BASE_URL}admin/kerajinan-add.php", 'error', $error);
    $folder = __DIR__ . "/../public/uploads/crafts/";
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $newName = time() . "-" . rand(1000, 9999) . "." . $ext;

    $destination = $folder . $newName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
        $image_path = "public/uploads/crafts/" . $newName;
    } else {
        redirect_with_msg("{$BASE_URL}admin/kerajinan-add.php", 'error', 'Gagal mengunggah gambar.');
    }
}

// Simpan ke database
db_exec("
    INSERT INTO crafts (title, region_id, category_id, artisan_id, price, description, image_path)
    VALUES (:t, :r, :c, :a, :p, :d, :img)
", [
    't'   => $title,
    'r'   => $region_id,
    'c'   => $category_id,
    'a'   => $artisan_id,
    'p'   => $price,
    'd'   => $desc,
    'img' => $image_path,
]);

redirect_with_msg("{$BASE_URL}admin/kerajinan-list.php", 'success', 'Kerajinan berhasil ditambahkan.');
