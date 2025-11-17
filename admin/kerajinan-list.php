<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

// Ambil semua kerajinan + kategori + pengrajin + daerah
$data = db_fetch_all("
    SELECT c.id, c.title, c.price, c.image_path,
           cat.name AS category_name,
           a.name AS artisan_name,
           r.name AS region_name
    FROM crafts c
    LEFT JOIN craft_categories cat ON c.category_id = cat.id
    LEFT JOIN artisans a ON c.artisan_id = a.id
    LEFT JOIN regions r ON c.region_id = r.id
    ORDER BY c.id DESC
");

require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">Daftar Kerajinan</h1>

<a href="<?= $BASE_URL . 'admin/kerajinan-add.php' ?>" class="kb-btn kb-btn-success">
    + Tambah Kerajinan
</a>

<table class="table kb-admin-table">
    <tr>
        <th>ID</th>
        <th>Judul</th>
        <th>Kategori</th>
        <th>Pengrajin</th>
        <th>Asal Daerah</th>
        <th>Harga</th>
        <th>Aksi</th>
    </tr>

<?php if (!$data): ?>
    <tr>
        <td colspan="7" class="kb-empty">Belum ada data kerajinan.</td>
    </tr>
<?php else: ?>
    <?php foreach ($data as $k): ?>
        <tr>
            <td><?= $k['id'] ?></td>
            <td><?= htmlspecialchars($k['title']) ?></td>
            <td><?= htmlspecialchars($k['category_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($k['artisan_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($k['region_name'] ?? '-') ?></td>
            <td>Rp <?= number_format($k['price'], 0, ',', '.') ?></td>

            <td>
                <a class="kb-link"
                   href="<?= $BASE_URL . 'admin/kerajinan-edit.php?id=' . $k['id'] ?>">
                   <svg class="kb-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z" fill="#6b4a1e"/><path d="M20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" fill="#6b4a1e"/></svg>
                   <span>Edit</span>
                </a>
                |
                <form method="POST" action="<?= $BASE_URL . 'admin/kerajinan-delete.php' ?>" style="display:inline" onsubmit="return confirm('Hapus data ini?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $k['id'] ?>">
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
