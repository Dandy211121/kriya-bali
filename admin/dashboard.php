<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

// Aktifkan menu
$GLOBALS['active_menu'] = 'dashboard';

require_once __DIR__ . '/_layout_start.php';

// Ambil data statistik
$total_artisans   = db_fetch("SELECT COUNT(*) AS total FROM artisans")['total'] ?? 0;
$total_crafts     = db_fetch("SELECT COUNT(*) AS total FROM crafts")['total'] ?? 0;
$total_categories = db_fetch("SELECT COUNT(*) AS total FROM craft_categories")['total'] ?? 0;
?>
    
<h1 class="kb-admin-title">Dashboard Admin</h1>
<p class="kb-muted">Selamat datang kembali, <b><?= htmlspecialchars($_SESSION['user']['name']) ?></b>.</p>

<!-- GRID CARDS -->
<div class="kb-admin-grid">

    <!-- Total Pengrajin -->
    <div class="admin-stat-card">
        <div class="admin-stat-title">
            <i class="bi bi-people-fill" style="font-size:1.5rem; margin-right:6px;"></i>
            Total Pengrajin
        </div>
        <div class="admin-stat-number"><?= $total_artisans ?></div>
    </div>

    <!-- Total Kerajinan -->
    <div class="admin-stat-card">
        <div class="admin-stat-title">
            <i class="bi bi-basket-fill" style="font-size:1.5rem; margin-right:6px;"></i>
            Total Kerajinan
        </div>
        <div class="admin-stat-number"><?= $total_crafts ?></div>
    </div>

    <!-- Total Kategori -->
    <div class="admin-stat-card">
        <div class="admin-stat-title">
            <i class="bi bi-tags-fill" style="font-size:1.5rem; margin-right:6px;"></i>
            Total Kategori
        </div>
        <div class="admin-stat-number"><?= $total_categories ?></div>
    </div>

</div>

<!-- SPACE UNTUK FEATURE KE DEPAN -->
<div style="margin-top:40px;">
    <h2 class="kb-admin-title" style="font-size:1.5rem;">Aktivitas Terbaru</h2>
    <p class="kb-muted">Fitur ini bisa digunakan untuk menampilkan log data terbaru atau grafik statistik.</p>

    <div style="
        background:white;
        padding:25px;
        border-radius:18px;
        box-shadow:0 4px 14px rgba(0,0,0,0.07);
        text-align:center;
        color:#9b7c57;">
        <i class="bi bi-graph-up" style="font-size:2.5rem; opacity:0.5;"></i>
        <p style="margin-top:12px;">Belum ada data aktivitas. Fitur ini dapat dikembangkan menjadi grafik statistik.</p>
    </div>
</div>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
