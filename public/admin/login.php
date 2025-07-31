<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

require_once '../../src/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = getDBConnection(); // Use the PDO connection

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        // Assuming password is MD5 hashed in the database for now
        // For production, use password_verify with password_hash
        if (MD5($password) === $admin['password']) { // Simplified check for MD5
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: index.php');
            exit();
        }
    }

    $error = 'Invalid username or password';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen">
    <div class="w-full max-w-xs">
        <form method="POST" class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-2xl font-bold mb-4 text-primary text-center">Admin Login</h2>
            <?php if (isset($error)): ?>
                <p class="text-red-500 text-xs italic mb-4"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="username">
                    Username
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="username" name="username" type="text" placeholder="Username">
            </div>
            <div class="mb-6">
                <label class="block text-secondary text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="******************">
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Sign In
                </button>
            </div>
        </form>
    </div>
</body>
</html>