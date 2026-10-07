<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id      = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$nama    = trim($_POST['nama'] ?? '');
$alamat  = trim($_POST['alamat'] ?? '');
$telepon = trim($_POST['telepon'] ?? '');

if (!$id) {
    header('Location: list.php');
    exit;
}

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
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

$stmt = $pdo->prepare(
    "UPDATE anggota SET nama = :nama, alamat = :alamat, no_hp = :no_hp
     WHERE id = :id"
);
$stmt->execute([
    'nama'   => $nama,
    'alamat' => $alamat,
    'no_hp'  => $telepon,
    'id'     => $id,
]);

if ($stmt->rowCount() === 0) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Anggota tidak ditemukan.'];
} else {
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil diperbarui.'];
}
header('Location: list.php');
exit;