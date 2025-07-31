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
    if (isset($_POST['add_team'])) {
        addTeam($_POST, $_FILES);
        header('Location: teams.php');
        exit;
    } elseif (isset($_POST['delete_team'])) {
        deleteTeam($_POST['id']);
        header('Location: teams.php');
        exit;
    }
}

$teams = getTeams();

include 'templates/header.php';
?>

<h2 class="text-3xl font-bold mb-4 text-primary">Manage Teams</h2>

<!-- Add Team Form -->
<div class="mb-8">
    <h3 class="text-2xl font-bold mb-4 text-primary">Add New Team</h3>
    <form method="POST" enctype="multipart/form-data" class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-secondary text-sm font-bold mb-2" for="name">
                Team Name
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Team Name">
        </div>
        <div class="mb-4">
            <label class="block text-secondary text-sm font-bold mb-2" for="logo">
                Team Logo
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="logo" name="logo" type="file">
        </div>
        <div class="flex items-center justify-end">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="add_team">
                Add Team
            </button>
        </div>
    </form>
</div>

<!-- Teams Table -->
<div class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h3 class="text-2xl font-bold mb-4 text-primary">All Teams</h3>
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-700">
                <th class="py-2 text-secondary">ID</th>
                <th class="py-2 text-secondary">Logo</th>
                <th class="py-2 text-secondary">Name</th>
                <th class="py-2 text-secondary">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($teams as $team): ?>
                <tr class="border-b border-gray-700 last:border-b-0">
                    <td class="py-2 text-primary"><?php echo $team['id']; ?></td>
                    <td class="py-2"><img src="../images/<?php echo $team['logo']; ?>" alt="<?php echo $team['name']; ?>" class="h-8"></td>
                    <td class="py-2 text-primary"><?php echo $team['name']; ?></td>
                    <td class="py-2">
                        <form method="POST" class="inline-block">
                            <input type="hidden" name="id" value="<?php echo $team['id']; ?>">
                            <button class="text-red-500 hover:text-red-700" type="submit" name="delete_team">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
