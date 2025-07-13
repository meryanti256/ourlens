<?php
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];
$role     = $_POST['role'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND role='$role'");
$data = mysqli_fetch_assoc($query);

if ($data && password_verify($password, $data['password'])) {
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];

    if ($data['role'] == 'admin') {
        header("Location: dashboard-admin.php");
    } elseif ($data['role'] == 'penjual') {
        header("Location: dashboard-penjual.php");
    } else {
        header("Location: dashboard-pembeli.php");
    }
} else {
    echo "<script>alert('Akun tidak ditemukan'); window.location='login.php';</script>";
}
?>

