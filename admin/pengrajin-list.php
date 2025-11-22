<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

// Ambil data pengrajin + daerah
$rows = db_fetch_all("
    SELECT a.*, r.name AS region_name
    FROM artisans a
    LEFT JOIN regions r ON r.id = a.region_id
    ORDER BY a.id DESC
");

$GLOBALS['active_menu'] = 'pengrajin';
require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">Daftar Pengrajin</h1>

<!-- Tombol Tambah -->
<div class="kb-admin-top-action">
    <a href="pengrajin-add.php" class="kb-admin-btn">
        <i class="bi bi-plus-circle"></i> Tambah Pengrajin
    </a>
</div>

<table class="kb-admin-table">
    <thead>
        <tr>
            <th class="kb-col-img">Foto</th>
            <th class="kb-col-title">Nama</th>
            <th class="kb-col-region">Daerah</th>
            <th class="kb-col-location">Lokasi</th>
            <th class="kb-col-category">Deskripsi</th>
            <th class="kb-col-action">Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($rows as $r): ?>
        <tr>

            <!-- FOTO -->
            <td>
                <?php if ($r['photo_path']): ?>
                    <img src="<?= asset($r['photo_path']) ?>" class="kb-admin-thumb">
                <?php else: ?>
                    <div class="kb-admin-thumb bg-light d-flex justify-content-center align-items-center text-muted">
                        <i class="bi bi-person" style="font-size:1.5rem;"></i>
                    </div>
                <?php endif; ?>
            </td>

            <!-- NAMA -->
            <td><?= htmlspecialchars($r['name']) ?></td>

            <!-- DAERAH -->
            <td><?= htmlspecialchars($r['region_name'] ?? '-') ?></td>

            <!-- LOKASI -->
            <td><?= htmlspecialchars($r['lokasi'] ?? '-') ?></td>

            <!-- DESKRIPSI (ringkas) -->
            <td>
                <?= htmlspecialchars(strlen($r['description']) > 50
                    ? substr($r['description'], 0, 50) . '...' 
                    : $r['description']) ?>
            </td>

            <!-- AKSI -->
            <td>
                <a href="pengrajin-edit.php?id=<?= $r['id'] ?>" 
                   class="kb-btn-edit-sm me-2">Edit</a>

                <a href="pengrajin-delete.php?id=<?= $r['id'] ?>"
                   class="kb-btn-delete-sm"
                   onclick="return confirm('Yakin ingin menghapus?')">
                    Hapus
                </a>
            </td>

        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
