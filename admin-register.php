<?php
require_once __DIR__ . '/config/db.php';

// Jika admin sudah login
if (is_logged_in()) {
    header("Location: {$BASE_URL}admin/dashboard.php");
    exit;
}

$error = '';
$success = '';
$step = $_GET['step'] ?? 'register'; // register | verify

/* ============================================================
   STEP 1 — REGISTER (Admin mengisi form pendaftaran)
============================================================ */
if ($step === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['password'] ?? '');
    $pass2 = trim($_POST['password2'] ?? '');

    if ($name === '' || $email === '' || $pass === '') {
        $error = "Semua field wajib diisi.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    }
    elseif (strlen($pass) < 6) {
        $error = "Password minimal 6 karakter.";
    }
    elseif ($pass !== $pass2) {
        $error = "Konfirmasi password tidak cocok.";
    }
    else {
        $cek = db_fetch("SELECT id FROM users WHERE email = :e", ['e' => $email]);

        if ($cek) {
            $error = "Email sudah terdaftar.";
        } else {

            // Generate verification code
            $verification_code = str_pad(random_int(0, 999999), 6, "0", STR_PAD_LEFT);
            $code_hash = hash('sha256', $verification_code);

            // Insert admin (belum terverifikasi)
            db_exec("
                INSERT INTO users (name, email, password, role, verification_code, is_verified)
                VALUES (:n, :e, :p, 'admin', :c, 0)
            ", [
                'n' => $name,
                'e' => $email,
                'p' => password_hash($pass, PASSWORD_DEFAULT),
                'c' => $code_hash
            ]);

            // Simpan ke session
            $_SESSION['admin_register'] = [
                'email' => $email,
                'name'  => $name,
                'verification_code' => $verification_code
            ];

            $success = "Akun admin dibuat! Kode verifikasi telah dikirim ke email.";
        }
    }
}

/* ============================================================
   STEP 2 — VERIFY (Admin memasukkan kode verifikasi)
============================================================ */
if ($step === 'verify' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    if (!isset($_SESSION['admin_register'])) {
        $error = "Sesi verifikasi tidak ditemukan.";
    } else {
        $input_code = trim($_POST['verification_code'] ?? '');
        $expected   = $_SESSION['admin_register']['verification_code'];
        $email      = $_SESSION['admin_register']['email'];

        if ($input_code === '') {
            $error = "Kode verifikasi wajib diisi.";
        }
        elseif ($input_code !== $expected) {
            $error = "Kode verifikasi salah.";
        }
        else {
            db_exec("
                UPDATE users 
                SET is_verified = 1, verified_at = NOW(), verification_code = NULL
                WHERE email = :e
            ", ['e' => $email]);

            $success = "Akun berhasil diverifikasi! Mengalihkan ke halaman login...";
            unset($_SESSION['admin_register']);

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
                    Daftar Akun Admin
                </h2>

                <!-- ALERT -->
                <?php if ($error): ?>
                    <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success text-center"><?= $success ?></div>
                <?php endif; ?>

                <!-- ============================
                     FORM REGISTER ADMIN
                ============================= -->
                <?php if ($step === 'register'): ?>

                    <form method="POST" class="mt-3">
                        <?= csrf_field() ?>
                        <input type="hidden" name="step" value="register">

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
                            Daftar & Dapatkan Kode Verifikasi
                        </button>
                    </form>

                    <?php if (isset($_SESSION['admin_register'])): ?>
                        <div class="mt-4 p-3 rounded bg-light border">
                            <p class="fw-bold mb-1">📧 Kode Verifikasi (Demo):</p>
                            <p class="fs-3 fw-bold text-primary">
                                <?= htmlspecialchars($_SESSION['admin_register']['verification_code']) ?>
                            </p>
                            <p class="text-muted small">
                                (Pada production, kode dikirim melalui email.)
                            </p>
                        </div>

                        <form method="POST" class="mt-3" action="?step=verify">
                            <?= csrf_field() ?>
                            <input type="hidden" name="step" value="verify">

                            <label class="fw-semibold">Masukkan Kode Verifikasi</label>
                            <input type="text" name="verification_code" 
                                   class="form-control" maxlength="6" pattern="\d{6}"
                                   placeholder="6 digit" required>

                            <button class="btn btn-success w-100 fw-bold mt-3">
                                Verifikasi
                            </button>
                        </form>
                    <?php endif; ?>

                <!-- ============================
                     FORM VERIFY ONLY
                ============================= -->
                <?php elseif ($step === 'verify'): ?>

                    <form method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="step" value="verify">

                        <label class="fw-semibold">Masukkan Kode Verifikasi</label>
                        <input type="text" name="verification_code"
                               class="form-control"
                               placeholder="6 digit"
                               maxlength="6" pattern="\d{6}" required autofocus>

                        <button class="btn btn-success w-100 fw-bold mt-3">
                            Verifikasi
                        </button>
                    </form>

                <?php endif; ?>

                <!-- BACK LINK -->
                <div class="text-center mt-4">
                    <a href="<?= $BASE_URL ?>login.php?role=admin" 
                       class="fw-bold text-muted">← Kembali ke Login</a>
                </div>

            </div>

        </div>
    </div>

</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
