<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Daftar Anggota";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$adalahAdmin = ($_SESSION['role'] ?? '') === 'admin';

$perPage = 5;
$keyword = trim($_GET['q'] ?? '');
$page    = max(1, (int) ($_GET['page'] ?? 1));

// hitung total baris (ikut pencarian kalau ada)
if ($keyword !== '') {
    $hitung = $pdo->prepare("SELECT COUNT(*) FROM anggota WHERE nama ILIKE :kw");
    $hitung->execute(['kw' => '%' . $keyword . '%']);
    $totalRows = (int) $hitung->fetchColumn();
} else {
    $totalRows = (int) $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
}

$totalPages = max(1, (int) ceil($totalRows / $perPage));
$page       = min($page, $totalPages);
$offset     = ($page - 1) * $perPage;

if ($keyword !== '') {
    $stmt = $pdo->prepare(
        "SELECT * FROM anggota WHERE nama ILIKE :kw
         ORDER BY id DESC LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue(':kw', '%' . $keyword . '%');
} else {
    $stmt = $pdo->prepare("SELECT * FROM anggota ORDER BY id DESC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$daftarAnggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <section>
      <h2>Daftar Anggota</h2>

      <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
      <?php endif; ?>

      <div class="search-box">
        <form method="get" action="list.php">
          <span>
            <label for="search-input">Cari Nama Anggota</label>
            <input type="text" id="search-input" name="q" data-kolom="1"
                   value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Ketik nama anggota...">
          </span>
          <button type="submit">Cari</button>
          <?php if ($keyword !== ''): ?>
            <a href="list.php">Tampilkan semua</a>
          <?php endif; ?>
        </form>
      </div>
      <p id="info-jumlah" data-satuan="anggota"></p>

      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>No. Anggota</th>
              <th>Nama</th>
              <th>Alamat</th>
              <th>No. Telepon</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($daftarAnggota)): ?>
              <tr>
                <?php if ($keyword !== ''): ?>
                  <td colspan="5">Tidak ada anggota yang cocok dengan "<?php echo htmlspecialchars($keyword); ?>".</td>
                <?php else: ?>
                  <td colspan="5">Belum ada data anggota. Silakan tambah lewat menu "Tambah Anggota".</td>
                <?php endif; ?>
              </tr>
            <?php else: ?>
              <?php foreach ($daftarAnggota as $anggota): ?>
                <tr>
                  <td><?php echo htmlspecialchars($anggota['no_anggota']); ?></td>
                  <td><?php echo htmlspecialchars($anggota['nama']); ?></td>
                  <td><?php echo htmlspecialchars((string) $anggota['alamat']); ?></td>
                  <td><?php echo htmlspecialchars((string) $anggota['no_hp']); ?></td>
                  <td>
                    <a href="edit.php?id=<?php echo (int) $anggota['id']; ?>" class="btn-edit">Edit</a>
                    <?php if ($adalahAdmin): ?>
                    <form class="form-hapus" method="post" action="hapus.php"
                          data-nama="<?php echo htmlspecialchars($anggota['nama']); ?>">
                      <input type="hidden" name="id" value="<?php echo (int) $anggota['id']; ?>">
                      <button type="submit" class="btn-hapus">Hapus</button>
                    </form>
                    <?php endif; ?>
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