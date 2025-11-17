<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

// Ambil semua pengrajin + nama daerah (JOIN)
$pengrajin = db_fetch_all("
    SELECT a.id, a.name, r.name AS region_name
    FROM artisans a
    LEFT JOIN regions r ON a.region_id = r.id
    ORDER BY a.id DESC
");

require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">Daftar Pengrajin</h1>

<a href="<?= $BASE_URL . 'admin/pengrajin-add.php' ?>" class="kb-btn kb-btn-success">+ Tambah Pengrajin</a>

<table class="table kb-admin-table">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Asal Daerah</th>
        <th>Aksi</th>
    </tr>

    <?php if (!$pengrajin): ?>
        <tr>
            <td colspan="4" class="kb-empty">Belum ada data</td>
        </tr>
    <?php else: ?>
        <?php foreach ($pengrajin as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['region_name'] ?? '-') ?></td>
                <td>
                    <a class="kb-link" 
                       href="<?= $BASE_URL . 'admin/pengrajin-edit.php?id=' . $p['id'] ?>">
                       <svg class="kb-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z" fill="#6b4a1e"/><path d="M20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" fill="#6b4a1e"/></svg>
                       <span>Edit</span>
                    </a>
                    |
                    <form method="POST" action="<?= $BASE_URL . 'admin/pengrajin-delete.php' ?>" style="display:inline" onsubmit="return confirm('Hapus data ini?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <button type="submit" class="kb-link-button kb-link-danger">
                            <svg class="kb-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 3v1H4v2h16V4h-5V3H9z" fill="#b32828"/><path d="M6 7v13a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6z" fill="#b32828"/></svg>
                            <span>Hapus</span>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
