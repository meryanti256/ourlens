<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'penjual') {
    header("Location: ../login.php");
    exit();
}

$id_penjual = $_SESSION['id'];
$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];
$kategori = $_POST['kategori'];
$harga = $_POST['harga'];

$namaFile = basename($_FILES['foto']['name']);
$targetDir = "gambar/"; // ← ganti ke dalam folder penjual
$targetFile = $targetDir . $namaFile;

if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
    // Simpan data ke database
    $query = "INSERT INTO foto (id_penjual, judul, deskripsi, kategori, harga, file_path) 
              VALUES ('$id_penjual', '$judul', '$deskripsi', '$kategori', '$harga', '$namaFile')";

    if (mysqli_query($conn, $query)) {
        header("Location: unggah-foto.php?status=sukses");
        exit();
    } else {
        echo "❌ Gagal menyimpan ke database.";
    }
} else {
    echo "❌ Gagal mengunggah file ke folder. Pastikan folder /penjual/gambar tersedia dan bisa ditulis.";
}
?>
