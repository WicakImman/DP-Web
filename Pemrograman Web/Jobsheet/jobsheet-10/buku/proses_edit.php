<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id        = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$judul     = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$isbn      = trim($_POST['isbn'] ?? '');
$tahun     = $_POST['tahun'] ?? '';
$kategori  = trim($_POST['kategori'] ?? '');
$stok      = $_POST['stok'] ?? '';

if (!$id) {
    header('Location: list.php');
    exit;
}

$errors = [];

if ($judul === '') {
    $errors[] = "Judul wajib diisi.";
}
if ($pengarang === '') {
    $errors[] = "Pengarang wajib diisi.";
}
if ($isbn !== '' && !preg_match('/^[0-9-]+$/', $isbn)) {
    $errors[] = "ISBN hanya boleh berisi angka dan tanda hubung (-).";
}
if (!is_numeric($tahun) || $tahun < 1900 || $tahun > 2026) {
    $errors[] = "Tahun harus di antara 1900-2026.";
}
if (!in_array($kategori, ['fiksi', 'non-fiksi', 'sains'], true)) {
    $errors[] = "Kategori tidak valid.";
}
if (!is_numeric($stok) || $stok < 0) {
    $errors[] = "Stok tidak boleh kosong atau negatif.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

$stmt = $pdo->prepare(
    "UPDATE buku SET judul = :judul, pengarang = :pengarang, tahun = :tahun,
                     isbn = :isbn, stok = :stok, kategori = :kategori
     WHERE id = :id"
);
$stmt->execute([
    'judul'     => $judul,
    'pengarang' => $pengarang,
    'tahun'     => (int) $tahun,
    'isbn'      => $isbn === '' ? null : $isbn,
    'stok'      => (int) $stok,
    'kategori'  => $kategori,
    'id'        => $id,
]);

if ($stmt->rowCount() === 0) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Buku tidak ditemukan.'];
} else {
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil diperbarui.'];
}
header('Location: list.php');
exit;