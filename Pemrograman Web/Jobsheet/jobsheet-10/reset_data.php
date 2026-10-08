<?php
session_start();
require __DIR__ . '/includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$pdo->exec("TRUNCATE TABLE buku, anggota RESTART IDENTITY");

header('Location: index.php');
exit;
