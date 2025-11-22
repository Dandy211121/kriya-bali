<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$id = intval($_GET['id']);
$data = db_fetch("SELECT * FROM artisans WHERE id = :id", ['id' => $id]);

$regions = db_fetch_all("SELECT id, name FROM regions ORDER BY name");

$GLOBALS['active_menu'] = 'pengrajin';
require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">Edit Pengrajin</h1>

<div class="kb-admin-card">

<form method="POST" action="pengrajin-edit-save.php" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $id ?>">

    <label>Nama Pengrajin</label>
    <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" class="form-control" required>

    <label>Daerah</label>
    <select name="region_id" class="form-control">
        <?php foreach ($regions as $r): ?>
            <option value="<?= $r['id'] ?>" <?= $r['id'] == $data['region_id'] ? 'selected' : '' ?>>
                <?= $r['name'] ?>
            </option>
        <?php endforeach ?>
    </select>

    <label>Deskripsi</label>
    <textarea name="description" rows="4" class="form-control"><?= htmlspecialchars($data['description']) ?></textarea>

    <label>Lokasi</label>
    <input type="text" name="lokasi" class="form-control" value="<?= htmlspecialchars($data['lokasi']) ?>">

    <label class="mt-3">Foto Baru (opsional)</label>
    <input type="file" name="image" class="form-control">

    <?php if ($data['photo_path']): ?>
        <img src="<?= asset($data['photo_path']) ?>" width="150" class="mt-3 rounded shadow">
    <?php endif; ?>

    <div class="mt-4">
        <button class="kb-admin-btn">Simpan</button>
        <a href="pengrajin-list.php" class="kb-admin-btn">Batal</a>
    </div>
</form>

</div>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
