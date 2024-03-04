<?php
session_start();

// Periksa apakah pengguna sudah login dan apakah photo_id ada dalam parameter URL
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: admin_foto.php");
    exit();
}

// Hubungkan ke database
$host = 'localhost';
$dbname = 'gallery';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil user_id dari session
    $user_id = $_SESSION['user_id'];

    // Ambil photo_id dari parameter URL
    $photo_id = $_GET['id'];

    // Periksa apakah pengguna memiliki hak akses untuk menghapus foto
    $stmt = $pdo->prepare("SELECT * FROM photos WHERE photo_id = ? AND user_id = ?");
    $stmt->execute([$photo_id, $user_id]);
    $photo = $stmt->fetch();

    if (!$photo) {
        // Jika tidak ada hak akses, redirect ke halaman photo.php
        header("Location: admin_foto.php");
        exit();
    }

    // Hapus foto dari database
    $stmt = $pdo->prepare("DELETE FROM photos WHERE photo_id = ?");
    $stmt->execute([$photo_id]);

    // Hapus file gambar dari server jika ada
    if (file_exists($photo['image_path'])) {
        unlink($photo['image_path']);
    }

    // Redirect ke halaman photo.php setelah menghapus foto
    header("Location: admin_foto.php?album_id=" . $photo['album_id']);
    exit();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    // Handle error, misalnya tampilkan pesan kesalahan
}
?>
 