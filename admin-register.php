<?php
require_once __DIR__ . '/config/db.php';

// Jika sudah login, arahkan ke dashboard
if (is_logged_in()) {
    header("Location: {$BASE_URL}admin/dashboard.php");
    exit;
}

$error = '';
$success = '';
$step = $_GET['step'] ?? 'register'; // register atau verify

// ---- STEP 1: REGISTER (kirim verifikasi code) ----
if ($step === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['password'] ?? '');
    $pass2 = trim($_POST['password2'] ?? '');

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
        // Cek email sudah terdaftar
        $cek = db_fetch("SELECT id FROM users WHERE email = :e", ['e' => $email]);

        if ($cek) {
            $error = "Email sudah terdaftar.";
        } else {
            // Generate verification code (6 digit)
            $verification_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $code_hash = hash('sha256', $verification_code);

            // Insert user (belum verified)
            db_exec(
                "INSERT INTO users (name, email, password, role, verification_code, is_verified)
                 VALUES (:n, :e, :p, 'admin', :code, 0)",
                [
                    'n' => $name,
                    'e' => $email,
                    'p' => password_hash($pass, PASSWORD_DEFAULT),
                    'code' => $code_hash
                ]
            );

            // Simpan ke session untuk verifikasi
            $_SESSION['admin_register'] = [
                'email' => $email,
                'name' => $name,
                'verification_code' => $verification_code
            ];

            $success = "Akun berhasil dibuat. Kode verifikasi telah dikirim ke email: <strong>{$email}</strong>";
            // Di production, kirim email dengan kode. Untuk sekarang, tampilkan di UI (demo only)
        }
    }
}

// ---- STEP 2: VERIFY (verifikasi kode) ----
if ($step === 'verify' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    if (!isset($_SESSION['admin_register'])) {
        $error = "Sesi verifikasi tidak ditemukan. Silakan daftar ulang.";
    } else {
        $input_code = trim($_POST['verification_code'] ?? '');
        $expected_code = $_SESSION['admin_register']['verification_code'];
        $email = $_SESSION['admin_register']['email'];

        if ($input_code === '') {
            $error = "Kode verifikasi wajib diisi.";
        }
        else if ($input_code !== $expected_code) {
            $error = "Kode verifikasi salah.";
        }
        else {
            // Update user menjadi verified
            db_exec(
                "UPDATE users SET is_verified = 1, verified_at = NOW(), verification_code = NULL
                 WHERE email = :e",
                ['e' => $email]
            );

            $success = "Akun terverifikasi! Silakan login.";
            unset($_SESSION['admin_register']);

            // Redirect ke login admin setelah 2 detik
            echo "<meta http-equiv='refresh' content='2; url={$BASE_URL}login.php?role=admin'>";
        }
    }
}

include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/navbar.php';
?>

<h1>Daftar Akun Admin</h1>

<?php if ($error): ?>
<div class="kb-alert kb-alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="kb-alert kb-alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if ($step === 'register'): ?>
    <!-- STEP 1: Registrasi Form -->
    <form method="POST" class="kb-form">
        <?= csrf_field() ?>
        <input type="hidden" name="step" value="register">

        <label>Nama Lengkap</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" minlength="6" required>

        <label>Konfirmasi Password</label>
        <input type="password" name="password2" minlength="6" required>

        <button class="kb-btn kb-btn-primary">Daftar & Dapatkan Kode Verifikasi</button>
    </form>

    <?php if (isset($_SESSION['admin_register'])): ?>
    <div style="margin-top: 30px; padding: 20px; background: #f0f8ff; border-radius: 5px;">
        <p><strong>📧 Kode Verifikasi (Demo):</strong></p>
        <p style="font-size: 24px; font-weight: bold; letter-spacing: 2px; color: #0066cc;">
            <?= htmlspecialchars($_SESSION['admin_register']['verification_code']) ?>
        </p>
        <p style="font-size: 12px; color: #666;">
            <em>(Dalam production, kode ini dikirim ke email. Di sini ditampilkan untuk testing.)</em>
        </p>
        <form method="POST" action="?step=verify" class="kb-form">
            <?= csrf_field() ?>
            <input type="hidden" name="step" value="verify">

            <label>Masukkan Kode Verifikasi</label>
            <input type="text" name="verification_code" placeholder="6 digit code" maxlength="6" pattern="\d{6}" required>

            <button class="kb-btn kb-btn-success">Verifikasi</button>
        </form>
    </div>
    <?php endif; ?>

<?php elseif ($step === 'verify'): ?>
    <!-- STEP 2: Verifikasi Form -->
    <form method="POST" class="kb-form">
        <?= csrf_field() ?>
        <input type="hidden" name="step" value="verify">

        <label>Masukkan Kode Verifikasi</label>
        <input type="text" name="verification_code" placeholder="6 digit code" maxlength="6" pattern="\d{6}" required autofocus>

        <button class="kb-btn kb-btn-success">Verifikasi</button>
    </form>

    <p>
        <a href="<?= $BASE_URL ?>admin-register.php">← Kembali ke Registrasi</a>
    </p>
<?php endif; ?>

<p>
    <a href="<?= $BASE_URL ?>login.php?role=admin">← Kembali ke Login Admin</a>
</p>

<?php include __DIR__ . '/partials/footer.php'; ?>
