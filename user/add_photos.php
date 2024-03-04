<?php
session_start();

// Periksa apakah pengguna sudah login dan apakah album_id ada dalam parameter URL
if (!isset($_SESSION['user_id']) || !isset($_GET['album_id'])) {
    header("Location: photo.php");
    exit();
}

// Tangani proses form jika data telah dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Hubungkan ke database
    $host = 'localhost';
    $dbname = 'gallery';
    $user = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fungsi untuk menangani unggah gambar
        function uploadImage($file)
        {
            $targetDirectory = "uploads/";
            if (!file_exists($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }

            $targetFile = $targetDirectory . basename($file['name']);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($file["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
                return "File bukan gambar.";
            }

            // Check if file already exists
            if (file_exists($targetFile)) {
                $uploadOk = 0;
                return "File sudah ada.";
            }

            // Check file size
            if ($file["size"] > 5000000) {
                $uploadOk = 0;
                return "Ukuran file terlalu besar.";
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $uploadOk = 0;
                return "Hanya file JPG, JPEG, PNG, dan GIF yang diizinkan.";
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                return false;
            } else {
                if (move_uploaded_file($file["tmp_name"], $targetFile)) {
                    return $targetFile; // Path file berhasil diunggah
                } else {
                    return false; // Gagal mengunggah file
                }
            }
        }

        // Ambil data formulir
        $title = $_POST['title'];
        $description = $_POST['description'];

        // Validasi formulir
        // Tambahkan logika validasi sesuai kebutuhan

        // Handle unggah gambar
        $image_path = '';
        if (!empty($_FILES['image']['name'])) {
            $image_path = uploadImage($_FILES['image']);
            if ($image_path === false) {
                $error_message = "Gagal mengunggah gambar. Pastikan file adalah gambar JPG, JPEG, PNG, atau GIF dengan ukuran maksimal 500KB.";
            }
        }

        // Jika tidak ada kesalahan validasi dan gambar berhasil diunggah, tambahkan foto ke database
        if (!isset($error_message) && $image_path !== false) {
            $album_id = $_GET['album_id'];

            // Gunakan prepared statement untuk mencegah SQL injection
            $stmt = $pdo->prepare("INSERT INTO photos (user_id, album_id, title, description, image_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $album_id, $title, $description, $image_path]);

            // Redirect ke halaman photo setelah menambahkan foto
            header("Location: photo.php?album_id=" . $album_id);
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        // Handle error, misalnya tampilkan pesan kesalahan
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Photo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 font-sans">

    <!-- Include Gallery Navbar -->
    <?php include('gallery_navbar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 p-8 flex flex-col justify-center items-center">
        <!-- Page Content -->
        <div class="mb-4">
            <h2 class="text-3xl font-bold">Add Photo</h2>
        </div>

        <!-- Form untuk menambahkan foto -->
        <form action="add_photos.php?album_id=<?php echo $_GET['album_id']; ?>" method="post" enctype="multipart/form-data" class="mx-auto max-w-md">
            <?php if (isset($error_message)) : ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline"><?php echo $error_message; ?></span>
                </div>
            <?php endif; ?>
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-600">Title</label>
                <input type="text" name="title" id="title" class="mt-1 p-2 w-full border rounded-md">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-600">Description</label>
                <input type="text" name="description" id="description" class="mt-1 p-2 w-full border rounded-md">
            </div>
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-600">Select Image</label>
                <input type="file" name="image" id="image" accept="image/*">
            </div>
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Upload Photo</button>
                <!-- Button to return to dashboard -->
                <a href="user_dashboard.php" class="block text-center mt-4 text-blue-500 hover:text-blue-700">kembali</a>
            </div>
        </form>
    </div>
</body>
</html>
