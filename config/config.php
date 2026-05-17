<?php
/**
 * Central configuration file for YTHT Downloader
 * Loads environment variables and defines helper functions
 * Uses vlucas/phpdotenv for environment variable management
 * Provides an env() function for easy access to environment variables  
 */
// config.php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Create a Dotenv instance
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Access env variables
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}