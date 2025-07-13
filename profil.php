<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penjual') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$id_penjual = $_SESSION['id'];
$query = mysqli_query($conn, "SELECT username, email, nama_lengkap, telepon, alamat FROM users WHERE id = $id_penjual");
$profil = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Penjual</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="penjual.css">
    <style>
        .main-content {
            margin-top: 100px;
            padding: 30px 20px;
        }

        .profil-box {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border-left: 6px solid #1A237E;
        }

        .profil-box h2 {
            font-size: 24px;
            text-align: center;
            color: #1A237E;
            margin-bottom: 28px;
            font-weight: bold;
        }

        .profil-info {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .profil-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 10px;
        }

        .profil-label {
            width: 30%;
            font-weight: bold;
            color: #1A237E;
        }

        .profil-value {
            width: 68%;
            color: #333;
        }

        .edit-btn {
            display: block;
            margin: 30px auto 0;
            background: #1A237E;
            color: white;
            text-align: center;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: 0.3s;
        }

        .edit-btn:hover {
            background-color: #303F9F;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }

            .profil-row {
                flex-direction: column;
            }

            .profil-label,
            .profil-value {
                width: 100%;
            }

            .profil-label {
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="logo">OURLENS</div>
    <nav class="menu">
        <a href="dashboard-penjual.php">Dashboard</a>
        <a href="unggah-foto.php">Unggah Foto</a>
        <a href="foto-saya.php">Foto Saya</a>
        <a href="pesanan.php">Pesanan Masuk</a>
        <a href="profil.php" class="active">Profil</a>
        <a href="../logout.php">Logout</a>
    </nav>
</div>

<div class="main-content">
    <div class="profil-box">
        <h2>👤 Profil Penjual</h2>
        <div class="profil-info">
            <div class="profil-row">
                <div class="profil-label">Username</div>
                <div class="profil-value"><?= htmlspecialchars($profil['username']) ?></div>
            </div>
            <div class="profil-row">
                <div class="profil-label">Nama Lengkap</div>
                <div class="profil-value"><?= htmlspecialchars($profil['nama_lengkap']) ?></div>
            </div>
            <div class="profil-row">
                <div class="profil-label">Email</div>
                <div class="profil-value"><?= htmlspecialchars($profil['email']) ?></div>
            </div>
            <div class="profil-row">
                <div class="profil-label">Telepon</div>
                <div class="profil-value"><?= htmlspecialchars($profil['telepon']) ?></div>
            </div>
            <div class="profil-row">
                <div class="profil-label">Alamat</div>
                <div class="profil-value"><?= htmlspecialchars($profil['alamat']) ?></div>
            </div>
        </div>

        <a href="edit-profil.php" class="edit-btn">✏️ Edit Profil</a>
    </div>
</div>

</body>
</html>
