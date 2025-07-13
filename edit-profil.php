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

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];

    mysqli_query($conn, "UPDATE users SET 
        username = '$username', 
        nama_lengkap = '$nama', 
        email = '$email', 
        telepon = '$telepon', 
        alamat = '$alamat' 
        WHERE id = $id_penjual");

    header("Location: profil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil Penjual</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="penjual.css">
    <style>
        body {
            background-color: #F9FAFC;
            font-family: 'Segoe UI', sans-serif;
        }

        .main-content {
            padding: 40px 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .profil-box {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border-left: 6px solid #1A237E;
        }

        .profil-box h2 {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
            color: #1A237E;
        }

        form {
            display: block;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 30px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        label {
            margin-bottom: 6px;
            display: block;
            font-weight: 600;
            color: #1A237E;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        button[type="submit"] {
            margin-top: 30px;
            background: #1A237E;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 50px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        button:hover {
            background-color: #303F9F;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .main-content {
                padding: 20px;
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
        <a href="pesanan.php">Pesanan Masuk</a>
        <a href="profil.php" class="active">Profil</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="profil-box">
        <h2>✏️ Edit Profil Penjual</h2>
        <form method="POST">
            <div class="form-grid">
                <div>
                    <label>Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($profil['username']) ?>" required>
                </div>
                <div>
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($profil['nama_lengkap']) ?>" required>
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($profil['email']) ?>" required>
                </div>
                <div>
                    <label>No. Telepon</label>
                    <input type="text" name="telepon" value="<?= htmlspecialchars($profil['telepon']) ?>" required>
                </div>
                <div class="full-width">
                    <label>Alamat</label>
                    <textarea name="alamat" rows="3" required><?= htmlspecialchars($profil['alamat']) ?></textarea>
                </div>
            </div>
            <button type="submit">💾 Simpan Perubahan</button>
        </form>
    </div>
</div>

</body>
</html>
