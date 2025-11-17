<?php 
require_once __DIR__.'/partials/header.php';
require_once __DIR__.'/partials/navbar.php';
?>

<h1 class="kb-title">Daftar Pengrajin</h1>

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

<!-- Form Filter -->
<form class="kb-grid three" method="get">
  
  <input type="text" 
         name="q" 
         value="<?= htmlspecialchars($q) ?>" 
         placeholder="Cari pengrajin...">

  <select name="region">
    <option value="">Semua Daerah</option>
    <?php foreach ($regions as $r): ?>
      <option value="<?= $r['id'] ?>" 
              <?= $region == $r['id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($r['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <button class="kb-btn">Cari</button>
  <a class="kb-btn kb-btn-outline" href="<?= $BASE_URL ?>pengrajin.php">Reset</a>

</form>

<!-- Tabel Data -->
<table class="table kb-table">
  <thead>
    <tr>
      <th>Nama</th>
      <th>Daerah</th>
      <th>Kerajinan</th>
    </tr>
  </thead>
  <tbody>

  <?php if (!$rows): ?>
    <tr>
      <td colspan="3" class="kb-empty">Tidak ada data pengrajin ditemukan.</td>
    </tr>
  <?php else: ?>
    <?php foreach ($rows as $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['region_name'] ?? '-') ?></td>
        <td>
          <a class="kb-link" 
             href="<?= $BASE_URL ?>kerajinan.php?artisan=<?= $row['id'] ?>">
             Lihat Kerajinan
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>

  </tbody>
</table>

<?php include __DIR__.'/partials/footer.php'; ?>
