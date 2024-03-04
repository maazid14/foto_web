<?php
session_start();
include"../koneksi.php";

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Periksa apakah ada parameter ID yang dikirimkan
if (!isset($_GET['id'])) {
    header("Location: photo.php");
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

    // Ambil informasi foto berdasarkan ID
    $photo_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM photos WHERE photo_id = ?");
    $stmt->execute([$photo_id]);
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Periksa apakah foto ditemukan
    if (!$photo) {
        header("Location: photo.php");
        exit();
    }

    // Tangani proses form jika data telah dikirim
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ambil data formulir yang diubah
        $title = $_POST['title'];
        $description = $_POST['description'];

        // Update informasi foto ke database
        $stmt = $pdo->prepare("UPDATE photos SET title = ?, description = ? WHERE photo_id = ?");
        $stmt->execute([$title, $description, $photo_id]);

        // Redirect kembali ke halaman photo setelah mengedit
        header("Location: photo.php?album_id=" . $photo['album_id']);
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    // Handle error, misalnya tampilkan pesan kesalahan
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Photo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 font-sans">

    <!-- Include Gallery Navbar -->
    <?php include('gallery_navbar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 p-8 flex flex-col justify-center items-center">
        <!-- Page Content -->
        <div class="mb-4">
            <h2 class="text-3xl font-bold">Edit Photo</h2>
        </div>

        <!-- Form untuk mengedit foto -->
        <form action="edit_foto.php?id=<?php echo $photo_id; ?>" method="post" class="mx-auto max-w-md">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-600">Title</label>
                <input type="text" name="title" id="title" class="mt-1 p-2 w-full border rounded-md" value="<?php echo $photo['title']; ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-600">Description</label>
                <input type="text" name="description" id="description" class="mt-1 p-2 w-full border rounded-md" value="<?php echo $photo['description']; ?>">
            </div>
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html>
