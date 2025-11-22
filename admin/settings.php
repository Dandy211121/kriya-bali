<?php
require_once __DIR__ . '/../config/db.php';

// Hanya superadmin
require_superadmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $new = trim($_POST['new_key'] ?? '');

    if ($new === '') {
        $error = "Password verifikasi admin tidak boleh kosong.";
    } else {
        // simpan hashed ke settings
        set_setting('admin_master_key', password_hash($new, PASSWORD_DEFAULT));

        header("Location: settings.php?success=1");
        exit;
    }
}

if (isset($_GET['success'])) {
    $success = "Password verifikasi admin berhasil diperbarui!";
}

require_once __DIR__ . '/_layout_start.php';
?>

<h1 class="kb-admin-title">
    <i class="bi bi-gear-fill"></i> Pengaturan Superadmin
</h1>

<p class="kb-muted" style="margin-top:-6px;">
    Halaman ini digunakan untuk mengganti Super Key. Hanya superadmin yang dapat mengakses.
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


<div class="kb-admin-card" style="max-width:600px;">

    <form method="POST" class="kb-form">
        <?= csrf_field() ?>

        <label>Password Verifikasi Admin Baru</label>
        <input type="password" name="new_key" required>

        <button class="kb-btn kb-btn-success" style="margin-top:8px;">
            <i class="bi bi-shield-lock-fill"></i> Simpan Perubahan
        </button>
    </form>

</div>

<?php require_once __DIR__ . '/_layout_end.php'; ?>
