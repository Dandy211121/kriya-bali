<?php 
require_once __DIR__ . '/partials/header.php'; 
require_once __DIR__ . '/partials/navbar.php'; 
?>

<div class="container" style="max-width:900px; margin:auto; padding-bottom:60px;">

    <!-- Header -->
    <h1 class="header-title">Tentang Kriya Bali</h1>
    <div class="header-line"></div>

    <p style="text-align:center; max-width:700px; margin:auto; color:#6b4a1e; font-size:1.1rem;">
        Kriya Bali adalah platform yang didedikasikan untuk melestarikan, mengenalkan,
        dan mempermudah akses terhadap data pengrajin serta kerajinan tradisional Bali.
    </p>

    <div class="kb-home-cards" style="margin-top:50px;">

        <div class="kb-card-feature">
            <span class="kb-icon-large">🧵</span>
            <h3>Tujuan Website</h3>
            <p class="kb-muted">
                Menyediakan pusat informasi kerajinan Bali yang akurat, mudah diakses, dan terintegrasi dalam satu platform digital.
            </p>
        </div>

        <div class="kb-card-feature">
            <span class="kb-icon-large">🏺</span>
            <h3>Melestarikan Kerajinan Lokal</h3>
            <p class="kb-muted">
                Website ini membantu menjaga keberlanjutan budaya lokal dengan memberikan ruang promosi bagi pengrajin Bali.
            </p>
        </div>

        <div class="kb-card-feature">
            <span class="kb-icon-large">🌏</span>
            <h3>Menghubungkan ke Dunia</h3>
            <p class="kb-muted">
                Dengan tampilan modern dan sistem yang terstruktur, Kriya Bali mempermudah masyarakat lokal maupun internasional 
                dalam mencari informasi tentang kerajinan tradisional Bali.
            </p>
        </div>

    </div>

    <div style="text-align:center; margin-top:40px;">
        <a class="kb-btn kb-btn-outline-sm" href="<?= $BASE_URL ?>">← Kembali ke Beranda</a>
    </div>

</div>

<?php 
require_once __DIR__ . '/partials/footer.php'; 
?>
