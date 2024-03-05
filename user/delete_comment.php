<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "gallery");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["commen_id"])) {
    // Ambil ID komentar yang akan dihapus
    $comment_id = $_GET["commen_id"];

    // Query untuk menghapus komentar berdasarkan ID komentar
    $sql = "DELETE FROM commens WHERE commen_id = '$comment_id'";

    // Eksekusi query
    if (mysqli_query($conn, $sql)) {
        // Redirect kembali ke halaman sebelumnya setelah penghapusan berhasil
        header("Location: {$_SERVER['user/comment_photo.php']}");
        exit();
    } else {
        echo "Error deleting comment: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
header("Location:user/comment_photo.php?album_id=" . $photo['album_id']);
exit();

// Menutup koneksi ke database
mysqli_close($conn);
