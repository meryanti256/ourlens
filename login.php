<?php
include 'koneksi.php';
session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    $query = "SELECT * FROM users WHERE username='$username' AND role='$role'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password']) || $password === $user['password']) {
            $_SESSION['id']       = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            if ($role === 'pembeli') {
            $_SESSION['id_pembeli'] = $user['id'];
            $_SESSION['nama']       = $user['nama_lengkap']; 
            }


            if ($role == 'admin') {
                header("Location: admin/dashboard-admin.php");
            } elseif ($role == 'penjual') {
                header("Location: penjual/dashboard-penjual.php");
            } elseif ($role == 'pembeli') {
                header("Location: pembeli/dashboard-pembeli.php");
            }
            exit();
        } else {
            $message = "Password salah!";
        }
    } else {
        $message = "Akun tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login OurLens</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login-container">
    <div class="login-left">
        <img src="gambarlogin/login.png">
    </div>
    <div class="login-right">
        <h2>Welcome To OURLENS!</h2>
        <?php if (!empty($message)) echo "<div class='error'>$message</div>"; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="">-- Pilih peran --</option>
                <option value="admin">Admin</option>
                <option value="penjual">Penjual</option>
                <option value="pembeli">Pembeli</option>
            </select>
            <div class="form-extra">
                <label><input type="checkbox"> Remember me</label>
                <a href="#">Forgot password?</a>
            </div>
            <button type="submit">Sign In</button>
        </form>
        <p>Don't have an account? <a href="register.php">Sign up</a></p>
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