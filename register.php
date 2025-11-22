<?php
require_once __DIR__ . '/config/db.php';

// Jika user sudah login, tidak boleh daftar lagi
if (is_logged_in()) {
    header("Location: {$BASE_URL}");
    exit;
}

$error = '';
$success = '';

// Proses submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = trim($_POST['password']);
    $pass2 = trim($_POST['password2']);

    if ($name === '' || $email === '' || $pass === '') {
        $error = "Semua field wajib diisi.";
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    }
    else if (strlen($pass) < 6) {
        $error = "Password minimal 6 karakter.";
    }
    else if ($pass !== $pass2) {
        $error = "Konfirmasi password tidak cocok.";
    }
    else {
        // cek email sudah ada atau belum
        $cek = db_fetch("SELECT id FROM users WHERE email = :e", ['e' => $email]);

        if ($cek) {
            $error = "Email sudah digunakan.";
        } else {

            // Auto verified
            db_exec("
                INSERT INTO users (name, email, password, role, is_verified, verified_at)
                VALUES (:n, :e, :p, 'user', 1, NOW())
            ", [
                'n' => $name,
                'e' => $email,
                'p' => password_hash($pass, PASSWORD_DEFAULT)
            ]);

            // Auto login
            $user = db_fetch("SELECT * FROM users WHERE email = :e", ['e' => $email]);
            $_SESSION['user'] = $user;

            header("Location: {$BASE_URL}");
            exit;
        }
    }
}

include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/navbar.php';
?>

<div class="container py-5">

    <!-- CARD REGISTER -->
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-lg p-4" style="border-radius:18px;">
                
                <h2 class="fw-bold text-center mb-4" style="color:#8B5E34;">Daftar Akun Pengguna</h2>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" class="mt-3">

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

                    <button class="btn btn-warning w-100 fw-bold mt-3">
                        Daftar
                    </button>

                </form>

                <div class="text-center mt-3">
                    Sudah punya akun?
                    <a href="<?= $BASE_URL ?>login.php?role=user" class="fw-bold" style="color:#8B5E34;">
                        Login Sekarang
                    </a>
                </div>

            </div>

        </div>
    </div>

</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
