<?php
// Pastikan untuk memulai sesi
session_start();

// Hubungkan ke database
$host = 'localhost';
$dbname = 'gallery';
$user = 'root';
$password = '';

// Inisialisasi variabel pesan kesalahan
$titleError = $descriptionError = '';

// Jika formulir dikirim, proses data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data formulir
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Validasi formulir
    if (empty($title)) {
        $titleError = 'Title is required';
    }

    if (empty($description)) {
        $descriptionError = 'Description is required';
    }

    // Jika tidak ada kesalahan validasi, tambahkan album ke database
    if (empty($titleError) && empty($descriptionError)) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Ambil user_id dari sesi
            $user_id = $_SESSION['user_id'];

            // Tambahkan album ke database
            $stmt = $pdo->prepare("INSERT INTO albums (user_id, title, description) VALUES (:user_id, :title, :description)");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->execute();

            // Redirect ke halaman user_dashboard setelah menambahkan album
            header("Location: ../admin/home_admin.php");
            exit();
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Album</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 font-sans flex items-center justify-center h-screen">

    <!-- Main Content -->
    <div class="w-full max-w-md p-8">
        <!-- Page Content -->
        <h2 class="text-3xl font-bold mb-4">Add Album</h2>

        <!-- Add Album Form -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-600">Title:</label>
                <input type="text" name="title" id="title" class="mt-1 p-2 w-full border rounded focus:outline-none focus:ring focus:border-blue-300" required>
                <span class="text-red-500"><?php echo $titleError; ?></span>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-600">Description:</label>
                <textarea name="description" id="description" rows="4" class="mt-1 p-2 w-full border rounded focus:outline-none focus:ring focus:border-blue-300" required></textarea>
                <span class="text-red-500"><?php echo $descriptionError; ?></span>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add Album</button>
        </form>
    </div>

</body>
</html>
