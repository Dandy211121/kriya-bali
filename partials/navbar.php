<?php require_once __DIR__ . '/../config/db.php'; ?>

<header class="kb-topbar">
    <div class="kb-container kb-nav">

        <a class="kb-brand" href="<?= $BASE_URL ?>">
            <img src="<?= asset('public/img/logo.png') ?>" class="kb-logo">
            <span>Kriya Bali</span>
        </a>

        <nav class="kb-menu">
            <a href="<?= $BASE_URL ?>">Beranda</a>
            <a href="<?= $BASE_URL ?>pengrajin.php">Pengrajin</a>
            <a href="<?= $BASE_URL ?>kerajinan.php">Kerajinan</a>
            <a href="<?= $BASE_URL ?>tentang.php">Tentang</a>

            <?php if (is_logged_in()): ?>

                <?php if (is_admin() || is_superadmin()): ?>
                    <!-- Tombol admin hanya muncul ketika user berada di halaman public -->
                    <?php if (strpos($_SERVER['REQUEST_URI'], '/admin/') === false): ?>
                        <a href="<?= $BASE_URL ?>admin/dashboard.php">Admin</a>
                    <?php endif; ?>
                <?php endif; ?>

                <a class="kb-btn" href="<?= $BASE_URL ?>logout.php">Logout</a>

            <?php else: ?>

                <a class="kb-btn" href="<?= $BASE_URL ?>login-select.php">Login</a>

            <?php endif; ?>
        </nav>

    </div>
</header>
