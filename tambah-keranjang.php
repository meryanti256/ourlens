<?php
session_start();
include '../koneksi.php';

$id_pembeli = $_SESSION['id_pembeli'] ?? 0;
$id_foto = $_POST['id_foto'] ?? 0;

$cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_pembeli = $id_pembeli AND id_foto = $id_foto");

if (mysqli_num_rows($cek) == 0) {
    mysqli_query($conn, "INSERT INTO keranjang (id_pembeli, id_foto) VALUES ($id_pembeli, $id_foto)");
    $_SESSION['notif_keranjang'] = "Foto berhasil ditambahkan ke keranjang!";
} else {
    $_SESSION['notif_keranjang'] = "Foto sudah ada di keranjang!";
}

$previous = $_SERVER['HTTP_REFERER'] ?? 'dashboard-pembeli.php';
header("Location: $previous");
exit;
