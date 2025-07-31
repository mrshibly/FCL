<?php
require_once '../src/database.php';
require_once '../src/functions.php';

$player_id = $_GET['id'] ?? null;

if (!$player_id) {
    header('Location: players.php');
    exit;
}

$player = getPlayerById($player_id);

?>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <img src="<?php echo htmlspecialchars($player['photo'] ?? 'images/default_player.png'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($player['name']); ?>">
        </div>
        <div class="col-md-8">
            <h2><?php echo htmlspecialchars($player['name']); ?></h2>
            <p><strong>Team:</strong> <a href="team_profile.php?id=<?php echo $player['team_id']; ?>"><?php echo htmlspecialchars($player['team_name']); ?></a></p>
            <p><strong>Jersey Number:</strong> <?php echo htmlspecialchars($player['jersey_number']); ?></p>
            <p><strong>Position:</strong> <?php echo htmlspecialchars($player['position']); ?></p>
            <hr>
            <h4>Stats</h4>
            <p><strong>Matches Played:</strong> <?php echo htmlspecialchars($player['matches_played']); ?></p>
            <p><strong>Goals:</strong> <?php echo htmlspecialchars($player['goals']); ?></p>
            <p><strong>Assists:</strong> <?php echo htmlspecialchars($player['assists']); ?></p>
            <p><strong>Yellow Cards:</strong> <?php echo htmlspecialchars($player['yellow_cards']); ?></p>
            <p><strong>Red Cards:</strong> <?php echo htmlspecialchars($player['red_cards']); ?></p>
            <p><strong>MVP Awards:</strong> <?php echo htmlspecialchars($player['mvp_awards']); ?></p>
        </div>
    </div>
</div>
