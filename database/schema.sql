-- Main entity tables (less frequently changed)
CREATE TABLE teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    logo VARCHAR(255)
);

CREATE TABLE players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    photo VARCHAR(255),
    -- General position, can be overridden per season
    position VARCHAR(50) 
);

CREATE TABLE seasons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    start_date DATE,
    end_date DATE,
    is_active BOOLEAN DEFAULT false
);

-- Tables for season-specific data and stats
CREATE TABLE season_teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    season_id INT NOT NULL,
    team_id INT NOT NULL,
    matches_played INT DEFAULT 0,
    wins INT DEFAULT 0,
    draws INT DEFAULT 0,
    losses INT DEFAULT 0,
    goals_for INT DEFAULT 0,
    goals_against INT DEFAULT 0,
    goal_difference INT DEFAULT 0,
    points INT DEFAULT 0,
    FOREIGN KEY (season_id) REFERENCES seasons(id),
    FOREIGN KEY (team_id) REFERENCES teams(id)
);

CREATE TABLE season_players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    season_id INT NOT NULL,
    player_id INT NOT NULL,
    team_id INT NOT NULL,
    jersey_number INT,
    matches_played INT DEFAULT 0,
    goals INT DEFAULT 0,
    assists INT DEFAULT 0,
    yellow_cards INT DEFAULT 0,
    red_cards INT DEFAULT 0,
    mvp_awards INT DEFAULT 0,
    FOREIGN KEY (season_id) REFERENCES seasons(id),
    FOREIGN KEY (player_id) REFERENCES players(id),
    FOREIGN KEY (team_id) REFERENCES teams(id)
);

-- Match-related tables
CREATE TABLE matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    season_id INT NOT NULL,
    team1_id INT NOT NULL,
    team2_id INT NOT NULL,
    match_date DATETIME,
    venue VARCHAR(255),
    team1_score INT,
    team2_score INT,
    status ENUM('upcoming', 'live', 'finished') DEFAULT 'upcoming',
    mvp_id INT, -- references players(id)
    FOREIGN KEY (season_id) REFERENCES seasons(id),
    FOREIGN KEY (team1_id) REFERENCES teams(id),
    FOREIGN KEY (team2_id) REFERENCES teams(id),
    FOREIGN KEY (mvp_id) REFERENCES players(id)
);

CREATE TABLE match_lineups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    match_id INT NOT NULL,
    player_id INT NOT NULL,
    team_id INT NOT NULL,
    FOREIGN KEY (match_id) REFERENCES matches(id),
    FOREIGN KEY (player_id) REFERENCES players(id),
    FOREIGN KEY (team_id) REFERENCES teams(id)
);

CREATE TABLE match_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    match_id INT NOT NULL,
    player_id INT NOT NULL,
    event_type ENUM('goal', 'assist', 'yellow_card', 'red_card') NOT NULL,
    minute INT,
    FOREIGN KEY (match_id) REFERENCES matches(id),
    FOREIGN KEY (player_id) REFERENCES players(id)
);

-- Admin Table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);