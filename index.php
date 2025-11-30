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

        <h1 class="fw-bold display-5" style="text-transform: uppercase;">
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
<section class="container-fluid py-5" style="background-color: #FFF7E6;">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@400;600&display=swap');

        /* Judul Section & Kartu */
        .font-serif-modern {
            font-family: 'Playfair Display', serif;
            color: #8B5E34; /* Coklat Kriya Bali */
            font-weight: 700;
        }

        /* Teks Deskripsi */
        .font-sans-modern {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #6C757D; /* Abu-abu lembut */
            line-height: 1.6;
            font-size: 0.95rem;
        }

        /* Kartu Modern Interaktif */
        .card-modern {
            border: none;
            border-radius: 20px;
            background: #FFFFFF;
            box-shadow: 0 10px 30px rgba(139, 94, 52, 0.05); /* Bayangan sangat halus */
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            padding: 40px 25px;
            height: 100%;
            position: relative; /* Wajib untuk stretched-link */
            cursor: pointer;    /* Ubah kursor jadi tangan */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card-modern:hover {
            transform: translateY(-10px); /* Naik saat hover */
            box-shadow: 0 20px 40px rgba(139, 94, 52, 0.15);
        }

        /* Efek Hover Tombol saat Kartu Disorot */
        .card-modern:hover .btn-modern-outline {
            background-color: #D4A15A;
            color: #fff;
            border-color: #D4A15A;
        }

        /* Tombol Outline Modern */
        .btn-modern-outline {
            border: 1px solid #D4A15A;
            color: #8B5E34;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            border-radius: 50px; /* Bentuk Pill */
            padding: 12px 30px;
            background: transparent;
            transition: all 0.3s ease;
            width: 100%;
            display: block;
            margin-top: auto; /* Dorong ke bawah */
        }
        
        /* Garis Bawah Judul */
        .title-underline {
            width: 70px; 
            height: 4px; 
            background: #D4A15A; 
            margin: 15px auto; 
            border-radius: 10px;
        }
    </style>

    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-5" style="color:#8B5E34;">EKSPLORASI KRIYA BALI</h2>
            <div class="title-underline"></div>
        </div>

        <div class="row g-4 justify-content-center">

            <div class="col-md-4">
                <div class="card-modern text-center">
                    <div class="fs-1 mb-3">👩‍🎨</div>
                    
                    <h4 class="fw-bold mb-3" style="color:#8B5E34;">Data Pengrajin</h4>
                    
                    <p class="font-sans-modern mb-4">
                        Temukan profil lengkap para pengrajin berbakat dari berbagai daerah di Bali.
                    </p>
                    
                    <a href="pengrajin.php" class="btn btn-modern-outline text-decoration-none stretched-link">
                        Lihat Pengrajin
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-modern text-center">
                    <div class="fs-1 mb-3">🏺</div>
                    
                    <h4 class="fw-bold mb-3" style="color:#8B5E34;">Galeri Kerajinan</h4>
                    
                    <p class="font-sans-modern mb-4">
                        Jelajahi koleksi karya seni mulai dari ukiran, anyaman, hingga lukisan handmade.
                    </p>
                    
                    <a href="kerajinan.php" class="btn btn-modern-outline text-decoration-none stretched-link">
                        Lihat Koleksi
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-modern text-center">
                    <div class="fs-1 mb-3">ℹ️</div>
                    
                    <h4 class="fw-bold mb-3" style="color:#8B5E34;">Tentang Kami</h4>
                    
                    <p class="font-sans-modern mb-4">
                        Ketahui visi misi kami dalam melestarikan budaya Bali melalui teknologi digital.
                    </p>
                    
                    <a href="tentang.php" class="btn btn-modern-outline text-decoration-none stretched-link">
                        Baca Selengkapnya
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>


<?php include __DIR__.'/partials/footer.php'; ?>
