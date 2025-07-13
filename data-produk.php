<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$query = "
    SELECT 
        f.*, 
        u_penjual.username AS penjual,
        u_pembeli.username AS pembeli,
        t.tanggal AS tanggal_transaksi
    FROM foto f
    LEFT JOIN users u_penjual ON f.id_penjual = u_penjual.id
    LEFT JOIN transaksi t ON f.id = t.id_foto
    LEFT JOIN users u_pembeli ON t.id_pembeli = u_pembeli.id
    ORDER BY f.id DESC
";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Produk / Foto</title>
    <link rel="stylesheet" href="admin.css?v=<?= time() ?>">
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
            color: white;
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

        .btn-merah {
            background-color: #B22222;
            color: white;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-merah:hover {
            background-color: #d93434;
        }

        .status-tersedia {
            color: green;
            font-weight: bold;
        }

        .status-terjual {
            color: red;
            font-weight: bold;
        }

        img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
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
        <a href="data-produk.php" class="active">Produk</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php">Laporan</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="main-content">
    <h1>Data Produk / Foto</h1>
    <div class="card-table">
        <table class="tabel-keren">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Penjual</th>
                    <th>Pembeli</th>
                    <th>Tgl Upload</th>
                    <th>Tgl Transaksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <?php
                            $imgPath = '../penjual/gambar/' . htmlspecialchars($row['file_path']);
                            echo (!empty($row['file_path']) && file_exists($imgPath))
                                ? "<img src='$imgPath' alt='Foto'>"
                                : "<span style='color:gray'>(Tidak ada)</span>";
                        ?>
                    </td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td>
                        <?= $row['status'] === 'terjual'
                            ? '<span class="status-terjual">Terjual</span>'
                            : '<span class="status-tersedia">Tersedia</span>' ?>
                    </td>
                    <td><?= htmlspecialchars($row['penjual']) ?></td>
                    <td><?= $row['pembeli'] ? htmlspecialchars($row['pembeli']) : '-' ?></td>
                    <td>
                        <?= isset($row['uploaded_at']) && $row['uploaded_at']
                            ? date('d-m-Y H:i', strtotime($row['uploaded_at']))
                            : '-' ?>
                    </td>
                    <td>
                        <?= isset($row['tanggal_transaksi']) && $row['tanggal_transaksi']
                            ? date('d-m-Y H:i', strtotime($row['tanggal_transaksi']))
                            : '-' ?>
                    </td>
                    <td>
                        <a href="hapus-foto.php?id=<?= $row['id'] ?>" 
                           onclick="return confirm('Yakin ingin menghapus foto ini?')" 
                           class="btn-merah">🗑 Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
