<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/admin_code.php';

if (is_logged_in()) {
    header("Location: {$BASE_URL}admin/dashboard.php");
    exit;
}

$error = '';
$success = '';

/* ============================================================
   REGISTER ADMIN TANPA OTP
============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $name       = trim($_POST['name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = trim($_POST['password'] ?? '');
    $password2  = trim($_POST['password2'] ?? '');
    $admin_code = trim($_POST['admin_code'] ?? '');

    // Validasi dasar
    if ($name === '' || $email === '' || $password === '' || $password2 === '') {
        $error = "Semua field wajib diisi.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    }
    elseif ($password !== $password2) {
        $error = "Konfirmasi password tidak cocok.";
    }
    elseif ($admin_code !== $ADMIN_FIXED_CODE) {
        $error = "Kode rahasia admin salah!";
    }
    else {
        // Cek apakah email sudah digunakan
        $cek = db_fetch("SELECT id FROM users WHERE email = :e", ['e' => $email]);

        if ($cek) {
            $error = "Email sudah terdaftar.";
        } else {
            // Insert akun admin langsung verified
            db_exec("
                INSERT INTO users (name, email, password, role, is_verified, verified_at)
                VALUES (:n, :e, :p, 'admin', 1, NOW())
            ", [
                'n' => $name,
                'e' => $email,
                'p' => password_hash($password, PASSWORD_BCRYPT)
            ]);

            $success = "Akun admin berhasil dibuat! Anda akan diarahkan ke login...";
            echo "<meta http-equiv='refresh' content='2; url={$BASE_URL}login.php?role=admin'>";
        }
    }
}

include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/navbar.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-lg p-4" style="border-radius:18px;">

                <h2 class="fw-bold text-center mb-4" style="color:#8B5E34;">
                    Daftar Admin (Kode Rahasia)
                </h2>

                <!-- ALERTS -->
                <?php if ($error): ?>
                    <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success text-center"><?= $success ?></div>
                <?php endif; ?>

                <form method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-semibold">Konfirmasi Password</label>
                        <input type="password" name="password2" class="form-control" minlength="6" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-semibold">Kode Admin Rahasia</label>
                        <input type="password" name="admin_code"
                               class="form-control"
                               placeholder="Masukkan kode rahasia admin"
                               required>
                    </div>

                    <button class="btn btn-warning w-100 fw-bold mt-3">
                        Daftar Admin
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="<?= $BASE_URL ?>login.php?role=admin" 
                       class="fw-bold text-muted">← Kembali ke Login</a>
                </div>

            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
