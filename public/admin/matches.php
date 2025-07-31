<?php
require_once '../../src/database.php';
require_once '../../src/functions.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$active_season = getActiveSeason();

if (!$active_season) {
    // Redirect or show an error if no active season is set
    echo "<div class=\"bg-red-500 text-white p-4 rounded-md\">No active season. Please set an active season to manage matches.</div>";
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_match'])) {
        $team1_id = $_POST['team1_id'];
        $team2_id = $_POST['team2_id'];
        $match_date = $_POST['match_date'];
        $venue = $_POST['venue'];
        $season_id = $active_season['id'];

        addMatch($season_id, $team1_id, $team2_id, $match_date, $venue);
        header('Location: matches.php');
        exit;
    } elseif (isset($_POST['delete_match'])) {
        deleteMatch($_POST['id']);
        header('Location: matches.php');
        exit;
    } elseif (isset($_POST['update_result'])) {
        $match_id = $_POST['match_id'];
        $team1_score = $_POST['team1_score'];
        $team2_score = $_POST['team2_score'];
        $mvp_id = $_POST['mvp_id'] === '' ? NULL : $_POST['mvp_id'];
        $status = $_POST['status'];

        updateMatchResult($match_id, $team1_score, $team2_score, $mvp_id, $status);
        header('Location: matches.php');
        exit;
    } elseif (isset($_POST['add_event'])) {
        $match_id = $_POST['match_id'];
        $player_id = $_POST['player_id'];
        $event_type = $_POST['event_type'];
        $minute = $_POST['minute'];

        addMatchEvent($match_id, $player_id, $event_type, $minute);
        header('Location: matches.php');
        exit;
    }
}

$teams = getTeamsBySeason($active_season['id']);
$players = getPlayersBySeason($active_season['id']);
$matches = getMatchesBySeason($active_season['id']);

include 'templates/header.php';
?>

<h2 class="text-3xl font-bold mb-4 text-primary">Manage Matches (Season: <?php echo htmlspecialchars($active_season['name']); ?>)</h2>

<!-- Add Match Form -->
<div class="mb-8">
    <h3 class="text-2xl font-bold mb-4 text-primary">Add New Match</h3>
    <form method="POST" class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="team1_id">
                    Home Team
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="team1_id" name="team1_id" required>
                    <option value="">Select Home Team</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?php echo $team['id']; ?>"><?php echo htmlspecialchars($team['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="team2_id">
                    Away Team
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="team2_id" name="team2_id" required>
                    <option value="">Select Away Team</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?php echo $team['id']; ?>"><?php echo htmlspecialchars($team['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="match_date">
                    Match Date & Time
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="match_date" name="match_date" type="datetime-local" required>
            </div>
            <div class="mb-4">
                <label class="block text-secondary text-sm font-bold mb-2" for="venue">
                    Venue
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="venue" name="venue" type="text" placeholder="Venue" required>
            </div>
        </div>
        <div class="flex items-center justify-end">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="add_match">
                Add Match
            </button>
        </div>
    </form>
</div>

<!-- Matches Table -->
<div class="bg-primary shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h3 class="text-2xl font-bold mb-4 text-primary">All Matches</h3>
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-700">
                <th class="py-2 text-secondary">ID</th>
                <th class="py-2 text-secondary">Home Team</th>
                <th class="py-2 text-secondary">Away Team</th>
                <th class="py-2 text-secondary">Date & Time</th>
                <th class="py-2 text-secondary">Venue</th>
                <th class="py-2 text-secondary">Score</th>
                <th class="py-2 text-secondary">Status</th>
                <th class="py-2 text-secondary">MVP</th>
                <th class="py-2 text-secondary">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($matches)): ?>
                <tr>
                    <td colspan="9" class="py-4 text-center text-secondary">No matches have been added yet for this season.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($matches as $match): ?>
                    <tr class="border-b border-gray-700 last:border-b-0">
                        <td class="py-2 text-primary"><?php echo $match['id']; ?></td>
                        <td class="py-2 font-bold text-primary"><?php echo htmlspecialchars($match['team1_name']); ?></td>
                        <td class="py-2 font-bold text-primary"><?php echo htmlspecialchars($match['team2_name']); ?></td>
                        <td class="py-2 text-primary"><?php echo date('Y-m-d H:i', strtotime($match['match_date'])); ?></td>
                        <td class="py-2 text-primary"><?php echo htmlspecialchars($match['venue']); ?></td>
                        <td class="py-2 text-primary"><?php echo ($match['team1_score'] !== null && $match['team2_score'] !== null) ? htmlspecialchars($match['team1_score']) . ' - ' . htmlspecialchars($match['team2_score']) : 'N/A'; ?></td>
                        <td class="py-2 text-primary"><?php echo htmlspecialchars(ucfirst($match['status'])); ?></td>
                        <td class="py-2 text-primary"><?php echo htmlspecialchars($match['mvp_name'] ?? 'N/A'); ?></td>
                        <td class="py-2">
                            <form method="POST" class="inline-block">
                                <input type="hidden" name="id" value="<?php echo $match['id']; ?>">
                                <button class="text-red-500 hover:text-red-700" type="submit" name="delete_match">Delete</button>
                            </form>
                            <button onclick="toggleResultForm(<?php echo $match['id']; ?>)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-sm">Update Result</button>
                            <button onclick="toggleEventForm(<?php echo $match['id']; ?>)" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-2 rounded text-sm ml-2">Add Event</button>
                        </td>
                    </tr>
                    <tr id="resultForm-<?php echo $match['id']; ?>" class="hidden">
                        <td colspan="9" class="p-4 bg-gray-700">
                            <form method="POST" class="flex items-center space-x-4">
                                <input type="hidden" name="match_id" value="<?php echo $match['id']; ?>">
                                <div class="flex-1">
                                    <label class="block text-secondary text-sm font-bold mb-2" for="team1_score-<?php echo $match['id']; ?>">Score</label>
                                    <div class="flex space-x-2">
                                        <input type="number" id="team1_score-<?php echo $match['id']; ?>" name="team1_score" value="<?php echo $match['team1_score'] ?? ''; ?>" class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" placeholder="<?php echo $match['team1_name']; ?> Score">
                                        <input type="number" id="team2_score-<?php echo $match['id']; ?>" name="team2_score" value="<?php echo $match['team2_score'] ?? ''; ?>" class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" placeholder="<?php echo $match['team2_name']; ?> Score">
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-secondary text-sm font-bold mb-2" for="status-<?php echo $match['id']; ?>">Status</label>
                                    <select id="status-<?php echo $match['id']; ?>" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="upcoming" <?php echo ($match['status'] === 'upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                                        <option value="live" <?php echo ($match['status'] === 'live') ? 'selected' : ''; ?>>Live</option>
                                        <option value="finished" <?php echo ($match['status'] === 'finished') ? 'selected' : ''; ?>>Finished</option>
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-secondary text-sm font-bold mb-2" for="mvp_id-<?php echo $match['id']; ?>">MVP</label>
                                    <select id="mvp_id-<?php echo $match['id']; ?>" name="mvp_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select MVP</option>
                                        <?php foreach ($players as $player): ?>
                                            <option value="<?php echo $player['id']; ?>" <?php echo ($match['mvp_id'] == $player['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($player['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" name="update_result" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save Result</button>
                            </form>
                        </td>
                    </tr>
                    <tr id="eventForm-<?php echo $match['id']; ?>" class="hidden">
                        <td colspan="9" class="p-4 bg-gray-700">
                            <form method="POST" class="flex items-center space-x-4">
                                <input type="hidden" name="match_id" value="<?php echo $match['id']; ?>">
                                <div class="flex-1">
                                    <label class="block text-secondary text-sm font-bold mb-2" for="player_id-event-<?php echo $match['id']; ?>">Player</label>
                                    <select id="player_id-event-<?php echo $match['id']; ?>" name="player_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" required>
                                        <option value="">Select Player</option>
                                        <?php foreach ($players as $player): ?>
                                            <option value="<?php echo $player['id']; ?>"><?php echo htmlspecialchars($player['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-secondary text-sm font-bold mb-2" for="event_type-<?php echo $match['id']; ?>">Event Type</label>
                                    <select id="event_type-<?php echo $match['id']; ?>" name="event_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" required>
                                        <option value="">Select Event</option>
                                        <option value="goal">Goal</option>
                                        <option value="assist">Assist</option>
                                        <option value="yellow_card">Yellow Card</option>
                                        <option value="red_card">Red Card</option>
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-secondary text-sm font-bold mb-2" for="minute-event-<?php echo $match['id']; ?>">Minute</label>
                                    <input type="number" id="minute-event-<?php echo $match['id']; ?>" name="minute" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" placeholder="Minute" min="1" max="120" required>
                                </div>
                                <button type="submit" name="add_event" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Event</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function toggleResultForm(matchId) {
    const formRow = document.getElementById(`resultForm-${matchId}`);
    formRow.classList.toggle('hidden');
    // Hide event form if open
    document.getElementById(`eventForm-${matchId}`).classList.add('hidden');
}

function toggleEventForm(matchId) {
    const formRow = document.getElementById(`eventForm-${matchId}`);
    formRow.classList.toggle('hidden');
    // Hide result form if open
    document.getElementById(`resultForm-${matchId}`).classList.add('hidden');
}
</script>