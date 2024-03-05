<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Comment</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    /* Add this style to your existing CSS */
    a.delete-link {
        color: #fff;
        /* Set the color of the delete link */
        text-decoration: none;
        background-color: #0056b3;
        padding: 5px;

        /* Remove the default underline */
    }

    a.delete-link:hover {
        text-decoration: underline;

        /* Add underline on hover */
    }
    </style>
</head>

<body>
    <?php include('gallery_navbar.php'); ?>

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

        session_start();
        $user_id = $_SESSION['user_id'];

        $photoID = $_GET['photo_id'];

        // Menyimpan data ke dalam database jika formulir telah disubmit
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Mengambil data yang dikirimkan melalui formulir
            $user_id = $user_id; // Ganti dengan ID pengguna yang sesuai
            $photoID = $photoID;
            $comment_text = $_POST["comment_text"]; // Menggunakan "comment_text" yang sesuai dengan name textarea

            // Query untuk menyimpan data ke dalam database
            $sql = "INSERT INTO commens (user_id, photo_id, comment_text, created_at) 
            VALUES ('$user_id', '$photoID', '$comment_text', NOW())";

            // Menjalankan query
            if (mysqli_query($conn, $sql)) {
                // Berhasil menyimpan data
                header('Location: comment_photo.php?photo_id=' . $photoID);
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }

        // Menampilkan semua komentar dengan informasi pengguna
        $sql = "SELECT commens.*, users.username
        FROM commens
        JOIN users ON commens.user_id = users.user_id
        WHERE commens.photo_id = '$photoID'";

        $result = mysqli_query($conn, $sql);

        // Memeriksa apakah ada kesalahan dalam query sebelum menggunakan mysqli_num_rows
        if ($result === false) {
            echo "Error: " . mysqli_error($conn);
        } else {
            // Memeriksa apakah ada komentar yang ditemukan
            if (mysqli_num_rows($result) > 0) {
                // Menampilkan komentar dalam bentuk tabel
                echo "<table>";
                echo "<tr>
                <th>User</th>
                
                <th>Comment</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
        <td>" . $row["username"] . "</td>
        <td>" . $row["comment_text"] . "</td>
        <td>" . $row["created_at"] . "</td>
        <td><a class='delete-link' href='delete_comment.php?comment_id=" . $row["commen_id"] . "'>Delete</a></td>
    </tr>";
                }

                echo "</table>";
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