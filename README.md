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

Requirements:
- PHP >= 7.4
- Web server (Apache/Nginx) or PHP built-in server
- `yt-dlp` or `youtube-dl` available on PATH or in `services/`

Steps:

1. Clone the repo:

```bash
git clone https://github.com/jumoyz/ytht.git
cd ytht
```

2. Install PHP dependencies (if any):

```bash
composer install
```

3. Serve the app (development):

```bash
php -S 127.0.0.1:8000 -t public
```

Then open http://127.0.0.1:8000 in your browser.

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
