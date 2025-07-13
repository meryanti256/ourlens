<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];

$query = "DELETE FROM users WHERE id = $id AND role = 'penjual'";
if (mysqli_query($conn, $query)) {
    header("Location: data-penjual.php");
    exit();
} else {
    echo "Gagal menghapus penjual.";
}
?>
