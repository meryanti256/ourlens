<?php
session_start();
$selectedRole = isset($_GET['role']) ? $_GET['role'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - OurLens</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h2>OurLens</h2>
        <form action="auth.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>

            <!-- Tambahan input tersembunyi -->
            <input type="hidden" name="role" value="<?= htmlspecialchars($selectedRole) ?>">

            <button type="submit">Login</button>
        </form>
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
