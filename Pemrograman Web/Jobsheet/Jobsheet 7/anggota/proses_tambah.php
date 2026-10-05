<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tambah.php');
    exit;
}

$nama    = trim($_POST['nama'] ?? '');
$alamat  = trim($_POST['alamat'] ?? '');
$telepon = trim($_POST['telepon'] ?? '');

$errors = [];

if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($alamat === '') {
    $errors[] = "Alamat wajib diisi.";
}
if ($telepon === '') {
    $errors[] = "No. telepon wajib diisi.";
} elseif (!preg_match('/^[0-9+\- ]{8,15}$/', $telepon)) {
    $errors[] = "No. telepon hanya boleh berisi angka, +, - dan spasi (8-15 karakter).";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

if (!isset($_SESSION['anggota'])) {
    $_SESSION['anggota'] = [];
}

$noAnggota = 'A' . str_pad(count($_SESSION['anggota']) + 1, 3, '0', STR_PAD_LEFT);

$_SESSION['anggota'][] = [
    'no_anggota' => $noAnggota,
    'nama'       => $nama,
    'alamat'     => $alamat,
    'no_hp'      => $telepon,
];

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
header('Location: list.php');
exit;