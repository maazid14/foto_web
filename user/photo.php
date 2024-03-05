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
    } 
} catch (PDOException $e) {
    echo json_encode(array("success" => false, "message" => "Connection failed: " . $e->getMessage()));
}

$userID = $_SESSION['user_id'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

// Ambil album_id dari parameter URL
$album_id = isset($_GET['album_id']) ? $_GET['album_id'] : null;

// Jika album_id tidak valid, kembalikan ke halaman sebelumnya
if (!$album_id) {
    header("Location: photo.php");
    exit();
}

// Ambil informasi album
$stmt = $pdo->prepare("SELECT * FROM albums WHERE album_id = :album_id");
$stmt->bindParam(':album_id', $album_id, PDO::PARAM_INT);
$stmt->execute();
$album = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil data foto dari database berdasarkan album_id
$stmt = $pdo->prepare("SELECT p.*, COUNT(l.like_id) as like_count, 
                            CASE WHEN l.user_id = :user_id THEN 1 ELSE 0 END as is_liked
                       FROM photos p
                       LEFT JOIN likes l ON p.photo_id = l.photo_id
                       WHERE p.album_id = :album_id
                       GROUP BY p.photo_id");
$stmt->bindParam(':album_id', $album_id, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
$stmt->execute();
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $album['title']; ?> - Photos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
    /* ... (CSS sebelumnya) */

    /* CSS untuk kontainer foto */
    .photos-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    /* CSS untuk setiap kartu foto */
    .photo-card {
        width: 300px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }

    .photo-card:hover {
        transform: scale(1.05);
    }

    /* CSS untuk detail foto */
    .photo-details {
        padding: 10px;
        text-align: center;
    }

    /* CSS untuk judul (title) foto */
    .photo-details h2 {
        font-weight: bold;
    }

    /* CSS untuk deskripsi foto */
    .photo-details p {
        color: #666;
    }

    /* CSS untuk tombol aksi */
    .photo-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: #f8f8f8;
    }

    /* CSS untuk tombol edit, delete, like, dan comment */
    .btn {
        display: inline-block;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        border: none;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #2c3e50;
        color: #fff;
    }

    .btn-edit {
        background-color: #3498db;
        color: #fff;
    }

    .btn-delete {
        background-color: #e74c3c;
        color: #fff;
    }

    .btn-like,
    .btn-comment {
        background-color: #2ecc71;
        color: #fff;
    }

    /* Hover effect untuk semua tombol */
    .btn:hover {
        background-color: #2c3e50;
    }
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <!-- Include Gallery Navbar -->
    <?php include('gallery_navbar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <!-- Page Content -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold"><?php echo $album['title']; ?> - Photos</h2>
            <a href="add_photos.php?album_id=<?php echo $album['album_id']; ?>"
                class="bg-blue-500 text-white px-4 py-2 rounded">Add Photos</a>
        </div>

        <!-- Loop untuk menampilkan foto-foto -->
        <div class="photos-container">
            <?php foreach ($photos as $photo) : ?>
            <div class="photo-card">
                <img src="<?php echo $photo['image_path']; ?>" alt="<?php echo $photo['title']; ?>">
                <div class="photo-details">
                    <h2><?php echo $photo['title']; ?></h2>
                    <p><?php echo $photo['description']; ?></p>
                </div>
                <div class="photo-actions">
                    <!-- Edit Button -->
                    <a href="edit_foto.php?id=<?php echo $photo['photo_id']; ?>" class="btn btn-edit">
                        <i class="fas fa-edit"></i>
                    </a>

                    <!-- Delete Button -->
                    <a href="delete_foto.php?id=<?php echo $photo['photo_id']; ?>"
                        onclick="return confirm('Anda yakin ingin menghapus user ini?')" class="btn btn-delete">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>

                <div class="photo-actions">
                    <!-- Like Button -->
                    <a href="like_photo.php" class="btn btn-like" data-photo-id="<?php echo $photo['photo_id']; ?>"
                        data-liked="<?php echo $photo['is_liked']; ?>">
                        <i class="fas fa-thumbs-up"></i> <span
                            class="like-text"><?php echo $photo['is_liked'] ? 'Unlike' : 'Like'; ?></span>
                    </a>

                    <!-- Display number of likes -->
                    <span class="like-count"><?php echo $photo['like_count']; ?> Likes</span>

                    <!-- Comment Button -->
                    <a href="comment_photo.php?photo_id=<?php echo $photo['photo_id']; ?>" class="btn btn-comment">
                        <i class="fas fa-comment"></i> Comment
                    </a>

                    <!-- Display number of comments -->

                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <script>
        document.querySelectorAll('.btn-like').forEach(function(likeButton) {
            likeButton.addEventListener('click', function(event) {
                event.preventDefault();

                var photoId = this.getAttribute('data-photo-id');
                var isLiked = this.getAttribute('data-liked') === '1';
                var likeText = this.querySelector('.like-text');
                var likeCount = this.nextElementSibling.querySelector('.like-count');

                // Kirim permintaan AJAX menggunakan fetch
                fetch('like_photo.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'photo_id=' + photoId,
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Log data respons untuk debugging
                        console.log(data);

                        // Perbarui likeText, likeCount, dan atribut data-liked berdasarkan respons
                        if (data.success) {
                            likeText.textContent = data.action === 'like' ? 'Unlike' : 'Like';
                            likeButton.setAttribute('data-liked', data.action === 'like' ? '1' :
                                '0');
                            likeCount.textContent = data.like_count + ' Likes';
                        } else {
                            console.error('Error:', data.error);
                        }
                    })
                    .catch(error => console.error('Fetch Error:', error));
            });
        });
        </script>

    </div>
</body>

</html>