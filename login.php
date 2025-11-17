<?php
require_once __DIR__ . '/config/db.php';

// ---- Ambil role login ----
$role = $_GET['role'] ?? ($_POST['role'] ?? 'user'); // default login user
$error = '';

// ---- Jika user sudah login ----
if (is_logged_in()) {
    $r = $_SESSION['user']['role'];
    if ($r === 'admin' || $r === 'superadmin') {
        header("Location: {$BASE_URL}admin/dashboard.php");
    } else {
        header("Location: {$BASE_URL}");
    }
    exit;
}

// ---- Proses Login ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['password'] ?? '');

    if ($email === '' || $pass === '') {
        $error = "Email dan password wajib diisi.";
    } else {

        // ADMIN + SUPERADMIN
        if ($role === 'admin') {
            $user = db_fetch(
                "SELECT * FROM users 
                 WHERE email = :email
                 AND (role = 'admin' OR role = 'superadmin')",
                ['email' => $email]
            );
        }

        // USER BIASA
        else {
            $user = db_fetch(
                "SELECT * FROM users
                 WHERE email = :email
                 AND role = 'user'",
                ['email' => $email]
            );
        }

        // ---- Validasi Password ----
        if ($user && password_verify($pass, $user['password'])) {
            
            // Cek apakah akun sudah terverifikasi
            if (!$user['is_verified']) {
                $error = "Akun Anda belum terverifikasi. Silakan cek email Anda.";
            } else {
                session_regenerate_id(true);
                $_SESSION['user'] = $user;

                if ($user['role'] === 'admin' || $user['role'] === 'superadmin') {
                    header("Location: {$BASE_URL}admin/dashboard.php");
                } else {
                    header("Location: {$BASE_URL}");
                }
                exit;
            }
        }

        $error = "Email / Password salah atau role tidak sesuai.";
    }
}

// ---- UI ----
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/navbar.php';
?>

<h1>
    <?= ($role === 'admin') ? 'Login Admin' : 'Login Pengguna'; ?>
</h1>

<?php if ($error): ?>
<div class="kb-alert kb-alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" class="kb-form">
    <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
    <?= csrf_field() ?>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button class="kb-btn kb-btn-primary">Login</button>
</form>

<?php if ($role === 'user'): ?>
<p>
    Belum punya akun?
    <a href="<?= $BASE_URL ?>register.php">Daftar Sekarang</a>
</p>
<?php elseif ($role === 'admin'): ?>
<p>
    Belum punya akun admin?
    <a href="<?= $BASE_URL ?>admin-register.php">Daftar Admin</a>
</p>
<?php endif; ?>

<p><a href="login-select.php">← Kembali</a></p>

<?php include __DIR__ . '/partials/footer.php'; ?>
