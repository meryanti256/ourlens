<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penjual') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';
$id_penjual = $_SESSION['id'];
$username_penjual = $_SESSION['username'];

$qPenjual = mysqli_query($conn, "SELECT nama_lengkap FROM users WHERE id = $id_penjual");
$dataPenjual = mysqli_fetch_assoc($qPenjual);
$nama_penjual = $dataPenjual['nama_lengkap'] ?? $username_penjual;

$qFoto = mysqli_query($conn, "SELECT COUNT(*) AS total FROM foto WHERE id_penjual = $id_penjual");
$jumlahFoto = mysqli_fetch_assoc($qFoto)['total'] ?? 0;

$qPesanan = mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi t JOIN foto f ON t.id_foto = f.id WHERE f.id_penjual = $id_penjual");
$jumlahPesanan = mysqli_fetch_assoc($qPesanan)['total'] ?? 0;

$qTotal = mysqli_query($conn, "SELECT SUM(t.total) AS total FROM transaksi t JOIN foto f ON t.id_foto = f.id WHERE f.id_penjual = $id_penjual");
$totalPendapatan = mysqli_fetch_assoc($qTotal)['total'] ?? 0;

$qAktivitas = mysqli_query($conn, "
    SELECT t.tanggal, f.judul 
    FROM transaksi t
    JOIN foto f ON t.id_foto = f.id
    WHERE f.id_penjual = $id_penjual
    ORDER BY t.tanggal DESC
    LIMIT 5
");

$qGambarTerbeli = mysqli_query($conn, "
    SELECT DISTINCT f.file_path, f.judul
    FROM transaksi t
    JOIN foto f ON t.id_foto = f.id
    WHERE f.id_penjual = $id_penjual
    ORDER BY t.tanggal DESC
    LIMIT 10
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Penjual</title>
  <link rel="stylesheet" href="penjual.css?v=<?= time() ?>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    .slide-container {
      margin: 40px 30px 20px;
    }

    .slide-container h2 {
      font-size: 20px;
      color: #4a148c;
      margin-bottom: 16px;
    }

    .slide-scroll {
      display: flex;
      gap: 16px;
      overflow-x: auto;
      scroll-snap-type: x mandatory;
      padding-bottom: 10px;
    }

    .slide-item {
      flex: 0 0 auto;
      width: 220px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      scroll-snap-align: start;
    }

    .slide-item img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 12px 12px 0 0;
    }

    .slide-caption {
      text-align: center;
      padding: 10px;
      font-weight: bold;
      color: #1A237E;
    }
  </style>
</head>
<body>

<div class="topbar">
  <div class="logo">OURLENS</div>
  <div class="menu">
    <a href="dashboard-penjual.php" class="active">Dashboard</a>
    <a href="unggah-foto.php">Unggah Foto</a>
    <a href="foto-saya.php">Foto Saya</a>
    <a href="pesanan.php">Pesanan Masuk</a>
    <a href="profil.php">Profil</a>
    <a href="../logout.php">Logout</a>
  </div>
</div>

<div class="main-content">
  <div class="header">
    <h2>Hai, <strong><?= htmlspecialchars($nama_penjual) ?></strong> 👋</h2>
    <p>Selamat datang di dashboard penjual OURLENS!</p>
  </div>

  <div class="summary-boxes">
    <div class="summary-card">📷<br><strong><?= $jumlahFoto ?></strong><br>Foto Diunggah</div>
    <div class="summary-card">🛒<br><strong><?= $jumlahPesanan ?></strong><br>Pesanan Masuk</div>
    <div class="summary-card">💰<br><strong>Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></strong><br>Total Pendapatan</div>
  </div>

  <div class="dashboard-bawah">
    <div class="aktivitas-box">
      <h2>Aktivitas Terbaru</h2>
      <ul>
        <?php if (mysqli_num_rows($qAktivitas) > 0): ?>
          <?php while($a = mysqli_fetch_assoc($qAktivitas)): ?>
            <li>📥 Foto <strong><?= htmlspecialchars($a['judul']) ?></strong> dibeli pada <?= date('d M Y', strtotime($a['tanggal'])) ?></li>
          <?php endwhile; ?>
        <?php else: ?>
          <li>Belum ada transaksi terbaru.</li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="tombol-cepat-box">
      <h2>Tombol Cepat</h2>
      <button onclick="location.href='unggah-foto.php'">📤 Unggah Foto</button>
      <button onclick="location.href='foto-saya.php'">🖼️ Kelola Foto</button>
    </div>
  </div>

  <div class="slide-container">
    <center> <h2>🖼️ Gambar Terbaru yang Dibeli</h2> </center>
    <div class="slide-scroll">
      <?php while($g = mysqli_fetch_assoc($qGambarTerbeli)) : ?>
        <div class="slide-item">
          <img src="../penjual/gambar/<?= htmlspecialchars($g['file_path']) ?>" alt="<?= htmlspecialchars($g['judul']) ?>">
          <div class="slide-caption"><?= htmlspecialchars($g['judul']) ?></div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

</div>
</body>
</html>
