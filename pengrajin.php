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
    /* STYLE FLAT DESIGN UNTUK FILTER */
    .filter-section {
        background-color: #FFFCF9;
        border: 1px solid #E5DCC5;
        border-radius: 18px;
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

    /* KARTU PENGRAJIN MODERN */
    .artisan-card {
        border: none;
        border-radius: 18px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: white;
        position: relative; /* Penting untuk stretched-link */
        cursor: pointer;    /* Mengubah kursor jadi tangan saat disorot */
    }
    
    /* Efek Hover: Kartu naik & bayangan menebal */
    .artisan-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(139, 94, 52, 0.15) !important;
    }

    /* Efek Hover: Tombol berubah warna saat kartu disorot */
    .artisan-card:hover .btn-action {
        background-color: #B8863B;
        color: white;
        border-color: #B8863B;
    }

    .artisan-img-wrapper {
        height: 240px;
        overflow: hidden;
        position: relative;
        border-radius: 18px 18px 0 0;
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
        top: 12px;
        left: 12px;
        background: rgba(255, 255, 255, 0.95);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #8B5E34;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        z-index: 2; /* Di atas stretched link */
        position: relative; /* Agar badge tetap bisa diklik terpisah jika perlu */
    }

    .btn-back-link {
        color: #6c757d; /* Abu-abu lembut */
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px; /* Jarak ke judul */
    }
    
    .btn-back-link:hover {
        color: #8B5E34; /* Berubah jadi Coklat Kriya Bali saat disorot */
        transform: translateX(-5px); /* Efek geser kiri sedikit */
    }
</style>

<div class="container py-5">

    <div>
        <a href="index.php" class="btn-back-link">
            <i class="bi bi-arrow-left"></i>Kembali ke Beranda
        </a>
    </div>

    <div class="text-center mb-5">
        <h2 class="fw-bold display-5" style="color:#8B5E34;">
            DAFTAR PENGRAJIN
        </h2>
        <div class="mx-auto mt-2" style="width:90px; height:5px; background:#D4A15A; border-radius:10px;"></div>
        <p class="text-muted mt-3">Profil seniman dan pengrajin berbakat dari seluruh Bali.</p>
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
            <div class="card border-0 shadow-sm h-100 artisan-card">

                <div class="artisan-img-wrapper">
                    <span class="badge-overlay">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
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

                <div class="card-body d-flex flex-column p-4 text-center">
                    
                    <h4 class="fw-bold mb-2" style="color:#8B5E34;">
                        <?= htmlspecialchars($row['name']) ?>
                    </h4>

                    <p class="text-muted small mb-4">
                        <?= !empty($row['lokasi']) ? htmlspecialchars($row['lokasi']) : 'Lokasi tidak tersedia' ?>
                    </p>

                    <div class="mt-auto">
                        <a href="<?= $BASE_URL ?>pengrajin-detail.php?id=<?= $row['id'] ?>"
                           class="btn btn-outline-warning fw-bold px-4 rounded-pill w-100 btn-action stretched-link">
                            Lihat Profil
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
            <h3 class="fw-bold mt-3" style="color:#8B5E34;">Tidak Ada Pengrajin !</h3>
            <p class="text-muted">Coba cari dengan kata kunci lain.</p>
            <a href="pengrajin.php" class="btn btn-outline-warning rounded-pill mt-2 fw-bold" style="text-transform:uppercase;">Reset Filter</a>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__.'/partials/footer.php'; ?>