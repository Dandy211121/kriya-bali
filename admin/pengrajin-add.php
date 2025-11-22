<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$GLOBALS['active_menu'] = 'pengrajin';
require_once __DIR__ . '/_layout_start.php';

$regions = db_fetch_all("SELECT id, name FROM regions ORDER BY name");
?>

<h1 class="kb-admin-title">Tambah Pengrajin</h1>

<div class="kb-admin-card">

    <form method="POST" action="pengrajin-save.php" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <label>Nama Pengrajin</label>
        <input type="text" name="name" required>

        <label>Daerah</label>
        <select name="region_id" required>
            <option value="">Pilih Daerah</option>
            <?php foreach ($regions as $r): ?>
                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Deskripsi</label>
        <textarea name="description" rows="4"></textarea>

        <label>Foto Pengrajin</label>
        <input type="file" name="image">

        <div style="margin-top: 20px; display: flex; gap: 12px;">
            <button class="kb-admin-btn">
                <i class="bi bi-check-circle"></i> Simpan
            </button>

            <a href="pengrajin-list.php" class="kb-btn-delete" style="text-decoration:none;">
                <i class="bi bi-x-circle"></i> Batal
            </a>
        </div>

    </form>

</div>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
