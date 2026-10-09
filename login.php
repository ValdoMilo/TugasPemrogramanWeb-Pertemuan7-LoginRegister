<?php
require 'functions.php';

if (isset($_SESSION['user_email'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$saved_email = isset($_COOKIE['remember_email']) ? $_COOKIE['remember_email'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $users = getUsers();

    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_nama'] = $user['nama'];

            if ($remember) {
                setcookie('remember_email', $email, time() + (86400 * 30), "/");
            } else {
                setcookie('remember_email', '', time() - 3600, "/");
            }

            header("Location: dashboard.php");
            exit;
        }
    }
    $error = "Email atau password salah. Coba periksa kembali.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0a1426">
    <meta name="description" content="Login ke sistem">
    <title>Login | Sistem Akun</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="brand">
            <div class="brand-logo">A</div>
            <h2>Selamat Datang</h2>
            <p class="subtitle">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="loginForm" novalidate>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="<?php echo htmlspecialchars($saved_email); ?>"
                       placeholder="nama@contoh.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-with-toggle">
                    <input type="password" id="password" name="password"
                           placeholder="Masukkan password" required>
                    <button type="button" class="toggle-password"
                            onclick="togglePassword('password', this)"
                            aria-label="Tampilkan password">👁</button>
                </div>
            </div>

            <label class="checkbox-group">
                <input type="checkbox" name="remember" id="remember"
                       <?php echo $saved_email ? 'checked' : ''; ?>>
                <span>Ingat saya selama 30 hari</span>
            </label>

            <button type="submit">Masuk</button>
        </form>

        <div class="link">
            Belum punya akun? <a href="register.php">Daftar sekarang</a>
        </div>
    </div>

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            btn.textContent = isPassword ? '🙈' : '👁';
            btn.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
        }

        // Validasi form sebelum submit
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Mohon isi email dan password.');
                return;
            }
        });
    </script>
</body>
</html>