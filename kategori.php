<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$id_pembeli = $_SESSION['id_pembeli'] ?? 0;
$kategori = $_GET['k'] ?? 'semua';

$kategoriList = ['semua', 'keindahan alam', 'olahraga', 'lukisan'];

if (!in_array($kategori, $kategoriList)) {
    $kategori = 'semua';
}

if ($kategori === 'semua') {
    $fotoQ = mysqli_query($conn, "SELECT * FROM foto WHERE status = 'tersedia' ORDER BY uploaded_at DESC");
} else {
    $kategori_escaped = mysqli_real_escape_string($conn, $kategori);
    $fotoQ = mysqli_query($conn, "SELECT * FROM foto WHERE status = 'tersedia' AND kategori = '$kategori_escaped' ORDER BY uploaded_at DESC");
}

function formatKategori($k) {
    return ucwords(str_replace(['-', '_'], ' ', $k));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kategori - OurLens</title>
    <link rel="stylesheet" href="pembeli.css?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f3f6ff;
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
            right: 30px;
            top: 60px;
            background: white;
            color: black;
            list-style: none;
            border-radius: 6px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            min-width: 150px;
            z-index: 999;
        }
        #profileDropdown a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
        }
        #profileDropdown a:hover {
            background: #f0f0f0;
        }

        .main-content {
            padding: 30px;
        }

        h2 {
            font-size: 26px;
            font-weight: 700;
            color: #1A237E;
            margin-bottom: 20px;
        }

        .kategori-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .kategori-buttons .kategori-btn {
            padding: 8px 20px;
            border-radius: 30px;
            background-color: #d6e4ff;
            font-size: 16px;
            font-weight: 600;
            color: #1A237E;
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.04);
            transition: 0.2s ease;
        }
        .kategori-buttons .kategori-btn.active {
            background-color: #1A237E;
            color: #fff;
        }

        .foto-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 24px;
            margin-top: 20px;
        }

        .foto-item {
            width: 240px;
            background: #ffffff;
            padding: 14px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .foto-item:hover {
            transform: scale(1.04);
        }

        .foto-item img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .foto-item strong {
            display: block;
            margin-bottom: 6px;
            font-size: 15px;
            color: #1a237e;
        }

        .foto-item .harga {
            color: #1A237E;
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .foto-item .deskripsi {
            font-size: 12px;
            color: #333;
            margin-bottom: 10px;
            min-height: 30px;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 6px;
        }

        .btn-fav, .btn-cart {
            font-size: 13px;
            padding: 6px 8px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            color: white;
            flex: 1;
        }

        .btn-fav { background-color: #ff6eb0; }
        .btn-cart { background-color: #3f51b5; }
    </style>
</head>
<body>

<div class="topbar">
    <div class="logo">OURLENS</div>
    <nav class="menu">
        <a href="dashboard-pembeli.php">Dashboard</a>
        <a href="favorit.php">Favorit</a>
        <a href="keranjang.php">Keranjang</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="kategori.php?k=semua" class="active">Kategori</a>
        <a href="bantuan.php">Bantuan</a>
    </nav>
    <div class="user-section">
        <div class="dropdown">
            <button onclick="toggleDropdown()">👤 <?= htmlspecialchars($_SESSION['nama'] ?? 'Pengguna') ?> ▼</button>
            <div class="dropdown-content" id="profileDropdown">
                <a href="profil.php">Profil</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="main-content">
    <h2>Kategori Foto</h2>

    <div class="kategori-buttons">
        <?php foreach ($kategoriList as $k): ?>
            <a href="kategori.php?k=<?= $k ?>" class="kategori-btn <?= ($kategori == $k) ? 'active' : '' ?>">
                <?= formatKategori($k) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="foto-container">
        <?php if (mysqli_num_rows($fotoQ) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($fotoQ)): ?>
                <div class="foto-item">
                    <img src="../penjual/gambar/<?= htmlspecialchars($row['file_path']) ?>" 
                         alt="<?= htmlspecialchars($row['judul']) ?>" 
                         onerror="this.src='../assets/img/placeholder.png'">
                    <strong><?= htmlspecialchars($row['judul']) ?></strong>
                    <div class="harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></div>
                    <div class="deskripsi"><?= htmlspecialchars($row['deskripsi']) ?></div>
                    <div class="btn-group">
                        <form action="tambah-favorit.php" method="POST" style="margin:0;">
                            <input type="hidden" name="id_foto" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn-fav">❤️</button>
                        </form>
                        <form action="tambah-keranjang.php" method="POST" style="margin:0;">
                            <input type="hidden" name="id_foto" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn-cart">🛒</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada foto pada kategori ini.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById("profileDropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.getElementById("profileDropdown").style.display = "none";
        }
    });
</script>

</body>
</html>
