<?php
require_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_admin();

// Untuk memberikan class "active" pada menu
$active_menu = $GLOBALS['active_menu'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kriya Bali Admin</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- ADMIN CSS PREMIUM -->
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

            <!-- Dashboard -->
            <a href="<?= $BASE_URL ?>admin/dashboard.php" 
               class="kb-admin-link <?= ($active_menu === 'dashboard') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <!-- Pengrajin -->
            <a href="<?= $BASE_URL ?>admin/pengrajin-list.php" 
               class="kb-admin-link <?= ($active_menu === 'pengrajin') ? 'active' : '' ?>">
                <i class="bi bi-people"></i>
                <span>Pengrajin</span>
            </a>

            <!-- Kerajinan -->
            <a href="<?= $BASE_URL ?>admin/kerajinan-list.php" 
               class="kb-admin-link <?= ($active_menu === 'kerajinan') ? 'active' : '' ?>">
                <i class="bi bi-basket"></i>
                <span>Kerajinan</span>
            </a>

            <!-- Hanya Superadmin -->
            <?php if (is_superadmin()): ?>

                <a href="<?= $BASE_URL ?>admin/admin-register.php" 
                   class="kb-admin-link <?= ($active_menu === 'admin_register') ? 'active' : '' ?>">
                    <i class="bi bi-person-plus"></i>
                    <span>Tambah Admin</span>
                </a>

                <a href="<?= $BASE_URL ?>admin/settings.php" 
                   class="kb-admin-link <?= ($active_menu === 'settings') ? 'active' : '' ?>">
                    <i class="bi bi-gear"></i>
                    <span>Pengaturan Admin</span>
                </a>

                <a href="<?= $BASE_URL ?>admin/deletes-log.php" 
                   class="kb-admin-link <?= ($active_menu === 'log') ? 'active' : '' ?>">
                    <i class="bi bi-trash"></i>
                    <span>Log Hapus</span>
                </a>

            <?php endif; ?>

            <!-- Logout -->
            <a href="<?= $BASE_URL ?>logout.php" class="kb-admin-link">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>

        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="kb-admin-main">
