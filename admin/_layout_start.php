<?php
require_once __DIR__ . '/../config/db.php';

// Pastikan session dimulai SEBELUM pengecekan role
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Izinkan admin dan superadmin
require_admin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kriya Bali Admin</title>

    <!-- CSS KHUSUS ADMIN -->
    <link rel="stylesheet" href="<?= asset('public/css/admin.css') ?>">

    <!-- favicon -->
    <link rel="icon" href="<?= asset('public/img/logo.png') ?>">
</head>

<body class="admin-mode">

<div class="kb-admin-wrap">

    <!-- SIDEBAR -->
    <aside class="kb-admin-aside">
        <a href="<?= $BASE_URL ?>admin/dashboard.php" class="kb-admin-brand">
            <img src="<?= asset('public/img/logo.png') ?>" class="kb-admin-logo">
            <span>Kriya Bali Admin</span>
        </a>

        <nav class="kb-admin-menu">

            <a href="<?= $BASE_URL ?>admin/dashboard.php" class="kb-admin-link">🏠 Dashboard</a>
            <a href="<?= $BASE_URL ?>admin/pengrajin-list.php" class="kb-admin-link">👩‍🎨 Pengrajin</a>
            <a href="<?= $BASE_URL ?>admin/kerajinan-list.php" class="kb-admin-link">🎨 Kerajinan</a>

            <!-- TOMBOL KHUSUS SUPERADMIN -->
            <?php if (is_superadmin()): ?>
                <a href="<?= $BASE_URL ?>admin/admin-register.php" class="kb-admin-link">➕ Tambah Admin</a>
                <a href="<?= $BASE_URL ?>admin/settings.php" class="kb-admin-link">⚙ Pengaturan Admin</a>
                <a href="<?= $BASE_URL ?>admin/deletes-log.php" class="kb-admin-link">🗑️ Log Hapus</a>
            <?php endif; ?>

            <a href="<?= $BASE_URL ?>logout.php" class="kb-admin-link">🚪 Logout</a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="kb-admin-main">
