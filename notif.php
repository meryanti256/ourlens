<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penjual') {
    header("Location: ../login.php");
    exit();
}

$id_penjual = $_SESSION['id'];
$notifQ = mysqli_query($conn, "SELECT t.*, f.judul FROM transaksi t JOIN foto f ON t.id_foto = f.id WHERE t.id_penjual = $id_penjual AND status = 'baru' ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Notifikasi Penjual</title>
  <link rel="stylesheet" href="penjual.css">
</head>
<body>
<div class="main-content">
  <h2>Notifikasi Pesanan Baru</h2>
  <ul>
    <?php while ($n = mysqli_fetch_assoc($notifQ)): ?>
      <li>
        🛒 Pesanan baru untuk foto <strong><?= htmlspecialchars($n['judul']) ?></strong> oleh ID Pembeli <?= $n['id_pembeli'] ?> pada <?= date('d M Y H:i', strtotime($n['tanggal'])) ?>
      </li>
    <?php endwhile; ?>
  </ul>
</div>
</body>
</html>
