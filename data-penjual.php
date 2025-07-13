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

$query = "SELECT * FROM users WHERE role = 'penjual'";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Penjual</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        }

        .topbar .menu a.active,
        .topbar .menu a:hover {
            border-bottom: 2px solid #F9D949;
        }

        .main-content {
            padding: 30px;
        }

        h1 {
            color: #1A237E;
            font-size: 26px;
            margin-bottom: 25px;
        }

        .card-table {
            background: white;
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
            padding: 24px;
            overflow-x: auto;
        }

        table.tabel-keren {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table.tabel-keren thead {
            background-color:#1A237E;
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

        .btn-ungu {
            background-color: #4B0082;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            margin-right: 6px;
            font-size: 14px;
            display: inline-block;
        }

        .btn-ungu:hover {
            background-color: #5e1ca6;
        }

        .btn-merah {
            background-color: #B22222;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
        }

        .btn-merah:hover {
            background-color: #d93434;
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="logo">OURLENS</div>
    <div class="menu">
        <a href="dashboard-admin.php">Dashboard</a>
        <a href="data-penjual.php" class="active">Data Penjual</a>
        <a href="data-pembeli.php">Data Pembeli</a>
        <a href="data-produk.php">Produk</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php">Laporan</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="main-content">
    <h1>Data Penjual</h1>
    <div class="card-table">
        <table class="tabel-keren">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <a href="edit-penjual.php?id=<?= $row['id'] ?>" class="btn-ungu"><i class="fas fa-edit"></i> Edit</a>
                            <a href="hapus-penjual.php?id=<?= $row['id'] ?>" class="btn-merah" onclick="return confirm('Yakin ingin menghapus penjual ini?')"><i class="fas fa-trash"></i> Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
