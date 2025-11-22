<?php
require_once __DIR__ . '/../config/db.php';
require_admin();
require_csrf();

$id = intval($_POST['id']);

db_exec("
    UPDATE crafts SET
        title = :t,
        artisan_id = :a,
        region_id = :r,
        category_id = :c,
        description = :d,
        location_address = :l,
        price = :p
    WHERE id = :id
", [
    't' => $_POST['title'],
    'a' => $_POST['artisan_id'],
    'r' => $_POST['region_id'],
    'c' => $_POST['category_id'],
    'd' => $_POST['description'],
    'l' => $_POST['location_address'],
    'p' => $_POST['price'],
    'id' => $id
]);

header("Location: kerajinan-list.php");
exit;
