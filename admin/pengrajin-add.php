<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$GLOBALS['active_menu'] = 'pengrajin';
require_once __DIR__ . '/_layout_start.php';

$regions = db_fetch_all("SELECT id, name FROM regions ORDER BY name");
?>

<h1 class="kb-admin-title">Tambah Pengrajin</h1>

<div class="kb-admin-card">

<form method="POST" action="pengrajin-add-save.php" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <label>Nama Pengrajin</label>
    <input type="text" name="name" required class="form-control">

    <label>Daerah</label>
    <select name="region_id" class="form-control" required>
        <option value="">Pilih Daerah</option>
        <?php foreach ($regions as $r): ?>
        <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
        <?php endforeach ?>
    </select>

    <label>Deskripsi</label>
    <textarea name="description" rows="4" class="form-control"></textarea>

    <label>Lokasi</label>
    <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Desa Mas, Ubud">

    <label class="mt-3">Foto Pengrajin</label>
    <input type="file" name="image" class="form-control">

    <div class="mt-4">
        <button class="kb-admin-btn">Simpan</button>
        <a href="pengrajin-list.php" class="kb-admin-btn">Batal</a>
    </div>
</form>

</div>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
