<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$koneksi = mysqli_connect("localhost", "root", "", "ourlens_db");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$id = $_GET['id'] ?? '';
if ($id) {
    mysqli_query($koneksi, "DELETE FROM users WHERE id = $id AND role='pembeli'");
}

header("Location: data-pembeli.php");
exit();
?>
