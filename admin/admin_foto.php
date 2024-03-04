<?php
// Hubungkan ke database
$host = 'localhost';
$dbname = 'gallery';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
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
$stmt = $pdo->prepare("SELECT * FROM photos WHERE album_id = :album_id");
$stmt->bindParam(':album_id', $album_id, PDO::PARAM_INT);
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
            <a href="admin_add_foto.php?album_id=<?php echo $album['album_id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Add Photos</a>
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
                <a href="admin_edit_foto.php?id=<?php echo $photo['photo_id']; ?>" class="btn btn-edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
                
                <!-- Delete Button -->
                <a href="admin_delete_foto.php?id=<?php echo $photo['photo_id']; ?>" onclick="return confirm('Anda yakin ingin menghapus user ini?')" class="btn btn-delete">
                    <i class="fas fa-trash-alt"></i> Delete
                </a>
            </div>
            
            <div class="photo-actions">
                <!-- Like Button -->
                <a href="admin_like.php" class="btn btn-like" data-photo-id="<?php echo $photo['photo_id']; ?>" data-liked="<?php echo isset($photo['is_liked']) ? $photo['is_liked'] : 'false'; ?>">
                    <i class="fas fa-thumbs-up"></i> <span class="like-text"><?php echo isset($photo['is_liked']) && $photo['is_liked'] ? 'Unlike' : 'Like'; ?></span>
                </a>
                
                <!-- Display number of likes -->
                <span class="like-count"><?php echo isset($photo['like_count']) ? $photo['like_count'] : 0; ?> Likes</span>
                
                <!-- Comment Button -->
                <a href="komen.php?id=<?php echo $photo['photo_id']; ?>" class="btn btn-comment">
                    <i class="fas fa-comment"></i> Comment
                </a>
                
                <!-- Display number of comments -->
                <span class="comment-count"><?php echo isset($photo['comment_count']) ? $photo['comment_count'] : 0; ?> Comments</span>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
document.querySelectorAll('.btn-like').forEach(function (likeButton) {
    likeButton.addEventListener('click', function (event) {
        event.preventDefault();

        var photoId = this.getAttribute('data-photo-id');
        var isLiked = this.getAttribute('data-liked') === 'true';
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
                likeButton.setAttribute('data-liked', data.action === 'like' ? 'true' : 'false');
                likeCount.textContent = data.like_count + ' Likes';
            } else {
                console.error('Error:', data.error);
            }
        })
        .catch(error => console.error('Fetch Error:', error));
    
    });
});

</script>










</body>
</html>