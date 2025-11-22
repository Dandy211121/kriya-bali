<?php
require_once __DIR__ . '/config/db.php';

// Role login
$role = $_GET['role'] ?? ($_POST['role'] ?? 'user');
$error = '';

// Jika sudah login
if (is_logged_in()) {
    $r = $_SESSION['user']['role'];
    if ($r === 'admin' || $r === 'superadmin') {
        header("Location: {$BASE_URL}admin/dashboard.php");
    } else {
        header("Location: {$BASE_URL}");
    }
    exit;
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['password'] ?? '');

    if ($email === '' || $pass === '') {
        $error = "Email dan password wajib diisi.";
    } else {

        // Query admin
        if ($role === 'admin') {
            $user = db_fetch(
                "SELECT * FROM users 
                 WHERE email = :email
                 AND (role = 'admin' OR role = 'superadmin')",
                ['email' => $email]
            );
        }
        // Query user biasa
        else {
            $user = db_fetch(
                "SELECT * FROM users
                 WHERE email = :email
                 AND role = 'user'",
                ['email' => $email]
            );
        }

        if ($user && password_verify($pass, $user['password'])) {

            if (!$user['is_verified']) {
                $error = "Akun Anda belum terverifikasi.";
            } else {
                session_regenerate_id(true);
                $_SESSION['user'] = $user;

                if ($user['role'] === 'admin' || $user['role'] === 'superadmin')
                    header("Location: {$BASE_URL}admin/dashboard.php");
                else
                    header("Location: {$BASE_URL}");

                exit;
            }
        }

        $error = "Email / Password salah atau role tidak sesuai.";
    }
}

include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/navbar.php';
?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-5">
            <div class="card shadow-lg p-4" style="border-radius:18px;">

                <h2 class="fw-bold text-center mb-4" style="color:#8B5E34;">
                    <?= ($role === 'admin') ? 'Login Admin' : 'Login Pengguna'; ?>
                </h2>

                <?php if ($error): ?>
                    <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" class="mt-3">

                    <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button class="btn btn-warning w-100 fw-bold mt-3">
                        Login
                    </button>
                </form>

                <!-- REGISTER LINK -->
                <div class="text-center mt-4">
                    <?php if ($role === 'user'): ?>
                        Belum punya akun?
                        <a href="<?= $BASE_URL ?>register.php" class="fw-bold" style="color:#8B5E34;">Daftar</a>
                    <?php else: ?>
                        Belum punya akun admin?
                        <a href="<?= $BASE_URL ?>admin-register.php" class="fw-bold" style="color:#8B5E34;">Daftar Admin</a>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-3">
                    <a href="login-select.php" class="text-muted fw-semibold">← Kembali</a>
                </div>

            </div>
        </div>

    </div>

</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
