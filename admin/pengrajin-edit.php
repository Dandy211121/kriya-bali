<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$GLOBALS['active_menu'] = 'pengrajin';

$id = intval($_GET['id'] ?? 0);

$data = db_fetch("
    SELECT * FROM artisans WHERE id = :id
", ['id' => $id]);

if (!$data) {
    die("<p>Pengrajin tidak ditemukan.</p>");
}

$regions = db_fetch_all("SELECT id, name FROM regions ORDER BY name");

require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">Edit Pengrajin</h1>

<div class="kb-admin-card">

    <form method="POST" action="pengrajin-edit-save.php" enctype="multipart/form-data">

        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <label>Nama Pengrajin</label>
        <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>

        <label>Daerah</label>
        <select name="region_id" required>
            <option value="">Pilih Daerah</option>
            <?php foreach ($regions as $r): ?>
                <option value="<?= $r['id'] ?>" <?= $r['id'] == $data['region_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($r['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Deskripsi</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($data['description']) ?></textarea>

        <label>Foto Pengrajin (Opsional)</label>
        <input type="file" name="image"

        <!-- Preview Foto -->
        <?php if ($data['photo_path']): ?>
            <p>Foto Lama:</p>
            <img src="<?= asset($data['photo_path']) ?>" 
                 style="width:100px; border-radius:10px; margin-bottom:12px;">
        <?php endif; ?>

        <div style="margin-top: 20px; display: flex; gap: 12px;">
            <button class="kb-admin-btn">
                <i class="bi bi-save"></i> Update
            </button>

            <a href="pengrajin-list.php" class="kb-btn-delete" style="text-decoration:none;">
                <i class="bi bi-x-circle"></i> Batal
            </a>
        </div>

    </form>

</div>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
