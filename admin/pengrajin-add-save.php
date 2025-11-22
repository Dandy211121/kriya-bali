<?php
require_once __DIR__ . '/../config/db.php';
require_admin();
require_csrf();

$name      = trim($_POST['name']);
$region_id = intval($_POST['region_id']);
$desc      = trim($_POST['description']);
$lokasi    = trim($_POST['lokasi']);

$photo_path = null;

// Upload foto
if (!empty($_FILES['image']['name'])) {

    $dir = __DIR__ . '/../public/uploads/artisans/';
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $newName = time() . '-' . rand(1000,9999) . '.' . $ext;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . $newName)) {
        $photo_path = 'public/uploads/artisans/' . $newName;
    }
}

db_exec("
    INSERT INTO artisans (name, region_id, description, lokasi, photo_path)
    VALUES (:n, :r, :d, :l, :p)
", [
    'n' => $name,
    'r' => $region_id,
    'd' => $desc,
    'l' => $lokasi,
    'p' => $photo_path
]);

header("Location: {$BASE_URL}admin/pengrajin-list.php");
exit;
