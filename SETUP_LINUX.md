# YTHT Downloader - Linux Setup Guide

This guide helps you set up YTHT Downloader on Linux servers.

## Prerequisites

### 1. Install System Dependencies

```bash
# Ubuntu/Debian
sudo apt-get update
sudo apt-get install -y php-cli php-curl php-json ffmpeg python3 python3-pip python3-venv

# CentOS/RHEL
sudo yum install -y php-cli php-curl ffmpeg python3 python3-pip

# Alpine
apk add --no-cache php-cli php-curl ffmpeg python3 py3-pip
```

### 2. Install yt-dlp

```bash
# Option 1: Using pip (recommended)
sudo pip3 install yt-dlp

# Option 2: Using package manager (Ubuntu/Debian)
sudo apt-get install -y yt-dlp

# Option 3: Manual installation
sudo curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp
sudo chmod +x /usr/local/bin/yt-dlp
```

Verify installation:
```bash
yt-dlp --version
```

### 3. Install Python Dependencies for Lyrics (Optional)

Lyrics generation requires OpenAI Whisper. Install in a virtual environment:

```bash
cd /path/to/ytht
python3 -m venv .venv
source .venv/bin/activate
pip install openai-whisper torch
deactivate
```

Update `src/LyricsGenerator.php` to use the venv:

```php
public function __construct()
{
    // Use venv Python if available
    $venvPath = __DIR__ . '/../.venv/bin/python3';
    if (file_exists($venvPath)) {
        $this->pythonPath = $venvPath;
    } else {
        $this->pythonPath = 'python3';
    }
    $this->scriptPath = __DIR__ . "/../services/whisper/transcribe.py";
}
```

### 4. Set Directory Permissions

```bash
# Navigate to app directory
cd /path/to/ytht

# Set permissions
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 public/downloads

# If using Apache
sudo a2enmod rewrite
```

### 5. Web Server Configuration

**Apache (.htaccess - already included):**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
```

**Nginx:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/ytht/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### 6. Run with PHP Built-in Server (Development)

```bash
cd /path/to/ytht
php -S localhost:8000 -t public
```

### 7. Troubleshooting

**yt-dlp not found:**
```bash
# Check if it's in PATH
which yt-dlp

# If not found, use full path in Config.php or ensure it's installed
sudo pip3 install --upgrade yt-dlp
```

**ffmpeg not found:**
```bash
which ffmpeg
sudo apt-get install ffmpeg  # or equivalent for your distro
```

**Permission denied:**
```bash
chmod +x /usr/local/bin/yt-dlp
chmod -R 775 public/downloads
```

**Whisper not available (lyrics won't work):**
This is optional. The app will work without it; lyrics generation will just fail gracefully.
