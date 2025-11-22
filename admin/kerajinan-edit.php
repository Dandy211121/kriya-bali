<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$id = intval($_GET['id']);
$data = db_fetch("SELECT * FROM crafts WHERE id = :id", ['id' => $id]);

$regions = db_fetch_all("SELECT * FROM regions ORDER BY name");
$artisans = db_fetch_all("SELECT * FROM artisans ORDER BY name");
$categories = db_fetch_all("SELECT * FROM craft_categories ORDER BY name");

require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">Edit Kerajinan</h1>

<form method="POST" action="kerajinan-edit-save.php" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $data['id'] ?>">

    <div class="mb-3">
        <label>Judul</label>
        <input class="form-control" name="title" value="<?= htmlspecialchars($data['title']) ?>">
    </div>

    <div class="mb-3">
        <label>Pengrajin</label>
        <select name="artisan_id" class="form-select">
            <?php foreach ($artisans as $a): ?>
                <option value="<?= $a['id'] ?>" <?= $a['id']==$data['artisan_id']?"selected":"" ?>>
                    <?= htmlspecialchars($a['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Daerah</label>
        <select name="region_id" class="form-select">
            <?php foreach ($regions as $r): ?>
                <option value="<?= $r['id'] ?>" <?= $r['id']==$data['region_id']?"selected":"" ?>>
                    <?= htmlspecialchars($r['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Kategori</label>
        <select name="category_id" class="form-select">
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id']==$data['category_id']?"selected":"" ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Lokasi Toko</label>
        <input class="form-control" name="location_address"
               value="<?= htmlspecialchars($data['location_address']) ?>">
    </div>

    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control"><?= htmlspecialchars($data['description']) ?></textarea>
    </div>

    <div class="mb-3">
        <label>Harga</label>
        <input class="form-control" name="price" value="<?= $data['price'] ?>">
    </div>

    <button class="kb-admin-btn">Simpan</button>
    <a href="pengrajin-list.php" class="kb-admin-btn">Batal</a>
</form>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
