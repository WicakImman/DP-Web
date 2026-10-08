<?php
$host = "localhost";
$port = "5432";
$db   = "simpus_mini";
$user = "postgres";
$pass = "hitam";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // mode lunak: dipakai remember.php supaya halaman tidak mati kalau database tidak tersedia
    if (!empty($koneksi_lunak)) {
        $pdo = null;
        return;
    }
    die("Koneksi database gagal: " . $e->getMessage());
}