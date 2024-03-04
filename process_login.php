<?php
session_start();

// Replace these with your actual database credentials
$host = 'localhost';
$dbname = 'gallery';
$user = 'root';
$password = '';

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve user information based on the provided username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $_POST['username']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and the password is correct
    if ($user && password_verify($_POST['password'], $user['password'])) {
        // Store user information in session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['acces_level']; // Use correct variable name

        // Redirect to the appropriate dashboard based on the user's role
        switch ($user['acces_level']) { // Use correct variable name
            case 'admin':
                header('Location: admin/admin_dashboard.php');
                break;
            case 'user':
                header('Location: user/user_dashboard.php');
                break;
            // Add other roles as needed
            default:
                // Handle other roles or situations as needed
                header('Location: index.php');
        }
    } else {
        // Invalid username or password
        header('Location: login.php');
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
