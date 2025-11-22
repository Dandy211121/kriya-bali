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
=======

if (!empty($_FILES['image']['name'])) {

    $error = validate_image_upload($_FILES['image']);
    if ($error !== null) {
        redirect_with_msg("{$BASE_URL}admin/kerajinan-add.php", 'error', $error);
    }
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
