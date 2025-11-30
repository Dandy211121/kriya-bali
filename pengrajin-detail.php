<?php
require_once __DIR__.'/partials/header.php';
require_once __DIR__.'/partials/navbar.php';

$id = intval($_GET['id'] ?? 0);

// Ambil Data Pengrajin
$data = db_fetch("
    SELECT a.*, r.name AS region_name
    FROM artisans a
    LEFT JOIN regions r ON r.id = a.region_id
    WHERE a.id = :id
", ['id' => $id]);

if (!$data) {
    echo "<div class='container py-5 text-center'>
            <h3 class='text-danger'>Pengrajin tidak ditemukan.</h3>
            <a href='pengrajin.php' class='btn btn-outline-warning mt-3 rounded-pill'>Kembali</a>
          </div>";
    require_once __DIR__.'/partials/footer.php';
    exit;
}

// Ambil kerajinan buatan pengrajin ini
$crafts = db_fetch_all("
    SELECT id, title, image_path, price 
    FROM crafts 
    WHERE artisan_id = :id
    ORDER BY id DESC
", ['id' => $id]);
?>

<style>
    /* Tombol Kembali */
    .btn-back-link {
        color: #6c757d;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-back-link:hover {
        color: #8B5E34;
        transform: translateX(-5px);
    }

    /* Foto Profil Utama */
    .profile-img-main {
        width: 100%;
        max-width: 350px;
        height: 350px;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    /* Kartu Kerajinan Interaktif */
    .craft-card-interactive {
        position: relative; /* Wajib untuk stretched-link */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 16px;
        cursor: pointer;
    }
    
    .craft-card-interactive:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(139, 94, 52, 0.15) !important;
    }

    /* Saat kartu dihover, tombol berubah warna */
    .craft-card-interactive:hover .btn-action {
        background-color: #B8863B;
        color: white;
        border-color: #B8863B;
    }
</style>

<div class="container py-5">

    <div class="mb-4">
        <a href="pengrajin.php" class="btn-back-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pengrajin
        </a>
    </div>

    <h1 class="fw-bold text-center mb-5" style="color:#8B5E34; text-transform:uppercase;">
        Profil Seniman
    </h1>

    <div class="row g-5 align-items-start">

        <div class="col-md-5 text-center">
            <?php if (!empty($data['photo_path'])): ?>
                <img src="<?= asset($data['photo_path']) ?>" class="profile-img-main">
            <?php else: ?>
                <div class="bg-light d-flex align-items-center justify-content-center text-muted rounded shadow-sm" style="width:100%; height:350px;">
                    <i class="bi bi-person display-1 opacity-25"></i>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-7">
            
            <h2 class="fw-bold mb-3" style="color:#8B5E34;">
                <?= htmlspecialchars($data['name']) ?>
            </h2>

            <div class="mb-4">
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold rounded-pill">
                    <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($data['region_name']) ?>
                </span>
            </div>

            <div class="mb-3">
                <h6 class="fw-bold text-muted text-uppercase small">Lokasi Workshop</h6>
                <p class="fs-5 text-dark">
                    <?= !empty($data['lokasi']) ? htmlspecialchars($data['lokasi']) : '-' ?>
                </p>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold text-muted text-uppercase small">Tentang Pengrajin</h6>
                <p class="text-secondary" style="line-height: 1.8;">
                    <?= nl2br(htmlspecialchars($data['description'] ?? 'Belum ada deskripsi.')) ?>
                </p>
            </div>

        </div>

    </div>

    <hr class="my-5" style="border-top: 2px dashed #D4A15A;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0" style="color:#8B5E34; text-transform:uppercase;">
            Karya Buatan <?= htmlspecialchars($data['name']) ?>
        </h3>
        <span class="badge bg-light text-dark border rounded-pill px-3">
            <?= count($crafts) ?> Item
        </span>
    </div>

    <?php if (!$crafts): ?>

        <div class="text-center py-5 bg-light rounded border border-dashed">
            <i class="bi bi-box-seam display-4 text-muted opacity-50 mb-3"></i>
            <h5 class="fw-bold text-muted">Belum ada koleksi</h5>
            <p class="text-muted small">Pengrajin ini belum menambahkan produk kerajinan.</p>
        </div>

    <?php else: ?>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($crafts as $c): ?>
            <div class="col">
                <div class="card shadow-sm h-100 craft-card-interactive">
                    
                    <?php if ($c['image_path']): ?>
                        <div style="height: 220px; overflow: hidden; border-radius: 16px 16px 0 0;">
                            <img src="<?= asset($c['image_path']) ?>" 
                                 class="card-img-top h-100 w-100"
                                 style="object-fit:cover;">
                        </div>
                    <?php else: ?>
                        <div class="bg-secondary text-white p-4 text-center h-100 d-flex align-items-center justify-content-center" style="border-radius: 16px 16px 0 0;">
                            Tidak ada gambar
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <h5 class="fw-bold text-dark mb-1">
                            <?= htmlspecialchars($c['title']) ?>
                        </h5>
                        <p class="text-warning fw-bold mb-3">
                            Rp <?= number_format($c['price'], 0, ',', '.') ?>
                        </p>

                        <a href="kerajinan-detail.php?id=<?= $c['id'] ?>"
                           class="btn btn-outline-warning w-100 rounded-pill fw-bold btn-sm btn-action stretched-link">
                           Lihat Detail
                        </a>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__.'/partials/footer.php'; ?>