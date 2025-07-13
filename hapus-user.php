<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Jangan biarkan admin hapus dirinya sendiri
    if ($_SESSION['id'] == $id) {
        echo "<script>alert('Anda tidak dapat menghapus akun Anda sendiri!'); window.location.href='kelola-user.php';</script>";
        exit();
    }

    // Cek apakah user ada
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
    if (mysqli_num_rows($cek) > 0) {
        $hapus = mysqli_query($conn, "DELETE FROM users WHERE id = $id");

        if ($hapus) {
            echo "<script>alert('User berhasil dihapus'); window.location.href='kelola-user.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus user'); window.location.href='kelola-user.php';</script>";
        }
    } else {
        echo "<script>alert('User tidak ditemukan'); window.location.href='kelola-user.php';</script>";
    }
} else {
    header("Location: kelola-user.php");
}
?>
