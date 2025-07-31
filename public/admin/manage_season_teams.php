<?php
require_once '../../src/database.php';
require_once '../../src/functions.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$season_id = $_GET['season_id'] ?? null;

if (!$season_id) {
    header('Location: seasons.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_team'])) {
        addTeamToSeason($_POST['team_id'], $season_id);
        header('Location: manage_season_teams.php?season_id=' . $season_id);
        exit;
    } elseif (isset($_POST['remove_team'])) {
        removeTeamFromSeason($_POST['team_id'], $season_id);
        header('Location: manage_season_teams.php?season_id=' . $season_id);
        exit;
    }
}

$season = getSeasonById($season_id);
$season_teams = getTeamsBySeason($season_id);
$all_teams = getTeams();

include 'templates/header.php';
?>

<h2 class="text-3xl font-bold mb-4 text-primary">Manage Teams for <?php echo htmlspecialchars($season['name']); ?></h2>

<!-- Add Team to Season Form -->
<div class="mb-8">
    <h3 class="text-2xl font-bold mb-4 text-primary">Add Team to Season</h3>
    <form method="POST" class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="team_id">Team</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="team_id" name="team_id" required>
                    <option value="">Select a team</option>
                    <?php foreach ($all_teams as $team): ?>
                        <option value="<?php echo $team['id']; ?>"><?php echo htmlspecialchars($team['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="flex items-center justify-end">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="add_team">Add Team</button>
        </div>
    </form>
</div>

<!-- Teams in Season Table -->
<div class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h3 class="text-2xl font-bold mb-4 text-primary">Teams in this Season</h3>
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-700">
                <th class="py-2 text-secondary">Name</th>
                <th class="py-2 text-secondary">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($season_teams as $team): ?>
                <tr class="border-b border-gray-700 last:border-b-0">
                    <td class="py-2 font-bold text-primary"><?php echo htmlspecialchars($team['name']); ?></td>
                    <td class="py-2">
                        <form method="POST" class="inline-block">
                            <input type="hidden" name="team_id" value="<?php echo $team['id']; ?>">
                            <button class="text-red-500 hover:text-red-700" type="submit" name="remove_team">Remove</button>
                        </form>
                        <a href="manage_season_players.php?season_id=<?php echo $season_id; ?>&team_id=<?php echo $team['id']; ?>" class="text-green-500 hover:text-green-700 ml-2">Manage Players</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
