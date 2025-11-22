<?php
require_once __DIR__ . '/partials/header.php';
require_once __DIR__ . '/partials/navbar.php';

// Validasi ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data lengkap
$data = db_fetch("
    SELECT c.*, 
           a.name AS artisan_name,
           r.name AS region_name,
           cat.name AS category_name
    FROM crafts c
    LEFT JOIN artisans a ON a.id = c.artisan_id
    LEFT JOIN regions  r ON r.id = c.region_id
    LEFT JOIN craft_categories cat ON cat.id = c.category_id
    WHERE c.id = :id
", ['id' => $id]);

// Jika tidak ditemukan
if (!$data) {
    echo "<div class='container py-5 text-center'>
            <h3 class='text-danger'>Kerajinan tidak ditemukan.</h3>
          </div>";
    require_once __DIR__ . '/partials/footer.php';
    exit;
}

// FIX: Samakan nama kolom lokasi toko
$lokasi_toko = $data['location_address'] ?? null;
?>

<div class="container py-5">

    <!-- TITLE -->
    <h1 class="fw-bold text-center mb-4" style="color:#8B5E34;">
        <?= htmlspecialchars($data['title']) ?>
    </h1>

    <div class="row g-4">

        <!-- LEFT: IMAGE -->
        <div class="col-md-5 text-center">
            <?php if ($data['image_path']): ?>
                <img src="<?= asset($data['image_path']) ?>" 
                     class="shadow-lg"
                     style="width:100%; max-width:400px; border-radius:14px; object-fit:cover;">
            <?php else: ?>
                <div class="bg-secondary text-white p-5 rounded">
                    Tidak ada gambar
                </div>
            <?php endif; ?>
        </div>

        <!-- RIGHT: DETAIL INFO -->
        <div class="col-md-7">

            <!-- Badge kategori dan daerah -->
            <div class="mb-3">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-2">
                    <?= htmlspecialchars($data['category_name'] ?? 'Tidak ada kategori') ?>
                </span>

                <span class="badge bg-light text-dark border fw-semibold px-3 py-2 mb-2">
                    📍 <?= htmlspecialchars($data['region_name'] ?? '-') ?>
                </span>
            </div>

            <!-- Pengrajin -->
            <p class="fs-5">
                <b>Pengrajin:</b>  
                <span style="color:#8B5E34;">
                    <?= htmlspecialchars($data['artisan_name'] ?? '-') ?>
                </span>
            </p>

            <!-- Harga -->
            <p class="fs-5">
                <b>Harga:</b>  
                <span class="fw-bold" style="color:#B8863B;">
                    Rp <?= number_format($data['price'], 0, ',', '.') ?>
                </span>
            </p>

            <!-- Lokasi Toko -->
            <p class="fs-5 mt-3">
                <b>Lokasi Toko:</b><br>
                <span style="color:#5a4630;">
                    <?= $lokasi_toko ? htmlspecialchars($lokasi_toko) : 'Tidak ada lokasi toko.' ?>
                </span>
            </p>

            <!-- Deskripsi -->
            <?php if ($data['description']): ?>
            <div class="mt-4">
                <b class="d-block mb-2">Deskripsi Kerajinan:</b>
                <div class="p-3 rounded" 
                     style="background:#FFF3D9; border-left:5px solid #D4A15A;">
                    <?= nl2br(htmlspecialchars($data['description'])) ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Aksi -->
            <div class="mt-4 d-flex gap-3">

                <a href="<?= $BASE_URL ?>kerajinan.php" 
                   class="btn btn-outline-warning fw-bold px-4 rounded-pill">
                    ← Kembali
                </a>

                <?php if ($data['artisan_id']): ?>
                <a href="<?= $BASE_URL ?>pengrajin-detail.php?id=<?= $data['artisan_id'] ?>"
                   class="btn btn-warning fw-bold px-4 rounded-pill">
                    Profil Pengrajin →
                </a>
                <?php endif; ?>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
