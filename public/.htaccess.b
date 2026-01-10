RewriteEngine On

# Enable error reporting for development
php_flag display_errors on
php_flag display_startup_errors on
php_value error_reporting E_ALL
php_value log_errors on

# Security headers (optional for now)
# Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
# Header always set X-Content-Type-Options nosniff

# Prevent access to sensitive files
<FilesMatch "\.(env|log|sql|json|lock)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Cache control for assets
<FilesMatch "\.(css|js|png|jpg|jpeg|gif|ico)$">
    ExpiresActive On
    ExpiresDefault "access plus 1 month"
</FilesMatch>

# Clean URLs
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]