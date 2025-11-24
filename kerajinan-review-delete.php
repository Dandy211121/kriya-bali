<?php
// File: kerajinan-review-delete.php
require_once __DIR__ . '/config/db.php';

// 1. Wajib Login
require_login();

// 2. Cek Method POST & Validasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf(); // Keamanan anti-hack

    $review_id = intval($_POST['review_id']);
    $user_id   = $_SESSION['user']['id'];

    // 3. Ambil data review dulu (untuk cek kepemilikan & redirect)
    $review = db_fetch("SELECT * FROM craft_reviews WHERE id = :id", ['id' => $review_id]);

    if ($review) {
        $craft_id = $review['craft_id'];

        // 4. PENTING: Cek apakah User yang login ADALAH pemilik review ini?
        if ($review['user_id'] == $user_id) {
            
            // Hapus data
            db_exec("DELETE FROM craft_reviews WHERE id = :id", ['id' => $review_id]);
            
            $msg = "deleted";
        } else {
            // Jika mencoba menghapus punya orang lain
            $msg = "unauthorized";
        }

        // Redirect kembali ke halaman detail
        header("Location: kerajinan-detail.php?id=$craft_id&msg=$msg");
        exit;
    }
}

// Jika error / akses langsung
header("Location: index.php");
exit;