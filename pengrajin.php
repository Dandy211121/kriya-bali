<?php 
require_once __DIR__.'/partials/header.php';
require_once __DIR__.'/partials/navbar.php';
?>

<?php
$q      = $_GET['q'] ?? '';
$region = $_GET['region'] ?? '';

// Ambil dropdown daerah
$regions = db_fetch_all("SELECT id, name FROM regions ORDER BY name");

// Base SQL
$sql = "
    SELECT a.*, r.name AS region_name
    FROM artisans a
    LEFT JOIN regions r ON r.id = a.region_id
    WHERE 1
";

$params = [];

// Filter nama
if ($q !== '') {
    $sql .= " AND a.name LIKE ?";
    $params[] = "%$q%";
}

// Filter daerah
if ($region !== '') {
    $sql .= " AND a.region_id = ?";
    $params[] = $region;
}

$rows = db_fetch_all($sql, $params);
?>

<div class="container">

  <!-- Header -->
  <h1 class="header-title">Daftar Pengrajin Tradisional Bali</h1>
  <div class="header-line"></div>
  <p style="text-align:center; max-width:600px; margin:auto; color:#92400e;">
    Jelajahi berbagai kerajinan khas Bali yang dibuat dengan tangan oleh para pengrajin berpengalaman
  </p>

  <!-- Filters -->
  <div class="filter-box">

    <div class="grid grid-3">

      <!-- Search -->
      <div style="position:relative;">
        <span class="icon">🔍</span>
        <input type="text" 
               name="q"
               class="input"
               placeholder="Cari pengrajin…"
               value="<?= htmlspecialchars($q) ?>">
      </div>

      <!-- Region -->
      <div style="position:relative;">
        <span class="icon">📍</span>
        <select name="region" class="select">
          <option value="">Semua Daerah</option>
          <?php foreach ($regions as $r): ?>
            <option value="<?= $r['id'] ?>" <?= $region == $r['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($r['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Category -->
      <div style="position:relative;">
        <span class="icon">🎨</span>
        <select name="category" class="select">
          <option value="">Semua Kategori</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $category == $c['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

    </div>

    <!-- Active Filters -->
    <?php if ($q || $region || $category): ?>
    <div style="margin-top:20px; display:flex; gap:10px; flex-wrap:wrap;">

      <?php if ($q): ?>
      <span class="badge">Pencarian: "<?= htmlspecialchars($q) ?>"</span>
      <?php endif; ?>

      <?php if ($region): ?>
      <span class="badge">Daerah: <?= htmlspecialchars($region_name) ?></span>
      <?php endif; ?>

      <?php if ($category): ?>
      <span class="badge">Kategori: <?= htmlspecialchars($category_name) ?></span>
      <?php endif; ?>

      <a href="pengrajin.php" class="badge-reset">Reset Semua</a>

    </div>
    <?php endif; ?>

  </div>

  <!-- Results Count -->
  <p class="result-count">
    Menampilkan <b><?= count($rows) ?></b> pengrajin
  </p>

  <!-- Data List -->
  <?php if ($rows): ?>
    <div class="card-grid">
      <?php foreach ($rows as $row): ?>
      <div class="card">
        <div class="card-title"><?= htmlspecialchars($row['name']) ?></div>
        <div class="card-sub"><?= htmlspecialchars($row['region_name']) ?></div>
        <br>
        <a href="<?= $BASE_URL ?>kerajinan.php?artisan=<?= $row['id'] ?>" 
           style="color:#b45309; text-decoration:underline;">
          Lihat Kerajinan →
        </a>
      </div>
      <?php endforeach; ?>
    </div>

  <?php else: ?>
    <div class="empty">
      <div class="empty-icon">🔍</div>
      <h3 style="color:#78350f;">Tidak Ada Hasil</h3>
      <p style="color:#b45309;">Tidak ditemukan pengrajin yang cocok dengan filter Anda.</p>
      <br>
      <a href="pengrajin.php">
        <button class="button-reset">Reset Pencarian</button>
      </a>
    </div>
  <?php endif; ?>

</div>

<?php include __DIR__.'/partials/footer.php'; ?>