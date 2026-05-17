<?php
/**
 * Configuration class for YTHT Downloader
 * Centralizes all configuration settings and constants
 */
class Config {
    const APP_NAME = 'YTHT Downloader';
    const VERSION = '1.0.0';
    
    // Development vs Production
    const IS_DEV = true; // Change to false in production
    
    public static function getBaseUrl() {
        return self::IS_DEV ? 'https://ytht.tmsht.local' : 'https://ytht.tmsht.com';
    }
    
    public static function getFfmpegPath() {
        // Allow override via environment variable
        $envPath = $_ENV['FFMPEG_PATH'] ?? $_SERVER['FFMPEG_PATH'] ?? null;
        if ($envPath && (file_exists($envPath) || strpos($envPath, 'ffmpeg') !== false)) {
            return $envPath;
        }
        return null; // Let yt-dlp find ffmpeg on system PATH
    }

    public static function getDownloadPath() {
        return __DIR__ . '/../public/downloads/';
    }
    
    public static function getMaxFileSize() {
        return 500 * 1024 * 1024; // 500MB
    }
    
    public static function getAllowedDomains() {
        return [
            'youtube.com',
            'www.youtube.com',
            'm.youtube.com',
            'youtu.be',
            'facebook.com',
            'www.facebook.com',
            'fb.watch',
            'm.facebook.com'
        ];
    }
}
?>