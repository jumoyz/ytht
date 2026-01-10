RewriteEngine On

# Enable PHP error reporting for development
php_flag display_errors on
php_value error_reporting E_ALL

# Allow access to actual files and directories
RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]

# Redirect everything else to index.php
RewriteRule ^(.*)$ index.php [QSA,L]

# Cache control for assets
<FilesMatch "\.(css|js|png|jpg|jpeg|gif|ico|webp)$">
    ExpiresActive On
    ExpiresDefault "access plus 1 month"
</FilesMatch>