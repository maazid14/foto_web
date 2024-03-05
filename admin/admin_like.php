<?php
session_start();

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
        $userId = $_SESSION['user_id']; // You should replace this with the actual user ID

        // Check if the user has already liked the photo
        $stmtCheck = $pdo->prepare("SELECT * FROM `likes` WHERE `user_id` = ? AND `photo_id` = ?");
        $stmtCheck->execute([$userId, $photoId]);

        if ($stmtCheck->rowCount() > 0) {
            // User has already liked the photo, so unlike it
            $stmtUnlike = $pdo->prepare("DELETE FROM `likes` WHERE `user_id` = ? AND `photo_id` = ?");
            $stmtUnlike->execute([$userId, $photoId]);
            echo json_encode(array("success" => true, "message" => "Unlike successful!"));
        } else {
            // User hasn't liked the photo, so insert a new like
            $stmtLike = $pdo->prepare("INSERT INTO `likes` (`user_id`, `photo_id`) VALUES (?, ?)");
            $stmtLike->execute([$userId, $photoId]);
            echo json_encode(array("success" => true, "message" => "Like added successfully!"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Invalid request!"));
    }
} catch (PDOException $e) {
    echo json_encode(array("success" => false, "message" => "Connection failed: " . $e->getMessage()));
}