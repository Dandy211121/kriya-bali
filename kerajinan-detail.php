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
    echo "<p class='kb-empty'>Kerajinan tidak ditemukan.</p>";
    require_once __DIR__ . '/partials/footer.php';
    exit;
}
?>

<h1 class="kb-title"><?= htmlspecialchars($data['title']) ?></h1>

<div class="kb-detail">

    <div class="kb-detail-image">
        <?php if ($data['image_path']): ?>
            <img src="<?= asset($data['image_path']) ?>" 
                 alt="Gambar Kerajinan" 
                 style="max-width:350px; border-radius:10px;">
        <?php else: ?>
            <div class="kb-no-image">Tidak ada gambar</div>
        <?php endif; ?>
    </div>

    <div class="kb-detail-info">

        <p><b>Kategori:</b> 
            <?= htmlspecialchars($data['category_name'] ?? '-') ?>
        </p>

        <p><b>Pengrajin:</b> 
            <?= htmlspecialchars($data['artisan_name'] ?? '-') ?>
        </p>

        <p><b>Asal Daerah:</b> 
            <?= htmlspecialchars($data['region_name'] ?? '-') ?>
        </p>

        <p><b>Harga:</b> 
            Rp <?= number_format($data['price'], 0, ',', '.') ?>
        </p>

        <?php if ($data['description']): ?>
            <p><b>Deskripsi:</b><br>
                <?= nl2br(htmlspecialchars($data['description'])) ?>
            </p>
        <?php endif; ?>

        <a href="<?= $BASE_URL ?>kerajinan.php" 
           class="kb-btn kb-btn-outline">← Kembali</a>
    </div>

</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
