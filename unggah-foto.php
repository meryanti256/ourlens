<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penjual') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Unggah Foto</title>
    <link rel="stylesheet" href="penjual.css">
    <style>
        .form-container {
            max-width: 700px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .form-container h1 {
            text-align: center;
            color: #1A237E;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1A237E;
            font-size: 15px;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 15px;
            background-color: #f8f9fb;
            color: #1A237E;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 16px;
            background-color: #1A237E;
            color: white;
            border: none;
            font-weight: bold;
            font-size: 16px;
            border-radius: 999px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #303F9F;
        }

        .alert-sukses {
            background-color: #d0f0da;
            color: #1b5e20;
            padding: 15px 20px;
            margin-bottom: 25px;
            border: 1px solid #a5d6a7;
            border-radius: 10px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="logo">OURLENS</div>
    <div class="menu">
        <a href="dashboard-penjual.php">Dashboard</a>
        <a href="unggah-foto.php" class="active">Unggah Foto</a>
        <a href="foto-saya.php">Foto Saya</a>
        <a href="pesanan.php">Pesanan Masuk</a>
        <a href="profil.php">Profil</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="form-container">

        <?php if (isset($_GET['status']) && $_GET['status'] === 'sukses') : ?>
            <div class="alert-sukses">✅ Foto berhasil diunggah!</div>
        <?php endif; ?>

        <center><h2>Unggah Foto</h2></center>
        <form action="proses-unggah.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul">Judul Foto</label>
                <input type="text" id="judul" name="judul" placeholder="Masukkan judul foto" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" placeholder="Tulis deskripsi singkat..." required></textarea>
            </div>

            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="keindahan alam">Keindahan Alam</option>
                    <option value="olahraga">Olahraga</option>
                    <option value="lukisan">Lukisan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="harga">Harga (Rp)</label>
                <input type="number" id="harga" name="harga" placeholder="Masukkan harga" required>
            </div>

            <div class="form-group">
                <label for="foto">Pilih File Foto</label>
                <input type="file" id="foto" name="foto" accept="image/*" required>
            </div>

            <button type="submit">Unggah</button>
        </form>
    </div>
</div>

</body>
</html>
