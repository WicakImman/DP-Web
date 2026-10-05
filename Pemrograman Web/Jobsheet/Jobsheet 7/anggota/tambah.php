<?php
$page_title = "Tambah Anggota";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

    <section>
      <h2>Tambah Anggota</h2>

      <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
      <?php endif; ?>

      <form id="form-tambah" method="post" action="proses_tambah.php" novalidate>
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" required>

        <label for="alamat">Alamat</label>
        <input type="text" id="alamat" name="alamat" required>

        <label for="telepon">No. Telepon</label>
        <input type="tel" id="telepon" name="telepon" required>

        <button type="submit">Simpan</button>
      </form>

    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>