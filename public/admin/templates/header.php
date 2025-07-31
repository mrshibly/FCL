<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FCL Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body class="bg-gray-900 text-white flex">

    <aside class="w-64 bg-primary p-6 flex flex-col">
        <div class="flex items-center mb-8">
            <img src="../images/logo.png" alt="FCL Logo" class="h-12 mr-3">
            <h1 class="text-xl font-bold">FCL Admin</h1>
        </div>
        <nav class="flex flex-col space-y-3">
            <a href="index.php" class="flex items-center py-2 px-4 rounded-lg <?php echo ($current_page == 'index.php') ? 'bg-accent text-gray-900' : 'text-secondary hover:bg-gray-700'; ?>">
                Dashboard
            </a>
            <a href="seasons.php" class="flex items-center py-2 px-4 rounded-lg <?php echo ($current_page == 'seasons.php' || $current_page == 'manage_season_teams.php' || $current_page == 'manage_season_players.php') ? 'bg-accent text-gray-900' : 'text-secondary hover:bg-gray-700'; ?>">
                Seasons
            </a>
            <a href="teams.php" class="flex items-center py-2 px-4 rounded-lg <?php echo ($current_page == 'teams.php') ? 'bg-accent text-gray-900' : 'text-secondary hover:bg-gray-700'; ?>">
                Teams
            </a>
            <a href="players.php" class="flex items-center py-2 px-4 rounded-lg <?php echo ($current_page == 'players.php') ? 'bg-accent text-gray-900' : 'text-secondary hover:bg-gray-700'; ?>">
                Players
            </a>
            <a href="matches.php" class="flex items-center py-2 px-4 rounded-lg <?php echo ($current_page == 'matches.php') ? 'bg-accent text-gray-900' : 'text-secondary hover:bg-gray-700'; ?>">
                Matches
            </a>
        </nav>
        <div class="mt-auto">
            <a href="logout.php" class="flex items-center py-2 px-4 rounded-lg text-red-500 hover:bg-red-700 hover:text-white">
                Logout
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col">
        <main class="flex-1 p-8">
