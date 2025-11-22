<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$GLOBALS['active_menu'] = 'kerajinan';
require_once __DIR__ . '/_layout_start.php';

// Ambil data kerajinan
$rows = db_fetch_all("
    SELECT 
        cr.*, 
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

<div class="kb-admin-top-action">
    <a href="<?= $BASE_URL ?>admin/kerajinan-add.php" class="kb-admin-btn">
        <i class="bi bi-plus-circle"></i> Tambah Kerajinan
    </a>
</div>

<table class="kb-admin-table">
    <thead>
        <tr>
            <th class="kb-col-img">Gambar</th>
            <th class="kb-col-title">Judul</th>
            <th class="kb-col-region">Daerah</th>
            <th class="kb-col-category">Kategori</th>
            <th class="kb-col-artisan">Pengrajin</th>
            <th class="kb-col-location">Lokasi Toko</th>
            <th class="kb-col-price">Harga</th>
            <th class="kb-col-action">Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($rows as $r): ?>
        <tr>

            <!-- GAMBAR -->
            <td>
                <?php if ($r['image_path']): ?>
                    <img src="<?= asset($r['image_path']); ?>" class="kb-admin-thumb">
                <?php else: ?>
                    <div class="kb-admin-thumb bg-light d-flex justify-content-center align-items-center text-muted">
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

            <!-- LOKASI TOKO — FIXED -->
            <td>
                <?= !empty($r['location_address']) 
                    ? htmlspecialchars($r['location_address']) 
                    : "<span class='text-muted'>-</span>" ?>
            </td>

            <!-- HARGA -->
            <td>Rp <?= number_format($r['price'], 0, ',', '.') ?></td>

            <!-- AKSI -->
            <td>
                <a href="<?= $BASE_URL ?>admin/kerajinan-edit.php?id=<?= $r['id'] ?>" 
                 class="kb-btn-edit-sm me-2">
                    Edit
             </a>

            <form method="POST" action="<?= $BASE_URL ?>admin/kerajinan-delete.php"
                style="display:inline;"
                onsubmit="return confirm('Yakin ingin menghapus kerajinan ini?');">

                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                <?= csrf_field() ?>

                <button type="submit" class="kb-btn-delete-sm">
                    Hapus
                </button>
            </form>
        </td>


        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
