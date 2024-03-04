

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil_admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 font-sans">

    <!-- Include Gallery Navbar -->
    <?php include('../admin/gallery_navbar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <!-- Page Content -->
        <h2 class="text-3xl font-bold mb-4">Hai Admin</h2>

        <!-- Users Table -->
     <!-- Users Table -->
<table class="min-w-full bg-white border border-gray-300 shadow-md rounded">
    <thead>
        <tr>
            <th class="py-2 px-4 border-b text-center">Name</th>
            <th class="py-2 px-4 border-b text-center">Email</th>
            <th class="py-2 px-4 border-b text-center">Alamat</th>
            <th class="py-2 px-4 border-b text-center">Tanggal Lahir</th>
        </tr>
    </thead>
    <tbody>
       
            <tr class="text-center">
                <td class="py-2 px-4 border-b">Mazidu nurusiam</td>
                <td class="py-2 px-4 border-b">mazid@gmail.com</td>
                <td class="py-2 px-4 border-b">Kota Banjar,Desa Balokang,Dusun Ciaren</td>
                <td class="py-2 px-4 border-b">08-10-2005</td>
             
                   
                </td>
            </tr>
            
       
    </tbody>
</table>

    </div>

</body>
</html>
