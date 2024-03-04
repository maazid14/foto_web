<?php
// Hubungkan ke database
$host = 'localhost';
$dbname = 'gallery';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil data album dari database
    $stmt = $pdo->prepare("SELECT * FROM albums");
    $stmt->execute();
    $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>CodePen - Forest Parallex Website</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

@import url('https://fonts.googleapis.com/css2?family=Rancho&display=swap');

:root {
    --primary: #094b65;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    overflow-x: hidden;
    background: #fff;
    min-height: 100vh;
}

#header {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    padding: 30px 100px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 100;
}

#header .logo {
    color: var(--primary);
    font-weight: 700;
    font-size: 2em;
    text-decoration: none;
}

#header ul {
    display: flex;
    justify-content: center;
    align-items: center;
}

#header ul li {
    list-style: none;
    margin-left: 20px;
}

#header ul li a {
    text-decoration: none;
    padding: 6px 15px;
    color: var(--primary);
    border-radius: 20px;
}

#header ul li a:hover,
#header ul li a.active {
    background: var(--primary);
    color: #fff;
}

section {
    position: relative;
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

section::before {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100px;
    background: linear-gradient(to top, var(--primary), transparent);
    z-index: 10;
}

section img {
    position: absolute;
    top: 0px;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    pointer-events: none;
}

section #text {
    position: absolute;
    color: var(--primary);
    font-size: 10vw;
    text-align: center;
    line-height: .55em;
    font-family: 'Rancho', cursive;
    transform: translatey(-50%);
}

section #text span {
    font-size: .20em;
    letter-spacing: 2px;
    font-weight: 400;
}

#btn {
    text-decoration: none;
    display: inline-block;
    padding: 8px 30px;
    background: #fff;
    color: var(--primary);
    font-size: 1.2em;
    font-weight: 500;
    letter-spacing: 2px;
    border-radius: 40px;
    transform: translatey(100px);
}

.sec {
    position: relative;
    padding: 100px;
    background: var(--primary);
}

.sec h2 {
    font-size: 3.5em;
    color: #fff;
    margin-bottom: 10px;
}

.sec p {
    font-size: 1em;
    color: #fff;
}

footer {
    position: relative;
    padding: 0px 100px;
    background: var(--primary);
}

footer a {
    text-decoration: none;
    color: #fff;
}
  </style>

</head>
<body>
<!-- partial:index.partial.html -->
<header id="header">
        <a href="" class="logo">Galley foto</a>
        <ul>
            <li><a href="login.php">login</a></li>
        </ul>
    </header>
    
    <Section>
        <h2 id="text"><span>Wellcome to my</span><br>Gallery</h2>
        
        <img src="https://user-images.githubusercontent.com/65358991/170092504-132fa547-5ced-40e5-ab64-ded61518fac2.png" id="bird1">
        <img src="https://user-images.githubusercontent.com/65358991/170092542-9747edcc-fb51-4e21-aaf5-a61119393618.png" id="bird2">
        <img src="https://user-images.githubusercontent.com/65358991/170092559-883fe071-eb4f-4610-8c8b-a037d061c617.png" id="forest">
        <img src="https://user-images.githubusercontent.com/65358991/170092605-eada6510-d556-45cc-b7fa-9e4d1d258d26.png" id="rocks">
        <img src="https://user-images.githubusercontent.com/65358991/170092616-5a70c4af-2eed-496f-bde9-b5fcc7142a31.png" id="water">
    </Section>
    
    <div class="sec">
       
    </div>
    <div class="flex-1 p-8">
    <!-- Page Content -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-3xl font-bold">Albums foto</h2>
       
    </div>
<!-- partial -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php foreach ($albums as $album) : ?>
            <div class="bg-white p-4 rounded shadow-md">
                <a href="/user/?php echo $album['album_id']; ?>">
                    <!-- Tambahkan foto album atau placeholder jika album tidak memiliki foto -->
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM photos WHERE album_id = :album_id LIMIT 1");
                    $stmt->bindParam(':album_id', $album['album_id'], PDO::PARAM_INT);
                    $stmt->execute();
                    $photo = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($photo) {
                        // Check if 'photo_path' index exists in the $photo array
                        if (isset($photo['photo_path'])) {
                            echo '<img src="/.uploads/' . $photo['photo_path'] . '" alt="" class="rounded shadow-md">';
                        } else {
                            echo '<div class="w-full h-32 bg-gray-300"></div>'; // Placeholder if the album does not have a photo_path
                        }
                    } else {
                        // Handle the case when $photo is not defined or empty
                        echo '<div class="w-full h-32 bg-gray-300"></div>';
                    }
                    
                    ?>
                    <h3 class="text-xl font-semibold mb-2"><?php echo $album['title']; ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo $album['description']; ?></p>
                    <small class="text-gray-500">Created at: <?php echo $album['created_at']; ?></small>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
  <script>
    "use strict";
let text = document.getElementById('text');
let bird1 = document.getElementById('bird1');
let bird2 = document.getElementById('bird2');
let btn = document.getElementById('btn');
let rocks = document.getElementById('rocks');
let forest = document.getElementById('forest');
let water = document.getElementById('water');
let header = document.getElementById('header');
window.addEventListener('scroll', function () {
    let value = window.scrollY;
    text.style.top = 50 + value * -.1 + '%';
    bird2.style.top = value * -1.5 + 'px';
    bird2.style.left = value * 2 + 'px';
    bird1.style.top = value * -1.5 + 'px';
    bird1.style.left = value * -5 + 'px';
    btn.style.marginTop = value * 1.5 + 'px';
    rocks.style.top = value * -.12 + 'px';
    forest.style.top = value * .25 + 'px';
    header.style.top = value * .5 + 'px';
});
  </script>

</body>
</html>
