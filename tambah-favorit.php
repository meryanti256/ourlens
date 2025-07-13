<?php
session_start();
if (!isset($_SESSION['id_pembeli'])) {
    header("Location: ../login.php");
    exit();
}

include '../koneksi.php';

$id_pembeli = intval($_SESSION['id_pembeli']);
$id_foto = intval($_POST['id_foto'] ?? 0);

$cek = mysqli_query($conn, "SELECT * FROM favorites WHERE id_pembeli = $id_pembeli AND id_foto = $id_foto");
if (mysqli_num_rows($cek) == 0) {
    mysqli_query($conn, "INSERT INTO favorites (id_pembeli, id_foto) VALUES ($id_pembeli, $id_foto)");
    $_SESSION['notif_favorit'] = "📌 Foto berhasil ditambahkan ke Favorit!";
} else {
    $_SESSION['notif_favorit'] = "⚠️ Foto ini sudah ada di Favorit.";
}

header("Location: dashboard-pembeli.php");
exit();
