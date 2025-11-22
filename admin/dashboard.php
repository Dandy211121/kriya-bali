<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$GLOBALS['active_menu'] = 'dashboard';
require_once __DIR__ . '/_layout_start.php';

// Hitung total data
$total_pengrajin = db_fetch("SELECT COUNT(*) AS total FROM artisans")['total'];
$total_kerajinan = db_fetch("SELECT COUNT(*) AS total FROM crafts")['total'];
$total_kategori  = db_fetch("SELECT COUNT(*) AS total FROM craft_categories")['total'];
?>

<h1 class="kb-admin-title">Dashboard Admin</h1>

<p class="kb-muted">
    Selamat datang kembali, <b><?= htmlspecialchars($_SESSION['user']['name']) ?></b>.
</p>

<!-- STATISTICS CARDS IN FULL WIDTH STYLE -->
<div class="kb-dashboard-stats">

    <div class="kb-stat-card">
        <div class="kb-stat-icon"><i class="bi bi-people"></i></div>
        <div class="kb-stat-info">
            <div class="kb-stat-label">Total Pengrajin</div>
            <div class="kb-stat-number"><?= $total_pengrajin ?></div>
        </div>
    </div>

    <div class="kb-stat-card">
        <div class="kb-stat-icon"><i class="bi bi-basket"></i></div>
        <div class="kb-stat-info">
            <div class="kb-stat-label">Total Kerajinan</div>
            <div class="kb-stat-number"><?= $total_kerajinan ?></div>
        </div>
    </div>

    <div class="kb-stat-card">
        <div class="kb-stat-icon"><i class="bi bi-tags"></i></div>
        <div class="kb-stat-info">
            <div class="kb-stat-label">Total Kategori</div>
            <div class="kb-stat-number"><?= $total_kategori ?></div>
        </div>
    </div>

</div>

<h2 class="kb-section-title">Aktivitas Terbaru</h2>

<div class="kb-activity-box">
    <div class="kb-activity-empty">
        <i class="bi bi-graph-up"></i>
        <p>Belum ada data aktivitas. Fitur ini dapat dikembangkan menjadi grafik statistik.</p>
    </div>
</div>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
