<?php
require 'functions.php';

if (isset($_SESSION['user_email'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = sanitizeInput($_POST['nama']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if (empty($nama) || empty($email) || empty($password)) {
        $error = "Semua kolom wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        $users = getUsers();
        $emailExists = false;

        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $emailExists = true;
                break;
            }
        }

        if ($emailExists) {
            $error = "Email sudah terdaftar. Gunakan email lain.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $users[] = [
                'nama' => $nama,
                'email' => $email,
                'password' => $hashedPassword
            ];

            saveUsers($users);
            $success = "Registrasi berhasil! Silakan login.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0a1426">
    <meta name="description" content="Daftar akun baru">
    <title>Daftar | Sistem Akun</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="brand">
            <div class="brand-logo">A</div>
            <h2>Buat Akun Baru</h2>
            <p class="subtitle">Isi data di bawah untuk mendaftar</p>
        </div>

        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="registerForm" novalidate>
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama"
                       placeholder="Nama lengkap Anda" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       placeholder="nama@contoh.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-with-toggle">
                    <input type="password" id="password" name="password"
                           placeholder="Minimal 6 karakter" required minlength="6"
                           oninput="checkStrength(this.value)">
                    <button type="button" class="toggle-password"
                            onclick="togglePassword('password', this)"
                            aria-label="Tampilkan password">👁</button>
                </div>
                <div class="password-strength" id="strengthBox">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <span class="strength-text" id="strengthText"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="konfirmasi">Konfirmasi Password</label>
                <div class="input-with-toggle">
                    <input type="password" id="konfirmasi" name="konfirmasi"
                           placeholder="Ulangi password" required minlength="6">
                    <button type="button" class="toggle-password"
                            onclick="togglePassword('konfirmasi', this)"
                            aria-label="Tampilkan password">👁</button>
                </div>
            </div>

            <button type="submit">Daftar Sekarang</button>
        </form>

        <div class="link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
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

        function checkStrength(val) {
            const box = document.getElementById('strengthBox');
            const fill = document.getElementById('strengthFill');
            const text = document.getElementById('strengthText');

            if (!val) {
                box.classList.remove('visible');
                return;
            }

            box.classList.add('visible');

            let score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 10) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            fill.className = 'strength-fill';

            if (score <= 2) {
                fill.classList.add('weak');
                text.textContent = 'Lemah — tambahkan huruf besar, angka, atau simbol';
            } else if (score <= 3) {
                fill.classList.add('medium');
                text.textContent = 'Sedang — cukup baik, tapi bisa lebih kuat';
            } else {
                fill.classList.add('strong');
                text.textContent = 'Kuat — password Anda aman';
            }
        }

        // Validasi konfirmasi password
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            const pass = document.getElementById('password').value;
            const konf = document.getElementById('konfirmasi').value;

            if (pass !== konf) {
                e.preventDefault();
                alert('Konfirmasi password tidak cocok.');
            }
        });
    </script>
</body>
</html>