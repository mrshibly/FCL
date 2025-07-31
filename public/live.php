<?php
require_once '../src/database.php';
require_once '../src/functions.php';

$live_matches = getLiveMatches();

?>

<div class="container">
    <h2>Live Matches</h2>
    <?php if (empty($live_matches)): ?>
        <p>There are no live matches at the moment.</p>
    <?php else: ?>
        <?php foreach ($live_matches as $match): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($match['team1_name']); ?> vs <?php echo htmlspecialchars($match['team2_name']); ?></h5>
                    <p class="card-text">Score: <?php echo htmlspecialchars($match['team1_score']); ?> - <?php echo htmlspecialchars($match['team2_score']); ?></p>
                    <p class="card-text">Goalscorers:</p>
                    <ul>
                        <?php 
                        $goalscorers = getGoalscorers($match['id']);
                        foreach ($goalscorers as $scorer): ?>
                            <li><?php echo htmlspecialchars($scorer['player_name']); ?> (<?php echo htmlspecialchars($scorer['minute']); ?>')</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    // Refresh the page every 30 seconds to get the latest scores
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
