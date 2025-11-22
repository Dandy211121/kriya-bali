<?php
require_once __DIR__ . '/../config/db.php';

// Hanya superadmin yang boleh membuka halaman ini
require_superadmin();

$error = '';
$success = '';

// Ambil master key (HASHED)
$stored_hash = get_setting('admin_master_key');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $name   = trim($_POST['name'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $pass   = trim($_POST['password'] ?? '');
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

            db_exec("
                INSERT INTO users (name, email, password, role)
                VALUES (:n, :e, :p, 'admin')
            ", [
                'n' => $name,
                'e' => $email,
                'p' => password_hash($pass, PASSWORD_DEFAULT)
            ]);

            header("Location: admin-register.php?success=1");
            exit;
        }
    }
}

if (isset($_GET['success'])) {
    $success = "Admin baru berhasil dibuat!";
}

require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">
    <i class="bi bi-person-plus-fill" style="margin-right:6px;"></i>
    Tambah Admin Baru
</h1>

<p class="kb-muted" style="margin-top:-8px;">
    Hanya Superadmin yang dapat menambahkan admin baru.
</p>

<?php if ($error): ?>
    <div class="kb-alert kb-alert-error">
        <i class="bi bi-exclamation-triangle-fill"></i> 
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="kb-alert kb-alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<div class="kb-admin-card" style="max-width: 600px;">

<form method="POST" class="kb-form">
    <?= csrf_field() ?>

    <label>Nama Admin</label>
    <input type="text" name="name" required>

    <label>Email Admin</label>
    <input type="email" name="email" required>

    <label>Password Admin Baru</label>
    <input type="password" name="password" required>

    <label>Password Verifikasi (Superadmin Key)</label>
    <input type="password" name="verify_key" required>

    <button class="kb-btn kb-btn-success" style="margin-top:10px;">
        <i class="bi bi-shield-lock-fill"></i> Buat Admin
    </button>
</form>

</div>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
