<?php
/**
*
*
*/
class Validator {
    public static function validateUrl($url) {
        if (empty($url)) {
            throw new Exception('URL cannot be empty');
        }
        
        // Basic URL validation
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception('Invalid URL format');
        }
        
        $parsed = parse_url($url);
        $domain = strtolower($parsed['host'] ?? '');
        
        // Remove www. prefix for comparison
        $domain = preg_replace('/^www\./', '', $domain);
        
        $allowed = Config::getAllowedDomains();
        $allowed = array_map(function($d) {
            return preg_replace('/^www\./', '', strtolower($d));
        }, $allowed);
        
        if (!in_array($domain, $allowed)) {
            throw new Exception('Unsupported domain. Only YouTube and Facebook are supported.');
        }
        
        return true;
    }
    
    public static function sanitizeFilename($filename) {
        // Remove invalid characters
        $filename = preg_replace('/[^\w\s\-\.]/', '', $filename);
        // Replace spaces with underscores
        $filename = preg_replace('/\s+/', '_', $filename);
        // Limit length
        if (strlen($filename) > 100) {
            $filename = substr($filename, 0, 100);
        }
        return $filename;
    }
}
?>