<?php
require_once '../../src/database.php';
require_once '../../src/functions.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$active_season = getActiveSeason();
$stats = getDashboardStats($active_season['id'] ?? null);

// The main admin template header includes the sidebar navigation
include 'templates/header.php';
?>

<h1 class="text-4xl font-bold text-primary mb-6">Admin Dashboard</h1>

<?php if ($active_season): ?>
    <div class="bg-primary shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-bold text-accent mb-4">Active Season: <?php echo htmlspecialchars($active_season['name']); ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-700 p-4 rounded-lg text-center">
                <p class="text-4xl font-bold"><?php echo $stats['team_count']; ?></p>
                <p class="text-secondary">Teams</p>
            </div>
            <div class="bg-gray-700 p-4 rounded-lg text-center">
                <p class="text-4xl font-bold"><?php echo $stats['player_count']; ?></p>
                <p class="text-secondary">Players</p>
            </div>
            <div class="bg-gray-700 p-4 rounded-lg text-center">
                <p class="text-4xl font-bold"><?php echo $stats['match_count']; ?></p>
                <p class="text-secondary">Matches</p>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="bg-yellow-900 text-white p-6 rounded-lg mb-8">
        <h2 class="text-2xl font-bold">No Active Season</h2>
        <p>There is no active season set. Please go to the <a href="seasons.php" class="underline font-bold">Seasons</a> page to create or activate a season to begin managing the tournament.</p>
    </div>
<?php endif; ?>

<div class="bg-primary shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-primary mb-4">Quick Actions</h2>
    <div class="flex flex-wrap gap-4">
        <a href="seasons.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Manage Seasons</a>
        <a href="teams.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Manage Teams</a>
        <a href="players.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Manage Players</a>
        <a href="matches.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Manage Matches</a>
    </div>
</div>


<?php include 'templates/footer.php'; ?>
