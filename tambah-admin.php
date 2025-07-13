<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $insert = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'admin')");

    if ($insert) {
        echo "<script>alert('Admin baru berhasil ditambahkan'); window.location.href='kelola-user.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan admin');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Admin</title>
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
        }

        .main-content {
            padding: 40px;
            max-width: 600px;
            margin: auto;
        }

        h1 {
            font-size: 26px;
            color: #1A237E;
            margin-bottom: 30px;
            text-align: center;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        label {
            font-weight: 500;
            display: block;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px 14px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        button[type="submit"] {
            background-color: #1A237E;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0e1b5f;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #1A237E;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
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
        <a href="kelola-user.php">Kelola User</a>
        <a href="admin.php" class="active">Tambah Admin</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="main-content">
    <h1>Tambah Admin Baru</h1>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required placeholder="Contoh: adminbaru">

        <label>Password</label>
        <input type="password" name="password" required placeholder="Minimal 6 karakter">

        <button type="submit">+ Tambah Admin</button>
    </form>

    <a href="kelola-user.php" class="back-link">← Kembali ke Kelola User</a>
</div>

</body>
</html>
