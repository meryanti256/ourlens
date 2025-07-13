<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$id_pembeli = $_SESSION['id'];
$fotoDipilih = $_POST['foto_terpilih'] ?? [];

if (empty($fotoDipilih)) {
    echo "Tidak ada foto yang dipilih. <a href='keranjang.php'>Kembali ke keranjang</a>";
    exit();
}

$total = 0;
$dataFoto = [];
$keranjang_ids = [];

foreach ($fotoDipilih as $id_keranjang) {
    $id_keranjang = intval($id_keranjang);
    $q = mysqli_query($conn, "SELECT k.id, k.id_foto, f.judul, f.harga, f.file_path 
        FROM keranjang k JOIN foto f ON k.id_foto = f.id 
        WHERE k.id = $id_keranjang AND k.id_pembeli = $id_pembeli");

    if ($row = mysqli_fetch_assoc($q)) {
        $dataFoto[] = $row;
        $keranjang_ids[] = $id_keranjang;
        $total += $row['harga'];
    }
}

$_SESSION['checkout_data'] = $dataFoto;
$_SESSION['checkout_total'] = $total;
$_SESSION['checkout_keranjang_ids'] = $keranjang_ids;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pembayaran - OurLens</title>
    <link rel="stylesheet" href="pembeli.css?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7ff;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1A237E;
            padding: 14px 30px;
            color: white;
        }

        .topbar .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .topbar .menu a {
            margin: 0 14px;
            color: white;
            text-decoration: none;
            padding-bottom: 3px;
        }

        .topbar .menu a:hover {
            border-bottom: 2px solid #ffc107;
        }

        .topbar .user {
            font-weight: bold;
        }

        .main-content {
            padding: 30px 20px;
        }

        h1 {
            color: #1A237E;
            font-size: 26px;
            margin-bottom: 25px;
            text-align: center;
        }

        .foto-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 18px;
            margin-bottom: 30px;
            max-width: 1000px;
            margin-inline: auto;
        }

            .foto-item {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.06);
        padding: 10px;
        text-align: center;
        width: 160px;
    }

    .foto-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 4px;
        display: block;
    }

    .judul {
        font-weight: 600;
        color: #1A237E;
        font-size: 13px;
        margin: 0;
        line-height: 1.1;
    }

    .harga {
        color: #4c1d95;
        font-weight: 600;
        font-size: 13px;
        margin: 2px 0 0 0; /* nyaris gak ada jarak */
        line-height: 1.1;
    }



        .total-box {
            margin: 30px auto;
            background: #E3E8FF;
            border-left: 6px solid #1A237E;
            padding: 16px 20px;
            font-size: 17px;
            font-weight: bold;
            color: #1A237E;
            border-radius: 8px;
            text-align: right;
            max-width: 1000px;
        }

        .btn-lanjut {
            display: block;
            margin: 0 auto 40px;
            padding: 12px 24px;
            font-size: 15px;
            background-color: #1A237E;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-lanjut:hover {
            background-color: #0f174d;
        }

        @media (max-width: 600px) {
            .foto-item {
                width: 100%;
            }

            .topbar {
                flex-direction: column;
                gap: 10px;
            }

            .topbar .menu {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 12px;
            }
        }

        .foto-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
            max-width: 1000px;
            margin-inline: auto;
            }

            .foto-item-konfirmasi {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.06);
            padding: 14px 12px;
            text-align: center;
            transition: 0.3s;
            width: 180px;
            }

            .foto-item-konfirmasi:hover {
            transform: scale(1.02);
            }

            .foto-item-konfirmasi img {
            width: 100%;
            height: 130px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 6px;
            }

            .judul-konfirmasi {
            font-weight: 600;
            color: #1A237E;
            font-size: 14px;
            margin-bottom: 2px;
            }

            .harga-konfirmasi {
            color: #4c1d95;
            font-weight: 600;
            font-size: 13px;
            margin: 0;
            }

                </style>
</head>
<body>

<div class="topbar">
  <div class="logo">OURLENS</div>
  <div class="menu">
    <a href="dashboard-pembeli.php">Dashboard</a>
    <a href="favorit.php">Favorit</a>
    <a href="keranjang.php">Keranjang</a>
    <a href="transaksi.php">Transaksi</a>
    <a href="kategori.php">Kategori</a>
    <a href="bantuan.php">Bantuan</a>
  </div>
  <div class="user">👤 <?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?></div>
</div>

<div class="main-content">
  <h1>Konfirmasi Pembayaran</h1>

  <div class="foto-list">
  <?php foreach ($dataFoto as $foto): ?>
    <div class="foto-item-konfirmasi">
      <img src="../penjual/gambar/<?= htmlspecialchars($foto['file_path']) ?>" alt="<?= htmlspecialchars($foto['judul']) ?>">
      <div class="judul-konfirmasi"><?= htmlspecialchars($foto['judul']) ?></div>
      <div class="harga-konfirmasi">Rp <?= number_format($foto['harga'], 0, ',', '.') ?></div>
    </div>
  <?php endforeach; ?>
</div>


  <div class="total-box">
    Total Bayar: Rp <?= number_format($total, 0, ',', '.') ?>
  </div>

  <form action="konfirmasi_bayar.php" method="post">
    <button type="submit" class="btn-lanjut">✅ Lanjut ke Pembayaran</button>
  </form>
</div>

</body>
</html>
