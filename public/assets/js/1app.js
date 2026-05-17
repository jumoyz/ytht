class YTHTDownloader {
    constructor() {
        this.init();
        this.registerServiceWorker();
    }

    init() {
        const form = document.getElementById('downloadForm');
        const buttons = form.querySelectorAll('button[data-format]');
        
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const format = button.getAttribute('data-format');
                this.handleDownload(format);
            });
        });

        // Check if page was loaded offline
        if (!navigator.onLine) {
            this.showOfflineMessage();
        }

        // Listen for online/offline events
        window.addEventListener('online', () => this.hideOfflineMessage());
        window.addEventListener('offline', () => this.showOfflineMessage());
    }
    /*
    async handleDownload(format) {
        const urlInput = document.getElementById('videoUrl');
        const url = urlInput.value.trim();

        if (!url) {
            this.showError('Please enter a video URL');
            return;
        }

        this.showProgress();
        
        try {
            /*
            const response = await fetch('convert.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    url: url,
                    format: format
                })
            });
            */
           /*
            fetch('convert.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    url: document.getElementById('videoUrl').value,
                    format: format
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    console.log('Downloaded:', data);
                } else {
                    alert(data.error);
                }
            });

            const result = await response.json();
            
            if (result.success) {
                this.showSuccess(result, format);
            } else {
                this.showError(result.error);
            }
        } catch (error) {
            this.showError('Network error: ' + error.message);
        } finally {
            this.hideProgress();
        }
    }
    */
   async handleDownload(format) {
        const urlInput = document.getElementById('videoUrl');
        const url = urlInput.value.trim();

        if (!url) {
            this.showError('Please enter a video URL');
            return;
        }

        this.showProgress();

        try {
            const response = await fetch('convert.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ url, format })
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result, format);
            } else {
                this.showError(result.error);
            }
        } catch (error) {
            this.showError('Network error: ' + error.message);
        } finally {
            this.hideProgress();
        }
    }

    showProgress() {
        document.getElementById('progressSection').classList.remove('d-none');
        document.getElementById('resultSection').classList.add('d-none');
        
        // Simulate progress animation
        let progress = 0;
        const progressBar = document.getElementById('progressBar');
        const interval = setInterval(() => {
            progress += Math.random() * 10;
            if (progress >= 90) {
                clearInterval(interval);
            }
            progressBar.style.width = Math.min(progress, 90) + '%';
        }, 500);
    }

    hideProgress() {
        document.getElementById('progressSection').classList.add('d-none');
        document.getElementById('progressBar').style.width = '0%';
    }
    /*
    showSuccess(result, format) {
        const resultSection = document.getElementById('resultSection');
        const downloadUrl = `downloads/${result.filename}`;
        
        resultSection.innerHTML = `
            <div class="alert alert-success">
                <h4><i class="fas fa-check-circle"></i> Download Ready!</h4>
                <p><strong>Title:</strong> ${result.title}</p>
                <p><strong>Size:</strong> ${result.filesize}</p>
                <p><strong>Format:</strong> ${format.toUpperCase()}</p>
                <div class="mt-3">
                    <a href="${downloadUrl}" class="btn btn-success" download>
                        <i class="fas fa-download me-2"></i>
                        Download ${format.toUpperCase()}
                    </a>
                    <button class="btn btn-outline-secondary" onclick="this.closest('.alert').remove()">
                        Close
                    </button>
                </div>
            </div>
        `;
        resultSection.classList.remove('d-none');
    }
    */
    showSuccess(result, format) {
        const resultSection = document.getElementById('resultSection');
        //const downloadUrl = `downloads/${result.filename}`;
        const downloadUrl = `download.php?file=${encodeURIComponent(result.filename)}`;

        resultSection.innerHTML = `
            <div class="alert alert-success">
                <h4><i class="fas fa-check-circle"></i> Your Download is Ready!</h4>
                <div class="row">
                    <div class="col-md-4">
                        <img src="${result.thumbnail}" alt="Thumbnail" class="img-fluid rounded mb-2">
                    </div>
                    <div class="col-md-8">
                        <p><strong>Title:</strong> ${result.title}</p>
                        <p><strong>Description:</strong> ${result.description || 'No description available.'}</p>
                        <p><strong>Channel:</strong> ${result.channel || 'Unknown'}</p>
                        <p><strong>Published:</strong> ${result.publishDate || 'N/A'}</p>
                        <p><strong>Duration:</strong> ${result.duration || 'N/A'}</p>
                        <p><strong>Size:</strong> ${result.filesize}</p>
                        <p><strong>Format:</strong> ${format.toUpperCase()}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="${downloadUrl}" class="btn btn-success" download>
                        <i class="fas fa-download me-2"></i>
                        Download ${format.toUpperCase()}
                    </a>
                    <button class="btn btn-outline-secondary" onclick="this.closest('.alert').remove()">
                        Close
                    </button>
                </div>
            </div>
        `;
        resultSection.classList.remove('d-none');
    }

    showError(message) {
        const resultSection = document.getElementById('resultSection');
        resultSection.innerHTML = `
            <div class="alert alert-danger">
                <h4><i class="fas fa-exclamation-triangle"></i> Error</h4>
                <p>${message}</p>
                <button class="btn btn-outline-danger btn-sm" onclick="this.closest('.alert').remove()">
                    Close
                </button>
            </div>
        `;
        resultSection.classList.remove('d-none');
    }

    showOfflineMessage() {
        // Remove existing message if any
        this.hideOfflineMessage();
        
        const message = document.createElement('div');
        message.className = 'alert alert-warning alert-dismissible fade show';
        message.innerHTML = `
            <i class="fas fa-wifi"></i> You are currently offline. Some features may not be available.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        message.id = 'offlineAlert';
        
        document.querySelector('main').prepend(message);
    }

    hideOfflineMessage() {
        const existingAlert = document.getElementById('offlineAlert');
        if (existingAlert) {
            existingAlert.remove();
        }
    }

    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                await navigator.serviceWorker.register('/service-worker.js');
                console.log('Service Worker registered successfully');
            } catch (error) {
                console.log('Service Worker registration failed:', error);
            }
        }
    }
}

// Utility functions
function clearUrl() {
    document.getElementById('videoUrl').value = '';
    document.getElementById('videoUrl').focus();
}

// PWA Install Prompt
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    
    // Show install prompt
    const installPrompt = document.createElement('div');
    installPrompt.id = 'installPrompt';
    installPrompt.innerHTML = `
        <p>Install YTHT Downloader for quick access?</p>
        <button class="btn btn-primary btn-sm me-2" onclick="installApp()">Install</button>
        <button class="btn btn-outline-secondary btn-sm" onclick="this.parentElement.remove()">Not Now</button>
    `;
    document.body.appendChild(installPrompt);
});

async function installApp() {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        if (outcome === 'accepted') {
            document.getElementById('installPrompt')?.remove();
        }
        deferredPrompt = null;
    }
}

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new YTHTDownloader();
});