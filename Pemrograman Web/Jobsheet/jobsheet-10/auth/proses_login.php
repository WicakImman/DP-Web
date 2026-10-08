<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/remember.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Latihan 3: batasi percobaan gagal per username (disimpan sementara di $_SESSION)
$maksGagal = 5;
$lamaKunci = 60; // detik
$kunci     = strtolower(substr($username, 0, 50));
$catatan   = $_SESSION['gagal_login'][$kunci] ?? ['jumlah' => 0, 'sampai' => 0];

if ($catatan['sampai'] > time()) {
    $sisa = $catatan['sampai'] - time();
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => "Terlalu banyak percobaan gagal. Coba lagi dalam $sisa detik."];
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    unset($_SESSION['gagal_login'][$kunci]);
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama']    = $user['nama'];
    $_SESSION['role']    = $user['role'];

    if (!empty($_POST['ingat'])) {
        try {
            pasang_cookie_ingat($pdo, (int) $user['id']);
        } catch (PDOException $e) {
        }
    }

    header('Location: ../index.php');
    exit;
}

// gagal: hitung percobaan
$catatan['jumlah']++;
if ($catatan['jumlah'] >= $maksGagal) {
    $catatan['jumlah'] = 0;
    $catatan['sampai'] = time() + $lamaKunci;
    $pesan = "Terlalu banyak percobaan gagal. Coba lagi dalam $lamaKunci detik.";
} else {
    $pesan = 'Username atau password salah. Sisa percobaan: ' . ($maksGagal - $catatan['jumlah']) . '.';
}
$_SESSION['gagal_login'][$kunci] = $catatan;

$_SESSION['flash'] = ['type' => 'error', 'pesan' => $pesan];
header('Location: login.php');
exit;