<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli' || !isset($_SESSION['id'])) {
    die("Akses ditolak: Anda bukan pembeli.");
}

$id_pembeli = intval($_SESSION['id']);
$id_foto = isset($_POST['id_foto']) ? intval($_POST['id_foto']) : 0;

if ($id_foto <= 0) {
    die("ID foto tidak valid.");
}

$q = mysqli_query($conn, "SELECT id_penjual, harga FROM foto WHERE id = $id_foto");
if (!$q || mysqli_num_rows($q) === 0) {
    die("Foto tidak ditemukan.");
}

$data = mysqli_fetch_assoc($q);
$id_penjual = intval($data['id_penjual']);
$harga = intval($data['harga']);
$tanggal = date('Y-m-d');

$insert = mysqli_query($conn, "
    INSERT INTO transaksi (id_pembeli, id_penjual, id_foto, tanggal, total, status)
    VALUES ($id_pembeli, $id_penjual, $id_foto, '$tanggal', $harga, 'selesai')
");

if (!$insert) {
    die("Gagal simpan transaksi: " . mysqli_error($conn));
}

$update = mysqli_query($conn, "
    UPDATE foto 
    SET status = 'terjual', id_pembeli = $id_pembeli 
    WHERE id = $id_foto
");

if (!$update) {
    die("Gagal update foto: " . mysqli_error($conn));
}

echo "Transaksi berhasil. ID PEMBELI TERSIMPAN: $id_pembeli";
?>
