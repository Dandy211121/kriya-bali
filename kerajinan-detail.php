<?php
require_once __DIR__ . '/partials/header.php';
require_once __DIR__ . '/partials/navbar.php';

// 1. Validasi ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 2. Ambil Data Kerajinan
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

if (!$data) {
    echo "<div class='container py-5 text-center'><h3 class='text-danger'>Kerajinan tidak ditemukan.</h3></div>";
    require_once __DIR__ . '/partials/footer.php';
    exit;
}

// 3. Ambil Data Review (Komentar)
$reviews = db_fetch_all("
    SELECT r.*, u.name as user_name 
    FROM craft_reviews r
    JOIN users u ON u.id = r.user_id
    WHERE r.craft_id = :cid
    ORDER BY r.created_at DESC
", ['cid' => $id]);

// 4. Hitung Rata-rata Rating
$total_rating = 0;
$count_rating = count($reviews);
if ($count_rating > 0) {
    foreach ($reviews as $rev) {
        $total_rating += $rev['rating'];
    }
    $avg_rating = round($total_rating / $count_rating, 1);
} else {
    $avg_rating = 0;
}

$lokasi_toko = $data['location_address'] ?? null;
?>

<style>
    /* Styling Link Kembali */
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
        transform: translateX(-5px); /* Efek geser kiri sedikit */
    }

    /* Styling Bintang */
    .star-gold { color: #FFC107; }
    .star-grey { color: #e4e5e9; }
    
    /* Styling Review Card */
    .review-card {
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
    }
    
    /* Styling Form Rating */
    .rating-select {
        display: flex;
        flex-direction: row-reverse;
        justify-content: start;
        gap: 5px;
    }
    .rating-select input { display: none; }
    .rating-select label {
        font-size: 2rem;
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s;
    }
    .rating-select input:checked ~ label,
    .rating-select label:hover,
    .rating-select label:hover ~ label {
        color: #FFC107;
    }
</style>

<div class="container py-5">

    <div class="mb-4">
        <a href="kerajinan.php" class="btn-back-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Galeri
        </a>
    </div>

    <div class="text-center mb-4">
        <h1 class="fw-bold mb-2" style="color:#8B5E34; font-family: 'Playfair Display', serif;">
            <?= htmlspecialchars($data['title']) ?>
        </h1>
        
        <div class="d-flex justify-content-center align-items-center gap-2">
            <div class="fs-5 text-warning">
                <?php for($i=1; $i<=5; $i++): ?>
                    <?php if($i <= round($avg_rating)): ?>
                        <i class="bi bi-star-fill"></i>
                    <?php else: ?>
                        <i class="bi bi-star"></i>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            <span class="text-muted fw-bold">(<?= $avg_rating ?> / 5.0)</span>
            <span class="text-muted small">• <?= $count_rating ?> Ulasan</span>
        </div>
    </div>

    <div class="row g-5">

        <div class="col-md-5 text-center">
            <?php if ($data['image_path']): ?>
                <img src="<?= asset($data['image_path']) ?>" 
                     class="shadow-lg sticky-top"
                     style="width:100%; max-width:400px; border-radius:18px; object-fit:cover; top: 100px; z-index:1;">
            <?php else: ?>
                <div class="bg-secondary text-white p-5 rounded">Tidak ada gambar</div>
            <?php endif; ?>
        </div>

        <div class="col-md-7">

            <div class="p-4 mb-4" style="background: #FFFCF9; border: 1px solid #E5DCC5; border-radius: 18px;">
                <div class="mb-3">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-2"><?= htmlspecialchars($data['category_name']) ?></span>
                    <span class="badge bg-light text-dark border fw-semibold px-3 py-2">📍 <?= htmlspecialchars($data['region_name']) ?></span>
                </div>

                <p class="fs-5 mb-1"><b>Pengrajin:</b> <span style="color:#8B5E34;"><?= htmlspecialchars($data['artisan_name']) ?></span></p>
                <p class="fs-4 mb-3"><b>Harga:</b> <span class="fw-bold" style="color:#B8863B;">Rp <?= number_format($data['price'], 0, ',', '.') ?></span></p>
                <p class="mb-0"><b>Lokasi Toko:</b> <br><?= $lokasi_toko ? htmlspecialchars($lokasi_toko) : '-' ?></p>
            </div>

            <div class="mb-5">
                <h4 class="fw-bold mb-3" style="color:#8B5E34; font-family: 'Playfair Display', serif;">
                    Deskripsi Kerajinan
                </h4>

                <?php if (!empty($data['description'])): ?>
                    <div class="p-4" 
                         style="background-color: #fff; border: 1px solid #eee; border-radius: 12px; color: #555; line-height: 1.8; text-align: justify;">
                        <?= nl2br(htmlspecialchars($data['description'])) ?>
                    </div>
                <?php else: ?>
                    <div class="p-3 text-muted fst-italic border rounded" style="background: #f8f9fa;">
                        <i class="bi bi-info-circle me-1"></i> Belum ada deskripsi yang ditambahkan untuk item ini.
                    </div>
                <?php endif; ?>
            </div>

            <hr class="my-5" style="border-top: 2px dashed #D4A15A;">

            <h3 class="fw-bold mb-4" style="color:#8B5E34; font-family: 'Playfair Display', serif;">Ulasan Pengguna</h3>

            <?php if (is_logged_in()): ?>
                <div class="card border-0 shadow-sm p-4 mb-5" style="border-radius: 18px; background: #fffcf5;">
                    <h5 class="fw-bold mb-3">Tulis Ulasan Anda</h5>
                    
                    <form action="kerajinan-review-save.php" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="craft_id" value="<?= $id ?>">

                        <div class="mb-3">
                            <label class="form-label d-block fw-semibold">Rating:</label>
                            <div class="rating-select">
                                <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="Sempurna"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Sangat Bagus"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Bagus"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Cukup"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Buruk"><i class="bi bi-star-fill"></i></label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Komentar:</label>
                            <textarea name="comment" class="form-control" rows="3" placeholder="Ceritakan pengalaman Anda..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-warning text-white fw-bold px-4 rounded-pill">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            
            <?php else: ?>
                <div class="alert alert-warning d-flex align-items-center mb-5" role="alert">
                    <i class="bi bi-lock-fill fs-4 me-3"></i>
                    <div>
                        Ingin memberikan ulasan? <br>
                        Silakan <a href="login-select.php" class="fw-bold text-dark text-decoration-underline">Login terlebih dahulu</a>.
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($reviews): ?>
                <?php foreach ($reviews as $rev): ?>
                    <div class="review-card position-relative">
                        
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">
                                    <?= htmlspecialchars($rev['user_name']) ?>
                                    
                                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $rev['user_id']): ?>
                                        <span class="badge bg-light text-secondary border ms-2" style="font-size: 0.7rem;">Anda</span>
                                    <?php endif; ?>
                                </h6>
                                <small class="text-muted" style="font-size: 0.8rem;">
                                    <?= date('d M Y, H:i', strtotime($rev['created_at'])) ?>
                                </small>
                            </div>

                            <div class="text-warning">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <?= ($i <= $rev['rating']) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>' ?>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <p class="mt-3 mb-0 text-secondary" style="white-space: pre-wrap;"><?= htmlspecialchars($rev['comment']) ?></p>

                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $rev['user_id']): ?>
                            <div class="position-absolute bottom-0 end-0 p-3">
                                <form action="kerajinan-review-delete.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus ulasan ini?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="review_id" value="<?= $rev['id'] ?>">
                                    
                                    <button type="submit" class="btn btn-sm text-danger border-0 p-0" title="Hapus Ulasan">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-muted border rounded" style="background:#fafafa;">
                    <i class="bi bi-chat-square-text fs-3 d-block mb-2"></i>
                    Belum ada ulasan. Jadilah yang pertama memberikan review!
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>