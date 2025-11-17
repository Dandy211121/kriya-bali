<?php
require_once __DIR__ . '/config/db.php';

// Jika user sudah login, tidak boleh daftar lagi
if (is_logged_in()) {
    header("Location: {$BASE_URL}");
    exit;
}

$error = '';
$success = '';

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
            // Auto verified untuk user biasa (tidak perlu verifikasi)
            db_exec("
                INSERT INTO users (name, email, password, role, is_verified, verified_at)
                VALUES (:n, :e, :p, 'user', 1, NOW())
            ", [
                'n' => $name,
                'e' => $email,
                'p' => password_hash($pass, PASSWORD_DEFAULT)
            ]);

            // Auto login setelah register
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

<h1>Daftar Akun</h1>

<?php if ($error): ?>
<div class="kb-alert kb-alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" class="kb-form">

    <?= csrf_field() ?>

    <label>Nama Lengkap</label>
    <input type="text" name="name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" minlength="6" required>

    <label>Konfirmasi Password</label>
    <input type="password" name="password2" minlength="6" required>

    <button class="kb-btn kb-btn-primary">Daftar</button>

</form>

<p>
    <a href="<?= $BASE_URL ?>login.php?role=user">← Kembali ke Login</a>
</p>

<?php include __DIR__ . '/partials/footer.php'; ?>
