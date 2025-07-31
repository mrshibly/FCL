<?php
require_once '../src/database.php';
require_once '../src/functions.php';

$active_season = getActiveSeason();

if ($active_season) {
    $players = getPlayersBySeason($active_season['id']);
} else {
    $players = [];
}

?>

<div class="container">
    <h2 class="text-4xl font-bold text-primary mb-6 text-center">Players</h2>
    <?php if ($active_season): ?>
        <h3 class="text-2xl font-bold text-accent mb-4 text-center">Season: <?php echo htmlspecialchars($active_season['name']); ?></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($players as $player): ?>
                <a href="index.php?page=player_profile&id=<?php echo $player['player_id']; ?>" class="bg-primary rounded-xl shadow-lg p-6 flex flex-col items-center text-center transform hover:scale-105 transition-transform duration-300 ease-in-out">
                    <?php if ($player['photo']): ?>
                        <img src="<?php echo htmlspecialchars($player['photo']); ?>" alt="<?php echo htmlspecialchars($player['name']); ?>" class="h-24 w-24 object-cover rounded-full mb-4">
                    <?php else: ?>
                        <div class="h-24 w-24 bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <span class="text-gray-500 text-4xl">?</span>
                        </div>
                    <?php endif; ?>
                    <h3 class="text-xl font-bold text-primary"><?php echo htmlspecialchars($player['name']); ?></h3>
                    <p class="text-secondary"><?php echo htmlspecialchars($player['team_name']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-secondary text-center">No active season. Players will be displayed here once a season is active.</p>
    <?php endif; ?>
</div>

