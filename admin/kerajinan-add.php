<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$success = '';
$error = '';

// Ambil dropdown data
$pengrajin = db_fetch_all("SELECT id, name FROM artisans ORDER BY name ASC");
$kategori  = db_fetch_all("SELECT id, name FROM craft_categories ORDER BY name ASC");
$region    = db_fetch_all("SELECT id, name FROM regions ORDER BY name ASC");

// Ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF protection
    require_csrf();
    
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price       = trim($_POST['price']);
    $artisan_id  = $_POST['artisan_id'];
    $category_id = $_POST['category_id'];
    $region_id   = $_POST['region_id'];

    // Validasi
    if ($title === '' || $price === '' || !$artisan_id || !$category_id || !$region_id) {
        $error = "Semua field wajib diisi.";
    }

    // Upload file (optional)
    $imagePath = null;
    if (!$error && !empty($_FILES['image']['name'])) {

        $uploadDir = __DIR__ . '/../public/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $tmp  = $_FILES['image']['tmp_name'];
        $name = $_FILES['image']['name'];
        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        $allowed = ['jpg','jpeg','png','gif'];
        if (!in_array($ext, $allowed)) {
            $error = "Format gambar tidak didukung (hanya JPG, PNG, GIF).";
        } else {
            $newName = uniqid('craft_', true) . '.' . $ext;
            $dest    = $uploadDir . $newName;

            if (!move_uploaded_file($tmp, $dest)) {
                $error = "Gagal upload gambar.";
            } else {
                $imagePath = 'public/uploads/' . $newName;
            }
        }
    }

    // Jika tidak ada error → simpan ke database
    if (!$error) {
        db_exec("
            INSERT INTO crafts (title, description, price, artisan_id, region_id, category_id, image_path)
            VALUES (:title, :desc, :price, :artisan, :region, :category, :image)
        ", [
            'title'    => $title,
            'desc'     => $description,
            'price'    => $price,
            'artisan'  => $artisan_id,
            'region'   => $region_id,
            'category' => $category_id,
            'image'    => $imagePath
        ]);

        // Redirect agar UX lebih baik
        header("Location: " . $BASE_URL . "admin/kerajinan-list.php?success=1");
        exit;
    }
}

include __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">Tambah Kerajinan</h1>

<?php if ($error): ?>
    <div class="kb-alert kb-alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="kb-form">
    <?= csrf_field() ?>

    <label>Judul Kerajinan</label>
    <input type="text" name="title" required>

    <label>Deskripsi</label>
    <textarea name="description"></textarea>

    <label>Harga (Rp)</label>
    <input type="number" name="price" required>

    <label>Pilih Pengrajin</label>
    <select name="artisan_id" required>
        <option value="">-- Pilih Pengrajin --</option>
        <?php foreach ($pengrajin as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Pilih Daerah</label>
    <select name="region_id" required>
        <option value="">-- Pilih Daerah --</option>
        <?php foreach ($region as $r): ?>
            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Pilih Kategori</label>
    <select name="category_id" required>
        <option value="">-- Pilih Kategori --</option>
        <?php foreach ($kategori as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Upload Gambar</label>
    <input type="file" name="image" accept="image/*">

    <button class="kb-btn kb-btn-success">Simpan</button>
    <a href="<?= $BASE_URL . 'admin/kerajinan-list.php' ?>" class="kb-btn kb-btn-outline">← Kembali</a>
</form>

<?php include __DIR__ . '/_layout_end.php'; ?>
