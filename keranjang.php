<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';
$id_pembeli = $_SESSION['id'];

$query = "
    SELECT k.id, f.judul, f.harga, f.file_path 
    FROM keranjang k
    JOIN foto f ON k.id_foto = f.id
    WHERE k.id_pembeli = $id_pembeli
";
$result = mysqli_query($conn, $query);
$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}

if (isset($_GET['hapus'])) {
    $hapusId = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM keranjang WHERE id = $hapusId AND id_pembeli = $id_pembeli");
    header("Location: keranjang.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Saya</title>
    <link rel="stylesheet" href="pembeli.css?v=<?= time() ?>">
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4ff;
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

        .notif-icon {
        position: relative;
        font-size: 20px;
        color: #FFC107;
        text-decoration: none;
        }

        .notif-count {
        position: absolute;
        top: -8px;
        right: -10px;
        background: red;
        color: white;
        font-size: 10px;
        padding: 2px 5px;
        border-radius: 50%;
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
            padding: 30px 40px;
        }

        h1 {
            color: #1A237E;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        th {
            background-color:#1A237E;
            color: white;
            padding: 14px;
        }

        td {
            padding: 14px;
            text-align: center;
        }

        tr:hover {
            background-color: #f4ecff;
        }

        .total-row {
            background-color:#1A237E;
            color: white;
            font-weight: bold;
            text-align: right;
        }
        .total-row {
         background-color: #1A237E;
         color: white;
         font-weight: bold;
         text-align: right;
        }

        .total-row:hover {
        background-color: #1A237E !important;
        color: white;
        }


        .img-fixed {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .hapus-link {
            color: red;
            font-weight: bold;
            text-decoration: none;
        }

        .hapus-link:hover {
            text-decoration: underline;
        }

        .btn-bayar {
            background-color:#1A237E;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            float: right;
            margin-top: 20px;
        }

        .btn-bayar:hover {
            background-color:#1A237E;
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="logo">OURLENS</div>
    <nav class="menu">
        <a href="dashboard-pembeli.php">Dashboard</a>
        <a href="favorit.php">Favorit</a>
        <a href="keranjang.php" class="active">Keranjang</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="kategori.php">Kategori</a>
        <a href="bantuan.php">Bantuan</a>
    </nav>
    <div class="user-section">
        <div class="dropdown">
            <button onclick="toggleDropdown()">👤 <?= $_SESSION['nama'] ?? 'User' ?> ▼</button>
            <ul id="profileDropdown">
                <li><a href="profil.php">Profil</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="main-content">
    <h2 class="section-title">Keranjang Saya</h2>

    <?php if (empty($items)) : ?>
      <center> <p>Keranjang kamu masih kosong.</p> </center>
    <?php else : ?>
        <form id="checkoutForm" action="payment.php" method="post">
            <table>
                <tr>
                    <th>Pilih</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($items as $item) : ?>
                    <tr>
                        <td><input type="checkbox" name="foto_terpilih[]" value="<?= $item['id'] ?>" class="foto-check" data-harga="<?= $item['harga'] ?>"></td>
                        <td><img src="../penjual/gambar/<?= htmlspecialchars($item['file_path']) ?>" class="img-fixed" alt="<?= htmlspecialchars($item['judul']) ?>"></td>
                        <td><?= htmlspecialchars($item['judul']) ?></td>
                        <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td><a href="keranjang.php?hapus=<?= $item['id'] ?>" class="hapus-link" onclick="return confirm('Hapus item ini?')">Hapus</a></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3">Total:</td>
                    <td colspan="2" id="totalHarga">Rp 0</td>
                </tr>
            </table>
            <button type="submit" class="btn-bayar">🧾 Lanjut ke Pembayaran</button>
        </form>
    <?php endif; ?>
</div>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById("profileDropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.foto-check');
        const totalHarga = document.getElementById('totalHarga');

        function updateTotal() {
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseInt(cb.dataset.harga);
                }
            });
            totalHarga.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));
    });

    window.onclick = function(event) {
        if (!event.target.matches('.dropdown button')) {
            var dropdowns = document.querySelectorAll(".dropdown ul");
            dropdowns.forEach(dd => dd.style.display = "none");
        }
    }
</script>

</body>
</html>
