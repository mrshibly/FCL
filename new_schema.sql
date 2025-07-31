-- Create the new 'seasons' table
CREATE TABLE seasons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    start_date DATE,
    end_date DATE
);

-- Create the new 'match_lineups' table
CREATE TABLE match_lineups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    match_id INT NOT NULL,
    team_id INT NOT NULL,
    player_id INT NOT NULL,
    FOREIGN KEY (match_id) REFERENCES matches(id),
    FOREIGN KEY (team_id) REFERENCES teams(id),
    FOREIGN KEY (player_id) REFERENCES players(id)
);

-- Add the 'photo' column to the 'players' table
ALTER TABLE players
ADD COLUMN photo VARCHAR(255) DEFAULT NULL AFTER team_id;

-- Add the 'season_id' and 'status' columns to the 'matches' table
ALTER TABLE matches
ADD COLUMN season_id INT NOT NULL AFTER id,
ADD COLUMN status ENUM('upcoming', 'live', 'finished') DEFAULT 'upcoming' AFTER team2_score;

-- Add the foreign key constraint for the new 'season_id' column
ALTER TABLE matches
ADD CONSTRAINT fk_season FOREIGN KEY (season_id) REFERENCES seasons(id);
