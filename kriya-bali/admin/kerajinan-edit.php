<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

// Validasi ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data kerajinan
$data = db_fetch("SELECT * FROM crafts WHERE id = :id", ['id' => $id]);

if (!$data) {
    die("<p style='color:red;'>Data kerajinan tidak ditemukan.</p>");
}

// Ambil dropdown data
$pengrajin = db_fetch_all("SELECT id, name FROM artisans ORDER BY name ASC");
$kategori  = db_fetch_all("SELECT id, name FROM craft_categories ORDER BY name ASC");
$region    = db_fetch_all("SELECT id, name FROM regions ORDER BY name ASC");

$error = '';
$success = '';

// Proses ketika submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF protection
    require_csrf();

    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price       = trim($_POST['price']);
    $artisan_id  = $_POST['artisan_id'];
    $category_id = $_POST['category_id'];
    $region_id   = $_POST['region_id'];

    if ($title === '' || $price === '' || !$artisan_id || !$category_id || !$region_id) {
        $error = "Semua field wajib diisi.";
    }

    // Upload gambar jika ada file baru
    $imagePath = $data['image_path']; // gunakan gambar lama
    if (!$error && !empty($_FILES['image']['name'])) {

        $uploadDir = __DIR__ . '/../public/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $tmp  = $_FILES['image']['tmp_name'];
        $name = $_FILES['image']['name'];
        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed)) {
            $error = "Format gambar tidak didukung.";
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

    if (!$error) {
        db_exec("
            UPDATE crafts 
            SET 
                title = :title,
                description = :desc,
                price = :price,
                artisan_id = :artisan,
                region_id = :region,
                category_id = :category,
                image_path = :image
            WHERE id = :id
        ", [
            'title'    => $title,
            'desc'     => $description,
            'price'    => $price,
            'artisan'  => $artisan_id,
            'region'   => $region_id,
            'category' => $category_id,
            'image'    => $imagePath,
            'id'       => $id
        ]);

        header("Location: " . $BASE_URL . "admin/kerajinan-list.php?updated=1");
        exit;
    }
}

require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">Edit Kerajinan</h1>

<?php if ($error): ?>
    <div class="kb-alert kb-alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="kb-form">
    <?= csrf_field() ?>

    <label>Judul Kerajinan</label>
    <input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" required>

    <label>Deskripsi</label>
    <textarea name="description"><?= htmlspecialchars($data['description']) ?></textarea>

    <label>Harga (Rp)</label>
    <input type="number" name="price" value="<?= $data['price'] ?>" required>

    <label>Pilih Pengrajin</label>
    <select name="artisan_id" required>
        <option value="">-- Pilih Pengrajin --</option>
        <?php foreach ($pengrajin as $p): ?>
            <option value="<?= $p['id'] ?>" <?= $p['id'] == $data['artisan_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Pilih Daerah</label>
    <select name="region_id" required>
        <option value="">-- Pilih Daerah --</option>
        <?php foreach ($region as $r): ?>
            <option value="<?= $r['id'] ?>" <?= $r['id'] == $data['region_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Pilih Kategori</label>
    <select name="category_id" required>
        <option value="">-- Pilih Kategori --</option>
        <?php foreach ($kategori as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id'] == $data['category_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Gambar Saat Ini</label>
    <?php if ($data['image_path']): ?>
        <img src="<?= $BASE_URL . $data['image_path'] ?>" style="width:120px; border-radius: 8px;">
    <?php else: ?>
        <p><i>Tidak ada gambar</i></p>
    <?php endif; ?>

    <label>Upload Gambar Baru (opsional)</label>
    <input type="file" name="image" accept="image/*">

    <button class="kb-btn kb-btn-success" type="submit">Update</button>
    <a href="<?= $BASE_URL . 'admin/kerajinan-list.php' ?>" class="kb-btn kb-btn-outline">← Kembali</a>
</form>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
