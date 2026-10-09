<?php
// [Req 8] Logout functionality
session_start();
session_unset(); // Menghapus semua variabel session
session_destroy(); // Menghancurkan session

// Menghapus cookie remember_email jika ingin logout penuh (Opsional, di-comment agar remember me tetap bekerja setelah logout)
// setcookie('remember_email', '', time() - 3600, "/");

header("Location: login.php");
exit;
?>