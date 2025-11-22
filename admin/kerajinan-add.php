<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$GLOBALS['active_menu'] = 'kerajinan';

$regions = db_fetch_all("SELECT * FROM regions ORDER BY name");
$artisans = db_fetch_all("SELECT * FROM artisans ORDER BY name");
$categories = db_fetch_all("SELECT * FROM craft_categories ORDER BY name");

require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">Tambah Kerajinan</h1>

<form method="POST" action="kerajinan-add-save.php" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Pengrajin</label>
        <select name="artisan_id" class="form-select" required>
            <option value="">Pilih Pengrajin</option>
            <?php foreach ($artisans as $a): ?>
                <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Daerah</label>
        <select name="region_id" class="form-select" required>
            <option value="">Pilih Daerah</option>
            <?php foreach ($regions as $r): ?>
                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Kategori</label>
        <select name="category_id" class="form-select" required>
            <option value="">Pilih Kategori</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Lokasi Toko</label>
        <input type="text" name="location_addres" class="form-control">
    </div>

    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="price" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Gambar</label>
        <input type="file" name="image" class="form-control">
    </div>

    <button class="kb-admin-btn">Simpan</button>
</form>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
