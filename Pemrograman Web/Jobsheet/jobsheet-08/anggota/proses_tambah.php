<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

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

$jumlah    = (int) $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
$noAnggota = 'A' . str_pad($jumlah + 1, 3, '0', STR_PAD_LEFT);

try {
    $stmt = $pdo->prepare(
        "INSERT INTO anggota (nama, no_anggota, alamat, no_hp)
         VALUES (:nama, :no_anggota, :alamat, :no_hp)
         RETURNING id"
    );
    $stmt->execute([
        'nama'       => $nama,
        'no_anggota' => $noAnggota,
        'alamat'     => $alamat,
        'no_hp'      => $telepon,
    ]);
} catch (PDOException $e) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'No. Anggota sudah dipakai, coba lagi.'];
    header('Location: tambah.php');
    exit;
}

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
header('Location: list.php');
exit;
