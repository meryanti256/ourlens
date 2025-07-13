<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$id = $_SESSION['id_pembeli'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$profil = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $update = mysqli_query($conn, "UPDATE users SET 
        username = '$username',
        nama_lengkap = '$nama',
        email = '$email',
        telepon = '$telepon',
        alamat = '$alamat'
        WHERE id = $id
    ");

    if ($update) {
        $_SESSION['username'] = $username;
        header("Location: profil.php");
        exit();
    } else {
        echo "Gagal memperbarui profil: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Profil</title>
  <link rel="stylesheet" href="pembeli.css?v=<?= time() ?>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    .form-container {
        background-color: #fff;
        padding: 30px 40px;
        border-radius: 16px;
        max-width: 700px;
        margin: 60px auto;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    }

    .form-container h2 {
        text-align: center;
        color: #1A237E;
        margin-bottom: 30px;
    }

    form label {
        font-weight: 600;
        color: #444;
    }

    form input, textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 18px;
        border-radius: 10px;
        border: 1px solid #ccc;
        font-size: 15px;
    }

    button {
        display: block;
        width: 100%;
        padding: 14px;
        background-color: #4C1D95;
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #6A1BC9;
    }
  </style>
</head>
<body>

<!-- TOPBAR sama seperti dashboard -->
<div class="topbar">
  <div class="logo">OURLENS</div>
  <nav class="menu">
    <a href="dashboard-pembeli.php">Dashboard</a>
    <a href="favorit.php">Favorit</a>
    <a href="keranjang.php">Keranjang</a>
    <a href="transaksi.php">Transaksi</a>
    <a href="kategori.php">Kategori</a>
    <a href="bantuan.php">Bantuan</a>
  </nav>
  <div class="user-section">
    <div class="dropdown">
      <button onclick="toggleDropdown()">👤 <?= htmlspecialchars($profil['nama_lengkap']) ?> ▼</button>
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

<div class="form-container">
    <h2>✏️ Edit Profil</h2>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($profil['username']) ?>" required>

        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($profil['nama_lengkap']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($profil['email']) ?>" required>

        <label>No. Telepon</label>
        <input type="text" name="telepon" value="<?= htmlspecialchars($profil['telepon']) ?>" required>

        <label>Alamat</label>
        <textarea name="alamat" rows="4" required><?= htmlspecialchars($profil['alamat']) ?></textarea>

        <button type="submit">💾 Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
