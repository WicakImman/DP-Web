<?php
$page_title = "Tambah Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

    <section>
      <h2>Tambah Buku</h2>

      <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
      <?php endif; ?>

      <form id="form-tambah" method="post" action="proses_tambah.php" novalidate>
        <label for="judul">Judul</label>
        <input type="text" id="judul" name="judul" required>

        <label for="pengarang">Pengarang</label>
        <input type="text" id="pengarang" name="pengarang" required>

        <label for="isbn">ISBN (opsional)</label>
        <input type="text" id="isbn" name="isbn" placeholder="contoh: 978-602-03-1234-5">

        <label for="tahun">Tahun Terbit</label>
        <input type="number" id="tahun" name="tahun" min="1900" max="2099" required>

        <label for="kategori">Kategori</label>
        <select id="kategori" name="kategori">
          <option value="fiksi">Fiksi</option>
          <option value="non-fiksi">Non-Fiksi</option>
          <option value="sains">Sains</option>
        </select>

        <label for="stok">Stok</label>
        <input type="number" id="stok" name="stok" min="0" required>

        <button type="submit">Simpan</button>
      </form>

    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
