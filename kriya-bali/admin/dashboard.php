<?php
require_once __DIR__ . '/../config/db.php';
require_admin(); // hanya admin & superadmin boleh masuk

// Ambil nama dari session
$userName = $_SESSION['user']['name'] ?? 'Admin';

// Load layout admin
require_once __DIR__ . '/_layout_start.php';

// Tambahkan class active ke menu (hanya digunakan di file layout)
$GLOBALS['active_menu'] = 'dashboard';
?>

<h1 class="kb-admin-title">Dashboard Admin</h1>
<p class="kb-muted">Selamat datang, <b><?= htmlspecialchars($userName) ?></b>!</p>

<div class="kb-admin-grid">

    <!-- Total Pengrajin -->
    <div class="kb-admin-card">
        <h3>Total Pengrajin</h3>
        <p class="kb-number">
            <?php
            try {
                $total = db_fetch("SELECT COUNT(*) AS total FROM artisans")['total'] ?? 0;
                echo $total;
            } catch (PDOException $e) {
                echo "<span style='color:red;'>Error: tabel 'artisans' tidak ditemukan.</span>";
            }
            ?>
        </p>
    </div>

    <!-- Total Kerajinan -->
    <div class="kb-admin-card">
        <h3>Total Kerajinan</h3>
        <p class="kb-number">
            <?php
            try {
                $total = db_fetch("SELECT COUNT(*) AS total FROM crafts")['total'] ?? 0;
                echo $total;
            } catch (PDOException $e) {
                echo "<span style='color:red;'>Error: tabel 'crafts' tidak ditemukan.</span>";
            }
            ?>
        </p>
    </div>

    <!-- Total Kategori -->
    <div class="kb-admin-card">
        <h3>Total Kategori</h3>
        <p class="kb-number">
            <?php
            try {
                $total = db_fetch("SELECT COUNT(*) AS total FROM craft_categories")['total'] ?? 0;
                echo $total;
            } catch (PDOException $e) {
                echo "<span style='color:red;'>Error: tabel 'craft_categories' tidak ditemukan.</span>";
            }
            ?>
        </p>
    </div>

</div>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
