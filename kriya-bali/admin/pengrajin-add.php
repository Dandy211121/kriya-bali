<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

// Ambil daftar region
$regions = db_fetch_all("SELECT id, name FROM regions ORDER BY name ASC");

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF protection
    require_csrf();
    $nama = trim($_POST['name']);
    $asal = $_POST['region_id'];

    if ($nama === '' || $asal === '') {
        $error = "Semua field wajib diisi.";
    } else {
        db_exec("INSERT INTO artisans (name, region_id) VALUES (:name, :region)", [
            'name'   => $nama,
            'region' => $asal
        ]);

        header("Location: " . $BASE_URL . "admin/pengrajin-list.php");
        exit;
    }
}

require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">Tambah Pengrajin</h1>

<?php if (!empty($error)): ?>
    <div class="kb-alert kb-alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" class="kb-form">
    <?= csrf_field() ?>

    <label>Nama Pengrajin:</label>
    <input type="text" name="name" required>

    <label>Asal Daerah:</label>
    <select name="region_id" required>
        <option value="">-- Pilih Daerah --</option>
        <?php foreach ($regions as $r): ?>
            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <button class="kb-btn kb-btn-success">Simpan</button>
    <a href="<?= $BASE_URL . 'admin/pengrajin-list.php' ?>" class="kb-btn kb-btn-outline">← Kembali</a>
</form>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
