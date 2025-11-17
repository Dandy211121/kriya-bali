<?php include __DIR__.'/partials/header.php'; ?>
<?php include __DIR__.'/partials/navbar.php'; ?>

<h1>Beranda</h1>
<p class="kb-muted">Selamat datang di Kriya Bali, pusat informasi pengrajin dan kerajinan Bali.</p>

<div class="kb-cards three">
  
  <div class="kb-card">
    <h3>Pengrajin</h3>
    <p class="kb-muted">Jelajahi daftar pengrajin berdasarkan daerah dan kategori.</p>
    <a class="kb-btn" href="<?= $BASE_URL ?>pengrajin.php">Lihat Pengrajin</a>
  </div>

  <div class="kb-card">
    <h3>Kerajinan</h3>
    <p class="kb-muted">Telusuri kerajinan tradisional Bali lengkap dengan detailnya.</p>
    <a class="kb-btn" href="<?= $BASE_URL ?>kerajinan.php">Lihat Kerajinan</a>
  </div>

  <div class="kb-card">
    <h3>Tentang Website</h3>
    <p class="kb-muted">Informasi mengenai sistem informasi Kriya Bali.</p>
    <a class="kb-btn" href="<?= $BASE_URL ?>tentang.php">Tentang Kami</a>
  </div>

</div>

<?php include __DIR__.'/partials/footer.php'; ?>
