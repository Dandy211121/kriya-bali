<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$GLOBALS['active_menu'] = 'kerajinan';

$id = intval($_GET['id'] ?? 0);

$data = db_fetch("
    SELECT * FROM crafts WHERE id = :id
", ['id' => $id]);

if (!$data) {
    die("<p>Kerajinan tidak ditemukan.</p>");
}

$regions    = db_fetch_all("SELECT id, name FROM regions ORDER BY name");
$categories = db_fetch_all("SELECT id, name FROM craft_categories ORDER BY name");
$artisans   = db_fetch_all("SELECT id, name FROM artisans ORDER BY name");

require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">Edit Kerajinan</h1>

<div class="kb-admin-card">

    <form method="POST" action="kerajinan-edit-save.php" enctype="multipart/form-data">
        
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <label>Judul Kerajinan</label>
        <input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" required>

        <label>Daerah</label>
        <select name="region_id" required>
            <?php foreach ($regions as $r): ?>
                <option value="<?= $r['id'] ?>" <?= $r['id'] == $data['region_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($r['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Kategori Kerajinan</label>
        <select name="category_id" required>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id'] == $data['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Pengrajin</label>
        <select name="artisan_id" required>
            <?php foreach ($artisans as $a): ?>
                <option value="<?= $a['id'] ?>" <?= $a['id'] == $data['artisan_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Harga</label>
        <input type="number" name="price" value="<?= $data['price'] ?>" required>

        <label>Deskripsi</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($data['description']) ?></textarea>

        <label>Foto Kerajinan (opsional)</label>
        <input type="file" name="image">

        <?php if ($data['image_path']): ?>
            <p>Foto Lama:</p>
            <img src="<?= asset($data['image_path']) ?>"
                 style="width:120px; border-radius:10px; margin-bottom:12px;">
        <?php endif; ?>

        <div style="margin-top:20px; display:flex; gap:12px;">
            <button class="kb-admin-btn">
                <i class="bi bi-save"></i> Update
            </button>

            <a href="kerajinan-list.php" class="kb-btn-delete">
                <i class="bi bi-x-circle"></i> Batal
            </a>
        </div>

    </form>

</div>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
