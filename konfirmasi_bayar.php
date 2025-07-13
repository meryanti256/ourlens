<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';
$id_pembeli = $_SESSION['id'];
$total = $_SESSION['checkout_total'] ?? 0;
$dataFoto = $_SESSION['checkout_data'] ?? [];
$keranjang_ids = $_SESSION['checkout_keranjang_ids'] ?? [];

if (empty($dataFoto) || empty($keranjang_ids)) {
    header("Location: keranjang.php");
    exit();
}

// RESET kode VA jika daftar keranjang berubah
if (isset($_SESSION['kode_va']) && isset($_SESSION['checkout_keranjang_ids'])) {
    if ($_SESSION['checkout_keranjang_ids'] !== $keranjang_ids) {
        unset($_SESSION['kode_va']);
    }
}

// Bikin kode VA jika belum ada
if (!isset($_SESSION['kode_va'])) {
    $_SESSION['kode_va'] = rand(1000000000, 9999999999);
}
$kode_va = $_SESSION['kode_va'];

// Proses setelah user klik "saya sudah membayar"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['konfirmasi'])) {
    foreach ($dataFoto as $foto) {
        $id_foto = $foto['id_foto'];
        $harga = $foto['harga'];

        $kode_akses = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

        mysqli_query($conn, "
            INSERT INTO transaksi (id_pembeli, id_foto, total, tanggal, status, kode_akses)
            VALUES ($id_pembeli, $id_foto, $harga, NOW(), 'pending', '$kode_akses')
        ");

        mysqli_query($conn, "UPDATE foto SET status = 'terjual' WHERE id = $id_foto");
        mysqli_query($conn, "DELETE FROM favorites WHERE id_pembeli = $id_pembeli AND id_foto = $id_foto");
    }

    $ids = implode(",", array_map('intval', $keranjang_ids));
    mysqli_query($conn, "DELETE FROM keranjang WHERE id IN ($ids) AND id_pembeli = $id_pembeli");

    unset($_SESSION['checkout_data'], $_SESSION['checkout_total'], $_SESSION['checkout_keranjang_ids'], $_SESSION['kode_va']);
    header("Location: transaksi.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Instruksi Pembayaran</title>
  <link rel="stylesheet" href="pembeli.css?v=<?= time() ?>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9f5ff;
      color: #373bb5;
      margin: 0;
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

    .menu a {
      margin: 0 12px;
      color: white;
      text-decoration: none;
      font-weight: 500;
    }

    .menu a.active,
    .menu a:hover {
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
      right: 0;
      background: white;
      color: black;
      list-style: none;
      border-radius: 6px;
      margin-top: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      min-width: 150px;
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
      max-width: 600px;
      margin: 80px auto;
      padding: 20px;
      background-color: white;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .main-content h1 {
      color: #1A237E;
      margin-bottom: 16px;
    }

    .va {
      font-size: 28px;
      font-weight: bold;
      color: #1A237E;
      margin-bottom: 10px;
    }

    .total {
      font-size: 20px;
      font-weight: bold;
      margin: 16px 0;
      color: #000;
    }

    .form-check {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
      font-size: 15px;
    }

    .form-check input {
      margin-right: 10px;
      transform: scale(1.2);
    }

    .btn {
      background-color: #1A237E;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 12px;
      font-size: 16px;
      cursor: pointer;
      font-weight: bold;
    }

    .btn:hover {
      background-color: #000b7d;
    }

    @media screen and (max-width: 600px) {
      .main-content {
        margin: 30px 15px;
        padding: 20px;
      }
    }
  </style>
  <script>
    function toggleDropdown() {
      const dropdown = document.getElementById('profileDropdown');
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    document.addEventListener('click', function(e) {
      if (!e.target.closest('.dropdown')) {
        document.getElementById('profileDropdown').style.display = 'none';
      }
    });
  </script>
</head>
<body>

<div class="topbar">
  <div class="logo">OURLENS</div>
  <nav class="menu">
    <a href="dashboard-pembeli.php">Dashboard</a>
    <a href="favorit.php">Favorit</a>
    <a href="keranjang.php">Keranjang</a>
    <a href="transaksi.php" class="active">Transaksi</a>
    <a href="kategori.php">Kategori</a>
    <a href="bantuan.php">Bantuan</a>
  </nav>
  <div class="user-section">
    <div class="dropdown">
      <button onclick="toggleDropdown()">👤</button>
      <ul id="profileDropdown">
        <li><a href="profil.php">Profil Saya</a></li>
        <li><a href="../logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</div>

<div class="main-content">
  <h1>Instruksi Pembayaran</h1>
  <p>Silakan transfer ke Virtual Account berikut:</p>
  <div class="va"><?= $kode_va ?></div>
  <div class="total">Total Bayar: <span>Rp <?= number_format($total, 0, ',', '.') ?></span></div>

  <form action="" method="post">
    <div class="form-check">
      <input type="checkbox" name="konfirmasi" id="konfirmasi" required>
      <label for="konfirmasi">Saya Sudah Membayar</label>
    </div>
    <button type="submit" class="btn">Kirim Konfirmasi</button>
  </form>
</div>

</body>
</html>
