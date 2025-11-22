<?php require_once __DIR__ . '/../config/db.php'; ?>

<nav class="navbar navbar-expand-lg shadow-sm" style="background: #8B5E34;">
    <div class="container">

        <!-- Brand / Logo -->
        <a class="navbar-brand d-flex align-items-center text-light fw-bold" href="<?= $BASE_URL ?>">
            <img src="<?= asset('public/img/logo.png') ?>" 
                 alt="Logo" 
                 class="me-2" 
                 style="height: 42px; border-radius: 10px;">
            Kriya Bali
        </a>

        <!-- Toggle Button Mobile -->
        <button class="navbar-toggler text-light border-light" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#kbNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="kbNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link text-light" href="<?= $BASE_URL ?>">Beranda</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-light" href="<?= $BASE_URL ?>pengrajin.php">Pengrajin</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-light" href="<?= $BASE_URL ?>kerajinan.php">Kerajinan</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-light" href="<?= $BASE_URL ?>tentang.php">Tentang</a>
                </li>

                <!-- USER LOGIN -->
                <?php if (is_logged_in()): ?>

                    <?php if (is_admin() || is_superadmin()): ?>
                        <?php if (strpos($_SERVER['REQUEST_URI'], '/admin/') === false): ?>
                            <li class="nav-item">
                                <a class="nav-link text-warning fw-bold" 
                                   href="<?= $BASE_URL ?>admin/dashboard.php">
                                   Admin
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="btn btn-warning text-dark ms-lg-3 fw-bold" 
                           href="<?= $BASE_URL ?>logout.php">
                           Logout
                        </a>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="btn btn-light text-dark ms-lg-3 fw-bold" 
                           href="<?= $BASE_URL ?>login-select.php">
                           Login
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>

    </div>
</nav>
