<?php
class Config {
    const APP_NAME = 'YTHT Downloader';
    const VERSION = '1.0.0';
    
    // Development vs Production
    const IS_DEV = true; // Change to false in production
    
    public static function getBaseUrl() {
        return self::IS_DEV ? 'https://ytht.local' : 'https://ytht.tmsht.com';
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