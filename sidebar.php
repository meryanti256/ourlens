<?php
$current_page = basename($_SERVER['PHP_SELF']);
$is_pengguna_open = in_array($current_page, ['data-penjual.php', 'data-pembeli.php']);
?>

<div class="sidebar">
  <h2>OURLENS</h2>

  <a href="dashboard-admin.php" class="<?= $current_page == 'dashboard-admin.php' ? 'active' : '' ?>">Dashboard</a>

  <!-- Data Pengguna Dropdown -->
  <div class="dropdown">
    <div class="dropdown-toggle" onclick="toggleMenu('pengguna')">
      Data Pengguna <span class="arrow <?= $is_pengguna_open ? 'rotate' : '' ?>" id="arrow-pengguna">▾</span>
    </div>
    <div class="dropdown-menu" id="menu-pengguna" style="display: <?= $is_pengguna_open ? 'block' : 'none' ?>;">
      <a href="data-penjual.php" class="submenu <?= $current_page == 'data-penjual.php' ? 'active-sub' : '' ?>">Data Penjual</a>
      <a href="data-pembeli.php" class="submenu <?= $current_page == 'data-pembeli.php' ? 'active-sub' : '' ?>">Data Pembeli</a>
    </div>
  </div>

  <a href="data-produk.php" class="<?= $current_page == 'data-produk.php' ? 'active' : '' ?>">Data Produk / Foto</a>
  <a href="kategori.php" class="<?= $current_page == 'kategori.php' ? 'active' : '' ?>">Kategori</a>
  <a href="transaksi.php" class="<?= $current_page == 'transaksi.php' ? 'active' : '' ?>">Transaksi</a>
  <a href="laporan.php" class="<?= $current_page == 'laporan.php' ? 'active' : '' ?>">Laporan</a>
  <a href="logout.php">Logout</a>
</div>

<style>
.sidebar {
  width: 250px;
  background-color: #e7cedb;
  padding: 30px 20px;
  font-family: 'Segoe UI', sans-serif;
}

.sidebar h2 {
  color: #5d0680;
  font-style: italic;
  font-weight: bold;
  margin-bottom: 25px;
  font-size: 22px;
}

.sidebar a,
.dropdown-toggle {
  display: block;
  padding: 10px 18px;
  color: #000;
  text-decoration: none;
  margin-bottom: 6px;
  border-radius: 10px;
  font-size: 16px;
  transition: 0.2s;
}

.sidebar a:hover,
.dropdown-toggle:hover {
  background-color: #dbc4e7;
}

.sidebar a.active {
  background-color: #c9a8ec;
  color: white;
  font-weight: bold;
}

.dropdown-toggle {
  cursor: pointer;
}

.arrow {
  float: right;
  transition: transform 0.3s ease;
}

.rotate {
  transform: rotate(180deg);
}

.dropdown-menu {
  margin-left: 10px;
  margin-top: 4px;
}

.submenu {
  background-color: #dcc8f7;
  padding: 8px 16px;
  margin: 4px 0;
  border-radius: 10px;
  display: block;
  color: #2c003e;
  font-size: 15px;
  transition: background 0.2s ease;
}

.submenu:hover,
.active-sub {
  background-color: #c9a4f3;
  font-weight: bold;
}
</style>

<script>
function toggleMenu(id) {
  const menu = document.getElementById('menu-' + id);
  const arrow = document.getElementById('arrow-' + id);
  if (menu.style.display === "block") {
    menu.style.display = "none";
    arrow.classList.remove("rotate");
  } else {
    menu.style.display = "block";
    arrow.classList.add("rotate");
  }
}
</script>
