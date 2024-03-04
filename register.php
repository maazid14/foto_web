<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h1 class="text-2xl font-bold mb-6">Register</h1>

        <form action="process_register.php" method="post">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-600">Name:</label>
                <input type="text" name="name" id="name" class="mt-1 p-2 w-full border rounded focus:outline-none focus:ring focus:border-blue-300" required>
            </div>

            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-600">Username:</label>
                <input type="text" name="username" id="username" class="mt-1 p-2 w-full border rounded focus:outline-none focus:ring focus:border-blue-300" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-600">Password:</label>
                <input type="password" name="password" id="password" class="mt-1 p-2 w-full border rounded focus:outline-none focus:ring focus:border-blue-300" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-600">Email:</label>
                <input type="email" name="email" id="email" class="mt-1 p-2 w-full border rounded focus:outline-none focus:ring focus:border-blue-300" required>
            </div>

            <!-- <div class="mb-4">
                <label for="acces_level" class="block text-sm font-medium text-gray-600">Access Level:</label>
                <select name="text" id="acces_level" class="mt-1 p-2 w-full border rounded focus:outline-none focus:ring focus:border-blue-300" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div> -->
            <label for="Akses level"></label>
            <select class="" name="acces_level" required>
                <option value="" selected hiden>Select Akses Level</option>
              
                <option value="user">user</option>
            </select>
                <br>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Register</button>
        </form>

        <p class="mt-4 text-sm">Sudah punya akun? <a href="login.php" class="text-blue-500">Login disini</a></p>
    </div>
</body>
</html>
