<?php
require_once __DIR__ . '/../config/db.php';

// Hanya superadmin yang boleh membuka halaman ini
require_superadmin();

$error = '';
$success = '';

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF protection
    require_csrf();

    $new = trim($_POST['new_key'] ?? '');

    if ($new === '') {
        $error = "Password verifikasi admin tidak boleh kosong.";
    } else {
        // Simpan ke database dalam bentuk HASH
        set_setting('admin_master_key', password_hash($new, PASSWORD_DEFAULT));

        // Redirect agar tidak double submit
        header("Location: settings.php?success=1");
        exit;
    }
}

// pesan sukses dari redirect
if (isset($_GET['success'])) {
    $success = "Password verifikasi admin berhasil diubah!";
}

require_once __DIR__ . '/_layout_start.php';
?>

<h1>Pengaturan Admin</h1>

<?php if ($error): ?>
    <div class="kb-alert kb-alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="kb-alert kb-alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" class="kb-form">
    <?= csrf_field() ?>

    <label>Password Verifikasi Admin Baru</label>
    <input type="password" name="new_key" required>

    <button class="kb-btn kb-btn-primary">Simpan</button>

</form>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
