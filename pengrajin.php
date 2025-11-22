<?php 
require_once __DIR__.'/partials/header.php';
require_once __DIR__.'/partials/navbar.php';

// Ambil parameter filter
$q      = $_GET['q'] ?? '';
$region = $_GET['region'] ?? '';

// Ambil dropdown daerah
$regions = db_fetch_all("SELECT id, name FROM regions ORDER BY name");

// Base SQL (TIDAK ADA kategori di pengrajin)
$sql = "
    SELECT a.*, 
           r.name AS region_name
    FROM artisans a
    LEFT JOIN regions r ON r.id = a.region_id
    WHERE 1
";

$params = [];

// Filter nama
if ($q !== '') {
    $sql .= " AND a.name LIKE ?";
    $params[] = "%$q%";
}

// Filter daerah
if ($region !== '') {
    $sql .= " AND a.region_id = ?";
    $params[] = $region;
}

$rows = db_fetch_all($sql, $params);
?>

<div class="container py-4">

    <h1 class="header-title">Daftar Pengrajin Tradisional Bali</h1>
    <div class="header-line"></div>

    <!-- Filter -->
    <form method="GET" class="filter-box mt-4">
        <div class="row g-3">

            <div class="col-md-6 position-relative">
                <input type="text" 
                       name="q" 
                       class="form-control input"
                       placeholder="Cari pengrajin…"
                       value="<?= htmlspecialchars($q) ?>">
            </div>

            <div class="col-md-6 position-relative">
                <select name="region" class="form-select select">
                    <option value="">Semua Daerah</option>
                    <?php foreach ($regions as $r): ?>
                        <option value="<?= $r['id'] ?>"
                            <?= $region == $r['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <div class="text-end mt-3">
            <button class="btn btn-warning px-4 fw-bold">Terapkan Filter</button>
        </div>
    </form>

    <p class="result-count">
        Menampilkan <b><?= count($rows) ?></b> pengrajin
    </p>

    <?php if ($rows): ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">

        <?php foreach ($rows as $row): ?>
        <div class="col">
            <div class="card shadow-sm h-100">

                <div class="card-body">
                    <h5 class="card-title fw-bold" style="color:#8B5E34;">
                        <?= htmlspecialchars($row['name']) ?>
                    </h5>

                    <p class="text-muted mb-2">
                        📍 <?= htmlspecialchars($row['region_name']) ?>
                    </p>

                    <a href="<?= $BASE_URL ?>kerajinan.php?artisan=<?= $row['id'] ?>"
                       class="btn btn-outline-warning fw-bold mt-3 rounded-pill">
                        Lihat Kerajinan →
                    </a>
                </div>

            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <?php else: ?>
        <div class="empty">
            <h3 class="fw-bold" style="color:#78350f;">Tidak Ada Pengrajin</h3>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__.'/partials/footer.php'; ?>
