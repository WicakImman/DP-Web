<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/remember.php';
coba_login_cookie();
$sudahLogin = isset($_SESSION['user_id']);

$__jobsheetRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__jobsheetRoot))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);
$judul = $page_title ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMPUS-Mini<?php echo $judul !== '' ? ' - ' . $judul : ''; ?></title>
  <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style-mobile-first.css">
</head>
<body>

<header>
    <h1>SIMPUS-Mini</h1>
    <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu">&#9776;</button>
    <nav>
        <ul>
            <li><a href="<?php echo $base; ?>index.php"<?php echo $judul === 'Beranda' ? ' class="active"' : ''; ?>>Beranda</a></li>
            <li><a href="<?php echo $base; ?>buku/list.php"<?php echo $judul === 'Daftar Buku' ? ' class="active"' : ''; ?>>Daftar Buku</a></li>
            <?php if ($sudahLogin): ?>
            <li><a href="<?php echo $base; ?>buku/tambah.php"<?php echo $judul === 'Tambah Buku' ? ' class="active"' : ''; ?>>Tambah Buku</a></li>
            <li><a href="<?php echo $base; ?>anggota/list.php"<?php echo $judul === 'Daftar Anggota' ? ' class="active"' : ''; ?>>Daftar Anggota</a></li>
            <li><a href="<?php echo $base; ?>anggota/tambah.php"<?php echo $judul === 'Tambah Anggota' ? ' class="active"' : ''; ?>>Tambah Anggota</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="auth-status">
        <?php if ($sudahLogin): ?>
            <span><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
            <a href="<?php echo $base; ?>auth/logout.php">Logout</a>
        <?php else: ?>
            <a href="<?php echo $base; ?>auth/login.php">Login</a>
        <?php endif; ?>
    </div>
</header>

<main>