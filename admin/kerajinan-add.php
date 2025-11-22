<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$GLOBALS['active_menu'] = 'kerajinan';

require_once __DIR__ . '/_layout_start.php';

$regions   = db_fetch_all("SELECT id, name FROM regions ORDER BY name");
$categories = db_fetch_all("SELECT id, name FROM craft_categories ORDER BY name");
$artisans  = db_fetch_all("SELECT id, name FROM artisans ORDER BY name");
?>

<h1 class="kb-admin-title">Tambah Kerajinan</h1>

<div class="kb-admin-card">

    <form method="POST" action="kerajinan-add-save.php" enctype="multipart/form-data">

        <?= csrf_field() ?>

        <label>Judul Kerajinan</label>
        <input type="text" name="title" required>

        <label>Daerah</label>
        <select name="region_id" required>
            <option value="">Pilih Daerah</option>
            <?php foreach ($regions as $r): ?>
                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Kategori Kerajinan</label>
        <select name="category_id" required>
            <option value="">Pilih Kategori</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Pengrajin</label>
        <select name="artisan_id" required>
            <option value="">Pilih Pengrajin</option>
            <?php foreach ($artisans as $a): ?>
                <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Harga</label>
        <input type="number" name="price" min="1000" required>

        <label>Deskripsi</label>
        <textarea name="description" rows="4"></textarea>

        <label>Foto Kerajinan</label>
        <input type="file" name="image" required>

        <div style="margin-top:20px; display:flex; gap:12px;">
            <button class="kb-admin-btn"><i class="bi bi-check-circle"></i> Simpan</button>

            <a href="kerajinan-list.php" class="kb-btn-delete">
                <i class="bi bi-x-circle"></i> Batal
            </a>
        </div>

    </form>

</div>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
