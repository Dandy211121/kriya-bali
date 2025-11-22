<?php
require_once __DIR__.'/partials/header.php';
require_once __DIR__.'/partials/navbar.php';

$id = intval($_GET['id'] ?? 0);

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
    require_once __DIR__.'/partials/footer.php';
    exit;
}

// Ambil kerajinan
$crafts = db_fetch_all("
    SELECT id, title, image_path, price 
    FROM crafts 
    WHERE artisan_id = :id
    ORDER BY id DESC
", ['id' => $id]);
?>

<div class="container py-5">

    <h1 class="fw-bold text-center mb-4" style="color:#8B5E34;">
        <?= htmlspecialchars($data['name']) ?>
    </h1>

    <div class="row g-4">

        <!-- Foto -->
        <div class="col-md-4 text-center">
            <?php if (!empty($data['photo_path'])): ?>
                <img src="<?= asset($data['photo_path']) ?>"
                     style="width:100%; max-width:350px; border-radius:14px; object-fit:cover;"
                     class="shadow">
            <?php else: ?>
                <div class="bg-secondary text-white p-5 rounded">
                    Tidak ada foto
                </div>
            <?php endif; ?>
        </div>

        <!-- Informasi -->
        <div class="col-md-8">

            <p><b>Daerah:</b> <?= htmlspecialchars($data['region_name']) ?></p>

            <!-- 🔥 Lokasi Pengrajin -->
            <p><b>Lokasi:</b> 
                <?= htmlspecialchars($data['lokasi'] ?: '-') ?>
            </p>

            <p><b>Deskripsi:</b><br>
                <?= nl2br(htmlspecialchars($data['description'] ?? '-')) ?>
            </p>

            <a href="<?= $BASE_URL ?>pengrajin.php"
               class="btn btn-outline-warning fw-bold rounded-pill px-4 mt-3">
               ← Kembali
            </a>

        </div>

    </div>

    <hr class="my-5">

    <h3 class="fw-bold" style="color:#8B5E34;">Kerajinan oleh <?= htmlspecialchars($data['name']) ?></h3>

    <?php if (!$crafts): ?>

        <div class="text-center py-5">
            <h4 class="fw-bold">Belum ada kerajinan</h4>
            <p class="text-muted">Pengrajin ini belum menambahkan kerajinan apa pun.</p>
        </div>

    <?php else: ?>

        <div class="row row-cols-1 row-cols-md-3 g-4 mt-3">

            <?php foreach ($crafts as $c): ?>
            <div class="col">
                <div class="card shadow-sm h-100">

                    <?php if ($c['image_path']): ?>
                        <img src="<?= asset($c['image_path']) ?>" 
                             class="card-img-top"
                             style="height:200px; object-fit:cover;">
                    <?php else: ?>
                        <div class="bg-secondary text-white p-4 text-center">
                            Tidak ada gambar
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <h5 class="fw-bold"><?= htmlspecialchars($c['title']) ?></h5>
                        <p class="text-muted small">
                            Rp <?= number_format($c['price'], 0, ',', '.') ?>
                        </p>

                        <a href="<?= $BASE_URL ?>kerajinan-detail.php?id=<?= $c['id'] ?>"
                           class="btn btn-outline-warning fw-bold rounded-pill mt-2">
                           Lihat Detail →
                        </a>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__.'/partials/footer.php'; ?>
