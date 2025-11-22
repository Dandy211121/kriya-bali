<?php
require_once __DIR__ . '/../config/db.php';
require_admin();
require_csrf();

$title = $_POST['title'];
$artisan = $_POST['artisan_id'];
$region = $_POST['region_id'];
$category = $_POST['category_id'];
$desc = $_POST['description'];
$location = $_POST['location_addres'];
$price = $_POST['price'];

$imagePath = null;

if (!empty($_FILES['image']['name'])) {
    $filename = time() . "-" . rand(1000,9999) . ".png";
    $path = "public/uploads/crafts/" . $filename;
    move_uploaded_file($_FILES['image']['tmp_name'], "../" . $path);
    $imagePath = $path;
}

db_exec("
    INSERT INTO crafts 
    (title, artisan_id, region_id, category_id, description, location_addres, price, image_path, created_at)
    VALUES
    (:t, :a, :r, :c, :d, :l, :p, :img, NOW())
", [
    't' => $title,
    'a' => $artisan,
    'r' => $region,
    'c' => $category,
    'd' => $desc,
    'l' => $location,
    'p' => $price,
    'img' => $imagePath
]);

header("Location: kerajinan-list.php");
exit;
