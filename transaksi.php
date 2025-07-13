<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$id_pembeli = $_SESSION['id'];
$username = $_SESSION['username'];

$query = "
    SELECT t.*, f.judul, f.harga, f.file_path, u.username AS penjual
    FROM transaksi t
    JOIN foto f ON t.id_foto = f.id
    LEFT JOIN users u ON f.id_penjual = u.id
    WHERE t.id_pembeli = $id_pembeli
    ORDER BY t.tanggal DESC
";
$result = mysqli_query($conn, $query);
$transaksi = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Transaksi - OurLens</title>
  <link rel="stylesheet" href="pembeli.css?v=<?= time() ?>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f3f6ff;
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
      border-bottom: 2px solid #FFC107;
    }
    .user-section {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .dropdown button {
      background: none;
      border: none;
      color: white;
      cursor: pointer;
      font-weight: bold;
    }
    #profileDropdown {
      display: none;
      position: absolute;
      right: 30px;
      top: 60px;
      background: white;
      color: black;
      list-style: none;
      border-radius: 6px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      min-width: 150px;
      z-index: 999;
    }
    #profileDropdown li a {
      display: block;
      padding: 10px;
      text-decoration: none;
      color: #333;
    }
    #profileDropdown li a:hover {
      background: #f0f0f0;
    }

    .main-content {
      padding: 30px;
    }

    h1 {
      font-size: 26px;
      font-weight: 700;
      color: #1A237E;
      margin-bottom: 20px;
    }

    .notif {
      background: #dff0d8;
      color: #3c763d;
      padding: 10px 20px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .foto-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .foto-item {
      width: 240px;
      background: #dbe8ff;
      padding: 14px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      text-align: center;
      transition: transform 0.3s ease;
    }

    .foto-item:hover {
      transform: scale(1.04);
    }

    .foto-item img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 10px;
    }

    .foto-item strong {
      display: block;
      margin-bottom: 6px;
      font-size: 15px;
      color: #1a237e;
    }

    .foto-item .harga {
      color: #1A237E;
      font-weight: bold;
      margin-bottom: 6px;
      font-size: 14px;
    }

    .foto-item .deskripsi {
      font-size: 12px;
      color: #333;
      margin-bottom: 6px;
    }

    .btn-group {
      margin-top: 8px;
    }

    .btn-cart {
      background-color: #3f51b5;
      color: white;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 500;
      display: inline-block;
      font-size: 14px;
    }

    .btn-cart:hover {
      background-color: #2c3cb3;
    }

    .btn-primary {
      margin-top: 30px;
      background: #1A237E;
      color: white;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      display: inline-block;
    }

    .btn-primary:hover {
      background-color: #121b60;
    }
  </style>
</head>
<body>

<div class="topbar">
  <div class="logo">OURLENS</div>
  <nav class="menu">
    <a href="dashboard-pembeli.php">Dashboard</a>
    <a href="favorit.php">Favorit</a>
    <a href="keranjang.php">Keranjang</a>
    <a href="transaksi.php" class="active">Transaksi</a>
    <a href="kategori.php?k=semua">Kategori</a>
    <a href="bantuan.php">Bantuan</a>
  </nav>
  <div class="user-section">
    <div class="dropdown">
      <button onclick="toggleDropdown()">👤 <?= htmlspecialchars($_SESSION['nama'] ?? $username) ?> ▼</button>
      <ul id="profileDropdown">
        <li><a href="profil.php">Profil</a></li>
        <li><a href="../logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</div>

<div class="main-content">
  <h1>🧾 Riwayat Transaksi</h1>

  <?php if (isset($_SESSION['notif_transaksi'])): ?>
    <div class="notif"><?= $_SESSION['notif_transaksi']; unset($_SESSION['notif_transaksi']); ?></div>
  <?php endif; ?>

  <?php if (empty($transaksi)): ?>
    <p>Belum ada transaksi yang dilakukan.</p>
  <?php else: ?>
    <div class="foto-container">
      <?php foreach ($transaksi as $t): ?>
        <div class="foto-item">
          <img src="../penjual/gambar/<?= htmlspecialchars($t['file_path']) ?>" alt="<?= htmlspecialchars($t['judul']) ?>">
          <strong><?= htmlspecialchars($t['judul']) ?></strong>
          <p class="harga">Rp <?= number_format($t['harga'], 0, ',', '.') ?></p>
          <p class="deskripsi">Penjual: <?= htmlspecialchars($t['penjual'] ?? 'Admin') ?></p>
          <p class="deskripsi" style="font-size: 12px; color: #777;">Tgl: <?= date('d M Y', strtotime($t['tanggal'])) ?></p>
          <div class="btn-group">
            <a class="btn-cart" href="../penjual/gambar/<?= htmlspecialchars($t['file_path']) ?>" download>⬇ Unduh</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <a href="dashboard-pembeli.php" class="btn-primary">⬅ Kembali ke Dashboard</a>
</div>

<script>
  function toggleDropdown() {
    const dropdown = document.getElementById("profileDropdown");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
  }

  document.addEventListener("click", function(e) {
    if (!e.target.closest(".dropdown")) {
      document.getElementById("profileDropdown").style.display = "none";
    }
  });
</script>

</body>
</html>
