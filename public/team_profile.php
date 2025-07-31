<?php
require_once '../src/database.php';
require_once '../src/functions.php';

$team_id = $_GET['id'] ?? null;

if (!$team_id) {
    header('Location: teams.php');
    exit;
}

$team = getTeamById($team_id);
$active_season = getActiveSeason();

$season_stats = null;
$season_players = [];

if ($active_season) {
    $season_stats = getTeamSeasonStats($team_id, $active_season['id']);
    $season_players = getPlayersBySeasonAndTeam($active_season['id'], $team_id);
}

?>

<div class="container">
    <div class="row">
        <div class="col-md-4 text-center">
            <?php if ($team['logo']): ?>
                <img src="images/<?php echo htmlspecialchars($team['logo']); ?>" alt="<?php echo htmlspecialchars($team['name']); ?> Logo" class="img-fluid mb-4">
            <?php endif; ?>
            <h2><?php echo htmlspecialchars($team['name']); ?></h2>
        </div>
        <div class="col-md-8">
            <?php if ($active_season && $season_stats): ?>
                <h3>Season (<?php echo htmlspecialchars($active_season['name']); ?>) Stats</h3>
                <ul class="list-group mb-4">
                    <li class="list-group-item">Matches Played: <?php echo htmlspecialchars($season_stats['matches_played']); ?></li>
                    <li class="list-group-item">Wins: <?php echo htmlspecialchars($season_stats['wins']); ?></li>
                    <li class="list-group-item">Draws: <?php echo htmlspecialchars($season_stats['draws']); ?></li>
                    <li class="list-group-item">Losses: <?php echo htmlspecialchars($season_stats['losses']); ?></li>
                    <li class="list-group-item">Goals For: <?php echo htmlspecialchars($season_stats['goals_for']); ?></li>
                    <li class="list-group-item">Goals Against: <?php echo htmlspecialchars($season_stats['goals_against']); ?></li>
                    <li class="list-group-item">Goal Difference: <?php echo htmlspecialchars($season_stats['goal_difference']); ?></li>
                    <li class="list-group-item">Points: <?php echo htmlspecialchars($season_stats['points']); ?></li>
                </ul>

                <h3>Season (<?php echo htmlspecialchars($active_season['name']); ?>) Roster</h3>
                <?php if (empty($season_players)): ?>
                    <p>No players assigned to this team for the active season.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($season_players as $player): ?>
                            <li class="list-group-item">
                                <a href="index.php?page=player_profile&id=<?php echo $player['id']; ?>">
                                    <?php echo htmlspecialchars($player['name']); ?> #<?php echo htmlspecialchars($player['jersey_number']); ?> (<?php echo htmlspecialchars($player['position']); ?>)
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            <?php else: ?>
                <p>No active season or no stats available for this team in the active season.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

