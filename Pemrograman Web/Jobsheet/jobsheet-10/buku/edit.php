<?php
require __DIR__ . '/../includes/koneksi.php';

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);
$buku = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$buku) {
    header('Location: list.php');
    exit;
}

$page_title = "Edit Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

    <section>
      <h2>Edit Buku</h2>

      <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
      <?php endif; ?>

      <form id="form-tambah" class="form-edit" method="post" action="proses_edit.php" novalidate>
        <input type="hidden" name="id" value="<?php echo (int) $buku['id']; ?>">

        <label for="judul">Judul</label>
        <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($buku['judul']); ?>" required>

        <label for="pengarang">Pengarang</label>
        <input type="text" id="pengarang" name="pengarang" value="<?php echo htmlspecialchars($buku['pengarang']); ?>" required>

        <label for="isbn">ISBN (opsional)</label>
        <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars((string) $buku['isbn']); ?>"
               placeholder="contoh: 978-602-03-1234-5">

        <label for="tahun">Tahun Terbit</label>
        <input type="number" id="tahun" name="tahun" min="1900" max="2099"
               value="<?php echo (int) $buku['tahun']; ?>" required>

        <label for="kategori">Kategori</label>
        <select id="kategori" name="kategori">
          <?php foreach (['fiksi' => 'Fiksi', 'non-fiksi' => 'Non-Fiksi', 'sains' => 'Sains'] as $value => $label): ?>
            <option value="<?php echo $value; ?>" <?php echo ($buku['kategori'] ?? '') === $value ? 'selected' : ''; ?>><?php echo $label; ?></option>
          <?php endforeach; ?>
        </select>

        <label for="stok">Stok</label>
        <input type="number" id="stok" name="stok" min="0" value="<?php echo (int) $buku['stok']; ?>" required>

        <button type="submit">Simpan Perubahan</button>
        <a href="list.php" class="link-batal">Batal</a>
      </form>

    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>