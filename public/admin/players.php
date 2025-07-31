<?php
require_once '../../src/database.php';
require_once '../../src/functions.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_player'])) {
        addPlayer($_POST, $_FILES);
        header('Location: players.php');
        exit;
    } elseif (isset($_POST['delete_player'])) {
        deletePlayer($_POST['id']);
        header('Location: players.php');
        exit;
    }
}

$players = getPlayers(); // Get all players, not season-specific

include 'templates/header.php';
?>

<h2 class="text-3xl font-bold mb-4 text-primary">Manage Players</h2>

<!-- Add Player Form -->
<div class="mb-8">
    <h3 class="text-2xl font-bold mb-4 text-primary">Add New Player</h3>
    <form method="POST" enctype="multipart/form-data" class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="name">Player Name</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Player Name" required>
            </div>
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="position">Position</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="position" name="position" type="text" placeholder="e.g., Midfielder">
            </div>
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="photo">Player Photo</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="photo" name="photo" type="file">
            </div>
        </div>
        <div class="flex items-center justify-end">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="add_player">Add Player</button>
        </div>
    </form>
</div>

<!-- Players Table -->
<div class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h3 class="text-2xl font-bold mb-4 text-primary">All Players</h3>
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-700">
                <th class="py-2 text-secondary">Photo</th>
                <th class="py-2 text-secondary">Name</th>
                <th class="py-2 text-secondary">Position</th>
                <th class="py-2 text-secondary">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($players as $player): ?>
                <tr class="border-b border-gray-700 last:border-b-0">
                    <td class="py-2"><img src="../<?php echo htmlspecialchars($player['photo'] ?? 'images/default_player.png'); ?>" alt="<?php echo htmlspecialchars($player['name']); ?>" class="h-12 w-12 object-cover rounded-full"></td>
                    <td class="py-2 font-bold text-primary"><?php echo htmlspecialchars($player['name']); ?></td>
                    <td class="py-2 text-primary"><?php echo htmlspecialchars($player['position']); ?></td>
                    <td class="py-2">
                        <a href="edit_player.php?id=<?php echo $player['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-2">Edit</a>
                        <form method="POST" class="inline-block">
                            <input type="hidden" name="id" value="<?php echo $player['id']; ?>">
                            <button class="text-red-500 hover:text-red-700" type="submit" name="delete_player">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
