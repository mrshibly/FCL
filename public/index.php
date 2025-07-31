<?php
require_once '../src/functions.php';

include 'templates/header.php';

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'teams':
        include 'teams.php';
        break;
    case 'players':
        include 'players.php';
        break;
    case 'fixtures':
        include 'fixtures.php';
        break;
    case 'rankings':
        include 'rankings.php';
        break;
    case 'team_profile':
        include 'team_profile.php';
        break;
    case 'player_profile':
        include 'player_profile.php';
        break;
    case 'match_details':
        include 'match_details.php';
        break;
    case 'seasons':
        include 'seasons.php';
        break;
    case 'season_details':
        include 'season_details.php';
        break;
    case 'live':
        include 'live.php';
        break;
    default:
        include 'home.php';
        break;
}

include 'templates/footer.php';
?>