<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

// Validasi ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data pengrajin
$row = db_fetch("SELECT * FROM artisans WHERE id = :id", ['id' => $id]);

if (!$row) {
    die("<p style='color:red;'>Pengrajin tidak ditemukan.</p>");
}

// Ambil daftar region
$regions = db_fetch_all("SELECT id, name FROM regions ORDER BY name ASC");

// Proses form ketika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF protection
    require_csrf();
    $name = trim($_POST['name']);
    $region_id = $_POST['region_id'];

    if ($name === '' || $region_id === '') {
        $error = "Semua field wajib diisi.";
    } else {
        db_exec("
            UPDATE artisans 
            SET name = :name, region_id = :region 
            WHERE id = :id
        ", [
            'name'   => $name,
            'region' => $region_id,
            'id'     => $id
        ]);

        header("Location: " . $BASE_URL . "admin/pengrajin-list.php");
        exit;
    }
}

require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">Edit Pengrajin</h1>

<?php if (!empty($error)): ?>
    <div class="kb-alert kb-alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" class="kb-form">
    <?= csrf_field() ?>

    <label>Nama Pengrajin:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>

    <label>Asal Daerah:</label>
    <select name="region_id" required>
        <option value="">-- Pilih Daerah --</option>
        <?php foreach ($regions as $r): ?>
            <option value="<?= $r['id'] ?>" 
                <?= $r['id'] == $row['region_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="kb-btn kb-btn-primary">Simpan Perubahan</button>
    <a href="<?= $BASE_URL . 'admin/pengrajin-list.php' ?>" class="kb-btn kb-btn-outline">← Kembali</a>
</form>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
