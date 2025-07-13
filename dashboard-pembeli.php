<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';
$id_pembeli = $_SESSION['id']; 
$queryUser = mysqli_query($conn, "SELECT nama_lengkap FROM users WHERE id = $id_pembeli");
$userData = mysqli_fetch_assoc($queryUser);
$nama_pembeli = $userData['nama_lengkap'] ?? 'Pembeli';

$favQ = mysqli_query($conn, "SELECT COUNT(*) AS total FROM favorites WHERE id_pembeli = $id_pembeli");
$total_fav = mysqli_fetch_assoc($favQ)['total'] ?? 0;
$beliQ = mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi WHERE id_pembeli = $id_pembeli");
$total_beli = mysqli_fetch_assoc($beliQ)['total'] ?? 0;
$totalQ = mysqli_query($conn, "SELECT SUM(total) AS total FROM transaksi WHERE id_pembeli = $id_pembeli");
$total_belanja = mysqli_fetch_assoc($totalQ)['total'] ?? 0;
$fotoQ = mysqli_query($conn, "SELECT * FROM foto WHERE status = 'tersedia' ORDER BY uploaded_at DESC LIMIT 4");

$aktivitasQ = mysqli_query($conn, "
    SELECT f.judul, f.file_path, t.tanggal
    FROM transaksi t
    JOIN foto f ON t.id_foto = f.id
    WHERE t.id_pembeli = $id_pembeli
    ORDER BY t.tanggal DESC
    LIMIT 3
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Pembeli</title>
  <link rel="stylesheet" href="pembeli.css?v=<?= time() ?>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    .promo-box {
      background: linear-gradient(90deg, #1A237E, #3949ab);
      color: white;
      padding: 40px;
      border-radius: 0px;
      text-align: center;
      margin: 30px 0 10px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }

    .promo-box h3 { margin: 0; font-size: 22px; font-weight: 600; }
    .promo-box p { margin-top: 8px; font-size: 14px; }

    .aktivitas-title {
      margin: 50px auto 20px;
      font-size: 22px;
      font-weight: 600;
      text-align: center;
      color: #1A237E;
    }
    .aktivitas-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin: 0 auto 60px;
      max-width: 1000px;
      padding: 0 10px;
    }
    .aktivitas-item {
      background: white;
      border-radius: 12px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.08);
      width: 180px;
      text-align: center;
      padding: 12px;
      transition: 0.3s;
    }
    .aktivitas-item:hover { transform: translateY(-3px); }
    .aktivitas-item img {
      width: 100%;
      height: 120px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 8px;
    }
    .aktivitas-item .judul { font-size: 14px; font-weight: 600; color: #1A237E; margin-bottom: 4px; }
    .aktivitas-item .tanggal { font-size: 12px; color: #555; }

   
    .notif-toast {
    position: fixed;
    bottom: 30px;   
    right: 30px;
    background-color: #1A237E;
    color: white;
    padding: 12px 18px;
    border-radius: 8px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    z-index: 9999;
    opacity: 0;
    transform: translateY(10px);
    transition: opacity 0.3s ease, transform 0.3s ease;
    font-size: 14px;
    }
    .notif-toast.show {
      opacity: 1;
      transform: translateY(0);
    }


  .notif-toast.show {
    opacity: 1;
    transform: translateY(0);
        }
      </style>
    </head>
    <body>

<div class="topbar">
  <div class="logo">OURLENS</div>
  <nav class="menu">
    <a href="dashboard-pembeli.php" class="active">Dashboard</a>
    <a href="favorit.php">Favorit</a>
    <a href="keranjang.php">Keranjang</a>
    <a href="transaksi.php">Transaksi</a>
    <a href="kategori.php">Kategori</a>
    <a href="bantuan.php">Bantuan</a>
  </nav>
  <div class="user-section">
    <div class="dropdown">
      <button onclick="toggleDropdown()">👤 <?= htmlspecialchars($nama_pembeli) ?> ▼</button>
      <ul id="profileDropdown" class="dropdown-content">
        <li><a href="profil.php">Profil</a></li>
        <li><a href="../logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</div>

<script>
function toggleDropdown() {
  const dropdown = document.getElementById('profileDropdown');
  dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}
window.addEventListener('click', function(e) {
  const btn = document.querySelector('button[onclick="toggleDropdown()"]');
  const dropdown = document.getElementById('profileDropdown');
  if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
    dropdown.style.display = 'none';
  }
});
</script>

<div class="main-content">

  <div id="notifToast" class="notif-toast"></div>

  <section class="hero-full-bg">
    <div class="hero-overlay">
      <h1>Selamat Datang, <?= htmlspecialchars($nama_pembeli) ?> 👋</h1>
      <p>Temukan foto terbaik dan favoritkan sebelum kehabisan ✨</p>
      <a href="kategori.php" class="cta-btn">Jelajahi Kategori</a>
    </div>
  </section>

  <div class="promo-box">
    <h3>🎉 Promo Hari Ini!</h3>
    <p>Dapatkan cashback 10% untuk setiap pembelian lebih dari Rp50.000</p>
  </div>

  <div class="summary-new">
    <div class="circle-item">
      <div class="circle-icon">❤️</div>
      <div class="circle-label"><?= $total_fav ?><br><span>Favorit</span></div>
    </div>
    <div class="circle-item">
      <div class="circle-icon">🛒</div>
      <div class="circle-label"><?= $total_beli ?><br><span>Pembelian</span></div>
    </div>
    <div class="circle-item">
      <div class="circle-icon">💰</div>
      <div class="circle-label">Rp <?= number_format($total_belanja, 0, ',', '.') ?><br><span>Total</span></div>
    </div>
  </div>

  <h2 class="section-title">Foto Terbaru</h2>
  <div class="foto-container">
    <?php while($row = mysqli_fetch_assoc($fotoQ)): ?>
      <div class="foto-item">
        <img src="../penjual/gambar/<?= htmlspecialchars($row['file_path']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>">
        <strong><?= htmlspecialchars($row['judul']) ?></strong>
        <div class="harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></div>
        <div class="deskripsi"><?= htmlspecialchars($row['deskripsi']) ?></div>
        <div class="btn-group">
          <button class="btn-fav" onclick="tambahFavorit(<?= $row['id'] ?>)">❤</button>
          <button class="btn-cart" onclick="tambahKeranjang(<?= $row['id'] ?>)">🛒</button>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <div class="aktivitas-title">Aktivitas Terbaru</div>
  <div class="aktivitas-grid">
    <?php if (mysqli_num_rows($aktivitasQ) > 0): ?>
      <?php while($a = mysqli_fetch_assoc($aktivitasQ)): ?>
        <div class="aktivitas-item">
          <img src="../penjual/gambar/<?= htmlspecialchars($a['file_path']) ?>" alt="<?= htmlspecialchars($a['judul']) ?>">
          <div class="judul"><?= htmlspecialchars($a['judul']) ?></div>
          <div class="tanggal"><?= date('d M Y', strtotime($a['tanggal'])) ?></div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="aktivitas-item">Belum ada aktivitas pembelian.</div>
    <?php endif; ?>
  </div>

</div>

<script>
function showToast(message) {
  const toast = document.getElementById('notifToast');
  toast.textContent = message;
  toast.classList.add('show');
  setTimeout(() => {
    toast.classList.remove('show');
  }, 3000);
}

function tambahFavorit(id) {
  fetch('tambah-favorit.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'id_foto=' + id
  }).then(() => showToast("❤️ Ditambahkan ke Favorit!"));
}

function tambahKeranjang(id) {
  fetch('tambah-keranjang.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'id_foto=' + id
  }).then(() => showToast("🛒 Ditambahkan ke Keranjang!"));
}
</script>
</body>
</html>
