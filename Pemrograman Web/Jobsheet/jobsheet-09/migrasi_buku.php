<?php
require __DIR__ . '/includes/koneksi.php';
header('Content-Type: text/plain; charset=utf-8');

$file = __DIR__ . '/data/buku.json';
if (!file_exists($file)) {
    die("File data/buku.json tidak ditemukan.");
}

$data = json_decode(file_get_contents($file), true);
if (!is_array($data)) {
    die("Isi buku.json tidak valid.");
}

$cek = $pdo->prepare("SELECT COUNT(*) FROM buku WHERE judul = :judul AND pengarang = :pengarang");
$tambah = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, stok)
     VALUES (:judul, :pengarang, :tahun, :stok)"
);

$masuk = 0;
$lewat = 0;

$pdo->beginTransaction();
try {
    foreach ($data as $buku) {
        $cek->execute([
            'judul'     => $buku['judul'],
            'pengarang' => $buku['pengarang'],
        ]);

        if ($cek->fetchColumn() > 0) {
            $lewat++;
            continue;
        }

        $tambah->execute([
            'judul'     => $buku['judul'],
            'pengarang' => $buku['pengarang'],
            'tahun'     => (int) $buku['tahun'],
            'stok'      => (int) $buku['stok'],
        ]);
        $masuk++;
    }
    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Migrasi gagal: " . $e->getMessage());
}

echo "Migrasi selesai.\n";
echo "Berhasil dimasukkan : $masuk buku\n";
echo "Dilewati (sudah ada): $lewat buku\n";