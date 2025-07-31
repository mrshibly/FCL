<?php
require_once '../src/database.php';
require_once '../src/functions.php';

$season_id = $_GET['id'] ?? null;

if (!$season_id) {
    header('Location: seasons.php');
    exit;
}

$season = getSeasonById($season_id);
$matches = getMatchesBySeason($season_id);

?>

<div class="container">
    <h2><?php echo htmlspecialchars($season['name']); ?> Matches</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Home Team</th>
                <th>Away Team</th>
                <th>Score</th>
                <th>Venue</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($matches as $match): ?>
                <tr>
                    <td><?php echo htmlspecialchars($match['match_date']); ?></td>
                    <td><a href="index.php?page=team_profile&id=<?php echo $match['team1_id']; ?>"><?php echo htmlspecialchars($match['team1_name']); ?></a></td>
                    <td><a href="index.php?page=team_profile&id=<?php echo $match['team2_id']; ?>"><?php echo htmlspecialchars($match['team2_name']); ?></a></td>
                    <td><a href="index.php?page=match_details&id=<?php echo $match['id']; ?>"><?php echo htmlspecialchars($match['team1_score']); ?> - <?php echo htmlspecialchars($match['team2_score']); ?></a></td>
                    <td><?php echo htmlspecialchars($match['venue']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
