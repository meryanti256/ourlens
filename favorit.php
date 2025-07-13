<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli' || !isset($_SESSION['id_pembeli'])) {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$id_pembeli = intval($_SESSION['id_pembeli']);


if (isset($_GET['hapus'])) {
    $id_foto = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM favorites WHERE id_pembeli = $id_pembeli AND id_foto = $id_foto");
    header("Location: favorit.php");
    exit();
}

$favoritQ = mysqli_query($conn, "
    SELECT f.id, f.judul, f.deskripsi, f.harga, f.file_path
    FROM favorites fav
    JOIN foto f ON fav.id_foto = f.id
    WHERE fav.id_pembeli = $id_pembeli
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Favorit Saya - OURLENS</title>
    <link rel="stylesheet" href="pembeli.css?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4ff;
        }
        .topbar {
            background-color: #1A237E;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 30px;
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
            border-bottom: 2px solid #FFC107;
        }
        .user-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .dropdown button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }
        #profileDropdown {
            display: none;
            position: absolute;
            right: 0;
            background: white;
            color: black;
            list-style: none;
            border-radius: 6px;
            margin-top: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            min-width: 150px;
            z-index: 999;
        }
        #profileDropdown li a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
        }
        #profileDropdown li a:hover {
            background: #f0f0f0;
        }

        .main-content {
            padding: 40px;
        }
        h1 {
            font-size: 26px;
            color: #1A237E;
            margin-bottom: 20px;
        }
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
        }
        .photo {
            position: relative;
            background: #fff;
            padding: 14px;
            border-radius: 14px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            width: 240px;
            transition: transform 0.3s ease;
            text-align: center;
        }
        .photo:hover {
            transform: scale(1.04);
        }
        .photo img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .hapus-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(255, 0, 0, 0.9);
            color: white;
            border: none;
            font-size: 14px;
            padding: 4px 8px;
            border-radius: 50%;
            cursor: pointer;
        }
        .deskripsi {
            font-size: 13px;
            color: #444;
            margin-top: 6px;
            min-height: 32px;
        }
        .harga {
            font-weight: bold;
            color: #3f51b5;
        }
        .aksi-group {
            margin-top: 10px;
        }
        .btn-beli {
            background-color: #3f51b5;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="logo">OURLENS</div>
    <nav class="menu">
        <a href="dashboard-pembeli.php">Dashboard</a>
        <a href="favorit.php" class="active">Favorit</a>
        <a href="keranjang.php">Keranjang</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="kategori.php?k=semua">Kategori</a>
        <a href="bantuan.php">Bantuan</a>
    </nav>
    <div class="user-section">
        <div class="dropdown">
            <button onclick="toggleDropdown()">👤 <?= htmlspecialchars($_SESSION['nama'] ?? 'Pengguna') ?> ▼</button>
            <ul id="profileDropdown">
                <li><a href="profil.php">Profil</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="main-content">
    <?php if (isset($_SESSION['notif_keranjang'])): ?>
        <script>alert("<?= $_SESSION['notif_keranjang'] ?>");</script>
        <?php unset($_SESSION['notif_keranjang']); ?>
    <?php endif; ?>

    <h1>Favorit Saya</h1>
    <div class="gallery">
        <?php if ($favoritQ && mysqli_num_rows($favoritQ) > 0): ?>
            <?php while ($foto = mysqli_fetch_assoc($favoritQ)): ?>
                <div class="photo">
                    <img src="../penjual/gambar/<?= htmlspecialchars($foto['file_path']) ?>" alt="<?= htmlspecialchars($foto['judul']) ?>">
                    <form method="get">
                        <input type="hidden" name="hapus" value="<?= $foto['id'] ?>">
                        <button type="submit" class="hapus-btn" title="Hapus dari Favorit">&times;</button>
                    </form>
                    <p><strong><?= htmlspecialchars($foto['judul']) ?></strong></p>
                    <p class="harga">Rp <?= number_format($foto['harga'], 0, ',', '.') ?></p>
                    <p class="deskripsi"><?= htmlspecialchars($foto['deskripsi']) ?></p>
                    <div class="aksi-group">
                        <form method="POST" action="tambah-keranjang.php">
                            <input type="hidden" name="id_foto" value="<?= $foto['id'] ?>">
                            <input type="hidden" name="redirect" value="favorit.php">
                            <button type="submit" class="btn-beli">🛒 Tambah ke Keranjang</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada foto favorit.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById("profileDropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    window.onclick = function(event) {
        if (!event.target.matches('.dropdown button')) {
            const dropdowns = document.querySelectorAll("#profileDropdown");
            dropdowns.forEach(dd => dd.style.display = "none");
        }
    }
</script>

</body>
</html>
