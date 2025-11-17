<?php
require_once __DIR__ . '/../config/db.php';

// Hanya superadmin yang boleh melihat log penghapusan
require_superadmin();

$q = trim($_GET['q'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

$where = '';
$params = [];
if ($q !== '') {
    $where = "WHERE table_name LIKE :q OR deleted_by_name LIKE :q OR ip_address LIKE :q";
    $params['q'] = "%$q%";
}

$countRow = db_fetch("SELECT COUNT(*) AS c FROM deletes_log $where", $params);
$total = $countRow['c'] ?? 0;
$totalPages = ($total > 0) ? ceil($total / $perPage) : 1;

$sql = "SELECT * FROM deletes_log $where ORDER BY id DESC LIMIT $perPage OFFSET $offset";
$rows = ($q !== '') ? db_fetch_all($sql, $params) : db_fetch_all($sql);

require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">Log Penghapusan</h1>

<form method="get" class="kb-admin-search" style="margin-bottom:16px;">
    <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari table, user, atau IP..." style="padding:8px 12px;border-radius:8px;border:1px solid #ddd;">
    <button class="kb-btn kb-btn-outline" type="submit">Cari</button>
</form>

<table class="table kb-admin-table">
    <tr>
        <th>#</th>
        <th>Tabel</th>
        <th>Deleted ID</th>
        <th>Deleted By</th>
        <th>User ID</th>
        <th>IP</th>
        <th>Waktu</th>
    </tr>

    <?php if (!$rows): ?>
        <tr>
            <td colspan="7" class="kb-empty">Belum ada log.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= htmlspecialchars($r['table_name']) ?></td>
                <td><?= $r['deleted_id'] ?></td>
                <td><?= htmlspecialchars($r['deleted_by_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($r['user_id'] ?? '-') ?></td>
                <td><?= htmlspecialchars($r['ip_address'] ?? '-') ?></td>
                <td><?= htmlspecialchars($r['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>

</table>

<div style="margin-top:16px;">
    <?php if ($page > 1): ?>
        <a class="kb-btn kb-btn-outline" href="?q=<?= urlencode($q) ?>&page=<?= $page-1 ?>">&laquo; Prev</a>
    <?php endif; ?>

    <?php if ($page < $totalPages): ?>
        <a class="kb-btn kb-btn-outline" href="?q=<?= urlencode($q) ?>&page=<?= $page+1 ?>">Next &raquo;</a>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
