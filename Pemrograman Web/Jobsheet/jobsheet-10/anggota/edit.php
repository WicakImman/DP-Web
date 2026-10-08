<?php
require __DIR__ . '/../includes/koneksi.php';

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
$stmt->execute(['id' => $id]);
$anggota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anggota) {
    header('Location: list.php');
    exit;
}

$page_title = "Edit Anggota";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

    <section>
      <h2>Edit Anggota</h2>

      <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
      <?php endif; ?>

      <form id="form-tambah" class="form-edit" method="post" action="proses_edit.php" novalidate>
        <input type="hidden" name="id" value="<?php echo (int) $anggota['id']; ?>">

        <label for="no_anggota">No. Anggota</label>
        <input type="text" id="no_anggota" value="<?php echo htmlspecialchars($anggota['no_anggota']); ?>" disabled>

        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($anggota['nama']); ?>" required>

        <label for="alamat">Alamat</label>
        <input type="text" id="alamat" name="alamat" value="<?php echo htmlspecialchars((string) $anggota['alamat']); ?>" required>

        <label for="telepon">No. Telepon</label>
        <input type="tel" id="telepon" name="telepon" value="<?php echo htmlspecialchars((string) $anggota['no_hp']); ?>" required>

        <button type="submit">Simpan Perubahan</button>
        <a href="list.php" class="link-batal">Batal</a>
      </form>

    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>