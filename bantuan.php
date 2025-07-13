<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$kontakQ = mysqli_query($conn, "SELECT * FROM users WHERE role = 'admin' LIMIT 1");
$kontak = mysqli_fetch_assoc($kontakQ);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bantuan - OurLens</title>
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

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .menu a {
            margin: 0 12px;
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .menu a.active,
        .menu a:hover {
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

        .dropdown-content {
            display: none;
            position: absolute;
            right: 30px;
            top: 60px;
            background-color: white;
            min-width: 150px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 6px;
            z-index: 99;
        }

        .dropdown-content a {
            color: #1A237E;
            padding: 10px 14px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f2f2f2;
        }

        .main-content {
            padding: 30px 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .main-content h1 {
            font-size: 26px;
            font-weight: 700;
            color: #1A237E;
        }

        .bantuan-content {
            background-color: #d6e4ff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            max-width: 800px;
            width: 100%;
            color: #4a148c;
            margin-top: 20px;
        }

        .bantuan-content h2 {
            color: #6a1b9a;
            margin-bottom: 20px;
        }

        .faq dt {
            font-weight: bold;
            margin-top: 10px;
        }

        .faq dd {
            margin-left: 20px;
            margin-bottom: 10px;
            color: #444;
        }

        .kontak {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #d9bdf0;
        }

        .kontak h3 {
            margin-bottom: 10px;
            color: #6a1b9a;
        }

        .kontak p {
            margin: 6px 0;
            font-size: 15px;
        }
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
        <a href="kategori.php?k=semua">Kategori</a>
        <a href="bantuan.php" class="active">Bantuan</a>
    </nav>
    <div class="user-section">
        <div class="dropdown">
            <button onclick="toggleDropdown()">👤 <?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?> ▼</button>
            <ul id="profileDropdown" class="dropdown-content">
                <li><a href="profil.php">Profil</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="main-content">
    <h1>Pusat Bantuan</h1>
    <div class="bantuan-content">
        <h2>Bantuan & FAQ</h2>
        <dl class="faq">
            <dt>Bagaimana cara menambahkan foto ke favorit?</dt>
            <dd>Klik ikon ❤️ di atas foto untuk menambahkannya ke daftar favorit Anda.</dd>

            <dt>Bagaimana cara membeli foto?</dt>
            <dd>Tekan ikon 🛒 untuk memasukkan foto ke daftar checkout, lalu lakukan pembayaran.</dd>

            <dt>Apakah saya bisa melihat riwayat transaksi saya?</dt>
            <dd>Ya, buka menu "Transaksi" di atas untuk melihat semua pembelian Anda.</dd>
        </dl>

        <div class="kontak">
            <h3>Kontak Admin</h3>
            <?php if ($kontak): ?>
                <p>📧 Email: <?= htmlspecialchars($kontak['email']) ?></p>
                <p>📞 Telepon: <?= htmlspecialchars($kontak['telepon']) ?></p>
                <p>🏠 Alamat: <?= htmlspecialchars($kontak['alamat']) ?></p>
            <?php else: ?>
                <p>Kontak belum tersedia.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById("profileDropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    document.addEventListener('click', function(e) {
        const ul = document.getElementById("profileDropdown");
        if (!e.target.closest('.dropdown')) {
            ul.style.display = 'none';
        }
    });
</script>

</body>
</html>
