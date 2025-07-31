<?php
require_once '../src/database.php';
require_once '../src/functions.php';

$active_season = getActiveSeason();

if ($active_season) {
    $matches = getMatchesBySeason($active_season['id']);
} else {
    $matches = [];
}

$latest_results = [];
$upcoming_fixtures = [];
$current_time = new DateTime();

foreach ($matches as $match) {
    $match_datetime = new DateTime($match['match_date']);
    if ($match_datetime < $current_time && $match['team1_score'] !== null) {
        $latest_results[] = $match;
    } elseif ($match_datetime > $current_time) {
        $upcoming_fixtures[] = $match;
    }
}

// Limit to a few for homepage display
$latest_results = array_slice($latest_results, 0, 3);
$upcoming_fixtures = array_slice($upcoming_fixtures, 0, 3);

?>

<section class="relative bg-cover bg-center h-screen flex items-center justify-center text-white" style="background-image: url('images/hero-bg.jpg');">
    <div class="absolute inset-0 bg-black opacity-60"></div>
    <div class="relative z-10 text-center px-4">
        <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-4 animate-fadeInUp">Friday Champions League</h1>
        <p class="text-lg md:text-xl mb-8 animate-fadeInUp animation-delay-200">Experience the thrill of elite amateur football. Teams, Players, Fixtures, and Rankings - all in one place.</p>
        <a href="index.php?page=fixtures" class="bg-accent hover:bg-accent-dark text-gray-900 font-bold py-3 px-8 rounded-full text-lg transition duration-300 ease-in-out transform hover:scale-105 animate-fadeInUp animation-delay-400">
            View Fixtures
        </a>
    </div>
</section>

<div class="container mx-auto px-4 py-8">
    <?php if ($active_season): ?>
        <h2 class="text-4xl font-bold text-primary mb-6 text-center">Active Season: <?php echo htmlspecialchars($active_season['name']); ?></h2>
        <!-- Latest Results Section -->
        <section class="mb-12">
            <h3 class="text-3xl font-bold text-primary mb-6 text-center">Latest Results</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($latest_results)): ?>
                    <p class="text-secondary text-center col-span-full">No recent match results to display.</p>
                <?php else: ?>
                    <?php foreach ($latest_results as $match): ?>
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
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="text-center mt-8">
                <a href="index.php?page=fixtures" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full transition duration-300 ease-in-out">View All Results</a>
            </div>
        </section>

        <!-- Upcoming Fixtures Section -->
        <section class="mb-12">
            <h3 class="text-3xl font-bold text-primary mb-6 text-center">Upcoming Fixtures</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($upcoming_fixtures)): ?>
                    <p class="text-secondary text-center col-span-full">No upcoming fixtures scheduled.</p>
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
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="text-center mt-8">
                <a href="index.php?page=fixtures" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full transition duration-300 ease-in-out">View All Fixtures</a>
            </div>
        </section>
    <?php else: ?>
        <p class="text-secondary text-center">No active season. Content will be displayed here once a season is active.</p>
    <?php endif; ?>
</div>

