<?php
require_once '../../src/database.php';
require_once '../../src/functions.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$player_id = $_GET['id'] ?? null;

if (!$player_id) {
    header('Location: players.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    updatePlayer($player_id, $_POST, $_FILES);
    header('Location: players.php');
    exit;
}

$player = getPlayerById($player_id);

include 'templates/header.php';
?>

<h2 class="text-3xl font-bold mb-4 text-primary">Edit Player</h2>

<form method="POST" enctype="multipart/form-data" class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="mb-4">
            <label class="block text-secondary text-sm font-bold mb-2" for="name">Player Name</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Player Name" value="<?php echo htmlspecialchars($player['name']); ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-secondary text-sm font-bold mb-2" for="position">Position</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="position" name="position" type="text" placeholder="e.g., Midfielder" value="<?php echo htmlspecialchars($player['position']); ?>">
        </div>
        <div class="mb-4">
            <label class="block text-secondary text-sm font-bold mb-2" for="photo">Player Photo</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="photo" name="photo" type="file">
            <?php if ($player['photo']): ?>
                <img src="../<?php echo htmlspecialchars($player['photo']); ?>" alt="<?php echo htmlspecialchars($player['name']); ?>" class="h-24 w-24 object-cover rounded-full mt-2">
            <?php endif; ?>
        </div>
    </div>
    <div class="flex items-center justify-end">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Update Player</button>
    </div>
</form>
