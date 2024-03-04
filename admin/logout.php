<?php
// Mulai sesi
session_start();

// Hapus data sesi
session_destroy();

// Hapus cookie jika diperlukan
// setcookie('nama_cookie', '', time() - 3600, '/');

// Redirect ke halaman login atau halaman utama
header("Location: ../login.php");
exit();
?>
