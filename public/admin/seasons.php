<?php
require_once '../../src/database.php';
require_once '../../src/functions.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_season'])) {
        addSeason($_POST);
        header('Location: seasons.php');
        exit;
    } elseif (isset($_POST['set_active'])) {
        setActiveSeason($_POST['id']);
        header('Location: seasons.php');
        exit;
    }
}

$seasons = getSeasons();

include 'templates/header.php';
?>

<h2 class="text-3xl font-bold mb-4 text-primary">Manage Seasons</h2>

<!-- Add Season Form -->
<div class="mb-8">
    <h3 class="text-2xl font-bold mb-4 text-primary">Add New Season</h3>
    <form method="POST" class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="name">Season Name</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="e.g., 2025-2026" required>
            </div>
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="start_date">Start Date</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="start_date" name="start_date" type="date">
            </div>
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="end_date">End Date</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="end_date" name="end_date" type="date">
            </div>
        </div>
        <div class="flex items-center justify-end">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="add_season">Add Season</button>
        </div>
    </form>
</div>

<!-- Seasons Table -->
<div class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h3 class="text-2xl font-bold mb-4 text-primary">All Seasons</h3>
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-700">
                <th class="py-2 text-secondary">Name</th>
                <th class="py-2 text-secondary">Start Date</th>
                <th class="py-2 text-secondary">End Date</th>
                <th class="py-2 text-secondary">Status</th>
                <th class="py-2 text-secondary">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($seasons as $season): ?>
                <tr class="border-b border-gray-700 last:border-b-0">
                    <td class="py-2 font-bold text-primary"><?php echo htmlspecialchars($season['name']); ?></td>
                    <td class="py-2 text-primary"><?php echo htmlspecialchars($season['start_date']); ?></td>
                    <td class="py-2 text-primary"><?php echo htmlspecialchars($season['end_date']); ?></td>
                    <td class="py-2 text-primary"><?php echo $season['is_active'] ? '<span class="text-green-500">Active</span>' : 'Inactive'; ?></td>
                    <td class="py-2">
                        <?php if (!$season['is_active']): ?>
                            <form method="POST" class="inline-block">
                                <input type="hidden" name="id" value="<?php echo $season['id']; ?>">
                                <button class="text-blue-500 hover:text-blue-700" type="submit" name="set_active">Set Active</button>
                            </form>
                        <?php endif; ?>
                        <a href="manage_season_teams.php?season_id=<?php echo $season['id']; ?>" class="text-green-500 hover:text-green-700 ml-2">Manage Teams</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
