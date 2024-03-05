<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "gallery";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['photo_id'])) {
        $photoId = $_POST['photo_id'];
        $userId = $_SESSION['user_id'];

        $stmtCheck = $pdo->prepare("SELECT * FROM `likes` WHERE `user_id` = ? AND `photo_id` = ?");
        $stmtCheck->execute([$userId, $photoId]);

        if ($stmtCheck->rowCount() > 0) {
            $stmtUnlike = $pdo->prepare("DELETE FROM `likes` WHERE `user_id` = ? AND `photo_id` = ?");
            $stmtUnlike->execute([$userId, $photoId]);

            // Fetch the latest like count
            $stmtLikeCount = $pdo->prepare("SELECT COUNT(`like_id`) AS `like_count` FROM `likes` WHERE `photo_id` = ?");
            $stmtLikeCount->execute([$photoId]);
            $likeCount = $stmtLikeCount->fetch(PDO::FETCH_ASSOC)['like_count'];

            echo json_encode(array("success" => true, "action" => "unlike", "like_count" => $likeCount));
        } else {
            $stmtLike = $pdo->prepare("INSERT INTO `likes` (`user_id`, `photo_id`) VALUES (?, ?)");
            $stmtLike->execute([$userId, $photoId]);

            // Fetch the latest like count
            $stmtLikeCount = $pdo->prepare("SELECT COUNT(`like_id`) AS `like_count` FROM `likes` WHERE `photo_id` = ?");
            $stmtLikeCount->execute([$photoId]);
            $likeCount = $stmtLikeCount->fetch(PDO::FETCH_ASSOC)['like_count'];

            echo json_encode(array("success" => true, "action" => "like", "like_count" => $likeCount));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Invalid request!"));
    }
} catch (PDOException $e) {
    echo json_encode(array("success" => false, "message" => "Connection failed: " . $e->getMessage()));
}