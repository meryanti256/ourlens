<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$id_pembeli = $_SESSION['id'];
$kodeAksesTerakhir = $_SESSION['kode_akses'] ?? null;
$verifikasi = $_POST['verifikasi'] ?? null;
$fotoList = [];
$error = '';

if (!$kodeAksesTerakhir && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: keranjang.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($verifikasi)) {
        $error = "⚠️ Kode akses tidak boleh kosong.";
    } else {
        $verifikasi = mysqli_real_escape_string($conn, $verifikasi);
        
        $cek = mysqli_query($conn, "SELECT * FROM transaksi WHERE kode_akses = '$verifikasi' AND id_pembeli = $id_pembeli");
        if (!$cek) {
            die("Query gagal (transaksi): " . mysqli_error($conn));
        }

        if (mysqli_num_rows($cek) > 0) {
            $detail = mysqli_query($conn, "
                SELECT f.file_path, f.judul 
                FROM transaksi_detail td 
                JOIN foto f ON td.id_foto = f.id 
                WHERE td.kode_akses = '$verifikasi'
            ");
            if (!$detail) {
                die("Query gagal (detail): " . mysqli_error($conn));
            }

            while ($row = mysqli_fetch_assoc($detail)) {
                $fotoList[] = $row;
            }
        } else {
            $error = "⚠️ Kode akses salah atau tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Berhasil</title>
    <link rel="stylesheet" href="pembeli.css?v=<?= time() ?>">
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="sidebar">
    <h1>OURLENS</h1>
    <ul>
        <li><a href="dashboard-pembeli.php">Dashboard</a></li>
        <li><a href="favorit.php">Favorit Saya</a></li>
        <li><a href="keranjang.php">Keranjang Saya</a></li>
        <li><a href="transaksi.php">Transaksi</a></li>
        <li><a href="kategori.php">Kategori</a></li>
        <li><a href="bantuan.php">Bantuan</a></li>
        <li><a href="profil.php">Profil</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <h1>✅ Pembayaran Berhasil</h1>

    <?php if ($kodeAksesTerakhir): ?>
        <p>Kode akses transaksi Anda (catat baik-baik):</p>
        <div style="background-color: #f1e5ff; padding: 20px; font-size: 20px; font-weight: bold; border-radius: 12px; width: fit-content;">
            <?= htmlspecialchars($kodeAksesTerakhir) ?>
        </div>
    <?php endif; ?>

    <form action="" method="post" style="margin-top: 30px;">
        <p>🔐 Masukkan Kode Akses untuk melihat dan unduh foto:</p>
        <input type="text" name="verifikasi" required value="<?= htmlspecialchars($verifikasi ?? '') ?>">
        <button type="submit">Verifikasi</button>
    </form>

    <?php if ($error): ?>
        <p style="color: red; font-weight: bold; margin-top: 20px;"><?= $error ?></p>
    <?php endif; ?>

    <?php if (!empty($fotoList)): ?>
        <h2>📥 Foto yang dapat diunduh:</h2>
        <ul>
            <?php foreach ($fotoList as $foto): ?>
                <li>
                    <?= htmlspecialchars($foto['judul']) ?> - 
                    <a href="../penjual/gambar/<?= urlencode($foto['file_path']) ?>" download>Download</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.dropdown-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                let menu = this.nextElementSibling;
                menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
            });
        });
    });
</script>

</body>
</html>
