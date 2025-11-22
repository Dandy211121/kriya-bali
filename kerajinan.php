<?php 
require_once __DIR__.'/partials/header.php';
require_once __DIR__.'/partials/navbar.php';

// Ambil filter
$q       = $_GET['q'] ?? '';
$region  = $_GET['region'] ?? '';
$cat     = $_GET['cat'] ?? '';
$artisan = $_GET['artisan'] ?? '';

// Dropdown data
$regions  = db_fetch_all("SELECT id,name FROM regions ORDER BY name");
$cats     = db_fetch_all("SELECT id,name FROM craft_categories ORDER BY name");
$artisans = db_fetch_all("SELECT id,name FROM artisans ORDER BY name");

// Base SQL
$sql = "
    SELECT cr.*, 
           ar.name AS artisan_name, 
           r.name AS region_name, 
           cc.name AS cat_name
    FROM crafts cr
    LEFT JOIN artisans ar ON ar.id = cr.artisan_id
    LEFT JOIN regions   r ON r.id = cr.region_id
    LEFT JOIN craft_categories cc ON cc.id = cr.category_id
    WHERE 1
";

$params = [];

// Filters
if ($q !== '') {
    $sql .= " AND cr.title LIKE ?";
    $params[] = "%$q%";
}
if ($region !== '') {
    $sql .= " AND cr.region_id = ?";
    $params[] = $region;
}
if ($cat !== '') {
    $sql .= " AND cr.category_id = ?";
    $params[] = $cat;
}
if ($artisan !== '') {
    $sql .= " AND cr.artisan_id = ?";
    $params[] = $artisan;
}

$rows = db_fetch_all($sql, $params);
?>

<div class="container py-5">

    <!-- TITLE -->
    <h1 class="header-title">Galeri Kerajinan Bali</h1>
    <div class="header-line"></div>
    <p class="text-center" style="max-width:650px; margin:auto; color:#92400e;">
        Temukan berbagai karya seni tradisional Bali, mulai dari ukiran, anyaman, hingga lukisan handmade.
    </p>

    <!-- FILTER FORM -->
    <form method="GET" class="filter-box mt-4">

        <div class="row g-3">

            <!-- Search -->
            <div class="col-md-3 position-relative">
                <i class="bi bi-search icon"></i>
                <input 
                    type="text" 
                    name="q"
                    class="form-control input"
                    placeholder="Cari kerajinan..."
                    value="<?= htmlspecialchars($q) ?>">
            </div>

            <!-- Region -->
            <div class="col-md-3 position-relative">
                <i class="bi bi-geo-alt icon"></i>
                <select name="region" class="form-select select">
                    <option value="">Semua Daerah</option>
                    <?php foreach ($regions as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= $region == $r['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Category -->
            <div class="col-md-3 position-relative">
                <i class="bi bi-tag icon"></i>
                <select name="cat" class="form-select select">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($cats as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $cat == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Artisan -->
            <div class="col-md-3 position-relative">
                <i class="bi bi-person icon"></i>
                <select name="artisan" class="form-select select">
                    <option value="">Semua Pengrajin</option>
                    <?php foreach ($artisans as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= $artisan == $a['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <div class="text-end mt-3">
            <button class="btn btn-warning fw-bold px-4">Terapkan Filter</button>
            <a href="kerajinan.php" class="btn btn-outline-warning fw-bold px-4 ms-2">Reset</a>
        </div>
    </form>

    <!-- RESULTS COUNT -->
    <p class="result-count mt-3">
        Menampilkan <b><?= count($rows) ?></b> kerajinan
    </p>

    <!-- LIST DATA -->
    <?php if ($rows): ?>

        <div class="row row-cols-1 row-cols-md-3 g-4 mt-2">

            <?php foreach ($rows as $row): ?>
            <div class="col">
                <div class="card shadow-sm h-100">

                    <!-- Image -->
                    <?php if ($row['image_path']): ?>
                        <img src="<?= asset($row['image_path']) ?>" 
                             class="card-img-top" 
                             style="height: 200px; object-fit: cover; border-radius: 12px 12px 0 0;">
                    <?php else: ?>
                        <div class="bg-secondary text-white p-5 text-center" 
                             style="border-radius: 12px 12px 0 0;">
                            Tidak ada gambar
                        </div>
                    <?php endif; ?>

                    <!-- Content -->
                    <div class="card-body">

                        <h5 class="card-title fw-bold" style="color:#8B5E34;">
                            <?= htmlspecialchars($row['title']) ?>
                        </h5>

                        <p class="text-muted small">📍 <?= htmlspecialchars($row['region_name']) ?></p>
                        <p class="text-muted small">🎨 <?= htmlspecialchars($row['cat_name']) ?></p>
                        <p class="text-muted small">👤 <?= htmlspecialchars($row['artisan_name']) ?></p>

                        <a href="<?= $BASE_URL ?>kerajinan-detail.php?id=<?= $row['id'] ?>"
                           class="btn btn-outline-warning rounded-pill fw-bold px-3 mt-2">
                            Lihat Detail →
                        </a>

                    </div>

                </div>
            </div>
            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="empty mt-5">
            <div class="empty-icon">😔</div>
            <h3 class="fw-bold" style="color:#78350f;">Tidak Ada Kerajinan</h3>
            <p style="color:#b45309;">Tidak ditemukan kerajinan yang cocok dengan filter Anda.</p>
        </div>

    <?php endif; ?>

</div>

<?php include __DIR__.'/partials/footer.php'; ?>
