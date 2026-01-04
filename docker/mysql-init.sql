-- MySQL initialization script for Gym & POS System
-- This script will run when MySQL container starts for the first time

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS gym_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Grant privileges to user
GRANT ALL PRIVILEGES ON gym_pos.* TO 'gym_user'@'%';
FLUSH PRIVILEGES;