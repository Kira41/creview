USE casino_db;
-- Insert sample data
INSERT INTO casinos (name, slug, casino_type, rating, bonus, features, description, logo, status, featured) VALUES
('Crown Coins', 'crown-coins', 'sweepstakes', 4.9, 'Get 200% More Coins on First Purchase - 1.5 Million CC + 75 SC', 
 '["24/7 support, unlike most competitors", "Only sweeps site with 4.7★ iOS app", "Offers Relax Gaming, Pragmatic Play"]',
 'Crown Coins Casino stands out as one of the premier sweepstakes casinos in the market, offering an exceptional gaming experience with a focus on player satisfaction and security.',
 'https://via.placeholder.com/120x60/4CAF50/white?text=Crown+Coins', 'active', TRUE),

('Stake', 'stake', 'online', 4.7, 'Up to 560,000 GC, $56 Stake Cash + 5% Rakeback',
 '["Exclusive promo code: CASINOORG", "3,000+ games inc. Stake originals", "Weekly slots battles for 50M GC"]',
 'Stake offers a comprehensive online casino experience with a vast game library and innovative features.',
 'https://via.placeholder.com/120x60/2196F3/white?text=Stake', 'active', TRUE),

('Jackpota', 'jackpota', 'online', 4.6, '80,000 Gold Coins + 40 Sweeps Coins and 75 Free Spins',
'["A 200M jackpot on every game", "Get 1,500 free gold coins daily", "Enjoy 700+ exciting games"]',
'Jackpota provides an exciting gaming environment with massive jackpots and daily rewards.',
'https://via.placeholder.com/120x60/FF9800/white?text=Jackpota', 'active', TRUE);

-- Additional sample casino with base64 logo
INSERT INTO casinos (id, name, slug, casino_type, rating, bonus, features, description, logo, status, featured) VALUES
(4, 'Demo Casino', 'demo-casino', 'online', 4.5, '100% Welcome Bonus up to $500', '["Fast payouts", "Great games"]', 'Demo Casino is an example used for testing.', 'data:image/png;base64,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', 'active', 1);

-- Insert sample games
INSERT INTO games (casino_id, name, game_type, rating) VALUES
(1, 'Online slots', 'slots', 4.5),
(1, 'Online roulette', 'roulette', 4.2),
(1, 'Online blackjack', 'blackjack', 4.0),
(1, 'Online keno', 'keno', 3.8),
(1, 'All casino games', 'other', 4.3);

-- Insert sample games for Demo Casino
INSERT INTO games (casino_id, name, game_type, rating) VALUES
(4, 'Demo Slots', 'slots', 4.2),
(4, 'Demo Roulette', 'roulette', 4.0);

-- Insert sample bonuses
INSERT INTO bonuses (casino_id, type, title, description, bonus_amount, bonus_percentage, free_spins, wagering_requirement, min_deposit, max_bonus, bonus_code, terms_conditions, valid_from, valid_until, status, featured) VALUES
(1, 'welcome', 'Gold Rush Welcome Bonus', '100% match up to $500 plus 50 free spins', 500.00, 100.00, 50, 35.00, 20.00, 500.00, 'GOLD500', 'Standard terms apply', '2025-01-01', '2025-12-31', 'active', TRUE),
(4, 'no_deposit', 'Demo Casino No Deposit', '$10 free credit for new players', 10.00, 0.00, 0, 0.00, NULL, 10.00, NULL, 'No wagering requirements for cashout', '2025-01-01', '2025-06-30', 'active', FALSE);

-- Insert sample categories
INSERT INTO categories (name, slug, description, icon, sort_order) VALUES
('Top Rated', 'top-rated', 'Highest rated casinos based on our expert reviews', 'fas fa-star', 1),
('New Casinos', 'new-casinos', 'Recently launched casino sites', 'fas fa-plus', 2),
('Mobile Casinos', 'mobile-casinos', 'Best casinos for mobile gaming', 'fas fa-mobile-alt', 3),
('Live Dealer', 'live-dealer', 'Casinos with live dealer games', 'fas fa-video', 4),
('Crypto Casinos', 'crypto-casinos', 'Casinos accepting cryptocurrency', 'fab fa-bitcoin', 5);

-- Insert sample site settings
INSERT INTO site_settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'CasinoReviews', 'text', 'Website name'),
('site_description', 'Trusted & Independent Online Casino Reviews', 'text', 'Website description'),
('casinos_per_page', '9', 'number', 'Number of casinos to display per page'),
('enable_user_reviews', 'true', 'boolean', 'Allow users to submit reviews'),
('contact_email', 'admin@casinoreviews.com', 'text', 'Contact email address'),
('google_analytics_id', '', 'text', 'Google Analytics tracking ID'),
('maintenance_mode', 'false', 'boolean', 'Enable maintenance mode');
