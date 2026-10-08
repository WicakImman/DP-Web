<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/remember.php';
coba_login_cookie();

if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Registrasi Petugas";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

    <section>
      <h2>Registrasi Petugas</h2>

      <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
      <?php endif; ?>

      <form id="form-register" method="post" action="proses_register.php">
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" required>

        <label for="username">Username</label>
        <input type="text" id="username" name="username" maxlength="50" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" minlength="6" required>

        <button type="submit">Daftar</button>
      </form>

      <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>