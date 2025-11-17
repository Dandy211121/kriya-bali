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
    echo "<p class='kb-empty'>Pengrajin tidak ditemukan.</p>";
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

<h1 class="kb-title"><?= htmlspecialchars($data['name']) ?></h1>

<div class="kb-detail">
    <div class="kb-detail-info">

        <p><b>Asal Daerah:</b> 
            <?= htmlspecialchars($data['region_name'] ?? '-') ?>
        </p>

        <?php if (!empty($data['description'])): ?>
            <p><b>Deskripsi:</b><br>
                <?= nl2br(htmlspecialchars($data['description'])) ?>
            </p>
        <?php endif; ?>

        <a class="kb-btn kb-btn-outline" href="<?= $BASE_URL ?>pengrajin.php">← Kembali</a>
    </div>
</div>

<h2 class="kb-subtitle">Kerajinan oleh <?= htmlspecialchars($data['name']) ?></h2>

<?php if (!$crafts): ?>
    <p class="kb-empty">Pengrajin ini belum memiliki kerajinan.</p>
<?php else: ?>
    <div class="kb-grid three">
        <?php foreach ($crafts as $c): ?>
            <div class="kb-card">

                <?php if ($c['image_path']): ?>
                    <img src="<?= asset($c['image_path']) ?>" class="kb-card-img">
                <?php else: ?>
                    <div class="kb-no-image">Tidak ada gambar</div>
                <?php endif; ?>

                <div class="kb-card-body">
                    <h3><?= htmlspecialchars($c['title']) ?></h3>
                    <p>Rp <?= number_format($c['price'], 0, ',', '.') ?></p>

                    <a class="kb-btn kb-btn-small" 
                       href="<?= $BASE_URL ?>kerajinan-detail.php?id=<?= $c['id'] ?>">
                       Lihat Detail
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
