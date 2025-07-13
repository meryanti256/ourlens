<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

$nama_pembeli = $_SESSION['nama_lengkap'] ?? 'Pembeli';

$dataFoto = [
    'Gunung Merbabu' => ['gambar' => 'gunung.jpg', 'harga' => 20000, 'penjual' => 'Admin'],
    'Senja di Gunung' => ['gambar' => 'senja.jpg', 'harga' => 18000, 'penjual' => 'Admin'],
    'Hutan Tropis' => ['gambar' => 'hutan.jpg', 'harga' => 22000, 'penjual' => 'Admin'],
    'lari' => ['gambar' => 'lari.jpg', 'harga' => 25000, 'penjual' => 'Admin'],
    'larilari' => ['gambar' => 'larilari.jpg', 'harga' => 17000, 'penjual' => 'Admin'],
    'yoga' => ['gambar' => 'yoga.jpg', 'harga' => 24000, 'penjual' => 'Admin'],
    'biru' => ['gambar' => 'biru.jpg', 'harga' => 20000, 'penjual' => 'Admin'],
    'Sawah' => ['gambar' => 'sawah.jpg', 'harga' => 18000, 'penjual' => 'Admin'],
    'omo' => ['gambar' => 'omo.jpg', 'harga' => 22000, 'penjual' => 'Admin'],
];

// Hapus item
if (isset($_GET['hapus'])) {
    $hapus = $_GET['hapus'];
    $_SESSION['checkout'] = array_filter($_SESSION['checkout'] ?? [], fn($item) => $item !== $hapus);
    header("Location: checkout.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="pembeli.css?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f5ff;
            margin: 0;
            padding: 0;
        }

        .main-content {
            padding: 40px;
            max-width: 1100px;
            margin: auto;
        }

        h1 {
            text-align: center;
            color: #1A237E;
        }

        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }

        .photo {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
            width: 260px;
        }

        .photo img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        .photo p {
            margin: 8px 0;
        }

        .photo a {
            display: inline-block;
            margin-top: 10px;
            color: red;
        }

        form button {
            background-color: #373bb5;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }

        form button:hover {
            background-color: #2b2f95;
        }

        h3 {
            text-align: center;
            color: #2C3E50;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="topbar">
  <div class="logo">OURLENS</div>
  <nav class="menu">
    <a href="dashboard-pembeli.php" class="active">Dashboard</a>
    <a href="favorit.php">Favorit</a>
    <a href="keranjang.php">Keranjang</a>
    <a href="transaksi.php">Transaksi</a>
    <a href="kategori.php">Kategori</a>
    <a href="bantuan.php">Bantuan</a>
  </nav>
  <div class="user-section">
    <div class="dropdown">
      <button onclick="toggleDropdown()">👤 <?= htmlspecialchars($nama_pembeli) ?> ▼</button>
      <ul id="profileDropdown">
        <li><a href="profil.php">Profil</a></li>
        <li><a href="../logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</div>

<div class="main-content">
    <h1>Checkout Saya</h1>
    <div class="gallery">
        <?php
        $total = 0;
        $checkout = $_SESSION['checkout'] ?? [];

        if (!empty($checkout)) {
            foreach ($checkout as $nama) {
                if (!isset($dataFoto[$nama])) continue;
                $foto = $dataFoto[$nama];
                $total += $foto['harga'];
                echo "
                <div class='photo'>
                    <img src='gambar/{$foto['gambar']}' alt='{$nama}'>
                    <p><strong>$nama</strong></p>
                    <p>Harga: Rp" . number_format($foto['harga'], 0, ',', '.') . "</p>
                    <p>Penjual: {$foto['penjual']}</p>
                    <a href='?hapus=" . urlencode($nama) . "' title='Hapus'>
                        <i class='fas fa-trash'></i> Hapus
                    </a>
                </div>";
            }

            echo "<h3>Total: Rp" . number_format($total, 0, ',', '.') . "</h3>
                  <form action='payment.php' method='post'>
                      <input type='hidden' name='total' value='$total'>
                      <button type='submit'>Lanjut ke Pembayaran</button>
                  </form>";
        } else {
            echo "<p>Keranjang kosong.</p>";
        }
        ?>
    </div>
</div>

<script>
function toggleDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}
window.addEventListener('click', function(e) {
    const btn = document.querySelector('button[onclick="toggleDropdown()"]');
    const dropdown = document.getElementById('profileDropdown');
    if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});
</script>

</body>
</html>
