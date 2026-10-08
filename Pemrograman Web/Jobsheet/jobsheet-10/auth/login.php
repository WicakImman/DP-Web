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

$page_title = "Login";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

    <section>
      <h2>Login Petugas</h2>

      <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
      <?php endif; ?>

      <form id="form-login" method="post" action="proses_login.php">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <div class="ingat-saya">
          <input type="checkbox" id="ingat" name="ingat" value="1">
          <label for="ingat">Ingat saya selama 30 hari</label>
        </div>

        <button type="submit">Masuk</button>
      </form>

      <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>