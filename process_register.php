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

    // Get user input from the form
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $email = $_POST['email'];
    $access_level = $_POST['acces_level']; // Use correct variable name

    // Insert user data into the database
    $stmt = $pdo->prepare("INSERT INTO users (name, username, password, email, acces_level) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $username, $password, $email, $access_level]);

    // Redirect to login page after successful registration
    header('Location: login.php');

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
