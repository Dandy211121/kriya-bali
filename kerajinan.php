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

<style>
    /* STYLE BARU: FLAT DESIGN 
       Tidak ada bayangan, hanya border halus.
    */
    .filter-section {
        background-color: #FFFCF9; /* Cream sangat muda */
        border: 1px solid #E5DCC5; /* Garis batas tipis */
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 40px;
    }

    /* Styling Input agar menyatu */
    .form-control-kriya, .form-select-kriya {
        border: 1px solid #D1C4AD;
        background-color: #fff;
        padding: 10px 10px 10px 38px;
        border-radius: 8px;
        color: #5A3E1B;
        font-size: 0.95rem;
        width: 100%;
    }

    .form-control-kriya:focus, .form-select-kriya:focus {
        border-color: #B8863B;
        outline: none;
        box-shadow: 0 0 0 3px rgba(184, 134, 59, 0.1);
    }

    .filter-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #B8863B;
        z-index: 5;
    }

    /* Kartu Kerajinan (Tetap rapi) */
    .craft-card {
        border: 1px solid #eee;
        border-radius: 12px;
        transition: transform 0.2s;
        background: white;
    }
    .craft-card:hover {
        border-color: #D4A15A;
        transform: translateY(-5px);
    }
    .craft-img-wrapper {
        height: 220px;
        overflow: hidden;
        position: relative;
        border-radius: 12px 12px 0 0;
    }
    .craft-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Badge kecil di gambar */
    .badge-overlay {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(255, 255, 255, 0.9);
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: bold;
        color: #5A3E1B;
    }
</style>

<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold display-5" style="color:#8B5E34;">
            Galeri Kerajinan
        </h1>
        <div class="mx-auto my-3" style="width: 60px; height: 3px; background: #D4A15A;"></div>
        <p class="lead mx-auto">Koleksi karya seni otentik dari seluruh Bali.</p>
    </div>

    <div class="filter-section">
        <form method="GET">
            <div class="row g-3">
                
                <div class="col-md-3 position-relative">
                    <i class="bi bi-search filter-icon"></i>
                    <input type="text" name="q" class="form-control-kriya" 
                           placeholder="Cari kerajinan..." value="<?= htmlspecialchars($q) ?>">
                </div>

                <div class="col-md-3 position-relative">
                    <i class="bi bi-geo-alt filter-icon"></i>
                    <select name="region" class="form-select-kriya">
                        <option value="">Semua Daerah</option>
                        <?php foreach ($regions as $r): ?>
                            <option value="<?= $r['id'] ?>" <?= $region == $r['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($r['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3 position-relative">
                    <i class="bi bi-grid filter-icon"></i>
                    <select name="cat" class="form-select-kriya">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($cats as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $cat == $c['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3 position-relative">
                    <i class="bi bi-person filter-icon"></i>
                    <select name="artisan" class="form-select-kriya">
                        <option value="">Semua Pengrajin</option>
                        <?php foreach ($artisans as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= $artisan == $a['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div class="text-muted small">
                        Ditemukan <b><?= count($rows) ?></b> kerajinan
                    </div>
                    <div>
                        <a href="kerajinan.php" class="btn btn-link text-decoration-none text-muted me-2">
                            Reset
                        </a>
                        <button class="btn btn-warning text-white fw-bold px-4 rounded-pill">
                            Terapkan Filter
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <?php if ($rows): ?>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($rows as $row): ?>
            <div class="col">
                <div class="card h-100 craft-card shadow-none"> <div class="craft-img-wrapper">
                        <span class="badge-overlay">
                            <?= htmlspecialchars($row['cat_name']) ?>
                        </span>
                        <?php if ($row['image_path']): ?>
                            <img src="<?= asset($row['image_path']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <h5 class="fw-bold text-dark mb-1" style="font-family: 'Playfair Display', serif;">
                            <?= htmlspecialchars($row['title']) ?>
                        </h5>
                        
                        <p class="text-muted small mb-3">
                            <i class="bi bi-geo-alt me-1"></i> <?= htmlspecialchars($row['region_name']) ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <small class="text-muted" style="font-size:0.8rem">Harga</small>
                                <div class="fw-bold fs-5" style="color:#B8863B;">
                                    Rp <?= number_format($row['price'], 0, ',', '.') ?>
                                </div>
                            </div>
                            <a href="kerajinan-detail.php?id=<?= $row['id'] ?>" 
                               class="btn btn-outline-warning btn-sm rounded-pill px-3">
                               Detail
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>

        <div class="text-center py-5">
            <h3 class="fw-bold text-muted">Belum ada data</h3>
            <p>Coba reset filter pencarian Anda.</p>
        </div>

    <?php endif; ?>

</div>

<?php include __DIR__.'/partials/footer.php'; ?>