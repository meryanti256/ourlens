<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$id_pembeli = $_SESSION['id'] ?? 0;
$keranjang_ids = $_SESSION['checkout_keranjang_ids'] ?? [];
$dataFoto = $_SESSION['checkout_data'] ?? [];

if (empty($keranjang_ids) || empty($dataFoto)) {
    echo "Transaksi gagal. Tidak ada data untuk diproses.";
    exit();
}

foreach ($dataFoto as $foto) {
    $id_foto = intval($foto['id_foto']);
    $harga = intval($foto['harga']);
    $tanggal = date('Y-m-d H:i:s');

    mysqli_query($conn, "INSERT INTO transaksi (id_pembeli, id_foto, total, tanggal) 
        VALUES ($id_pembeli, $id_foto, $harga, '$tanggal')");

    mysqli_query($conn, "UPDATE foto SET status = 'terjual' WHERE id = $id_foto");
}

// Hapus dari keranjang hanya yang dipilih
$id_string = implode(',', array_map('intval', $keranjang_ids));
mysqli_query($conn, "DELETE FROM keranjang WHERE id_pembeli = $id_pembeli AND id IN ($id_string)");

// Bersihkan session checkout
unset($_SESSION['checkout_data']);
unset($_SESSION['checkout_total']);
unset($_SESSION['checkout_keranjang_ids']);

echo "<script>alert('Pembayaran berhasil! Silakan cek transaksi Anda.');window.location='transaksi.php';</script>";
?>
