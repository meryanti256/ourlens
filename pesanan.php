<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penjual') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$id_penjual = $_SESSION['id'];
$query = "
    SELECT transaksi.*, users.username AS pembeli, foto.judul 
    FROM transaksi
    JOIN users ON transaksi.id_pembeli = users.id
    JOIN foto ON transaksi.id_foto = foto.id
    WHERE foto.id_penjual = '$id_penjual'
    ORDER BY transaksi.tanggal DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Masuk</title>
    <link rel="stylesheet" href="penjual.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 14px 20px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #DCE6F1;
            color: #1A237E;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f5f8fc;
        }

        .inline-form {
            display: inline;
        }

        .btn-link {
            all: unset;
            color: #1A237E;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .status-selesai {
            color: #388E3C;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="logo">OURLENS</div>
    <div class="menu">
        <a href="dashboard-penjual.php">Dashboard</a>
        <a href="unggah-foto.php">Unggah Foto</a>
        <a href="foto-saya.php">Foto Saya</a>
        <a href="pesanan.php" class="active">Pesanan Masuk</a>
        <a href="profil.php">Profil</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="header">
        <h2>📦 Pesanan Masuk</h2>
        <p>Lihat semua pesanan masuk dari pembeli</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Judul Foto</th>
                <th>Pembeli</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['pembeli']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                    <td>
                        <?php if ($row['status'] === 'pending'): ?>
                            <form method="post" action="update_status.php" class="inline-form">
                                <input type="hidden" name="id_transaksi" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn-link">Tandai Selesai</button>
                            </form>
                        <?php else: ?>
                            <span class="status-selesai"><?= htmlspecialchars($row['status']) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
