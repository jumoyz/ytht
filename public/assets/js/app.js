/**
 * YTHT Downloader class
 */
class YTHTDownloader {
    constructor() {
        this.form = document.getElementById('downloadForm');
        this.urlInput = document.getElementById('videoUrl');
        this.progressBar = document.getElementById('progressBar');
        this.progressSection = document.getElementById('progressSection');
        this.progressText = document.getElementById('progressText');
        this.resultSection = document.getElementById('resultSection');

        this.init();
        this.registerServiceWorker();
    }

    init() {
        // Attach event listeners to format buttons
        this.form.querySelectorAll('button[data-format]').forEach(button => {
            button.addEventListener('click', e => {
                e.preventDefault();
                const format = button.dataset.format;
                this.handleDownload(format);
            });
        });

        // Clear URL button
        document.getElementById('clearUrl')
            .addEventListener('click', () => this.clearUrl());

        // Offline detection
        if (!navigator.onLine) this.showOfflineMessage();
        window.addEventListener('online', () => this.hideOfflineMessage());
        window.addEventListener('offline', () => this.showOfflineMessage());
    }

    /**
     * Main download handler
     */
    async handleDownload(format) {
        const url = this.urlInput.value.trim();
        const generateLyrics = document.getElementById("generateLyrics")?.checked || false;

        if (!url) {
            this.showError('Please enter a video URL');
            return;
        }

        // Enforce MP3 for lyrics
        if (format === 'mp4' && generateLyrics) {
            this.showError("Lyrics generation requires MP3 format.");
            return;
        }

        this.showProgress(generateLyrics);

        try {
            const response = await fetch('convert.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    url,
                    format,
                    generate_lyrics: generateLyrics ? 1 : 0
                })
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result, format);
            } else {
                this.showError(result.error || 'Conversion failed');
            }

        } catch (error) {
            this.showError('Network error: ' + error.message);
        } finally {
            this.hideProgress();
        }
    }

    /**
     * Progress UI
     */
    showProgress(generateLyrics = false) {
        this.progressSection.classList.remove('d-none');
        this.resultSection.classList.add('d-none');

        this.progressText.innerText = generateLyrics
            ? "Downloading & generating lyrics..."
            : "Processing...";

        let progress = 0;

        this.progressInterval = setInterval(() => {
            progress += Math.random() * 8;
            if (progress >= 90) clearInterval(this.progressInterval);

            this.progressBar.style.width = Math.min(progress, 90) + '%';
        }, 400);
    }

    hideProgress() {
        this.progressSection.classList.add('d-none');
        this.progressBar.style.width = '0%';

        if (this.progressInterval) {
            clearInterval(this.progressInterval);
        }
    }

    /**
     * Success UI
     */
    showSuccess(result, format) {
        const downloadUrl = `download.php?file=${encodeURIComponent(result.filename)}`;

        let lyricsButton = '';

        if (result.lyrics) {
            const lyricsUrl = `download.php?file=${encodeURIComponent(result.lyrics)}`;
            lyricsButton = `
                <a href="${lyricsUrl}" class="btn btn-warning flex-fill" download>
                    <i class="fas fa-file-alt me-2"></i> Download Lyrics
                </a>
            `;
        }

        const lyricsNotice = result.lyrics_error
            ? `<div class="alert alert-warning mt-3 mb-0">
                <strong>Lyrics not generated:</strong> ${this.escapeHtml(result.lyrics_error)}
              </div>`
            : '';

        this.resultSection.innerHTML = `
            <div class="alert alert-success">
                <h4><i class="fas fa-check-circle"></i> Your Download is Ready!</h4>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <img src="${result.thumbnail}" alt="Thumbnail" class="img-fluid rounded mb-2">
                    </div>
                    <div class="col-12 col-md-8">
                        <p><strong>Title:</strong> ${result.title}</p>
                        <p><strong>Description:</strong> ${result.description || 'No description available.'}</p>
                        <p><strong>Channel:</strong> ${result.channel || 'Unknown'}</p>
                        <p><strong>Published:</strong> ${result.publishDate || 'N/A'}</p>
                        <p><strong>Duration:</strong> ${result.duration || 'N/A'}</p>
                        <p><strong>Size:</strong> ${result.filesize}</p>
                        <p><strong>Format:</strong> ${format.toUpperCase()}</p>
                    </div>
                </div>

                <div class="mt-3 d-flex flex-wrap gap-2">
                    <a href="${downloadUrl}" class="btn btn-success flex-fill" download>
                        <i class="fas fa-download me-2"></i> Download ${format.toUpperCase()}
                    </a>
                    ${lyricsButton}
                    <button class="btn btn-outline-secondary flex-fill" aria-label="Close result">
                        Close
                    </button>
                </div>
                ${lyricsNotice}
            </div>
        `;

        this.resultSection.classList.remove('d-none');

        this.resultSection.querySelector('button[aria-label="Close result"]')
            .addEventListener('click', () => this.resultSection.classList.add('d-none'));
    }

    /**
     * Error UI
     */
    showError(message) {
        this.resultSection.innerHTML = `
            <div class="alert alert-danger">
                <h4><i class="fas fa-exclamation-triangle"></i> Error</h4>
                <p>${message}</p>
                <button class="btn btn-outline-danger btn-sm" aria-label="Close error">Close</button>
            </div>
        `;

        this.resultSection.classList.remove('d-none');

        this.resultSection.querySelector('button[aria-label="Close error"]')
            .addEventListener('click', () => this.resultSection.classList.add('d-none'));
    }

    escapeHtml(value) {
        const div = document.createElement('div');
        div.innerText = value || '';
        return div.innerHTML;
    }

    /**
     * Offline UI
     */
    showOfflineMessage() {
        this.hideOfflineMessage();

        const message = document.createElement('div');
        message.className = 'alert alert-warning alert-dismissible fade show';
        message.id = 'offlineAlert';

        message.innerHTML = `
            <i class="fas fa-wifi"></i> You are currently offline. Some features may not be available.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.querySelector('main').prepend(message);
    }

    hideOfflineMessage() {
        const existingAlert = document.getElementById('offlineAlert');
        if (existingAlert) existingAlert.remove();
    }

    /**
     * Service Worker
     */
    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                await navigator.serviceWorker.register('/service-worker.js');
                console.log('Service Worker registered successfully');
            } catch (error) {
                console.warn('Service Worker registration failed:', error);
            }
        }
    }

    /**
     * Clear URL input
     */
    clearUrl() {
        this.urlInput.value = '';
        this.urlInput.focus();
    }
}

/**
 * PWA Install Prompt
 */
let deferredPrompt;

window.addEventListener('beforeinstallprompt', e => {
    e.preventDefault();
    deferredPrompt = e;

    const installPrompt = document.createElement('div');
    installPrompt.id = 'installPrompt';
    installPrompt.className = 'pwa-install-prompt';

    installPrompt.innerHTML = `
        <p>Install YTHT Downloader for quick access?</p>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm">Install</button>
            <button class="btn btn-outline-secondary btn-sm">Not Now</button>
        </div>
    `;

    document.body.appendChild(installPrompt);

    installPrompt.querySelector('.btn-primary').addEventListener('click', async () => {
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;

        if (outcome === 'accepted') installPrompt.remove();

        deferredPrompt = null;
    });

    installPrompt.querySelector('.btn-outline-secondary')
        .addEventListener('click', () => installPrompt.remove());
});

/**
 * Initialize App
 */
document.addEventListener('DOMContentLoaded', () => new YTHTDownloader());
