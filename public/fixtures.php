<?php
require_once '../src/database.php';
require_once '../src/functions.php';

$active_season = getActiveSeason();

if ($active_season) {
    $matches = getMatchesBySeason($active_season['id']);
} else {
    $matches = [];
}

$upcoming_fixtures = [];
$past_matches = [];
$current_time = new DateTime();

foreach ($matches as $match) {
    $match_datetime = new DateTime($match['match_date']);
    if ($match_datetime > $current_time) {
        $upcoming_fixtures[] = $match;
    } else {
        $past_matches[] = $match;
    }
}

?>

<div class="container">
    <h2 class="text-4xl font-bold text-primary mb-6 text-center">Fixtures & Results</h2>

    <?php if ($active_season): ?>
        <h3 class="text-2xl font-bold text-accent mb-4 text-center">Season: <?php echo htmlspecialchars($active_season['name']); ?></h3>

        <!-- Upcoming Fixtures -->
        <section class="mb-12">
            <h3 class="text-3xl font-bold text-primary mb-6 text-center">Upcoming Fixtures</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($upcoming_fixtures)): ?>
                    <p class="text-secondary text-center col-span-full">No upcoming fixtures scheduled for this season.</p>
                <?php else: ?>
                    <?php foreach ($upcoming_fixtures as $match): ?>
                        <div class="bg-primary rounded-xl shadow-lg p-6 flex flex-col items-center text-center transform hover:scale-105 transition-transform duration-300 ease-in-out">
                            <p class="text-secondary text-sm mb-2"><?php echo date('F j, Y - H:i', strtotime($match['match_date'])); ?></p>
                            <div class="flex items-center justify-center w-full mb-4">
                                <div class="flex flex-col items-center mx-2">
                                    <span class="text-lg font-semibold text-primary"><?php echo htmlspecialchars($match['team1_name']); ?></span>
                                </div>
                                <span class="text-3xl font-bold text-gray-500 mx-4">VS</span>
                                <div class="flex flex-col items-center mx-2">
                                    <span class="text-lg font-semibold text-primary"><?php echo htmlspecialchars($match['team2_name']); ?></span>
                                </div>
                            </div>
                            <p class="text-secondary text-sm">Venue: <?php echo htmlspecialchars($match['venue']); ?></p>
                            <a href="index.php?page=match_details&id=<?php echo $match['id']; ?>" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full transition duration-300">Details</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Past Matches -->
        <section>
            <h3 class="text-3xl font-bold text-primary mb-6 text-center">Past Matches</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($past_matches)): ?>
                    <p class="text-secondary text-center col-span-full">No past matches recorded for this season.</p>
                <?php else: ?>
                    <?php foreach ($past_matches as $match): ?>
                        <div class="bg-primary rounded-xl shadow-lg p-6 flex flex-col items-center text-center transform hover:scale-105 transition-transform duration-300 ease-in-out">
                            <p class="text-secondary text-sm mb-2"><?php echo date('F j, Y - H:i', strtotime($match['match_date'])); ?></p>
                            <div class="flex items-center justify-center w-full mb-4">
                                <div class="flex flex-col items-center mx-2">
                                    <span class="text-lg font-semibold text-primary"><?php echo htmlspecialchars($match['team1_name']); ?></span>
                                </div>
                                <span class="text-3xl font-bold text-accent mx-4">
                                    <?php echo htmlspecialchars($match['team1_score']); ?> - <?php echo htmlspecialchars($match['team2_score']); ?>
                                </span>
                                <div class="flex flex-col items-center mx-2">
                                    <span class="text-lg font-semibold text-primary"><?php echo htmlspecialchars($match['team2_name']); ?></span>
                                </div>
                            </div>
                            <p class="text-secondary text-sm">Venue: <?php echo htmlspecialchars($match['venue']); ?></p>
                            <?php if ($match['mvp_name']): ?>
                                <p class="text-secondary text-sm mt-1">MVP: <?php echo htmlspecialchars($match['mvp_name']); ?></p>
                            <?php endif; ?>
                            <a href="index.php?page=match_details&id=<?php echo $match['id']; ?>" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full transition duration-300">Details</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    <?php else: ?>
        <p class="text-secondary text-center">No active season. Fixtures and results will be displayed here once a season is active.</p>
    <?php endif; ?>
</div>
