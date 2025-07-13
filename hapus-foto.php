<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penjual') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];

// Cek apakah foto sudah dibeli
$cekTransaksi = mysqli_query($conn, "SELECT id FROM transaksi WHERE id_foto = '$id' AND status = 'selesai'");
if (mysqli_num_rows($cekTransaksi) > 0) {
    header("Location: foto-saya.php?status=gagal_dibeli");
    exit();
}

// Hapus dulu relasi foreign key
mysqli_query($conn, "DELETE FROM favorites WHERE id_foto = '$id'");

// Hapus file dari folder
$getFile = mysqli_query($conn, "SELECT file_path FROM foto WHERE id = '$id'");
$data = mysqli_fetch_assoc($getFile);
$path = 'gambar/' . $data['file_path'];
if (file_exists($path)) {
    unlink($path);
}

// Hapus dari database
$hapus = mysqli_query($conn, "DELETE FROM foto WHERE id = '$id'");

if ($hapus) {
    header("Location: foto-saya.php?status=hapus");
} else {
    header("Location: foto-saya.php?status=gagal_db");
}
?>
