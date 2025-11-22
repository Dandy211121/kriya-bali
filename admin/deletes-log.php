<?php
require_once __DIR__ . '/../config/db.php';

// Hanya superadmin
require_superadmin();

$q = trim($_GET['q'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

// WHERE filter
$where = '';
$params = [];

if ($q !== '') {
    $where = "WHERE table_name LIKE :q OR deleted_by_name LIKE :q OR ip_address LIKE :q";
    $params['q'] = "%$q%";
}

// total count
$countRow = db_fetch("SELECT COUNT(*) AS c FROM deletes_log $where", $params);
$total = $countRow['c'] ?? 0;
$totalPages = max(1, ceil($total / $perPage));

// data
$sql = "SELECT * FROM deletes_log $where ORDER BY id DESC LIMIT $perPage OFFSET $offset";
$rows = ($q !== '') ? db_fetch_all($sql, $params) : db_fetch_all($sql);

require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">
    <i class="bi bi-trash2-fill"></i>
    Log Penghapusan
</h1>

<p class="kb-muted" style="margin-top:-6px;">
    Semua aktivitas penghapusan akan dicatat di sini.
</p>

<div class="kb-admin-card" style="margin-top:20px;">

    <!-- Search -->
    <form method="get" style="display:flex; gap:10px; margin-bottom:18px;">
        <input 
            type="text" 
            name="q" 
            value="<?= htmlspecialchars($q) ?>" 
            class="kb-admin-input"
            style="flex:1;"
            placeholder="Cari nama tabel, user, atau alamat IP..."
        >
        <button class="kb-btn kb-btn-primary">
            <i class="bi bi-search"></i> Cari
        </button>
    </form>

    <!-- Table -->
    <table class="kb-admin-table">
        <tr>
            <th>#</th>
            <th>Tabel</th>
            <th>ID Terhapus</th>
            <th>Dihapus Oleh</th>
            <th>User ID</th>
            <th>IP Address</th>
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

                    <td>
                        <span class="kb-badge kb-badge-brown">
                            <?= htmlspecialchars($r['table_name']) ?>
                        </span>
                    </td>

                    <td>
                        <b><?= $r['deleted_id'] ?></b>
                    </td>

                    <td><?= htmlspecialchars($r['deleted_by_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['user_id'] ?? '-') ?></td>

                    <td>
                        <span class="kb-badge kb-badge-gold">
                            <?= htmlspecialchars($r['ip_address'] ?? '-') ?>
                        </span>
                    </td>

                    <td><?= htmlspecialchars($r['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>

    <!-- Pagination -->
    <div style="margin-top:20px; text-align:center;">
        <?php if ($page > 1): ?>
            <a class="kb-btn kb-btn-outline" 
               href="?q=<?= urlencode($q) ?>&page=<?= $page-1 ?>">
               &laquo; Prev
            </a>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
            <a class="kb-btn kb-btn-outline" 
               href="?q=<?= urlencode($q) ?>&page=<?= $page+1 ?>">
               Next &raquo;
            </a>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
