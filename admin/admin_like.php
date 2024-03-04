<?php
// Connect to your database (use your database configuration)
$host = "localhost";
$username = "root";
$password = "";
$database = "gallery";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the request contains a valid photo_id
    if (isset($_POST['photo_id'])) {
        $photoId = $_POST['photo_id'];
        $userId = 1; // You should replace this with the actual user ID
        
        // Insert like into the 'likes' table without specifying like_id and created_at
        $stmt = $pdo->prepare("INSERT INTO `likes` (`user_id`, `photo_id`) VALUES (?, ?)");
        $stmt->execute([$userId, $photoId]);

        echo json_encode(array("success" => true, "message" => "Like added successfully!"));
    } else {
        echo json_encode(array("success" => false, "message" => "Invalid request!"));
    }

} catch (PDOException $e) {
    echo json_encode(array("success" => false, "message" => "Connection failed: " . $e->getMessage()));
}
?>
