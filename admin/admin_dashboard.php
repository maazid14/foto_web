<?php
// Hubungkan ke database
$host = 'localhost';
$dbname = 'gallery';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil semua data pengguna dengan access level "user"
    $stmt = $pdo->prepare("SELECT * FROM users WHERE acces_level = :access_level");
    $stmt->bindValue(':access_level', 'user');
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-300 font-sans">

    <!-- Include Gallery Navbar -->
    <?php include('../admin/gallery_navbar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <!-- Page Content -->
        <h2 class="text-3xl font-bold mb-4">Daftar User</h2>

        <!-- Users Table -->
     <!-- Users Table -->
<table class="min-w-full bg-white border border-gray-300 shadow-md rounded">
    <thead>
        <tr>
            <th class="py-2 px-4 border-b text-center">Name</th>
            <th class="py-2 px-4 border-b text-center">Email</th>
            <th class="py-2 px-4 border-b text-center">Access Level</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
            <tr class="text-center">
                <td class="py-2 px-4 border-b">
                    <a href="user_albums.php?user_id=<?php echo $user['user_id']; ?>">
                        <?php echo $user['name']; ?>
                    </a>
                </td>
                <td class="py-2 px-4 border-b">
                    <a href="user_albums.php?user_id=<?php echo $user['user_id']; ?>">
                        <?php echo $user['email']; ?>
                    </a>
                </td>
                <td class="py-2 px-4 border-b">
                    <a href="user_albums.php?user_id=<?php echo $user['user_id']; ?>">
                        <?php echo $user['acces_level']; ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    </div>

</body>
</html>
