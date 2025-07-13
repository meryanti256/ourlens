<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

$id_pembeli = $_SESSION['id'];

if (isset($_POST['konfirmasi']) && isset($_POST['id_keranjang'])) {
    $id_keranjang_array = $_POST['id_keranjang']; // array dari input

    // Bersihkan dulu inputannya
    $id_keranjang_sanitized = array_map('intval', $id_keranjang_array);
    $id_keranjang_list = implode(',', $id_keranjang_sanitized);

    // Hapus hanya gambar yang dibeli
    $hapus = mysqli_query($conn, "DELETE FROM keranjang WHERE id_pembeli = '$id_pembeli' AND id_keranjang IN ($id_keranjang_list)");

    if ($hapus) {
        header("Location: transaksi.php?status=berhasil");
        exit();
    } else {
        echo "Gagal menghapus item keranjang.";
    }

} else {
    echo "Data tidak lengkap.";
}
?>
