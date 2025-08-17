-- Sample test data for casino_db
CREATE DATABASE IF NOT EXISTS casino_db;
USE casino_db;

-- Insert sample casino with base64 logo
INSERT INTO casinos (id, name, slug, casino_type, rating, bonus, features, description, logo, status, featured)
VALUES (
    1,
    'Demo Casino',
    'demo-casino',
    'online',
    4.5,
    '100% Welcome Bonus up to $500',
    '["Fast payouts", "Great games"]',
    'Demo Casino is an example used for testing.',
    'data:image/png;base64,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',
    'active',
    1
);

-- Insert sample games for the casino
INSERT INTO games (casino_id, name, game_type, rating) VALUES
    (1, 'Demo Slots', 'slots', 4.2),
    (1, 'Demo Roulette', 'roulette', 4.0);

