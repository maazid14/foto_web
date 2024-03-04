<?php
// Hubungkan ke database
$host = 'localhost';
$dbname = 'gallery';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil album berdasarkan album_id yang diterima dari parameter URL
    $album_id = isset($_GET['album_id']) ? intval($_GET['album_id']) : 0;
    $stmt_album = $pdo->prepare("SELECT * FROM albums WHERE album_id = :album_id");
    $stmt_album->bindParam(':album_id', $album_id, PDO::PARAM_INT);
    $stmt_album->execute();
    $album = $stmt_album->fetch(PDO::FETCH_ASSOC);

    // Check apakah album ditemukan
    if (!$album) {
        echo "Album not found.";
        exit();
    }

    // Periksa apakah formulir disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil data dari formulir
        $title = $_POST['title'];
        $description = $_POST['description'];

        // Validasi data (Anda dapat menambahkan validasi tambahan sesuai kebutuhan)
        if (empty($title)) {
            $error = "Title is required.";
        } else {
            // Update informasi album ke database
            $stmt_update = $pdo->prepare("UPDATE albums SET title = :title, description = :description WHERE album_id = :album_id");
            $stmt_update->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt_update->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt_update->bindParam(':album_id', $album_id, PDO::PARAM_INT);
            $stmt_update->execute();

            // Redirect ke halaman "photo.php" dengan menyertakan album_id
            header("Location: photo.php?album_id=$album_id");
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
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
<body class="bg-gray-100 font-sans flex items-center justify-center h-screen">

    <!-- Main Content -->
    <div class="w-full max-w-md p-8 bg-white rounded-md shadow-md">
        <!-- Page Content -->
        <h2 class="text-3xl font-bold mb-4 text-center">Edit Album</h2>

        <!-- Form Edit Album -->
        <form action="" method="post">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-600">Title</label>
                <input type="text" name="title" id="title" value="<?php echo $album['title']; ?>" class="mt-1 p-2 w-full border rounded-md">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-600">Description</label>
                <textarea name="description" id="description" class="mt-1 p-2 w-full border rounded-md"><?php echo $album['description']; ?></textarea>
            </div>
            <?php if (isset($error)) : ?>
                <div class="text-red-500 mb-4 text-center"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="text-center">
              
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
            <br>
           
        </div><br>
             <div class="text-center">
            <a href="user_dashboard.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Kembali</a>
        </div>
        </form>
    </div>
</body>
</html>



