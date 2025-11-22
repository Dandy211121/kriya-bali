<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$GLOBALS['active_menu'] = 'kerajinan';

require_once __DIR__ . '/_layout_start.php';

// Ambil data kerajinan
$rows = db_fetch_all("
    SELECT cr.*, 
           ar.name AS artisan_name,
           r.name AS region_name,
           cat.name AS category_name
    FROM crafts cr
    LEFT JOIN artisans ar ON ar.id = cr.artisan_id
    LEFT JOIN regions r ON r.id = cr.region_id
    LEFT JOIN craft_categories cat ON cat.id = cr.category_id
    ORDER BY cr.id DESC
");
?>

<h1 class="kb-admin-title">Daftar Kerajinan</h1>
<?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="kb-admin-card" style="background:#f0fff4; border-left:5px solid #28a745; margin-bottom:20px;">
        <b style="color:#1b7f3c;">✔ Kerajinan berhasil dihapus.</b>
    </div>
<?php endif; ?>

<!-- Pesan sukses delete -->
<?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="kb-admin-card" style="background:#f0fff4; border-left:5px solid #28a745; margin-bottom:20px;">
        <b style="color:#1b7f3c;">✔ Kerajinan berhasil dihapus.</b>
    </div>
<?php endif; ?>

<div style="margin-bottom: 25px;">
    <a href="<?= $BASE_URL ?>admin/kerajinan-add.php" class="kb-admin-btn">
        <i class="bi bi-plus-circle"></i> Tambah Kerajinan
    </a>
</div>

<table class="kb-admin-table">
    <thead>
        <tr>
            <th>Gambar</th>
            <th>Judul</th>
            <th>Daerah</th>
            <th>Kategori</th>
            <th>Pengrajin</th>
            <th>Harga</th>
            <th style="width:140px;">Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!$rows): ?>
            <tr>
                <td colspan="7" style="text-align:center; padding:25px;">
                    <em>Tidak ada data kerajinan.</em>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $r): ?>
            <tr>

                <!-- GAMBAR -->
                <td style="width:80px;">
                    <?php if ($r['image_path']): ?>
                        <img src="<?= asset($r['image_path']) ?>" 
                             style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                    <?php else: ?>
                        <div style="width:70px; height:70px; background:#eee; display:flex; justify-content:center; align-items:center; border-radius:8px; color:#999;">
                            <i class="bi bi-image" style="font-size:1.5rem;"></i>
                        </div>
                    <?php endif; ?>
                </td>

                <!-- JUDUL -->
                <td><?= htmlspecialchars($r['title']) ?></td>

                <!-- DAERAH -->
                <td><?= htmlspecialchars($r['region_name'] ?? '-') ?></td>

                <!-- KATEGORI -->
                <td><?= htmlspecialchars($r['category_name'] ?? '-') ?></td>

                <!-- PENGRAJIN -->
                <td><?= htmlspecialchars($r['artisan_name'] ?? '-') ?></td>

                <!-- HARGA -->
                <td>
                    Rp <?= number_format($r['price'], 0, ',', '.') ?>
                </td>

                <!-- AKSI -->
                <td>
                    <a href="<?= $BASE_URL ?>admin/kerajinan-edit.php?id=<?= $r['id'] ?>" 
                       class="kb-link" style="margin-right:12px;">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>

                    <form method="POST" action="<?= $BASE_URL ?>admin/kerajinan-delete.php"
                          style="display:inline;"
                          onsubmit="return confirm('Yakin ingin menghapus kerajinan ini?');">

                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                        <?= csrf_field() ?>

                        <button class="kb-link-button kb-link-danger">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>

                </td>

            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
