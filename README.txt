Absolutely! Here's a comprehensive brainstorm and a developer prompt to help you build **YTHT Downloader** as a mobile-first PHP web app with PWA support and browser extension integration.

---

## 🧠 Brainstorm: YTHT Downloader

### 🎯 Core Web App Features
- **Input field** for YouTube or Facebook video URL
- **Two conversion options**:
  - 🎥 Convert to MP4 (video)
  - 🎵 Convert to MP3 (audio)
- **Download button** after conversion
- **URL validation** and error handling
- **Progress indicator** during conversion
- **Mobile-first responsive design**
- **PWA capabilities**:
  - Service worker for caching
  - Web manifest for installability
  - Add to Home Screen support
  - Offline fallback page

### 🛠️ Backend Tech
- **PHP** (vanilla or Slim/Lumen micro-framework)
- **Shell integration** with `yt-dlp` or `youtube-dl` for conversion
- **Security**: Input sanitization, rate limiting, HTTPS (Let's Encrypt)

### 🌐 Environments
| Environment | Domain              | Notes                          |
|-------------|---------------------|--------------------------------|
| Development | `ytht.local`        | Localhost testing              |
| Production  | `ytht.tmsht.com`    | Live deployment                |

---

## 🧩 Chrome/Edge Extension Features

### 🔍 Extension Capabilities
- Detect when a user is watching a YouTube video
- Capture the current video URL
- Show a popup with:
  - "Download as MP4"
  - "Download as MP3"
- Send the URL to `ytht.tmsht.com` via query string or POST
- Open the downloader page with pre-filled URL

### 🧱 Extension Structure
```
/extension
  ├── manifest.json
  ├── background.js
  ├── content.js
  ├── popup.html
  └── popup.js
```

### 📄 `manifest.json` Highlights
```json
{
  "manifest_version": 3,
  "name": "YTHT Downloader",
  "version": "1.0",
  "description": "Download YouTube videos as MP4 or MP3",
  "permissions": ["activeTab", "scripting"],
  "action": {
    "default_popup": "popup.html",
    "default_icon": "icon.png"
  },
  "background": {
    "service_worker": "background.js"
  },
  "host_permissions": ["*://*.youtube.com/*"]
}
```

---

## 🧾 Developer Prompt

> Build a mobile-first PHP web app called **YTHT Downloader** with PWA support. The app should:
> - Accept a **YouTube or Facebook video URL**
> - Offer two conversion options: **MP4 (video)** and **MP3 (audio)**
> - Use `yt-dlp` or `youtube-dl` via PHP to handle conversion
> - Display a **progress indicator** and handle errors gracefully
> - Be responsive and optimized for mobile devices
> - Include PWA features: **service worker**, **manifest.json**, and **offline support**
> - Use `ytht.local` for development and `ytht.tmsht.com` for production
> - Multilanguage support (English, French, Haitan Creole and Spanish)
>
> Additionally, build a **Chrome/Edge browser extension** that:
> - Detects when a user is watching a YouTube video
> - Captures the video URL
> - Prompts the user to download it via **MP4 or MP3**
> - Sends the URL to the web app (`ytht.tmsht.com`) for processing

---

🛠️ Backend Tech
- **Backend**: PHP (vanilla)
- **Frontend**: HTML, CSS (Bootstrap or Tailwind), JavaScript (optional for UX)
- **Conversion Engine**:Shell integration with `yt-dlp` or `youtube-dl` for conversion
- **Security**: Input sanitization, rate limiting, HTTPS (Let's Encrypt), server-side validation 
- **Integration**: google login
---

## 📁 Full Folder Structure

```
ytht-downloader/
├── public/                      # Publicly accessible files
│   ├── index.php                # Main entry point, routing logic
│   ├── convert.php              # Handles conversion logic
│   ├── manifest.json            # PWA manifest
│   ├── service-worker.js        # PWA service worker
│   ├── offline.html             # Offline fallback page
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css        # Custom styles
│   │   ├── js/
│   │   │   └── app.js           # Frontend logic
│   │   └── icons/
│   │       ├── icon-192.png
│   │       └── icon-512.png
│   └── downloads/               # Temporary storage for converted files
│
├── src/                         # PHP backend logic
│   ├── Downloader.php           # Core class for handling yt-dlp
│   ├── Validator.php            # URL validation logic
│   └── Config.php               # Environment config (dev/prod)
│
├── views/                       # HTML templates
│   ├── header.php
│   ├── footer.php
│   └── home.php                 # Main UI layout
│
├── services/                       # Services
│   ├── youtube-dl.exe
│   └── yt-dlp.exe                 
│
├── extension/                   # Chrome/Edge browser extension
│   ├── manifest.json
│   ├── background.js
│   ├── content.js
│   ├── popup.html
│   ├── popup.js
│   └── icon.png
│
├── .htaccess                    # Apache config (if needed)
├── composer.json               # PHP dependencies (optional)
├── README.md                   # Project documentation
└── LICENSE                     # License file
```

---

## 🧩 Notes

- **`public/`**: Everything here is accessible via the browser. It includes your main app, assets, and PWA files.
- **`src/`**: Contains PHP logic for validation, conversion, and configuration.
- **`views/`**: Modular HTML components for clean separation of layout.
- **`extension/`**: Fully isolated browser extension codebase.
- **`downloads/`**: You may want to auto-clean this folder periodically for security and storage.

---

Inspiration from:
https://yt1d.com/en402/
https://www-y2mate.com/fr22/

1. Server Requirements
bash
# Install yt-dlp (recommended)
sudo curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp
sudo chmod a+rx /usr/local/bin/yt-dlp

# Or install youtube-dl
sudo curl -L https://yt-dl.org/downloads/latest/youtube-dl -o /usr/local/bin/youtube-dl
sudo chmod a+rx /usr/local/bin/youtube-dl

# Install PHP extensions
sudo apt install php php-curl php-json php-mbstring
2. Directory Permissions
bash
chmod 755 public/downloads/
chown www-data:www-data public/downloads/
3. Crontab for Cleanup
bash
# Clean old files every hour
0 * * * * find /path/to/ytht-downloader/public/downloads/ -type f -mmin +60 -delete
4. Browser Extension Installation
Open Chrome/Edge and go to chrome://extensions/

Enable "Developer mode"
Click "Load unpacked" and select the extension/ folder
The extension icon will appear in your toolbar

🔧 Development Notes
Local Development: Use ytht.local with proper SSL certificate
Production: Deploy to ytht.tmsht.com with Let's Encrypt SSL
Security: Input validation, rate limiting, and file cleanup
Mobile Optimization: Responsive design with PWA features
Multilanguage: Easy to add more languages as needed
