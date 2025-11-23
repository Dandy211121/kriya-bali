<?php 
require_once __DIR__.'/partials/header.php';
require_once __DIR__.'/partials/navbar.php';

// Ambil parameter filter
$q      = $_GET['q'] ?? '';
$region = $_GET['region'] ?? '';

// Ambil dropdown daerah
$regions = db_fetch_all("SELECT id, name FROM regions ORDER BY name");

// Base SQL
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

<style>
    /* STYLE FLAT DESIGN */
    .filter-section {
        background-color: #FFFCF9;
        border: 1px solid #E5DCC5;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 40px;
    }

    /* Styling Input */
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

    /* Kartu Pengrajin */
    .artisan-card {
        border: 1px solid #eee;
        border-radius: 12px;
        transition: transform 0.2s;
        background: white;
    }
    .artisan-card:hover {
        border-color: #D4A15A;
        transform: translateY(-5px);
    }
    .artisan-img-wrapper {
        height: 240px; /* Sedikit lebih tinggi untuk foto orang */
        overflow: hidden;
        position: relative;
        border-radius: 12px 12px 0 0;
    }
    .artisan-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    .artisan-card:hover .artisan-img-wrapper img {
        transform: scale(1.05);
    }
    
    /* Badge Daerah */
    .badge-overlay {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(255, 255, 255, 0.95);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #5A3E1B;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>

<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold display-5" style="color:#8B5E34;">
            Daftar Pengrajin
        </h1>
        <div class="mx-auto my-3" style="width: 60px; height: 3px; background: #D4A15A;"></div>
        <p class="lead mx-auto">Profil seniman dan pengrajin berbakat dari seluruh Bali.</p>
    </div>

    <div class="filter-section">
        <form method="GET">
            <div class="row g-3 align-items-center">

                <div class="col-md-5 position-relative">
                    <i class="bi bi-search filter-icon"></i>
                    <input type="text" 
                           name="q" 
                           class="form-control-kriya"
                           placeholder="Cari nama pengrajin..."
                           value="<?= htmlspecialchars($q) ?>">
                </div>

                <div class="col-md-4 position-relative">
                    <i class="bi bi-geo-alt filter-icon"></i>
                    <select name="region" class="form-select-kriya">
                        <option value="">Semua Daerah</option>
                        <?php foreach ($regions as $r): ?>
                            <option value="<?= $r['id'] ?>"
                                <?= $region == $r['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($r['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-warning text-white fw-bold w-100 rounded-pill">
                        Filter
                    </button>
                    <?php if($q != '' || $region != ''): ?>
                        <a href="pengrajin.php" class="btn btn-outline-secondary rounded-pill" title="Reset">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </form>
    </div>

    <div class="mb-4 text-muted small">
        Menampilkan <b><?= count($rows) ?></b> pengrajin
    </div>

    <?php if ($rows): ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">

        <?php foreach ($rows as $row): ?>
        <div class="col">
            <div class="card h-100 artisan-card shadow-none">

                <div class="artisan-img-wrapper">
                    <span class="badge-overlay">
                        <i class="bi bi-geo-alt me-1"></i>
                        <?= htmlspecialchars($row['region_name']) ?>
                    </span>

                    <?php if (!empty($row['photo_path'])): ?>
                        <img src="<?= asset($row['photo_path']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
                            <i class="bi bi-person fs-1 opacity-25"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-body d-flex flex-column">

                    <h5 class="card-title fw-bold mb-1" style="color:#5A3E1B; font-family: 'Playfair Display', serif;">
                        <?= htmlspecialchars($row['name']) ?>
                    </h5>

                    <p class="text-muted small mb-3">
                        <?= !empty($row['lokasi']) ? htmlspecialchars($row['lokasi']) : 'Lokasi tidak tersedia' ?>
                    </p>

                    <div class="mt-auto border-top pt-3 text-end">
                        <a href="<?= $BASE_URL ?>pengrajin-detail.php?id=<?= $row['id'] ?>"
                           class="btn btn-outline-warning btn-sm rounded-pill px-4 fw-bold">
                            Lihat Profil <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-person-x display-1 text-muted opacity-25"></i>
            <h3 class="fw-bold mt-3" style="color:#78350f;">Tidak Ada Pengrajin</h3>
            <p class="text-muted">Coba cari dengan kata kunci lain.</p>
            <a href="pengrajin.php" class="btn btn-outline-warning rounded-pill mt-2">Reset Filter</a>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__.'/partials/footer.php'; ?>