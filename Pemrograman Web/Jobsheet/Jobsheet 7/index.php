<?php
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';

$totalBuku = count($_SESSION['buku'] ?? []);
$totalAnggota = count($_SESSION['anggota'] ?? []);
?>

    <section>
      <h2>Selamat Datang</h2>
      <p>SIMPUS-Mini adalah sistem informasi sederhana untuk mengelola data buku dan anggota perpustakaan.</p>
    </section>

    <section>
      <h2>Ringkasan</h2>
      <article>
        <h3>Total Buku</h3>
        <p><?php echo $totalBuku; ?></p>
      </article>
      <article>
        <h3>Total Anggota</h3>
        <p><?php echo $totalAnggota; ?></p>
      </article>
      <article>
        <h3>Sedang Dipinjam</h3>
        <p>0</p>
      </article>
    </section>


    <section>
      <h2>Reset Data</h2>
      <form method="post" action="reset_data.php"
            onsubmit="return confirm('Hapus semua data buku dan anggota?');">
        <button type="submit">Reset Data</button>
      </form>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>