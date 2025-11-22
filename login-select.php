<?php
require_once __DIR__ . '/config/db.php';

// Jika sudah login → arahkan sesuai role
if (is_logged_in()) {

    $role = $_SESSION['user']['role'];

    if ($role === 'admin' || $role === 'superadmin') {
        header("Location: {$BASE_URL}admin/dashboard.php");
        exit;
    }

    header("Location: {$BASE_URL}");
    exit;
}

include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/navbar.php';
?>

<div class="container py-5 text-center">

    <h1 class="fw-bold mb-3" style="color:#8B5E34;">Pilih Jenis Login</h1>
    <p class="text-muted mb-4">Silakan pilih tipe akun untuk melanjutkan ke halaman login.</p>

    <div class="row justify-content-center g-4">

        <!-- Login Admin -->
        <div class="col-md-4">
            <a href="<?= $BASE_URL ?>login.php?role=admin" 
               class="text-decoration-none">
                <div class="card shadow-lg p-4 h-100 hover-zoom"
                     style="border-radius:16px;">
                    <div class="fs-1 mb-3" style="color:#8B5E34;">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <h4 class="fw-bold" style="color:#8B5E34;">Login Admin</h4>
                    <p class="text-muted">Masuk sebagai admin atau superadmin.</p>
                </div>
            </a>
        </div>

        <!-- Login User -->
        <div class="col-md-4">
            <a href="<?= $BASE_URL ?>login.php?role=user" 
               class="text-decoration-none">
                <div class="card shadow-lg p-4 h-100 hover-zoom"
                     style="border-radius:16px;">
                    <div class="fs-1 mb-3" style="color:#8B5E34;">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <h4 class="fw-bold" style="color:#8B5E34;">Login Pengguna</h4>
                    <p class="text-muted">Masuk sebagai pengguna biasa.</p>
                </div>
            </a>
        </div>

    </div>
</div>

<style>
    .hover-zoom:hover {
        transform: translateY(-6px);
        transition: 0.3s;
    }
</style>

<?php include __DIR__ . '/partials/footer.php'; ?>
