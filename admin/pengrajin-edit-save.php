<?php
require_once __DIR__ . '/../config/db.php';
require_admin();
require_csrf();

$id        = intval($_POST['id']);
$name      = trim($_POST['name']);
$region_id = intval($_POST['region_id']);
$desc      = trim($_POST['description']);
$lokasi    = trim($_POST['lokasi']);

$data = db_fetch("SELECT * FROM artisans WHERE id = :id", ['id' => $id]);
$photo_path = $data['photo_path'];

if (!empty($_FILES['image']['name'])) {

    $dir = __DIR__ . '/../public/uploads/artisans/';
    if (!is_dir($dir)) mkdir($dir, 0777);

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $newName = time() . '-' . rand(1000,9999) . "." . $ext;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . $newName)) {
        $photo_path = 'public/uploads/artisans/' . $newName;
    }
}

db_exec("
    UPDATE artisans 
    SET name = :n, region_id = :r, description = :d, lokasi = :l, photo_path = :p
    WHERE id = :id
", [
    'n'  => $name,
    'r'  => $region_id,
    'd'  => $desc,
    'l'  => $lokasi,
    'p'  => $photo_path,
    'id' => $id
]);

header("Location: {$BASE_URL}admin/pengrajin-list.php");
exit;
