-- Casino Review Database Schema
-- Run this SQL script to create the necessary tables for PHP integration

-- Create database (optional - you may already have one)
-- CREATE DATABASE casino_reviews CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE casino_reviews;

-- Casinos table - stores all casino information
CREATE TABLE casinos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    type ENUM('online', 'sweepstakes', 'crypto', 'live') NOT NULL DEFAULT 'online',
    rating DECIMAL(2,1) NOT NULL DEFAULT 0.0,
    bonus TEXT,
    bonus_value DECIMAL(10,2) DEFAULT 0.00,
    features JSON,
    description TEXT,
    full_review TEXT,
    logo VARCHAR(255),
    affiliate_url VARCHAR(500),
    website_url VARCHAR(500),
    license_info VARCHAR(255),
    established_year YEAR,
    min_deposit DECIMAL(8,2),
    withdrawal_time VARCHAR(100),
    payment_methods JSON,
    game_providers JSON,
    total_games INT DEFAULT 0,
    live_chat BOOLEAN DEFAULT FALSE,
    mobile_app BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive', 'pending', 'deleted') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    meta_title VARCHAR(255),
    meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_status (status),
    INDEX idx_rating (rating),
    INDEX idx_type (type),
    INDEX idx_featured (featured),
    INDEX idx_sort_order (sort_order)
);

-- Reviews table - stores individual user reviews
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    casino_id INT NOT NULL,
    user_name VARCHAR(100),
    user_email VARCHAR(255),
    rating DECIMAL(2,1) NOT NULL,
    title VARCHAR(255),
    review_text TEXT NOT NULL,
    pros TEXT,
    cons TEXT,
    games_rating DECIMAL(2,1),
    bonuses_rating DECIMAL(2,1),
    support_rating DECIMAL(2,1),
    mobile_rating DECIMAL(2,1),
    security_rating DECIMAL(2,1),
    verified BOOLEAN DEFAULT FALSE,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (casino_id) REFERENCES casinos(id) ON DELETE CASCADE,
    INDEX idx_casino_id (casino_id),
    INDEX idx_status (status),
    INDEX idx_rating (rating)
);

-- Categories table - for organizing casinos
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_status (status),
    INDEX idx_sort_order (sort_order)
);

-- Casino categories junction table
CREATE TABLE casino_categories (
    casino_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (casino_id, category_id),
    FOREIGN KEY (casino_id) REFERENCES casinos(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Bonuses table - detailed bonus information
CREATE TABLE bonuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    casino_id INT NOT NULL,
    type ENUM('welcome', 'no_deposit', 'reload', 'cashback', 'free_spins', 'vip') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    bonus_amount DECIMAL(10,2),
    bonus_percentage DECIMAL(5,2),
    free_spins INT DEFAULT 0,
    wagering_requirement DECIMAL(5,2),
    min_deposit DECIMAL(8,2),
    max_bonus DECIMAL(10,2),
    bonus_code VARCHAR(50),
    terms_conditions TEXT,
    valid_from DATE,
    valid_until DATE,
    status ENUM('active', 'inactive', 'expired') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (casino_id) REFERENCES casinos(id) ON DELETE CASCADE,
    INDEX idx_casino_id (casino_id),
    INDEX idx_type (type),
    INDEX idx_status (status),
    INDEX idx_featured (featured)
);

-- Admin users table
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    role ENUM('admin', 'editor', 'moderator') DEFAULT 'editor',
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_status (status)
);

-- Site settings table
CREATE TABLE site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'boolean', 'json') DEFAULT 'text',
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Page views tracking
CREATE TABLE page_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    casino_id INT,
    page_type ENUM('home', 'casino_detail', 'category', 'search') NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    referer VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (casino_id) REFERENCES casinos(id) ON DELETE SET NULL,
    INDEX idx_casino_id (casino_id),
    INDEX idx_page_type (page_type),
    INDEX idx_created_at (created_at)
);

-- Insert sample data
INSERT INTO casinos (name, slug, type, rating, bonus, features, description, logo, status, featured) VALUES
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

-- Create indexes for better performance
CREATE INDEX idx_casinos_rating_status ON casinos(rating DESC, status);
CREATE INDEX idx_casinos_featured_rating ON casinos(featured DESC, rating DESC);
CREATE INDEX idx_reviews_casino_status ON reviews(casino_id, status);

-- Create a view for casino statistics
CREATE VIEW casino_stats AS
SELECT 
    c.id,
    c.name,
    c.rating,
    COUNT(r.id) as review_count,
    AVG(r.rating) as user_rating,
    COUNT(pv.id) as view_count
FROM casinos c
LEFT JOIN reviews r ON c.id = r.casino_id AND r.status = 'approved'
LEFT JOIN page_views pv ON c.id = pv.casino_id
WHERE c.status = 'active'
GROUP BY c.id, c.name, c.rating;

-- Sample stored procedure for updating casino ratings
DELIMITER //
CREATE PROCEDURE UpdateCasinoRating(IN casino_id INT)
BEGIN
    DECLARE avg_rating DECIMAL(2,1);
    
    SELECT AVG(rating) INTO avg_rating
    FROM reviews 
    WHERE casino_id = casino_id AND status = 'approved';
    
    IF avg_rating IS NOT NULL THEN
        UPDATE casinos 
        SET rating = avg_rating, updated_at = NOW()
        WHERE id = casino_id;
    END IF;
END //
DELIMITER ;

-- Sample function to get casino rank
DELIMITER //
CREATE FUNCTION GetCasinoRank(casino_id INT) RETURNS INT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE casino_rank INT;
    
    SELECT rank_num INTO casino_rank
    FROM (
        SELECT id, ROW_NUMBER() OVER (ORDER BY rating DESC, featured DESC) as rank_num
        FROM casinos 
        WHERE status = 'active'
    ) ranked_casinos
    WHERE id = casino_id;
    
    RETURN IFNULL(casino_rank, 0);
END //
DELIMITER ;

