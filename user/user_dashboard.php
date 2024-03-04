<?php
// Hubungkan ke database
$host = 'localhost';
$dbname = 'gallery';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil data album dari database
    $stmt = $pdo->prepare("SELECT * FROM albums");
    $stmt->execute();
    $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<?php
// ... (kode yang ada sebelumnya)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albums</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Include Gallery Navbar -->
    <?php include('gallery_navbar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <!-- Page Content -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold">Albums</h2>
            <a href="add_album.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Album</a>
        </div>

        <!-- Albums Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-8">
            <?php foreach ($albums as $album) : ?>
                <div class="bg-white p-4 rounded shadow-md relative">
                    <a href="photo.php?album_id=<?php echo $album['album_id']; ?>">
                        <!-- Tambahkan foto album atau placeholder jika album tidak memiliki foto -->
                        <?php
                        $stmt = $pdo->prepare("SELECT * FROM photos WHERE album_id = :album_id LIMIT 1");
                        $stmt->bindParam(':album_id', $album['album_id'], PDO::PARAM_INT);
                        $stmt->execute();
                        $photo = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($photo) {
                            // Check if 'photo_path' index exists in the $photo array
                            if (isset($photo['photo_path'])) {
                                echo '<img src="/.uploads/' . $photo['photo_path'] . '" alt="" class="rounded shadow-md">';
                            } else {
                                echo '<div class="w-full h-32 bg-gray-300"></div>'; // Placeholder if the album does not have a photo_path
                            }
                        } else {
                            // Handle the case when $photo is not defined or empty
                            echo '<div class="w-full h-32 bg-gray-300"></div>';
                        }
                        ?>
                        <h3 class="text-xl font-semibold mb-2"><?php echo $album['title']; ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo $album['description']; ?></p>
                    </a>
                    
                    <!-- Tombol Edit dan Delete di bawah deskripsi -->
                    <div class="flex justify-between items-center mt-4">
                        <!-- Tombol Edit -->
                        <a href="../user/edit_album.php?album_id=<?php echo $album['album_id']; ?>" class="text-gray-600 hover:text-blue-500">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <!-- Tombol Delete -->
                        <a href="delete_album.php?album_id=<?php echo $album['album_id']; ?>" class="text-gray-600 hover:text-red-500">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>



</body>
</html>
