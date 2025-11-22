<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$GLOBALS['active_menu'] = 'pengrajin';

require_once __DIR__ . '/_layout_start.php';

// Ambil data pengrajin
$rows = db_fetch_all("
    SELECT a.*, r.name AS region_name
    FROM artisans a
    LEFT JOIN regions r ON r.id = a.region_id
    ORDER BY a.id DESC
");
?>

<h1 class="kb-admin-title">Daftar Pengrajin</h1>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="kb-admin-card" style="background:#f0fff4; border-left:5px solid #28a745;">
        <b style="color:#1b7f3c;">✔ Pengrajin berhasil dihapus.</b>
    </div>
<?php endif; ?>

<div style="margin-bottom: 25px;">
    <a href="<?= $BASE_URL ?>admin/pengrajin-add.php" class="kb-admin-btn">
        <i class="bi bi-person-plus" style="margin-right:6px;"></i> Tambah Pengrajin
    </a>
</div>

<table class="kb-admin-table">
    <thead>
        <tr>
            <th>Foto</th>
            <th>Nama</th>
            <th>Daerah</th>
            <th>Deskripsi</th>
            <th style="width:140px;">Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!$rows): ?>
            <tr>
                <td colspan="5" style="text-align:center; padding:25px;">
                    <em>Tidak ada pengrajin.</em>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $r): ?>
            <tr>
                <td style="width:70px;">
                    <?php if ($r['photo_path']): ?>
                        <img src="<?= asset($r['photo_path']) ?>" 
                             style="width:60px; height:60px; border-radius:8px; object-fit:cover;">
                    <?php else: ?>
                        <div style="width:60px; height:60px; background:#eee; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#aaa;">
                            <i class="bi bi-person" style="font-size:1.6rem;"></i>
                        </div>
                    <?php endif; ?>
                </td>

                <td><?= htmlspecialchars($r['name']) ?></td>
                <td><?= htmlspecialchars($r['region_name'] ?: '-') ?></td>

                <td style="max-width:260px; color:#7a6a50;">
                    <?= htmlspecialchars(substr($r['description'], 0, 70)) ?>...
                </td>

                <td>
                    <a href="<?= $BASE_URL ?>admin/pengrajin-edit.php?id=<?= $r['id'] ?>" 
                       class="kb-link" 
                       style="margin-right:12px;">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>

                    <form method="POST" action="<?= $BASE_URL ?>admin/pengrajin-delete.php"
                          style="display:inline;" 
                          onsubmit="return confirm('Yakin ingin menghapus pengrajin ini?');">

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
