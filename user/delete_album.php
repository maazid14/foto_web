<?php
// Assuming you have a function to get album data by ID (similar to previous examples)
function getAlbumById($albumId, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM albums WHERE album_id = :albumId");
    $stmt->bindParam(':albumId', $albumId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Assuming you have a function to delete an album by ID (adjust based on your database structure)
function deleteAlbumById($albumId, $pdo) {
    $stmt = $pdo->prepare("DELETE FROM albums WHERE album_id = :albumId");
    $stmt->bindParam(':albumId', $albumId, PDO::PARAM_INT);
    $stmt->execute();
}

$host = 'localhost';
$dbname = 'gallery';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get album ID from the form (assuming it's submitted as a hidden input field)
    $albumIdToDelete = isset($_POST['album_id']) ? $_POST['album_id'] : null;

    // Validate and sanitize input if necessary

    // Delete the album
    deleteAlbumById($albumIdToDelete, $pdo);

    // Redirect to a success page or display a success message
    header("Location: success_page.php");
    exit();
}

// Assuming you get the album ID from the query parameter (adjust based on your URL structure)
$albumId = isset($_GET['album_id']) ? $_GET['album_id'] : null;

// Fetch album data
$album = getAlbumById($albumId, $pdo);

// Check if the album exists before rendering the delete confirmation form
if ($album !== false) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Album</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 font-sans flex items-center justify-center h-screen">
    <div class="w-full max-w-md p-8 bg-white rounded-md shadow-md">
        <h2 class="text-3xl font-bold mb-4 text-center">Delete Album</h2>
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-600">Title</label>
            <p class="mt-1 p-2 w-full border rounded-md"><?php echo $album['title']; ?></p>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-600">Description</label>
            <p class="mt-1 p-2 w-full border rounded-md"><?php echo $album['description']; ?></p>
        </div>
        <form action="" method="post">
            <input type="hidden" name="album_id" value="<?php echo $album['album_id']; ?>">
            <div class="text-red-500 mb-4 text-center">
                Are you sure you want to delete this album? This action cannot be undone.
            </div>
            <div class="flex justify-center">
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete Album</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php
} else {
    echo "Album not found";
}
?>
