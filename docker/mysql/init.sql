-- ===================================
-- MySQL Initialization Script
-- Clinic Management System
-- ===================================

-- Set character set
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Create database if not exists (handled by Docker env vars, but kept as fallback)
CREATE DATABASE IF NOT EXISTS clinic_master 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- Grant privileges
GRANT ALL PRIVILEGES ON clinic_master.* TO 'clinic_user'@'%';
FLUSH PRIVILEGES;

-- Use the database
USE clinic_master;

-- Display confirmation
SELECT 'Database initialized successfully!' AS status;
