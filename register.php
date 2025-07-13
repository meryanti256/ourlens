<?php
include 'koneksi.php';
session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email']; // ← tambahkan ini
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    if ($role === 'admin') {
        $message = "Tidak diizinkan daftar sebagai admin.";
    } else {
        $checkUser = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
        if (mysqli_num_rows($checkUser) > 0) {
            $message = "Username sudah digunakan.";
        } else {
            $query = "INSERT INTO users (username, email, password, role) 
                      VALUES ('$username', '$email', '$password', '$role')";
            if (mysqli_query($conn, $query)) {
                header("Location: login.php");
                exit();
            } else {
                $message = "Gagal mendaftar.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar OurLens</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login-container">
    <div class="login-left">
        <img src="gambarlogin/login.png" alt="Gambar Daftar">
    </div>
    <div class="login-right">
        <h2>Welcome To OurLens!</h2>
        <?php if (!empty($message)) echo "<div class='error'>$message</div>"; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required> <!-- input email baru -->
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="">-- Pilih peran --</option>
                <option value="penjual">Penjual</option>
                <option value="pembeli">Pembeli</option>
            </select>
            <button type="submit">Daftar</button>
        </form>
        <p>already have an account?  <a href="login.php">Login disini</a></p>
    </div>
</div>

<div class="bubble-container">
    <div class="bubble b1"></div>
    <div class="bubble b2"></div>
    <div class="bubble b3"></div>
    <div class="bubble b4"></div>
</div>
<script src="script.js"></script>
</body>
</html>
