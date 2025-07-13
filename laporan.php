<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$koneksi = mysqli_connect("localhost", "root", "", "ourlens_db");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// LAPORAN PENJUAL
$penjualQuery = mysqli_query($koneksi, "
    SELECT u.username, COUNT(f.id) AS total_terjual, COALESCE(SUM(f.harga),0) AS total_pendapatan
    FROM users u
    LEFT JOIN foto f ON u.id = f.id_penjual AND f.status = 'terjual'
    WHERE u.role = 'penjual'
    GROUP BY u.id
");

// LAPORAN PEMBELI
$pembeliQuery = mysqli_query($koneksi, "
    SELECT u.username, COUNT(t.id) AS total_transaksi, COALESCE(SUM(t.total),0) AS total_pembelian
    FROM users u
    LEFT JOIN transaksi t ON u.id = t.id_pembeli
    WHERE u.role = 'pembeli'
    GROUP BY u.id
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan</title>
    <link rel="stylesheet" href="admin.css?v=<?=time()?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F9FAFC;
            margin: 0;
            color: #1A237E;
        }

        .topbar {
            background-color: #1A237E;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
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
            transition: 0.3s;
        }

        .topbar .menu a.active,
        .topbar .menu a:hover {
            border-bottom: 2px solid #F9D949;
        }

        .main-content {
            padding: 30px;
        }

        h1 {
            font-size: 26px;
            margin-bottom: 25px;
            color: #1A237E;
        }

        .card-table {
            background: white;
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
            padding: 24px;
            margin-bottom: 40px;
            overflow-x: auto;
        }

        .card-table h2 {
            font-size: 18px;
            margin-bottom: 20px;
            color: #1A237E;
        }

        table.tabel-keren {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table.tabel-keren thead {
            background-color: #1A237E;
            color: white;
        }

        table.tabel-keren th,
        table.tabel-keren td {
            padding: 12px 14px;
            text-align: left;
        }

        table.tabel-keren tbody tr:nth-child(even) {
            background-color: #f1f4f9;
        }

        table.tabel-keren tbody tr:hover {
            background-color: #e6eef9;
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="logo">OURLENS</div>
    <div class="menu">
        <a href="dashboard-admin.php">Dashboard</a>
        <a href="data-penjual.php">Data Penjual</a>
        <a href="data-pembeli.php">Data Pembeli</a>
        <a href="data-produk.php">Produk</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php" class="active">Laporan</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="main-content">
    <h1>Laporan</h1>

    <div class="card-table">
        <h2>📦 Laporan Penjualan per Penjual</h2>
        <table class="tabel-keren">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Penjual</th>
                    <th>Jumlah Foto Terjual</th>
                    <th>Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($penjualQuery)) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $row['total_terjual'] ?></td>
                    <td>Rp<?= number_format($row['total_pendapatan'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="card-table">
        <h2>🛒 Laporan Pembelian per Pembeli</h2>
        <table class="tabel-keren">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pembeli</th>
                    <th>Jumlah Transaksi</th>
                    <th>Total Pembelian</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($pembeliQuery)) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $row['total_transaksi'] ?></td>
                    <td>Rp<?= number_format($row['total_pembelian'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
