<?php
$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$perPage = 10;
$keyword = trim($_GET['q'] ?? '');
$page    = max(1, (int) ($_GET['page'] ?? 1));

// hitung total baris (ikut pencarian kalau ada)
if ($keyword !== '') {
    $hitung = $pdo->prepare("SELECT COUNT(*) FROM buku WHERE judul ILIKE :kw1 OR pengarang ILIKE :kw2");
    $hitung->execute(['kw1' => '%' . $keyword . '%', 'kw2' => '%' . $keyword . '%']);
    $totalRows = (int) $hitung->fetchColumn();
} else {
    $totalRows = (int) $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
}

$totalPages = max(1, (int) ceil($totalRows / $perPage));
$page       = min($page, $totalPages);
$offset     = ($page - 1) * $perPage;

if ($keyword !== '') {
    $stmt = $pdo->prepare(
        "SELECT * FROM buku WHERE judul ILIKE :kw1 OR pengarang ILIKE :kw2
         ORDER BY id DESC LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue(':kw1', '%' . $keyword . '%');
    $stmt->bindValue(':kw2', '%' . $keyword . '%');
} else {
    $stmt = $pdo->prepare("SELECT * FROM buku ORDER BY id DESC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <section>
      <h2>Daftar Buku</h2>

      <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
      <?php endif; ?>

      <div class="search-box">
        <form method="get" action="list.php">
          <span>
            <label for="search-input">Cari Judul atau Pengarang</label>
            <input type="text" id="search-input" name="q" data-kolom="0"
                   value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Ketik judul atau pengarang...">
          </span>
          <button type="submit">Cari</button>
          <?php if ($keyword !== ''): ?>
            <a href="list.php">Tampilkan semua</a>
          <?php endif; ?>
        </form>
      </div>
      <p id="info-jumlah" data-satuan="buku"></p>

      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Judul</th>
              <th>Pengarang</th>
              <th>Tahun</th>
              <th>Stok</th>
              <th>Ditambahkan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($daftarBuku)): ?>
              <tr>
                <?php if ($keyword !== ''): ?>
                  <td colspan="6">Tidak ada buku yang cocok dengan "<?php echo htmlspecialchars($keyword); ?>".</td>
                <?php else: ?>
                  <td colspan="6">Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".</td>
                <?php endif; ?>
              </tr>
            <?php else: ?>
              <?php foreach ($daftarBuku as $buku): ?>
                <tr>
                  <td><?php echo htmlspecialchars($buku['judul']); ?></td>
                  <td><?php echo htmlspecialchars($buku['pengarang']); ?></td>
                  <td><?php echo (int) $buku['tahun']; ?></td>
                  <td><?php echo (int) $buku['stok']; ?></td>
                  <td><?php echo $buku['tanggal_ditambahkan'] ? date('d/m/Y H:i', strtotime($buku['tanggal_ditambahkan'])) : '-'; ?></td>
                  <td>
                    <a href="edit.php?id=<?php echo (int) $buku['id']; ?>" class="btn-edit">Edit</a>
                    <form class="form-hapus" method="post" action="hapus.php"
                          data-nama="<?php echo htmlspecialchars($buku['judul']); ?>">
                      <input type="hidden" name="id" value="<?php echo (int) $buku['id']; ?>">
                      <button type="submit" class="btn-hapus">Hapus</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <nav class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="list.php?page=<?php echo $i; ?><?php echo $keyword !== '' ? '&amp;q=' . urlencode($keyword) : ''; ?>"
             class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
      </nav>

    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>