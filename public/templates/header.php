<?php
// This file is included by index.php, so paths are relative to index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friday Champions League</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-900 text-white">

    <div id="app">
        <header class="bg-primary shadow-md">
        <div class="container mx-auto px-4 py-2 flex justify-between items-center">
            <div class="flex items-center">
                <img src="images/logo.png" alt="FCL Logo" class="h-12 mr-4">
                <h1 class="text-2xl font-bold text-primary">Friday Champions League</h1>
            </div>
            <nav class="hidden md:flex items-center space-x-4">
                <ul class="flex space-x-4">
                    <li><a href="index.php?page=home" class="text-secondary hover:text-primary">Home</a></li>
                    <li><a href="index.php?page=seasons" class="text-secondary hover:text-primary">Seasons</a></li>
                    <li><a href="index.php?page=teams" class="text-secondary hover:text-primary">Teams</a></li>
                    <li><a href="index.php?page=players" class="text-secondary hover:text-primary">Players</a></li>
                    <li><a href="index.php?page=fixtures" class="text-secondary hover:text-primary">Fixtures</a></li>
                    <li><a href="index.php?page=rankings" class="text-secondary hover:text-primary">Rankings</a></li>
                    <li><a href="index.php?page=live" class="text-secondary hover:text-primary">Live</a></li>
                </ul>
            </nav>
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-secondary focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-primary px-4 pb-4">
            <ul class="flex flex-col space-y-2">
                <li><a href="index.php?page=home" class="block text-secondary hover:text-primary py-2">Home</a></li>
                <li><a href="index.php?page=seasons" class="block text-secondary hover:text-primary py-2">Seasons</a></li>
                <li><a href="index.php?page=teams" class="block text-secondary hover:text-primary py-2">Teams</a></li>
                <li><a href="index.php?page=players" class="block text-secondary hover:text-primary py-2">Players</a></li>
                <li><a href="index.php?page=fixtures" class="block text-secondary hover:text-primary py-2">Fixtures</a></li>
                <li><a href="index.php?page=rankings" class="block text-secondary hover:text-primary py-2">Rankings</a></li>
                <li><a href="index.php?page=live" class="block text-secondary hover:text-primary py-2">Live</a></li>
            </ul>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8 animate-fadeInUp">
