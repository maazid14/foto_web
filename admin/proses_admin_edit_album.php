<?php
include 'koneksi.php';

// Ambil album_id dari parameter URL
$album_id = isset($_GET['album_id']) ? $_GET['album_id'] : die('Album ID not found.');

// Ambil data album dari database berdasarkan album_id
$stmt = $pdo->prepare("SELECT * FROM albums WHERE album_id = :album_id");
$stmt->bindParam(':album_id', $album_id, PDO::PARAM_INT);
$stmt->execute();
$album = $stmt->fetch(PDO::FETCH_ASSOC);

// Periksa apakah formulir disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Perbarui data album ke database
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("UPDATE albums SET title = :title, description = :description WHERE album_id = :album_id");
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':album_id', $album_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: albums.php"); // Redirect kembali ke halaman albums setelah update
        exit();
    } else {
        echo "Failed to update album.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="bg-gray-100 font-sans">

    <!-- Include Gallery Navbar -->
    <?php include('../user/gallery_navbar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <!-- Page Content -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold">Edit Album</h2>
            <a href="albums.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</a>
        </div>

        <!-- Edit Album Form -->
        <form action="" method="POST">
            <div class="mb-4">
                <label for="title" class="block text-gray-600 font-bold">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo $album['title']; ?>" class="form-input w-full">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-600 font-bold">Description:</label>
                <textarea id="description" name="description" class="form-input w-full"><?php echo $album['description']; ?></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Album</button>
        </form>
    </div>

</body>

</html>
