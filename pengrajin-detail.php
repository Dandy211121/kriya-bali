<?php
require_once __DIR__ . '/partials/header.php';
require_once __DIR__ . '/partials/navbar.php';

// Validasi ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data pengrajin
$data = db_fetch("
    SELECT a.*, r.name AS region_name
    FROM artisans a
    LEFT JOIN regions r ON r.id = a.region_id
    WHERE a.id = :id
", ['id' => $id]);

if (!$data) {
    echo "<div class='container py-5 text-center'>
            <h3 class='text-danger'>Pengrajin tidak ditemukan.</h3>
          </div>";
    require_once __DIR__ . '/partials/footer.php';
    exit;
}

// Ambil semua kerajinan milik pengrajin
$crafts = db_fetch_all("
    SELECT id, title, image_path, price 
    FROM crafts 
    WHERE artisan_id = :id
    ORDER BY id DESC
", ['id' => $id]);
?>

<div class="container py-5">

    <!-- PROFILE TITLE -->
    <h1 class="fw-bold text-center mb-4" style="color:#8B5E34;">
        <?= htmlspecialchars($data['name']) ?>
    </h1>

    <!-- PROFILE DETAILS -->
    <div class="row g-4 align-items-start">

        <!-- LEFT: IMAGE OR PLACEHOLDER -->
        <div class="col-md-4 text-center">
            <?php if (!empty($data['image_path'])): ?>
                <img src="<?= asset($data['image_path']) ?>" 
                     class="shadow-lg"
                     style="width:100%; max-width:350px; border-radius:14px; object-fit:cover;">
            <?php else: ?>
                <div class="p-5 text-center bg-secondary text-white rounded" 
                     style="border-radius:14px;">
                    Tidak ada foto pengrajin
                </div>
            <?php endif; ?>
        </div>

        <!-- RIGHT: INFORMATION -->
        <div class="col-md-8">

            <!-- Badges -->
            <div class="mb-3">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2">
                    📍 <?= htmlspecialchars($data['region_name'] ?? '-') ?>
                </span>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <b>Deskripsi Pengrajin:</b>
                <div class="p-3 mt-2 rounded"
                     style="background:#FFF3D9; border-left:5px solid #D4A15A;">

                    <?php if (!empty($data['description'])): ?>
                        <?= nl2br(htmlspecialchars($data['description'])) ?>
                    <?php else: ?>
                        <span class="text-muted">Tidak ada deskripsi.</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Back Button -->
            <a href="<?= $BASE_URL ?>pengrajin.php" 
               class="btn btn-outline-warning fw-bold rounded-pill px-4">
                ← Kembali
            </a>

        </div>
    </div>

    <!-- ===============================
         LIST OF CRAFTS BY THIS ARTISAN
    ==================================-->
    <div class="mt-5">

        <h3 class="fw-bold mb-3" style="color:#8B5E34;">
            Kerajinan oleh <?= htmlspecialchars($data['name']) ?>
        </h3>

        <?php if (!$crafts): ?>

            <div class="text-center py-5">
                <div class="empty-icon" style="font-size:60px;">😔</div>
                <h4 class="fw-bold" style="color:#78350f;">Belum Ada Kerajinan</h4>
                <p class="text-muted">Pengrajin ini belum menambahkan kerajinan apa pun.</p>
            </div>

        <?php else: ?>

            <div class="row row-cols-1 row-cols-md-3 g-4">

                <?php foreach ($crafts as $c): ?>
                <div class="col">
                    <div class="card shadow-sm h-100">

                        <?php if ($c['image_path']): ?>
                            <img src="<?= asset($c['image_path']) ?>" 
                                 class="card-img-top"
                                 style="height:200px; object-fit:cover; border-radius:14px 14px 0 0;">
                        <?php else: ?>
                            <div class="bg-secondary text-white p-4 text-center rounded-top">
                                Tidak ada gambar
                            </div>
                        <?php endif; ?>

                        <div class="card-body">

                            <h5 class="card-title fw-bold" style="color:#8B5E34;">
                                <?= htmlspecialchars($c['title']) ?>
                            </h5>

                            <p class="text-muted small">
                                💰 Rp <?= number_format($c['price'], 0, ',', '.') ?>
                            </p>

                            <a href="<?= $BASE_URL ?>kerajinan-detail.php?id=<?= $c['id'] ?>"
                               class="btn btn-outline-warning fw-bold rounded-pill px-3 mt-2">
                                Lihat Detail →
                            </a>

                        </div>

                    </div>
                </div>
                <?php endforeach; ?>

            </div>

        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
