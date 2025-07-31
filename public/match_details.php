<?php
require_once '../src/database.php';
require_once '../src/functions.php';

$match_id = $_GET['id'] ?? null;

if (!$match_id) {
    header('Location: index.php');
    exit;
}

$match = getMatchDetails($match_id);
$events = getMatchEvents($match_id);
$lineups = getMatchLineups($match_id);

?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="card-title"><?php echo htmlspecialchars($match['team1_name']); ?> vs <?php echo htmlspecialchars($match['team2_name']); ?></h2>
                    <p class="card-text"><?php echo date('F j, Y, g:i a', strtotime($match['match_date'])); ?></p>
                    <p class="card-text">Venue: <?php echo htmlspecialchars($match['venue']); ?></p>
                    <h3><?php echo htmlspecialchars($match['team1_score']); ?> - <?php echo htmlspecialchars($match['team2_score']); ?></h3>
                    <?php if ($match['mvp_name']): ?>
                        <p><strong>MVP:</strong> <?php echo htmlspecialchars($match['mvp_name']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h3><?php echo htmlspecialchars($match['team1_name']); ?> Lineup</h3>
            <ul class="list-group">
                <?php foreach ($lineups['team1'] as $player): ?>
                    <li class="list-group-item"><?php echo htmlspecialchars($player['name']); ?> (<?php echo htmlspecialchars($player['position']); ?>)</li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-6">
            <h3><?php echo htmlspecialchars($match['team2_name']); ?> Lineup</h3>
            <ul class="list-group">
                <?php foreach ($lineups['team2'] as $player): ?>
                    <li class="list-group-item"><?php echo htmlspecialchars($player['name']); ?> (<?php echo htmlspecialchars($player['position']); ?>)</li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h3>Match Events</h3>
            <ul class="list-group">
                <?php foreach ($events as $event): ?>
                    <li class="list-group-item">
                        <?php echo htmlspecialchars($event['minute']); ?>' - <?php echo htmlspecialchars($event['event_type']); ?> by <?php echo htmlspecialchars($event['player_name']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
