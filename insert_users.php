<?php
// Proses saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Koneksi ke database
    $conn = mysqli_connect("localhost", "root", "", "ourlens_db");

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Ambil data dari form
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role']; // admin, penjual, pembeli

    // Insert ke tabel users
    $sql = "INSERT INTO users (username, password, email, role) 
            VALUES ('$username', '$password', '$email', '$role')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color: green;'>✅ User berhasil ditambahkan!</p>";
        echo "<a href='login.php'>➡️ Klik di sini untuk login</a>";
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah User - OurLens</title>
</head>
<body>
    <h2>Form Tambah User</h2>

    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Role:</label><br>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="penjual">Penjual</option>
            <option value="pembeli">Pembeli</option>
        </select><br><br>

        <button type="submit">Tambah User</button>
    </form>
</body>
</html>
