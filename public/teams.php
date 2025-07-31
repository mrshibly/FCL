<?php
require_once '../src/database.php';
require_once '../src/functions.php';

$active_season = getActiveSeason();

if ($active_season) {
    $teams = getTeamsBySeason($active_season['id']);
} else {
    $teams = [];
}

?>

<div class="container">
    <h2 class="text-4xl font-bold text-primary mb-6 text-center">Teams</h2>
    <?php if ($active_season): ?>
        <h3 class="text-2xl font-bold text-accent mb-4 text-center">Season: <?php echo htmlspecialchars($active_season['name']); ?></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($teams as $team): ?>
                <a href="index.php?page=team_profile&id=<?php echo $team['id']; ?>" class="bg-primary rounded-xl shadow-lg p-6 flex flex-col items-center text-center transform hover:scale-105 transition-transform duration-300 ease-in-out">
                    <?php if ($team['logo']): ?>
                        <img src="images/<?php echo htmlspecialchars($team['logo']); ?>" alt="<?php echo htmlspecialchars($team['name']); ?> Logo" class="h-24 w-24 object-contain mb-4">
                    <?php endif; ?>
                    <h3 class="text-2xl font-bold text-primary"><?php echo htmlspecialchars($team['name']); ?></h3>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-secondary text-center">No active season. Teams will be displayed here once a season is active.</p>
    <?php endif; ?>
</div>

