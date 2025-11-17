<?php 
require_once __DIR__.'/partials/header.php'; 
require_once __DIR__.'/partials/navbar.php'; 
?>

<h1 class="kb-title">Daftar Kerajinan</h1>

<?php
$q       = $_GET['q'] ?? '';
$region  = $_GET['region'] ?? '';
$cat     = $_GET['cat'] ?? '';
$artisan = $_GET['artisan'] ?? '';

// Dropdown data
$regions  = db_fetch_all("SELECT id,name FROM regions ORDER BY name");
$cats     = db_fetch_all("SELECT id,name FROM craft_categories ORDER BY name");
$artisans = db_fetch_all("SELECT id,name FROM artisans ORDER BY name");

// Base SQL
$sql = "
    SELECT cr.*, 
           ar.name AS artisan_name, 
           r.name AS region_name, 
           cc.name AS cat_name
    FROM crafts cr
    LEFT JOIN artisans ar ON ar.id = cr.artisan_id
    LEFT JOIN regions   r ON r.id = cr.region_id
    LEFT JOIN craft_categories cc ON cc.id = cr.category_id
    WHERE 1
";

$params = [];

// Filter
if ($q !== '') {
    $sql .= " AND cr.title LIKE ?";
    $params[] = "%$q%";
}

if ($region !== '') {
    $sql .= " AND cr.region_id = ?";
    $params[] = $region;
}

if ($cat !== '') {
    $sql .= " AND cr.category_id = ?";
    $params[] = $cat;
}

if ($artisan !== '') {
    $sql .= " AND cr.artisan_id = ?";
    $params[] = $artisan;
}

// Eksekusi
$rows = db_fetch_all($sql, $params);
?>

<!-- Form Filter -->
<form class="kb-grid four" method="get">

    <input type="text" name="q" 
           value="<?= htmlspecialchars($q) ?>" 
           placeholder="Cari kerajinan...">

    <select name="region">
        <option value="">Semua Daerah</option>
        <?php foreach($regions as $r): ?>
            <option value="<?= $r['id'] ?>" 
                <?= $region == $r['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="cat">
        <option value="">Semua Kategori</option>
        <?php foreach($cats as $c): ?>
            <option value="<?= $c['id'] ?>" 
                <?= $cat == $c['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="artisan">
        <option value="">Semua Pengrajin</option>
        <?php foreach($artisans as $a): ?>
            <option value="<?= $a['id'] ?>" 
                <?= $artisan == $a['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($a['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button class="kb-btn">Cari</button>
    <a href="<?= $BASE_URL ?>kerajinan.php" class="kb-btn kb-btn-outline">Reset</a>

</form>

<!-- Tabel Data Kerajinan -->
<table class="table kb-table">
    <thead>
        <tr>
            <th>Gambar</th>
            <th>Judul</th>
            <th>Daerah</th>
            <th>Kategori</th>
            <th>Pengrajin</th>
            <th>Detail</th>
        </tr>
    </thead>
    <tbody>

        <?php if (!$rows): ?>
            <tr>
                <td colspan="6" class="kb-empty">Tidak ada data ditemukan.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td>
                        <?php if ($row['image_path']): ?>
                            <img src="<?= asset($row['image_path']) ?>" 
                                 style="width:70px; border-radius:6px;">
                        <?php else: ?>
                            <span class="kb-muted">Tidak ada</span>
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['region_name']) ?></td>
                    <td><?= htmlspecialchars($row['cat_name']) ?></td>
                    <td><?= htmlspecialchars($row['artisan_name']) ?></td>

                    <td>
                        <a class="kb-link"
                           href="<?= $BASE_URL ?>kerajinan-detail.php?id=<?= $row['id'] ?>">
                            Lihat
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

    </tbody>
</table>

<?php include __DIR__.'/partials/footer.php'; ?>
