<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Responsive Sidebar Example</title>
<style>
body {
    font-family: Arial, sans-serif,;
    margin: 0;
    padding: 0;
}

.sidebar {
    height: 100%;
    width: 250px; /* Lebar sidebar */
    position: fixed;
    top: 0;
    left: -250px; /* Sembunyikan sidebar secara default */
    background-color: grey; /* Warna latar belakang sidebar */
    transition: left 0.3s ease;
    padding-top: 20px;
}

.sidebar.active {
    left: 0; /* Tampilkan sidebar ketika aktif */
}

.sidebar ul {
    list-style-type: none;
    padding:40px;
    margin: 0;
}

.sidebar ul li {
    padding: 8px 16px;
    color: #fff; /* Warna teks pada menu */
}

.sidebar ul li:hover {
    background-color: #555; /* Warna latar belakang menu saat dihover */
}

.content {
    margin-left: 250px; /* Lebar sidebar */
    padding: 20px;
}

/* Untuk tombol toggle */
.toggle-btn {
    position: fixed;
    top: 20px;
    left: 20px;
    cursor: pointer;
    z-index: 999;
    color: #555;
  
}

.toggle-btn span {
    display: block;
    width: 30px;
    height: 5px;
    background-color: #fff;
    margin-bottom: 5px;
    
}

/* Untuk responsif */
@media screen and (max-width: 600px) {
    .sidebar {
        width: 100%;
    }
    .content {
        margin-left: 0;
    }
}
</style>
</head>
<body>

<div class="sidebar" id="sidebar">


    <ul>
        <li><a href="home_admin.php">album</a></li>
        <li><a href="profil_admin.php">profile</a></li>
        <li><a href="logout.php">logout</a></li>

        <!-- Tambahkan menu sesuai kebutuhan -->
    </ul>
</div>

<div class="content">
    <div class="toggle-btn" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <h2>HALLO ADMIN</h2>
    <p>Ini ADALAH HALAMAN KHUSUS ADMIN</p>
</div>

<script>
function toggleSidebar() {
    var sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("active");
}
</script>

</body>
</html>
