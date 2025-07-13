<!DOCTYPE html>
<html>
<head>
  <title>Welcome - OurLens</title>
  <style>
    body {
      font-family: sans-serif;
      background: #f5f5f5;
      text-align: center;
      padding-top: 100px;
    }
    h2 {
      margin-bottom: 30px;
    }
    .btn {
      display: block;
      width: 200px;
      margin: 10px auto;
      padding: 12px;
      font-size: 16px;
      text-decoration: none;
      color: white;
      border-radius: 5px;
    }
    .admin { background-color: #007bff; }
    .penjual { background-color: #ff9800; }
    .pembeli { background-color: #4caf50; }
  </style>
</head>
<body>
  <h2>Selamat Datang di OurLens</h2>
  <p>Silakan pilih jenis pengguna:</p>
  <a href="index.php?role=admin" class="btn admin">Masuk sebagai Admin</a>
  <a href="index.php?role=penjual" class="btn penjual">Masuk sebagai Penjual</a>
  <a href="index.php?role=pembeli" class="btn pembeli">Masuk sebagai Pembeli</a>
</body>
</html>