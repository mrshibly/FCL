<?php
require_once '../../src/database.php';
require_once '../../src/functions.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$season_id = $_GET['season_id'] ?? null;
$team_id = $_GET['team_id'] ?? null;

if (!$season_id || !$team_id) {
    header('Location: seasons.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_player'])) {
        addPlayerToSeason($_POST['player_id'], $season_id, $team_id, $_POST['jersey_number']);
        header('Location: manage_season_players.php?season_id=' . $season_id . '&team_id=' . $team_id);
        exit;
    } elseif (isset($_POST['remove_player'])) {
        removePlayerFromSeason($_POST['player_id'], $season_id, $team_id);
        header('Location: manage_season_players.php?season_id=' . $season_id . '&team_id=' . $team_id);
        exit;
    }
}

$season = getSeasonById($season_id);
$team = getTeamById($team_id);
$season_players = getPlayersBySeasonAndTeam($season_id, $team_id);
$all_players = getPlayers();

include 'templates/header.php';
?>

<h2 class="text-3xl font-bold mb-4 text-primary">Manage Players for <?php echo htmlspecialchars($team['name']); ?> in <?php echo htmlspecialchars($season['name']); ?></h2>

<!-- Add Player to Season Form -->
<div class="mb-8">
    <h3 class="text-2xl font-bold mb-4 text-primary">Add Player to Roster</h3>
    <form method="POST" class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="player_id">Player</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="player_id" name="player_id" required>
                    <option value="">Select a player</option>
                    <?php foreach ($all_players as $player): ?>
                        <option value="<?php echo $player['id']; ?>"><?php echo htmlspecialchars($player['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="jersey_number">Jersey Number</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="jersey_number" name="jersey_number" type="number" placeholder="e.g., 10">
            </div>
        </div>
        <div class="flex items-center justify-end">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="add_player">Add Player</button>
        </div>
    </form>
</div>

<!-- Players in Season Table -->
<div class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h3 class="text-2xl font-bold mb-4 text-primary">Roster for this Season</h3>
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-700">
                <th class="py-2 text-secondary">Name</th>
                <th class="py-2 text-secondary">Jersey Number</th>
                <th class="py-2 text-secondary">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($season_players as $player): ?>
                <tr class="border-b border-gray-700 last:border-b-0">
                    <td class="py-2 font-bold text-primary"><?php echo htmlspecialchars($player['name']); ?></td>
                    <td class="py-2 text-primary"><?php echo htmlspecialchars($player['jersey_number']); ?></td>
                    <td class="py-2">
                        <form method="POST" class="inline-block">
                            <input type="hidden" name="player_id" value="<?php echo $player['id']; ?>">
                            <button class="text-red-500 hover:text-red-700" type="submit" name="remove_player">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
