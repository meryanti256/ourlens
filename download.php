<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pembeli') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$id_pembeli = $_SESSION['id'];
$id_foto = $_GET['id']; // pastikan ini dikirim lewat URL

// Cek apakah transaksi valid
$query = "SELECT foto.file_path FROM transaksi 
          JOIN foto ON transaksi.id_foto = foto.id 
          WHERE transaksi.id_foto = '$id_foto' 
            AND transaksi.id_pembeli = '$id_pembeli'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    $file_path = '../penjual/gambar/' . $data['file_path'];

    // Update status jadi selesai
    mysqli_query($conn, "UPDATE transaksi 
                         SET status = 'selesai' 
                         WHERE id_foto = '$id_foto' 
                           AND id_pembeli = '$id_pembeli'");

    // Kirim file ke browser
    if (file_exists($file_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit();
    } else {
        echo "File tidak ditemukan.";
    }
} else {
    echo "Transaksi tidak valid.";
}
?>
