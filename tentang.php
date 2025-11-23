<?php 
require_once __DIR__ . '/partials/header.php'; 
require_once __DIR__ . '/partials/navbar.php'; 
?>

<style>
    /* Membuat ikon berada dalam lingkaran emas */
    .icon-circle {
        width: 80px;
        height: 80px;
        background: #FFF3D9; /* Warna Cream Terang */
        color: #8B5E34;      /* Warna Coklat */
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 20px auto;
        font-size: 2.5rem;
        transition: 0.3s;
    }

    /* Efek saat kartu disorot */
    .feature-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(139, 94, 52, 0.1);
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(139, 94, 52, 0.15) !important;
        border-color: #D4A15A;
    }

    .feature-card:hover .icon-circle {
        background: #D4A15A; /* Berubah jadi emas saat hover */
        color: white;
    }
</style>

<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold display-5" style="color:#8B5E34;">
            Tentang Kriya Bali
        </h1>
        
        <div class="mx-auto my-3" style="width: 80px; height: 4px; background: #D4A15A; border-radius: 2px;"></div>

        <p class="lead mx-auto" style="max-width: 700px; color:#5A3E1B;">
            Kriya Bali adalah platform yang didedikasikan untuk <b>melestarikan</b>, <b>mengenalkan</b>,
            dan <b>mempermudah akses</b> terhadap kekayaan seni kerajinan tradisional Bali ke kancah global.
        </p>
    </div>

    <div class="row g-4 justify-content-center">

        <div class="col-md-4">
            <div class="card h-100 p-4 text-center border-0 shadow-sm feature-card" style="border-radius: 18px;">
                <div class="icon-circle">
                    <i class="bi bi-bullseye"></i> </div>
                <h3 class="fw-bold mb-3" style="color:#8B5E34; font-family: 'Playfair Display', serif;">
                    Visi & Misi
                </h3>
                <p class="text-muted">
                    Menyediakan pusat informasi kerajinan Bali yang akurat, mudah diakses, dan terintegrasi dalam satu platform digital modern.
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 p-4 text-center border-0 shadow-sm feature-card" style="border-radius: 18px;">
                <div class="icon-circle">
                    <i class="bi bi-flower1"></i> </div>
                <h3 class="fw-bold mb-3" style="color:#8B5E34; font-family: 'Playfair Display', serif;">
                    Pelestarian Budaya
                </h3>
                <p class="text-muted">
                    Membantu menjaga keberlanjutan budaya lokal dengan memberikan panggung digital dan ruang promosi bagi para pengrajin desa.
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 p-4 text-center border-0 shadow-sm feature-card" style="border-radius: 18px;">
                <div class="icon-circle">
                    <i class="bi bi-globe-asia-australia"></i> </div>
                <h3 class="fw-bold mb-3" style="color:#8B5E34; font-family: 'Playfair Display', serif;">
                    Jendela Dunia
                </h3>
                <p class="text-muted">
                    Menghubungkan masyarakat lokal maupun internasional dengan karya seni otentik Bali melalui sistem yang terstruktur dan transparan.
                </p>
            </div>
        </div>

    </div>

    <div class="text-center mt-5">
        <a href="<?= $BASE_URL ?>" class="btn btn-outline-warning rounded-pill px-4 py-2 fw-bold">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
        </a>
    </div>

</div>

<?php 
require_once __DIR__ . '/partials/footer.php'; 
?>
