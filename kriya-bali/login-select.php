<?php
require_once __DIR__ . '/config/db.php';

// Jika sudah login → arahkan sesuai role
if (is_logged_in()) {

    $role = $_SESSION['user']['role'];

    if ($role === 'admin' || $role === 'superadmin') {
        header("Location: {$BASE_URL}admin/dashboard.php");
        exit;
    }

    // Role user
    header("Location: {$BASE_URL}");
    exit;
}

include __DIR__ . '/partials/header.php';
?>

<style>
    .kb-login-choice {
        display: flex;
        gap: 20px;
        margin-top: 30px;
    }
</style>

<h1>Pilih Jenis Login</h1>

<div class="kb-login-choice">
    <a class="kb-btn kb-btn-primary"
       href="<?= $BASE_URL ?>login.php?role=admin">
        Login Admin
    </a>

    <a class="kb-btn kb-btn-success"
       href="<?= $BASE_URL ?>login.php?role=user">
        Login Pengguna
    </a>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
