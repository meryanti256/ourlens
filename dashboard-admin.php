<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../koneksi.php';

$username_admin = $_SESSION['username'];
$qAdmin = mysqli_query($conn, "SELECT nama_lengkap FROM users WHERE username = '$username_admin' LIMIT 1");
$dataAdmin = mysqli_fetch_assoc($qAdmin);
$nama_admin = $dataAdmin['nama_lengkap'] ?? $username_admin;

$qUser = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$jumlahUser = mysqli_fetch_assoc($qUser)['total'];

$qFoto = mysqli_query($conn, "SELECT COUNT(*) as total FROM foto");
$jumlahFoto = mysqli_fetch_assoc($qFoto)['total'];

$qTransaksi = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi");
$jumlahTransaksi = mysqli_fetch_assoc($qTransaksi)['total'];

$qKategori = mysqli_query($conn, "
    SELECT kategori, COUNT(*) as total 
    FROM foto 
    GROUP BY kategori 
    ORDER BY total DESC 
    LIMIT 1
");
$kategoriFavorit = mysqli_fetch_assoc($qKategori)['kategori'] ?? 'Belum ada';

$qAktivitas = mysqli_query($conn, "
    SELECT 'user' as tipe, username as info, created_at as waktu FROM users
    UNION
    SELECT 'upload' as tipe, judul as info, created_at as waktu FROM foto
    UNION
    SELECT 'transaksi' as tipe, u.username as info, t.tanggal as waktu 
    FROM transaksi t
    JOIN users u ON t.id_pembeli = u.id
    ORDER BY waktu DESC
    LIMIT 5
");

$qGambarTerbeli = mysqli_query($conn, "
    SELECT f.file_path, f.judul 
    FROM transaksi t
    JOIN foto f ON t.id_foto = f.id
    ORDER BY t.tanggal DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #F9FAFC;
      margin: 0;
      color:  #1A237E;
    }

    .topbar {
      background-color: #1A237E;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px 30px;
    }

    .topbar .logo {
      font-size: 24px;
      font-weight: bold;
    }

    .topbar .menu a {
      margin: 0 12px;
      color: white;
      text-decoration: none;
      font-weight: 500;
    }

    .topbar .menu a.active,
    .topbar .menu a:hover {
      border-bottom: 2px solid #F9D949;
    }

    .main-content {
      padding: 30px;
    }

    .header h2 {
      font-size: 24px;
      margin-bottom: 6px;
      color:#373bb5;
    }

    .header p {
      color: #444;
      margin-bottom: 30px;
    }

    .summary-boxes {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 40px;
    }

    .summary-card {
      flex: 1 1 200px;
      padding: 20px;
      background: #f0f4ff;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      transition: transform 0.3s;
      color:  #1A237E;
      font-weight: bold;
    }

    .summary-card:hover {
      transform: scale(1.03);
    }

    .dashboard-bawah {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
    }

    .aktivitas-box,
    .tombol-cepat-box {
      flex: 1;
      background: #f9f9f9;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .aktivitas-box h2,
    .tombol-cepat-box h2 {
      font-size: 18px;
      margin-bottom: 16px;
      color:#1A237E;
      border-bottom: 2px solid  #1A237E;
      padding-bottom: 6px;
    }

    .aktivitas-box ul {
      list-style: none;
      padding-left: 0;
    }

    .aktivitas-box li {
      margin-bottom: 10px;
      font-size: 15px;
    }

    .tombol-cepat-box button {
      background-color: #1A237E;
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      margin-bottom: 12px;
      font-weight: 600;
      cursor: pointer;
    }

    .tombol-cepat-box button:hover {
      background-color: #1A237E;
    }

    .slide-container {
      margin-top: 40px;
      text-align: center;
    }

    #slideshow {
      position: relative;
      width: 100%;
      max-width: 100%;
      height: 400px;
      margin: auto;
      overflow: hidden;
      border-radius: 12px;
      background: #e6ecff;
    }

    .slide {
      position: absolute;
      width: 100%;
      height: 100%;
      opacity: 0;
      transition: opacity 1s;
    }

    .slide.active {
      opacity: 1;
      z-index: 1;
    }

    .slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .caption {
      position: absolute;
      bottom: 0;
      background:  #1A237E;
      color: white;
      width: 100%;
      padding: 10px;
      font-weight: 500;
    }
  </style>
</head>
<body>

<div class="topbar">
  <div class="logo">OURLENS</div>
  <div class="menu">
    <a href="dashboard-admin.php" class="active">Dashboard</a>
    <a href="data-penjual.php">Data Penjual</a>
    <a href="data-pembeli.php">Data Pembeli</a>
    <a href="data-produk.php">Produk</a>
    <a href="transaksi.php">Transaksi</a>
    <a href="laporan.php">Laporan</a>
    <a href="../logout.php">Logout</a>
  </div>
</div>

<div class="main-content">
  <div class="header">
    <h2>Hai, <strong><?= htmlspecialchars($nama_admin) ?></strong> 👋</h2>
    <p>Selamat datang kembali di dashboard admin OURLENS!</p>
  </div>

  <div class="summary-boxes">
    <div class="summary-card">👥<br><strong><?= $jumlahUser ?></strong><br>Pengguna</div>
    <div class="summary-card">📷<br><strong><?= $jumlahFoto ?></strong><br>Foto</div>
    <div class="summary-card">💰<br><strong><?= $jumlahTransaksi ?></strong><br>Transaksi</div>
    <div class="summary-card">🏞️<br><strong><?= htmlspecialchars($kategoriFavorit) ?></strong><br>Favorit</div>
  </div>

  <div class="dashboard-bawah">
    <div class="aktivitas-box">
      <h2>Aktivitas Terbaru</h2>
      <ul>
        <?php while($a = mysqli_fetch_assoc($qAktivitas)) : ?>
          <li>
            <?php if ($a['tipe'] == 'user') : ?>
              🧍 Pengguna baru: <strong><?= htmlspecialchars($a['info']) ?></strong>
            <?php elseif ($a['tipe'] == 'upload') : ?>
              📤 Foto baru: <strong><?= htmlspecialchars($a['info']) ?></strong>
            <?php else : ?>
              🛒 Transaksi oleh: <strong><?= htmlspecialchars($a['info']) ?></strong>
            <?php endif; ?>
          </li>
        <?php endwhile; ?>
      </ul>
    </div>

    <div class="tombol-cepat-box">
      <h2>Tombol Cepat</h2>
      <button onclick="location.href='kelola-user.php'">Kelola User</button>
      <button onclick="location.href='tambah-admin.php'">Tambah Admin</button>
    </div>
  </div>

  <div class="slide-container">
    <h2>🖼️ Gambar Terbaru yang Dibeli</h2>
    <div id="slideshow">
      <?php $index = 0; while($g = mysqli_fetch_assoc($qGambarTerbeli)) : ?>
        <div class="slide<?= $index === 0 ? ' active' : '' ?>">
          <img src="../penjual/gambar/<?= htmlspecialchars($g['file_path']) ?>" alt="<?= htmlspecialchars($g['judul']) ?>">
          <div class="caption"><?= htmlspecialchars($g['judul']) ?></div>
        </div>
      <?php $index++; endwhile; ?>
    </div>
  </div>
</div>

<script>
  const slides = document.querySelectorAll('.slide');
  let current = 0;

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
    });
  }

  function nextSlide() {
    current = (current + 1) % slides.length;
    showSlide(current);
  }

  if (slides.length > 0) {
    setInterval(nextSlide, 3000);
  }
</script>

</body>
</html>
