<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penjual') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: foto-saya.php");
    exit();
}

$id_foto = $_GET['id'];
$id_penjual = $_SESSION['id'];

$query = "SELECT * FROM foto WHERE id = '$id_foto' AND id_penjual = '$id_penjual'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    header("Location: foto-saya.php");
    exit();
}

$foto = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $harga = (int) $_POST['harga'];
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $update = "UPDATE foto SET judul='$judul', harga='$harga', kategori='$kategori', deskripsi='$deskripsi' WHERE id='$id_foto'";

    if (mysqli_query($conn, $update)) {
        header("Location: foto-saya.php?status=berhasil_edit");
    } else {
        $error = "Gagal mengupdate data.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Foto</title>
    <link rel="stylesheet" href="penjual.css?v=<?= time() ?>">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #EEF1F8;
            margin: 0;
            padding: 40px 20px;
            color: #1A237E;
        }

        .edit-container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
            border-left: 8px solid #1A237E;
        }

        h2 {
            text-align: center;
            color: #1A237E;
            margin-bottom: 30px;
            font-weight: bold;
        }

        label {
            font-weight: bold;
            margin-top: 18px;
            display: block;
            color: #1A237E;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        textarea {
            resize: vertical;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 20px;
        }

        .btn-primary, .btn-secondary {
            padding: 12px 24px;
            font-weight: bold;
            border-radius: 999px;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            font-size: 15px;
            cursor: pointer;
            width: 100%;
            border: none;
        }

        .btn-primary {
            background-color: #1A237E;
            color: white;
        }

        .btn-primary:hover {
            background-color: #303F9F;
        }

        .btn-secondary {
            background-color: #e3e7f8;
            color: #1A237E;
        }

        .btn-secondary:hover {
            background-color: #d2dbf6;
        }

        .error {
            margin-top: 16px;
            color: red;
            text-align: center;
        }

        @media (max-width: 600px) {
            .edit-container {
                padding: 24px;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="edit-container">
    <h2>🖼️ Edit Informasi Foto</h2>

    <form method="POST">
        <label>Judul</label>
        <input type="text" name="judul" value="<?= htmlspecialchars($foto['judul']) ?>" required>

        <label>Harga</label>
        <input type="number" name="harga" value="<?= $foto['harga'] ?>" required>

        <label>Kategori</label>
        <select name="kategori" required>
            <option value="Alam" <?= $foto['kategori'] == 'Alam' ? 'selected' : '' ?>>Keindahan Alam</option>
            <option value="Olahraga" <?= $foto['kategori'] == 'Olahraga' ? 'selected' : '' ?>>Olahraga</option>
            <option value="Lukisan" <?= $foto['kategori'] == 'Lukisan' ? 'selected' : '' ?>>Lukisan</option>
        </select>

        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="4" required><?= htmlspecialchars($foto['deskripsi']) ?></textarea>

        <div class="button-group">
            <button type="submit" class="btn-primary">💾 Simpan</button>
            <a href="foto-saya.php" class="btn-secondary">Batal</a>
        </div>

        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
    </form>
</div>

</body>
</html>
