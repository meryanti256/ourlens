<?php
session_start();
require 'db.php';           // pakai PDO
$username   = $_POST['username'] ?? '';
$password   = $_POST['password'] ?? '';
$inputRole  = $_POST['role']    ?? '';

// ambil data user sesuai username & role
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? AND role = ? LIMIT 1');
$stmt->execute([$username, $inputRole]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        'id'       => $user['id'],
        'username' => $user['username'],
        'role'     => $user['role']
    ];
    switch ($user['role']) {
        case 'admin':   header('Location: dashboard-admin.php');   break;
        case 'penjual': header('Location: dashboard-penjual.php'); break;
        case 'pembeli': header('Location: dashboard-pembeli.php'); break;
    }
    exit;
}

$_SESSION['error'] = 'Username, password, atau role salah!';
header('Location: index.php?role=' . urlencode($inputRole));
exit;
