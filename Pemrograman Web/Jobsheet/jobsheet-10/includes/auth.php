<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/remember.php';
coba_login_cookie();

if (!isset($_SESSION['user_id'])) {
    // hitung path ke auth/login.php dari folder halaman yang sedang dibuka
    $__root = str_replace('\\', '/', dirname(__DIR__));
    $__dir  = str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME']));
    $__rel  = trim(substr($__dir, strlen($__root)), '/');
    $__naik = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);

    header('Location: ' . $__naik . 'auth/login.php');
    exit;
}

function wajib_admin(string $kembali = 'list.php'): void
{
    if (($_SESSION['role'] ?? '') !== 'admin') {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Hanya admin yang boleh melakukan aksi ini.'];
        header('Location: ' . $kembali);
        exit;
    }
}