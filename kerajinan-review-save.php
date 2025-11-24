<?php
// File: kerajinan-review-save.php
require_once __DIR__ . '/config/db.php';

// 1. Cek Login (Hanya user login yg boleh akses)
require_login();

// 2. Cek Method POST & CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $craft_id = intval($_POST['craft_id']);
    $user_id  = $_SESSION['user']['id'];
    $rating   = intval($_POST['rating']);
    $comment  = trim($_POST['comment']);

    // Validasi sederhana
    if ($rating < 1 || $rating > 5) {
        $rating = 5; // Default jika error
    }

    if (empty($comment)) {
        // Redirect jika kosong
        header("Location: kerajinan-detail.php?id=$craft_id&error=empty");
        exit;
    }

    // 3. Simpan ke Database
    db_exec("
        INSERT INTO craft_reviews (craft_id, user_id, rating, comment, created_at)
        VALUES (:cid, :uid, :rt, :cm, NOW())
    ", [
        'cid' => $craft_id,
        'uid' => $user_id,
        'rt'  => $rating,
        'cm'  => $comment
    ]);

    // 4. Redirect kembali dengan pesan sukses
    header("Location: kerajinan-detail.php?id=$craft_id&msg=review_success");
    exit;
}

// Jika diakses langsung tanpa POST, lempar ke home
header("Location: index.php");
exit;