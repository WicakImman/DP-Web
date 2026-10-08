<?php
// Fitur "Ingat Saya": cookie berisi id:token acak, di database hanya disimpan hash token-nya.

function pasang_cookie_ingat(PDO $pdo, int $userId): void
{
    $token = bin2hex(random_bytes(32));

    $stmt = $pdo->prepare(
        "UPDATE users SET remember_token = :token, remember_expires = NOW() + INTERVAL '30 days'
         WHERE id = :id"
    );
    $stmt->execute(['token' => hash('sha256', $token), 'id' => $userId]);

    setcookie('ingat_saya', $userId . ':' . $token, [
        'expires'  => time() + 60 * 60 * 24 * 30,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function hapus_cookie_ingat(?int $userId = null): void
{
    setcookie('ingat_saya', '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    if ($userId) {
        $koneksi_lunak = true;
        require __DIR__ . '/koneksi.php';
        if ($pdo) {
            try {
                $pdo->prepare("UPDATE users SET remember_token = NULL, remember_expires = NULL WHERE id = :id")
                    ->execute(['id' => $userId]);
            } catch (PDOException $e) {
                // kolom belum dibuat / database bermasalah: cookie sudah dihapus, cukup
            }
        }
    }
}

// Dipanggil setelah session dimulai. Tidak melakukan apa-apa kalau sudah login
// atau tidak ada cookie, jadi guard tetap tidak menyentuh database pada kasus umum.
function coba_login_cookie(): void
{
    if (isset($_SESSION['user_id']) || !isset($_COOKIE['ingat_saya'])) {
        return;
    }

    $bagian = explode(':', $_COOKIE['ingat_saya'], 2);
    if (count($bagian) !== 2 || !ctype_digit($bagian[0]) || $bagian[1] === '') {
        return;
    }

    $koneksi_lunak = true;
    require __DIR__ . '/koneksi.php';
    if (!$pdo) {
        return;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id AND remember_expires > NOW()");
        $stmt->execute(['id' => (int) $bagian[0]]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['remember_token'] !== null
            && hash_equals($user['remember_token'], hash('sha256', $bagian[1]))) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama']    = $user['nama'];
            $_SESSION['role']    = $user['role'];
            pasang_cookie_ingat($pdo, (int) $user['id']); // token diganti tiap dipakai
        }
    } catch (PDOException $e) {
        // database/kolom bermasalah: anggap saja belum login
    }
}