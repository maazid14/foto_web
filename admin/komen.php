<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Comment</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
    }

    label {
        font-weight: bold;
        color: #333;
    }

    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: vertical;
    }

    input[type="submit"] {
        display: block;
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .review {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
    }

    .review .user {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .review .date {
        font-style: italic;
        color: #888;
        font-size: 0.8em;
    }
    </style>
</head>
<?php include('gallery_navbar.php'); ?>

<body>

    <div class="container">
        <h2>Add Comment</h2>

        <!-- Tambahkan input hidden untuk menyimpan photo_id -->
        <form action="comment_photo.php" method="POST">
            <input type="hidden" name="photo_id" value="1"> <!-- Ganti nilai photo_id dengan nilai yang sesuai -->
            <label for="comment_text">Comment:</label><br>
            <textarea id="comment_text" name="comment_text" rows="4" required></textarea><br><br>
            <!-- Menggunakan name "comment_text" yang sesuai -->
            <input type="submit" name="submit" value="Submit">
        </form>

        <?php
        // Koneksi ke database
        $conn = mysqli_connect("localhost", "root", "", "gallery");

        // Menampilkan semua komentar dengan informasi pengguna
        $sql = "SELECT commens.*, users.username
        FROM commens
        JOIN users ON commens.user_id = users.user_id";
        $result = mysqli_query($conn, $sql);

        // Memeriksa apakah ada kesalahan dalam query sebelum menggunakan mysqli_num_rows
        if ($result === false) {
            echo "Error: " . mysqli_error($conn);
        } else {
            // Memeriksa apakah ada komentar yang ditemukan
            if (mysqli_num_rows($result) > 0) {
                // Menampilkan komentar dalam bentuk ulasan
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='review'>";
                    echo "<div class='user'>Username: " . $row["username"] . "</div>";
                    echo "<div>" . $row["comment_text"] . "</div>";
                    echo "<div class='date'>Created At: " . $row["created_at"] . "</div>";
                    echo "</div>";
                }
            } else {
                echo "No comments yet.";
            }
        }

        // Menutup koneksi ke database
        mysqli_close($conn);
        ?>


    </div>

</body>

</html>