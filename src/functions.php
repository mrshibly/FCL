<?php

function getSeasons() {
    $db = getDBConnection();
    $stmt = $db->query('SELECT * FROM seasons ORDER BY start_date DESC');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addSeason($data) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        INSERT INTO seasons (name, start_date, end_date)
        VALUES (:name, :start_date, :end_date)
    ');
    $stmt->execute([
        'name' => $data['name'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date']
    ]);
}

function setActiveSeason($id) {
    $db = getDBConnection();
    // First, set all seasons to inactive
    $db->query('UPDATE seasons SET is_active = false');
    // Then, set the selected season to active
    $stmt = $db->prepare('UPDATE seasons SET is_active = true WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

function getActiveSeason() {
    $db = getDBConnection();
    $stmt = $db->query('SELECT * FROM seasons WHERE is_active = true');
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getDashboardStats($season_id) {
    $db = getDBConnection();
    $stats = [
        'team_count' => 0,
        'player_count' => 0,
        'match_count' => 0
    ];

    if ($season_id) {
        $stmt = $db->prepare('SELECT COUNT(*) FROM season_teams WHERE season_id = :season_id');
        $stmt->execute(['season_id' => $season_id]);
        $stats['team_count'] = $stmt->fetchColumn();

        $stmt = $db->prepare('SELECT COUNT(*) FROM season_players WHERE season_id = :season_id');
        $stmt->execute(['season_id' => $season_id]);
        $stats['player_count'] = $stmt->fetchColumn();

        $stmt = $db->prepare('SELECT COUNT(*) FROM matches WHERE season_id = :season_id');
        $stmt->execute(['season_id' => $season_id]);
        $stats['match_count'] = $stmt->fetchColumn();
    }

    return $stats;
}

function getSeasonById($id) {
    $db = getDBConnection();
    $stmt = $db->prepare('SELECT * FROM seasons WHERE id = :id');
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getMatchesBySeason($season_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        SELECT m.*, t1.name as team1_name, t2.name as team2_name
        FROM matches m
        JOIN teams t1 ON m.team1_id = t1.id
        JOIN teams t2 ON m.team2_id = t2.id
        WHERE m.season_id = :season_id
        ORDER BY m.match_date DESC
    ');
    $stmt->execute(['season_id' => $season_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMatchDetails($match_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        SELECT m.*, t1.name as team1_name, t2.name as team2_name, p.name as mvp_name
        FROM matches m
        JOIN teams t1 ON m.team1_id = t1.id
        JOIN teams t2 ON m.team2_id = t2.id
        LEFT JOIN players p ON m.mvp_id = p.id
        WHERE m.id = :match_id
    ');
    $stmt->execute(['match_id' => $match_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getMatchEvents($match_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        SELECT me.*, p.name as player_name
        FROM match_events me
        JOIN players p ON me.player_id = p.id
        WHERE me.match_id = :match_id
        ORDER BY me.minute ASC
    ');
    $stmt->execute(['match_id' => $match_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMatchLineups($match_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        SELECT ml.team_id, p.name, p.position
        FROM match_lineups ml
        JOIN players p ON ml.player_id = p.id
        WHERE ml.match_id = :match_id
    ');
    $stmt->execute(['match_id' => $match_id]);
    $lineups = ['team1' => [], 'team2' => []];
    $match = getMatchDetails($match_id);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['team_id'] == $match['team1_id']) {
            $lineups['team1'][] = $row;
        } else {
            $lineups['team2'][] = $row;
        }
    }
    return $lineups;
}

function getLiveMatches() {
    $db = getDBConnection();
    $stmt = $db->query('
        SELECT m.*, t1.name as team1_name, t2.name as team2_name
        FROM matches m
        JOIN teams t1 ON m.team1_id = t1.id
        JOIN teams t2 ON m.team2_id = t2.id
        WHERE m.status = \'live\'
        ORDER BY m.match_date DESC
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getGoalscorers($match_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        SELECT p.name as player_name, me.minute
        FROM match_events me
        JOIN players p ON me.player_id = p.id
        WHERE me.match_id = :match_id AND me.event_type = \'goal\'
        ORDER BY me.minute ASC
    ');
    $stmt->execute(['match_id' => $match_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateMatchScore($match_id, $team1_score, $team2_score, $status) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        UPDATE matches
        SET team1_score = :team1_score, team2_score = :team2_score, status = :status
        WHERE id = :match_id
    ');
    $stmt->execute([
        'match_id' => $match_id,
        'team1_score' => $team1_score,
        'team2_score' => $team2_score,
        'status' => $status
    ]);
}

function addGoalscorer($match_id, $player_id, $minute) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        INSERT INTO match_events (match_id, player_id, event_type, minute)
        VALUES (:match_id, :player_id, \'goal\', :minute)
    ');
    $stmt->execute([
        'match_id' => $match_id,
        'player_id' => $player_id,
        'minute' => $minute
    ]);
}

function getPlayers() {
    $db = getDBConnection();
    $stmt = $db->query('SELECT * FROM players');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPlayerById($id) {
    $db = getDBConnection();
    $stmt = $db->prepare('SELECT * FROM players WHERE id = :id');
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addPlayer($data, $files) {
    $db = getDBConnection();
    $photo_path = null;

    if (isset($files['photo']) && $files['photo']['error'] === UPLOAD_ERR_OK) {
        $photo_path = 'images/players/' . basename($files['photo']['name']);
        move_uploaded_file($files['photo']['tmp_name'], '../public/' . $photo_path);
    }

    $stmt = $db->prepare('
        INSERT INTO players (name, photo, position)
        VALUES (:name, :photo, :position)
    ');
    $stmt->execute([
        'name' => $data['name'],
        'photo' => $photo_path,
        'position' => $data['position']
    ]);
}

function updatePlayer($id, $data, $files) {
    $db = getDBConnection();
    $photo_path = getPlayerById($id)['photo'];

    if (isset($files['photo']) && $files['photo']['error'] === UPLOAD_ERR_OK) {
        $photo_path = 'images/players/' . basename($files['photo']['name']);
        move_uploaded_file($files['photo']['tmp_name'], '../public/' . $photo_path);
    }

    $stmt = $db->prepare('
        UPDATE players
        SET name = :name, photo = :photo, position = :position
        WHERE id = :id
    ');
    $stmt->execute([
        'id' => $id,
        'name' => $data['name'],
        'photo' => $photo_path,
        'position' => $data['position']
    ]);
}

function deletePlayer($id) {
    $db = getDBConnection();
    $stmt = $db->prepare('DELETE FROM players WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

function getPlayersWithTeam() {
    $db = getDBConnection();
    $stmt = $db->query('
        SELECT p.*, t.name as team_name
        FROM players p
        LEFT JOIN teams t ON p.team_id = t.id
        ORDER BY p.name ASC
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTeams() {
    $db = getDBConnection();
    $stmt = $db->query('SELECT * FROM teams');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addTeamToSeason($team_id, $season_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('INSERT INTO season_teams (team_id, season_id) VALUES (:team_id, :season_id)');
    $stmt->execute(['team_id' => $team_id, 'season_id' => $season_id]);
}

function removeTeamFromSeason($team_id, $season_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('DELETE FROM season_teams WHERE team_id = :team_id AND season_id = :season_id');
    $stmt->execute(['team_id' => $team_id, 'season_id' => $season_id]);
}

function getTeamsBySeason($season_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        SELECT t.*
        FROM teams t
        JOIN season_teams st ON t.id = st.team_id
        WHERE st.season_id = :season_id
    ');
    $stmt->execute(['season_id' => $season_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addPlayerToSeason($player_id, $season_id, $team_id, $jersey_number) {
    $db = getDBConnection();
    $stmt = $db->prepare('INSERT INTO season_players (player_id, season_id, team_id, jersey_number) VALUES (:player_id, :season_id, :team_id, :jersey_number)');
    $stmt->execute(['player_id' => $player_id, 'season_id' => $season_id, 'team_id' => $team_id, 'jersey_number' => $jersey_number]);
}

function removePlayerFromSeason($player_id, $season_id, $team_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('DELETE FROM season_players WHERE player_id = :player_id AND season_id = :season_id AND team_id = :team_id');
    $stmt->execute(['player_id' => $player_id, 'season_id' => $season_id, 'team_id' => $team_id]);
}

function getPlayersBySeasonAndTeam($season_id, $team_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        SELECT p.*, sp.jersey_number
        FROM players p
        JOIN season_players sp ON p.id = sp.player_id
        WHERE sp.season_id = :season_id AND sp.team_id = :team_id
    ');
    $stmt->execute(['season_id' => $season_id, 'team_id' => $team_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTeamById($id) {
    $db = getDBConnection();
    $stmt = $db->prepare('SELECT * FROM teams WHERE id = :id');
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getStandingsBySeason($season_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        SELECT t.name, st.*
        FROM season_teams st
        JOIN teams t ON st.team_id = t.id
        WHERE st.season_id = :season_id
        ORDER BY st.points DESC, st.goal_difference DESC, st.goals_for DESC
    ');
    $stmt->execute(['season_id' => $season_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTopScorersBySeason($season_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        SELECT p.name, sp.goals, sp.player_id
        FROM season_players sp
        JOIN players p ON sp.player_id = p.id
        WHERE sp.season_id = :season_id
        ORDER BY sp.goals DESC
        LIMIT 10
    ');
    $stmt->execute(['season_id' => $season_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTopAssistersBySeason($season_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        SELECT p.name, sp.assists, sp.player_id
        FROM season_players sp
        JOIN players p ON sp.player_id = p.id
        WHERE sp.season_id = :season_id
        ORDER BY sp.assists DESC
        LIMIT 10
    ');
    $stmt->execute(['season_id' => $season_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPlayersBySeason($season_id) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        SELECT p.name, p.photo, sp.player_id, t.name as team_name, sp.team_id
        FROM season_players sp
        JOIN players p ON sp.player_id = p.id
        JOIN teams t ON sp.team_id = t.id
        WHERE sp.season_id = :season_id
        ORDER BY p.name ASC
    ');
    $stmt->execute(['season_id' => $season_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addTeam($data, $files) {
    $db = getDBConnection();
    $logo_path = null;

    if (isset($files['logo']) && $files['logo']['error'] === UPLOAD_ERR_OK) {
        $logo_name = basename($files['logo']['name']);
        $logo_path = 'images/' . $logo_name;
        move_uploaded_file($files['logo']['tmp_name'], '../public/' . $logo_path);
    }

    $stmt = $db->prepare('INSERT INTO teams (name, logo) VALUES (:name, :logo)');
    $stmt->execute([
        'name' => $data['name'],
        'logo' => $logo_path
    ]);
}

function deleteTeam($id) {
    $db = getDBConnection();
    $stmt = $db->prepare('DELETE FROM teams WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

function addMatch($season_id, $team1_id, $team2_id, $match_date, $venue) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        INSERT INTO matches (season_id, team1_id, team2_id, match_date, venue)
        VALUES (:season_id, :team1_id, :team2_id, :match_date, :venue)
    ');
    $stmt->execute([
        'season_id' => $season_id,
        'team1_id' => $team1_id,
        'team2_id' => $team2_id,
        'match_date' => $match_date,
        'venue' => $venue
    ]);
}

function deleteMatch($id) {
    $db = getDBConnection();
    $stmt = $db->prepare('DELETE FROM matches WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

function updateMatchResult($match_id, $team1_score, $team2_score, $mvp_id, $status) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        UPDATE matches
        SET team1_score = :team1_score, team2_score = :team2_score, mvp_id = :mvp_id, status = :status
        WHERE id = :match_id
    ');
    $stmt->execute([
        'match_id' => $match_id,
        'team1_score' => $team1_score,
        'team2_score' => $team2_score,
        'mvp_id' => $mvp_id,
        'status' => $status
    ]);

    // Update season_teams stats
    $match = getMatchDetails($match_id);
    $season_id = $match['season_id'];
    $team1_id = $match['team1_id'];
    $team2_id = $match['team2_id'];

    updateSeasonTeamStats($season_id, $team1_id, $team1_score, $team2_score);
    updateSeasonTeamStats($season_id, $team2_id, $team2_score, $team1_score);

    // Update season_players mvp_awards
    if ($mvp_id !== NULL) {
        $stmt = $db->prepare('UPDATE season_players SET mvp_awards = mvp_awards + 1 WHERE player_id = :player_id AND season_id = :season_id');
        $stmt->execute(['player_id' => $mvp_id, 'season_id' => $season_id]);
    }
}

function addMatchEvent($match_id, $player_id, $event_type, $minute) {
    $db = getDBConnection();
    $stmt = $db->prepare('
        INSERT INTO match_events (match_id, player_id, event_type, minute)
        VALUES (:match_id, :player_id, :event_type, :minute)
    ');
    $stmt->execute([
        'match_id' => $match_id,
        'player_id' => $player_id,
        'event_type' => $event_type,
        'minute' => $minute
    ]);

    // Update season_players stats based on event type
    $match = getMatchDetails($match_id);
    $season_id = $match['season_id'];

    if ($event_type === 'goal') {
        $stmt = $db->prepare('UPDATE season_players SET goals = goals + 1 WHERE player_id = :player_id AND season_id = :season_id');
    } elseif ($event_type === 'assist') {
        $stmt = $db->prepare('UPDATE season_players SET assists = assists + 1 WHERE player_id = :player_id AND season_id = :season_id');
    } elseif ($event_type === 'yellow_card') {
        $stmt = $db->prepare('UPDATE season_players SET yellow_cards = yellow_cards + 1 WHERE player_id = :player_id AND season_id = :season_id');
    } elseif ($event_type === 'red_card') {
        $stmt = $db->prepare('UPDATE season_players SET red_cards = red_cards + 1 WHERE player_id = :player_id AND season_id = :season_id');
    }

    if (isset($stmt)) {
        $stmt->execute(['player_id' => $player_id, 'season_id' => $season_id]);
    }
}

function updateSeasonTeamStats($season_id, $team_id, $goals_for, $goals_against) {
    $db = getDBConnection();
    $stmt = $db->prepare('SELECT * FROM season_teams WHERE season_id = :season_id AND team_id = :team_id');
    $stmt->execute(['season_id' => $season_id, 'team_id' => $team_id]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    $new_matches_played = ($stats['matches_played'] ?? 0) + 1;
    $new_wins = ($stats['wins'] ?? 0);
    $new_draws = ($stats['draws'] ?? 0);
    $new_losses = ($stats['losses'] ?? 0);
    $new_points = ($stats['points'] ?? 0);

    if ($goals_for > $goals_against) {
        $new_wins++;
        $new_points += 3;
    } elseif ($goals_for < $goals_against) {
        $new_losses++;
    } else {
        $new_draws++;
        $new_points += 1;
    }

    $new_goals_for = ($stats['goals_for'] ?? 0) + $goals_for;
    $new_goals_against = ($stats['goals_against'] ?? 0) + $goals_against;
    $new_goal_difference = $new_goals_for - $new_goals_against;

    $stmt = $db->prepare('
        UPDATE season_teams
        SET matches_played = :matches_played,
            wins = :wins,
            draws = :draws,
            losses = :losses,
            goals_for = :goals_for,
            goals_against = :goals_against,
            goal_difference = :goal_difference,
            points = :points
        WHERE season_id = :season_id AND team_id = :team_id
    ');
    $stmt->execute([
        'matches_played' => $new_matches_played,
        'wins' => $new_wins,
        'draws' => $new_draws,
        'losses' => $new_losses,
        'goals_for' => $new_goals_for,
        'goals_against' => $new_goals_against,
        'goal_difference' => $new_goal_difference,
        'points' => $new_points,
        'season_id' => $season_id,
        'team_id' => $team_id
    ]);
}
