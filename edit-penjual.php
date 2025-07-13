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
if (!$id) {
    header("Location: data-penjual.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET username='$username', email='$email', password='$hashed' WHERE id=$id AND role='penjual'";
    } else {
        $query = "UPDATE users SET username='$username', email='$email' WHERE id=$id AND role='penjual'";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: data-penjual.php");
        exit();
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }
} else {
    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE id=$id AND role='penjual'");
    $data = mysqli_fetch_assoc($result);
    if (!$data) {
        echo "Data tidak ditemukan.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Penjual</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="admin.css?v=<?=time()?>">
    <style>
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 90vh;
        }

        .form-box {
            background-color: #f3e6ff;
            padding: 40px;
            border-radius: 20px;
            width: 400px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .form-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #4b0082;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        .form-box button {
            background-color: #8000cc;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 999px;
            cursor: pointer;
            font-weight: bold;
        }

        .form-box .cancel {
            display: inline-block;
            margin-top: 10px;
            text-align: center;
            color: #4b0082;
            text-decoration: underline;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="form-container">
    <div class="form-box">
        <h2>Edit Data Penjual</h2>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>

            <label>Password (opsional):</label>
            <input type="password" name="password" placeholder="Kosongkan jika tidak diubah">

            <button type="submit">Simpan Perubahan</button>
            <a href="data-penjual.php" class="cancel">Batal</a>
        </form>
    </div>
</div>
</body>
</html>
