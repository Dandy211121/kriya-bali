<?php include __DIR__.'/partials/header.php'; ?>
<?php include __DIR__.'/partials/navbar.php'; ?>

<div class="kb-hero">
    
    <div class="kb-hero-slideshow">
        <div class="kb-slide active" style="background-image: url('public/img/hero1.jpg');"></div>
        <div class="kb-slide" style="background-image: url('public/img/hero2.jpg');"></div>
        <div class="kb-slide" style="background-image: url('public/img/hero3.png');"></div>
    </div>

    <div class="kb-hero-overlay"></div>

    <div class="kb-hero-content">
        <img src="public/img/logo.png" alt="Logo Kriya Bali" class="kb-hero-logo">
        <h1>Selamat Datang di Kriya Bali</h1>
        <p>Platform digital yang didedikasikan untuk melestarikan dan mempromosikan keindahan karya tangan pengrajin lokal Bali ke panggung dunia.</p>
        
        <?php if (!isset($_SESSION['user'])): ?>
            <a href="register.php" class="kb-btn" style="font-size: 1.1rem; padding: 10px 25px;">Gabung Sekarang</a>
        <?php endif; ?>
    </div>

</div>

<script>
    const slides = document.querySelectorAll('.kb-slide');
    let currentSlide = 0;

    setInterval(() => {
        // Hapus class 'active' dari gambar sekarang
        slides[currentSlide].classList.remove('active');
        
        // Pindah ke gambar berikutnya (looping)
        currentSlide = (currentSlide + 1) % slides.length;
        
        // Tambah class 'active' ke gambar baru
        slides[currentSlide].classList.add('active');
    }, 4000); // Ganti gambar setiap 4000ms (4 detik)
</script>

<div class="kb-home-cards">
  
  <div class="kb-card kb-card-feature">
    <span class="kb-icon-large">👩‍🎨</span>
    <h3>Data Pengrajin</h3>
    <p class="kb-muted">Temukan profil lengkap para seniman dan pengrajin berbakat dari berbagai pelosok daerah di Bali.</p>
    <a class="kb-btn-outline-sm" href="pengrajin.php">Lihat Pengrajin</a>
  </div>

  <div class="kb-card kb-card-feature">
    <span class="kb-icon-large">🏺</span>
    <h3>Galeri Kerajinan</h3>
    <p class="kb-muted">Jelajahi koleksi mahakarya tradisional mulai dari ukiran, anyaman, hingga lukisan yang memukau.</p>
    <a class="kb-btn-outline-sm" href="kerajinan.php">Lihat Koleksi</a>
  </div>

  <div class="kb-card kb-card-feature">
    <span class="kb-icon-large">ℹ️</span>
    <h3>Tentang Kami</h3>
    <p class="kb-muted">Pelajari lebih lanjut tentang visi kami dalam menjaga warisan budaya Bali melalui teknologi.</p>
    <a class="kb-btn-outline-sm" href="tentang.php">Baca Selengkapnya</a>
  </div>

</div>

<?php include __DIR__.'/partials/footer.php'; ?>