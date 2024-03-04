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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albums</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 font-sans">

    <!-- Include Gallery Navbar -->
    <?php include('gallery_navbar.php'); ?>

   <!-- Main Content -->
<div class="flex-1 p-9">
    <!-- Page Content -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-3xl font-bold">Albums</h2>
        <a href="../admin/admin_add_album.php" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">Add Album</a>
        <a href="../admin/profil_admin.php" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">Profil admin</a>
      

    </div>
    
    <!--edit album-->
    

    <!-- Albums Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php foreach ($albums as $album) : ?>
            <div class="bg-white p-4 rounded shadow-md">
                <a href="../admin/admin_foto.php?album_id=<?php echo $album['album_id']; ?>">
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
                    <small class="text-gray-500">Created at: <?php echo $album['created_at']; ?></small>
                     <a href="../admin/admin_edit_album.php?id" class="text">Edit</a>

                    
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>


</body>
</html>