# YTHT Downloader 

A lightweight PHP web app + browser extension for downloading videos using `yt-dlp`/`youtube-dl`.

---

##  Overview

**YTHT Downloader** provides a simple web UI (PHP) and a browser extension to fetch and download video/audio from supported providers (YouTube, Facebook, etc.) using `yt-dlp`/`youtube-dl` as the backend.

- Web UI: `public/` (main entry point `public/index.php`)
- Browser extension: `extension/` (load as an unpacked extension in Chrome/Edge/Firefox)
- Backend helpers: `services/` (bundled `yt-dlp.exe` / `youtube-dl.exe` binaries  see notes)

---

##  Quick start

### Requirements
- PHP >= 7.4
- Web server (Apache/Nginx) or PHP built-in server
- `yt-dlp` or `youtube-dl` available on PATH or in `services/`
- **FFmpeg** (required for MP3 conversion)

### Windows Setup

1. Clone the repo:
```bash
git clone https://github.com/jumoyz/ytht.git
cd ytht
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install FFmpeg:
   - Download from https://ffmpeg.org/download.html
   - Or use Chocolatey: `choco install ffmpeg`
   - Add to PATH so PHP can access it

4. **(Optional) Install Whisper for lyrics generation:**
```bash
pip install openai-whisper torch
```

5. Serve the app:
```bash
php -S 127.0.0.1:8000 -t public
```

Open http://127.0.0.1:8000 in your browser.

### Linux Setup

See [SETUP_LINUX.md](SETUP_LINUX.md) for detailed Linux/server installation instructions.

Quick start on Linux:
```bash
sudo apt-get install -y php-cli ffmpeg python3-pip yt-dlp
git clone https://github.com/jumoyz/ytht.git
cd ytht
composer install

# Optional: For lyrics generation
python3 -m venv .venv
source .venv/bin/activate
pip install openai-whisper torch
deactivate

php -S localhost:8000 -t public
```

---

##  Features

- **Video Downloads**: Download from YouTube, Facebook, and other supported sites
- **Format Support**: MP4 (video) and MP3 (audio) conversion
- **Lyrics Generation** *(optional)*: Auto-generate lyrics from audio using OpenAI Whisper
- **Cross-Platform**: Works on Windows, Linux, and macOS
- **Browser Extension**: Quick download integration with browser
- **PWA Support**: Install as a web app
- **Multi-Language**: English, French, Haitian Creole, Spanish
- **Rate Limiting**: Built-in protection against abuse

---

##  Troubleshooting

### FFmpeg not found
**Error:** "ffmpeg not found" or MP3 conversion fails

**Solutions:**
- **Windows**: Download from https://ffmpeg.org/download.html or use `choco install ffmpeg`
- **Linux**: `sudo apt-get install ffmpeg` (Ubuntu/Debian) or equivalent for your distro
- Verify installation: `ffmpeg -version`

### yt-dlp not working
**Error:** "Neither yt-dlp nor youtube-dl found"

**Solutions:**
- **Windows**: Use bundled `services/yt-dlp.exe` (already included)
- **Linux**: `sudo apt-get install yt-dlp` or `pip3 install yt-dlp`
- Verify installation: `yt-dlp --version`

### Lyrics generation not available
**Error:** "Whisper is not available" or lyrics don't generate

**Solutions:**
- This is optional. The app works fine without it.
- To enable, install: `pip install openai-whisper torch`
- Make sure Python 3 is installed: `python3 --version` or `python --version`
- On Windows, use Anaconda or dedicated Python distribution for better compatibility

### Permission denied (Linux)
**Error:** Permission issues when downloading or generating lyrics

**Solutions:**
```bash
sudo chown -R www-data:www-data /path/to/ytht
sudo chmod -R 775 /path/to/ytht/public/downloads
```

### YouTube Download Errors (HTTP 403, etc.)
**Error:** "Access denied (HTTP 403)" or "unable to download video data"

**Cause:** YouTube frequently blocks access to yt-dlp, especially old versions. YouTube forces SABR streaming which requires updated yt-dlp.

**Solutions:**
1. **Update yt-dlp** (most important!):
   ```bash
   # Windows
   D:\path\to\ytht\services\yt-dlp.exe -U
   
   # Linux
   pip3 install --upgrade yt-dlp
   ```

2. **Common YouTube issues:**
   - **HTTP 403 Forbidden**: Video is private, geoblocked, or YouTube blocked the request
   - **SABR streaming error**: Update yt-dlp and try again
   - **Video not found (404)**: Video was deleted or URL is invalid

3. **Try these workarounds:**
   - Use a different video URL (some formats work better than others)
   - Try downloading MP4 first, then MP3 (or vice versa)
   - Wait a few minutes and retry (rate limiting)
   - Check if the video is public and accessible in your region

4. **Last resort:**
   - Update FFmpeg: Download latest from https://ffmpeg.org
   - Update all dependencies: `pip install --upgrade yt-dlp openai-whisper`
   - Check yt-dlp GitHub for known issues: https://github.com/yt-dlp/yt-dlp/issues

**Note:** YouTube actively blocks downloads. Keep yt-dlp updated or downloads may fail.

---

##  Configuration

Edit `src/Config.php` to update:
- `APP_NAME`, `VERSION`
- `IS_DEV` (set to `false` in production)
- `getBaseUrl()` if hosting under a custom domain
- `getDownloadPath()` to change where downloads are stored

To use system `yt-dlp`/`youtube-dl` instead of bundled Windows binaries, install the tool and ensure it is in your PATH.

---

##  Browser extension

The `extension/` folder contains a simple extension that can be loaded as an unpacked extension in Chromium-based browsers or Firefox (from `about:debugging`). The extension integrates with the web app to provide quick downloads from pages.

To load in Chrome:
1. Open `chrome://extensions`
2. Enable "Developer mode"
3. Click "Load unpacked" and select the `extension/` folder

---

##  Security & legal notes

- This software executes external downloader binaries and writes files to disk  **do not** expose it publicly without proper rate limiting, authentication, and sanitization.
- Be mindful of copyright and terms of service for the content you download. Use this tool for content you have rights to download.

---

##  Binaries and repo hygiene

The repository currently contains `services/yt-dlp.exe` and `services/youtube-dl.exe`. If you prefer not to store binaries in git:
- Remove them and add them to `.gitignore`
- Provide instructions in `README.md` for users to download the binaries or install via package manager

If you want, I can remove these binaries and add instructions (and rewrite history to strip them from commits).

---

##  Contributing

Found a bug or want a feature? Open an issue or submit a PR on GitHub: https://github.com/jumoyz/ytht

---

##  License

See the `LICENSE` file in the repo for licensing details.

---

##  Contact

Maintainer: jumoy (https://github.com/jumoyz)
