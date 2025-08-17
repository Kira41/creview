CREATE TABLE casinos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('online', 'sweepstakes', 'crypto') NOT NULL,
    rating DECIMAL(2,1) NOT NULL,
    bonus TEXT,
    features JSON,
    description TEXT,
    logo VARCHAR(255),
    affiliate_url VARCHAR(255),
    status ENUM('active', 'inactive', 'deleted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
