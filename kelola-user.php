<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM users ORDER BY role, username");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User</title>
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
            overflow-x: auto;
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

        .btn-kembali {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            background-color: #1A237E;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.2s ease;
        }

        .btn-kembali:hover {
            background-color: #0e1b5f;
        }

        .btn-hapus {
            background-color: #D21312;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-hapus:hover {
            background-color: #A30D0D;
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
        <a href="laporan.php">Laporan</a>
        <a href="kelola-user.php" class="active">Kelola User</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="main-content">
    <h1>Kelola User</h1>

    <div class="card-table">
        <table class="tabel-keren">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td>
                            <a href="hapus-user.php?id=<?= $row['id'] ?>" 
                               onclick="return confirm('Yakin ingin menghapus user ini?')" 
                               class="btn-hapus">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="dashboard-admin.php" class="btn-kembali">← Kembali ke Dashboard</a>
    </div>
</div>

</body>
</html>
