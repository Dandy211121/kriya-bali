<?php include __DIR__.'/partials/header.php'; ?>
<?php include __DIR__.'/partials/navbar.php'; ?>

<!-- ================================
     HERO SECTION
================================= -->
<section class="position-relative overflow-hidden" style="height: 520px;">
    
    <div id="heroSlides" class="carousel slide carousel-fade h-100" data-bs-ride="carousel">
        <div class="carousel-inner h-100">

            <div class="carousel-item active h-100">
                <div class="h-100 w-100" 
                     style="background: url('public/img/hero1.jpg') no-repeat center center; background-size: cover;">
                </div>
            </div>

            <div class="carousel-item h-100">
                <div class="h-100 w-100" 
                     style="background: url('public/img/hero2.jpg') no-repeat center center; background-size: cover;">
                </div>
            </div>

            <div class="carousel-item h-100">
                <div class="h-100 w-100" 
                     style="background: url('public/img/hero3.png') no-repeat center center; background-size: cover;">
                </div>
            </div>

        </div>
    </div>

    <div class="position-absolute top-0 start-0 w-100 h-100" 
         style="background: rgba(0,0,0,0.55); z-index: 2;"></div>

    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-center text-light px-3" 
         style="z-index: 3;">
        
        <img src="public/img/logo.png" 
             class="mb-3 shadow-lg" 
             style="width: 140px; height: 140px; border-radius: 25%; border: 3px solid #fff; object-fit: cover;">

        <h1 class="fw-bold display-5">
            Selamat Datang di Kriya Bali
        </h1>
        
        <p class="lead mt-2" style="max-width: 650px; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
            Platform digital yang didedikasikan untuk melestarikan dan mempromosikan keindahan karya tangan pengrajin Bali ke panggung dunia.
        </p>

        <?php if (!isset($_SESSION['user'])): ?>
            <a href="register.php" class="btn-gold-premium mt-4">
                Gabung Sekarang <i class="bi bi-arrow-right"></i>
            </a>
        <?php endif; ?>
    </div>

</section>

<style>
.bg-cover {
    background-size: cover;
    background-position: center;
}
</style>


<!-- ================================
     FITUR UTAMA
================================= -->
<section class="container py-5">

    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color:#8B5E34;">Eksplorasi Kriya Bali</h2>
        <div class="mx-auto" style="width:90px; height:5px; background:#D4A15A; border-radius:10px;"></div>
    </div>

    <div class="row g-4">

        <!-- Pengrajin -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 18px;">
                <div class="fs-1 mb-3 kb-icon" style="color:#8B5E34;">👩‍🎨</div>
                <h4 class="fw-bold" style="color:#8B5E34;">Data Pengrajin</h4>
                <p class="lead mx-auto">
                    Temukan profil lengkap para pengrajin berbakat dari berbagai daerah di Bali.
                </p>
                <a href="pengrajin.php" class="btn btn-outline-warning fw-bold px-4 rounded-pill mt-auto">
                    Lihat Pengrajin
                </a>
            </div>
        </div>

        <!-- Kerajinan -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 18px;">
                <div class="fs-1 mb-3 kb-icon" style="color:#8B5E34;">🏺</div>
                <h4 class="fw-bold" style="color:#8B5E34;">Galeri Kerajinan</h4>
                <p class="lead mx-auto">
                    Jelajahi koleksi karya seni mulai dari ukiran, anyaman, hingga lukisan.
                </p>
                <a href="kerajinan.php" class="btn btn-outline-warning fw-bold px-4 rounded-pill mt-auto">
                    Lihat Koleksi
                </a>
            </div>
        </div>

        <!-- Tentang -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 18px;">
                <div class="fs-1 mb-3 kb-icon" style="color:#8B5E34;">ℹ️</div>
                <h4 class="fw-bold" style="color:#8B5E34;">Tentang Kami</h4>
                <p class="lead mx-auto">
                    Ketahui visi misi kami dalam melestarikan budaya Bali melalui teknologi.
                </p>
                <a href="tentang.php" class="btn btn-outline-warning fw-bold px-4 rounded-pill mt-auto">
                    Baca Selengkapnya
                </a>
            </div>
        </div>

    </div>

</section>


<?php include __DIR__.'/partials/footer.php'; ?>
