<?php
require 'functions.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

$nama = $_SESSION['user_nama'] ?? 'User';
$email = $_SESSION['user_email'] ?? '';
$initial = strtoupper(substr($nama, 0, 1));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0a1426">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container dashboard">
        <div class="avatar"><?php echo htmlspecialchars($initial); ?></div>

        <h2>Halo, <?php echo htmlspecialchars($nama); ?>!</h2>
        <p class="subtitle">Anda berhasil masuk ke dashboard terproteksi</p>

        <div class="dashboard-info">
            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-value"><?php echo htmlspecialchars($nama); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value"><?php echo htmlspecialchars($email); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value" style="color: var(--success);">● Online</span>
            </div>
        </div>

        <div class="dashboard-note">
            Dashboard ini dilindungi oleh session. Jika Anda logout, Anda akan
            diarahkan kembali ke halaman login.
        </div>

        <form action="logout.php" method="POST">
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</body>
</html>