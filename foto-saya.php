<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penjual') {
    header("Location: ../login.php");
    exit();
}
include '../koneksi.php';

$id_penjual = $_SESSION['id'];
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

if ($kategori && $kategori !== 'semua') {
    $query = "SELECT * FROM foto WHERE id_penjual = '$id_penjual' AND kategori = '$kategori' ORDER BY created_at DESC";
} else {
    $query = "SELECT * FROM foto WHERE id_penjual = '$id_penjual' ORDER BY created_at DESC";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Foto Saya</title>
    <link rel="stylesheet" href="penjual.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #F9FAFC;
            color: #1A237E;
            font-size: 15px;
        }

        .main-content {
            padding: 30px 40px;
        }

        .header h2 {
            font-size: 24px;
            margin-bottom: 6px;
            color: #1A237E;
        }

        .header p {
            color: #444;
            margin-bottom: 30px;
        }

        .filter-buttons {
            margin-bottom: 25px;
        }

        .filter-buttons a {
            text-decoration: none;
            background-color: #fff;
            border: 2px solid #1A237E;
            color: #1A237E;
            padding: 10px 20px;
            border-radius: 999px;
            margin-right: 10px;
            font-weight: 600;
            display: inline-block;
            transition: 0.3s ease;
        }

        .filter-buttons a:hover,
        .filter-buttons .active {
            background-color: #1A237E;
            color: white;
        }

        .notif {
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 500;
            max-width: 700px;
        }

        .success {
            background-color: #d0f0da;
            color: #1b5e20;
            border: 1px solid #a4d4aa;
        }

        .error {
            background-color: #ffe4e4;
            color: #d21c1c;
            border: 1px solid #f5a5a5;
        }

        .foto-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .foto-item {
            background-color: white;
            border-radius: 12px;
            padding: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform 0.2s ease;
            position: relative;
        }

        .foto-item:hover {
            transform: translateY(-5px);
        }

        .foto-item img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 10px;
            background: #eee;
            margin-bottom: 10px;
        }

        .foto-item p {
            margin: 4px 0;
            color: #1A237E;
        }

        .foto-item p:nth-child(2) {
            font-weight: bold;
            font-size: 16px;
        }

        .foto-item p:nth-child(3) {
            color: #303F9F;
            font-weight: bold;
        }

        .foto-aksi {
            margin-top: 8px;
            display: flex;
            justify-content: center;
            gap: 6px;
        }

        .btn-edit,
        .btn-hapus {
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            transition: all 0.2s ease;
        }

        .btn-edit {
            background-color: #E8EAF6;
            color: #1A237E;
        }

        .btn-edit:hover {
            background-color: #C5CAE9;
        }

        .btn-hapus {
            background-color: #FFEBEE;
            color: #C62828;
        }

        .btn-hapus:hover {
            background-color: #EF9A9A;
        }

        .terjual-label {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #4CAF50;
            color: white;
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 999px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
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
        <a href="foto-saya.php" class="active">Foto Saya</a>
        <a href="pesanan.php">Pesanan Masuk</a>
        <a href="profil.php">Profil</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="header">
        <h2>📸 Foto Saya</h2>
        <p>Kelola semua foto hasil karya kamu di sini</p>
    </div>

    <div class="filter-buttons">
        <a href="foto-saya.php?kategori=semua" class="<?= $kategori == '' || $kategori == 'semua' ? 'active' : '' ?>">Semua</a>
        <a href="foto-saya.php?kategori=keindahan alam" class="<?= $kategori == 'keindahan alam' ? 'active' : '' ?>">Keindahan Alam</a>
        <a href="foto-saya.php?kategori=olahraga" class="<?= $kategori == 'olahraga' ? 'active' : '' ?>">Olahraga</a>
        <a href="foto-saya.php?kategori=lukisan" class="<?= $kategori == 'lukisan' ? 'active' : '' ?>">Lukisan</a>
    </div>

    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'berhasil') {
            echo "<div class='notif success'>✅ Foto berhasil diunggah.</div>";
        } elseif ($_GET['status'] == 'gagal_upload') {
            echo "<div class='notif error'>❌ Gagal mengunggah file ke folder.</div>";
        } elseif ($_GET['status'] == 'gagal_db') {
            echo "<div class='notif error'>❌ Gagal menyimpan data ke database.</div>";
        } elseif ($_GET['status'] == 'hapus') {
            echo "<div class='notif success'>🗑️ Foto berhasil dihapus.</div>";
        } elseif ($_GET['status'] == 'gagal_dibeli') {
            echo "<div class='notif error'>❌ Foto sudah dibeli dan tidak bisa dihapus.</div>";
        }
    }
    ?>

    <div class="foto-grid">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($foto = mysqli_fetch_assoc($result)) : ?>
                <?php
                $id_foto = $foto['id'];
                $cek = mysqli_query($conn, "SELECT id FROM transaksi WHERE id_foto = '$id_foto' LIMIT 1");
                $sudah_terjual = mysqli_num_rows($cek) > 0;
                ?>
                <div class="foto-item">
                    <?php if ($sudah_terjual): ?>
                        <div class="terjual-label">✔ Terjual</div>
                    <?php endif; ?>
                    <img src="gambar/<?= htmlspecialchars($foto['file_path']) ?>" alt="<?= htmlspecialchars($foto['judul']) ?>" onerror="this.src='gambar/default.png';">
                    <p><?= htmlspecialchars($foto['judul']) ?></p>
                    <p>Rp <?= number_format($foto['harga'], 0, ',', '.') ?></p>
                    <?php if (!$sudah_terjual): ?>
                        <div class="foto-aksi">
                            <a href="edit-foto.php?id=<?= $foto['id'] ?>" class="btn-edit">✏️ Edit</a>
                            <a href="hapus-foto.php?id=<?= $foto['id'] ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus foto ini?')">🗑️ Hapus</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Belum ada foto yang diunggah.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
