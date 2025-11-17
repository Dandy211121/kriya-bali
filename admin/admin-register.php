<?php
require_once __DIR__ . '/../config/db.php';

// Hanya superadmin yang boleh membuka halaman ini
require_superadmin();

$error = '';
$success = '';

// Ambil master key (HASHED)
$stored_hash = get_setting('admin_master_key');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF protection
    require_csrf();

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['password'] ?? '');
    $verify = trim($_POST['verify_key'] ?? '');

    if ($name === '' || $email === '' || $pass === '' || $verify === '') {
        $error = "Semua field wajib diisi.";
    }
    else if (!password_verify($verify, $stored_hash)) {
        $error = "Password verifikasi admin SALAH!";
    }
    else {
        // cek email sudah digunakan?
        $cek = db_fetch("SELECT id FROM users WHERE email = :e", ['e' => $email]);

        if ($cek) {
            $error = "Email sudah digunakan.";
        } else {

            // buat admin baru
            db_exec("
                INSERT INTO users (name, email, password, role)
                VALUES (:n, :e, :p, 'admin')
            ", [
                'n' => $name,
                'e' => $email,
                'p' => password_hash($pass, PASSWORD_DEFAULT)
            ]);

            // redirect setelah sukses
            header("Location: admin-register.php?success=1");
            exit;
        }
    }
}

// pesan sukses redirect
if (isset($_GET['success'])) {
    $success = "Admin baru berhasil dibuat!";
}

require_once __DIR__ . '/_layout_start.php';
?>

<h1>Buat Admin Baru</h1>

<?php if ($error): ?>
    <div class="kb-alert kb-alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="kb-alert kb-alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" class="kb-form">
    <?= csrf_field() ?>

    <label>Nama Admin</label>
    <input type="text" name="name" required>

    <label>Email Admin</label>
    <input type="email" name="email" required>

    <label>Password Admin Baru</label>
    <input type="password" name="password" required>

    <label>Password Verifikasi Admin (Super Key)</label>
    <input type="password" name="verify_key" required>

    <button class="kb-btn kb-btn-success">Buat Admin</button>
</form>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
