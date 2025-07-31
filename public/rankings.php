<?php
require_once '../src/database.php';
require_once '../src/functions.php';

$active_season = getActiveSeason();

if ($active_season) {
    $standings = getStandingsBySeason($active_season['id']);
    $top_scorers = getTopScorersBySeason($active_season['id']);
    $top_assisters = getTopAssistersBySeason($active_season['id']);
} else {
    $standings = [];
    $top_scorers = [];
    $top_assisters = [];
}

?>

<h2 class="text-3xl font-bold mb-6 text-primary">League Standings</h2>

<?php if ($active_season): ?>
    <h3 class="text-2xl font-bold text-accent mb-4 text-center">Season: <?php echo htmlspecialchars($active_season['name']); ?></h3>
    <div class="bg-primary shadow-md rounded-xl p-6 mb-12">
        <?php if (empty($standings)): ?>
            <p class="text-secondary">No teams in the standings yet.</p>
        <?php else: ?>
            <table class="w-full text-left table-auto">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="py-3 px-4 rounded-tl-lg text-secondary">Pos</th>
                        <th class="py-3 px-4 text-secondary">Team</th>
                        <th class="py-3 px-4 text-secondary">P</th>
                        <th class="py-3 px-4 text-secondary">W</th>
                        <th class="py-3 px-4 text-secondary">D</th>
                        <th class="py-3 px-4 text-secondary">L</th>
                        <th class="py-3 px-4 text-secondary">GF</th>
                        <th class="py-3 px-4 text-secondary">GA</th>
                        <th class="py-3 px-4 text-secondary">GD</th>
                        <th class="py-3 px-4 rounded-tr-lg text-secondary">Pts</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $position = 1; foreach ($standings as $team): ?>
                        <tr class="border-b border-gray-700 last:border-b-0">
                            <td class="py-3 px-4 font-bold text-primary"><?php echo $position++; ?></td>
                            <td class="py-3 px-4 flex items-center text-primary">
                                <?php if ($team['logo']): ?>
                                    <img src="images/<?php echo htmlspecialchars($team['logo']); ?>" alt="<?php echo htmlspecialchars($team['name']); ?> Logo" class="h-6 w-6 mr-2 object-contain">
                                <?php endif; ?>
                                <a href="index.php?page=team_profile&id=<?php echo $team['team_id']; ?>"><?php echo htmlspecialchars($team['name']); ?></a>
                            </td>
                            <td class="py-3 px-4 text-secondary"><?php echo $team['matches_played']; ?></td>
                            <td class="py-3 px-4 text-secondary"><?php echo $team['wins']; ?></td>
                            <td class="py-3 px-4 text-secondary"><?php echo $team['draws']; ?></td>
                            <td class="py-3 px-4 text-secondary"><?php echo $team['losses']; ?></td>
                            <td class="py-3 px-4 text-secondary"><?php echo $team['goals_for']; ?></td>
                            <td class="py-3 px-4 text-secondary"><?php echo $team['goals_against']; ?></td>
                            <td class="py-3 px-4 text-secondary"><?php echo $team['goal_difference']; ?></td>
                            <td class="py-3 px-4 font-bold text-accent"><?php echo $team['points']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Top Scorers -->
        <div class="bg-primary shadow-md rounded-xl p-6">
            <h3 class="text-2xl font-bold mb-4 text-primary">Top Scorers</h3>
            <?php if (empty($top_scorers)): ?>
                <p class="text-secondary">No top scorers yet.</p>
            <?php else: ?>
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="bg-gray-700">
                            <th class="py-3 px-4 rounded-tl-lg text-secondary">#</th>
                            <th class="py-3 px-4 text-secondary">Player</th>
                            <th class="py-3 px-4 rounded-tr-lg text-secondary">Goals</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rank = 1; foreach ($top_scorers as $scorer): ?>
                            <tr class="border-b border-gray-700 last:border-b-0">
                                <td class="py-3 px-4 text-primary"><?php echo $rank++; ?></td>
                                <td class="py-3 px-4 text-primary"><a href="index.php?page=player_profile&id=<?php echo $scorer['player_id']; ?>"><?php echo htmlspecialchars($scorer['name']); ?></a></td>
                                <td class="py-3 px-4 font-bold text-accent"><?php echo $scorer['goals']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Top Assisters -->
        <div class="bg-primary shadow-md rounded-xl p-6">
            <h3 class="text-2xl font-bold mb-4 text-primary">Top Assisters</h3>
            <?php if (empty($top_assisters)): ?>
                <p class="text-secondary">No top assisters yet.</p>
            <?php else: ?>
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="bg-gray-700">
                            <th class="py-3 px-4 rounded-tl-lg text-secondary">#</th>
                            <th class="py-3 px-4 text-secondary">Player</th>
                            <th class="py-3 px-4 rounded-tr-lg text-secondary">Assists</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rank = 1; foreach ($top_assisters as $assister): ?>
                            <tr class="border-b border-gray-700 last:border-b-0">
                                <td class="py-3 px-4 text-primary"><?php echo $rank++; ?></td>
                                <td class="py-3 px-4 text-primary"><a href="index.php?page=player_profile&id=<?php echo $assister['player_id']; ?>"><?php echo htmlspecialchars($assister['name']); ?></a></td>
                                <td class="py-3 px-4 font-bold text-accent"><?php echo $assister['assists']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <p class="text-secondary text-center">No active season. Rankings will be displayed here once a season is active.</p>
<?php endif; ?>
