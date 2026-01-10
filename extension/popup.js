class YTHTExtension {
    constructor() {
        this.currentUrl = null;
        this.init();
    }

    async init() {
        try {
            // Get current active tab
            const [tab] = await chrome.tabs.query({ active: true, currentWindow: true });
            
            if (this.isSupportedUrl(tab.url)) {
                this.currentUrl = tab.url;
                this.showVideoFound();
                
                // Add event listeners
                document.getElementById('downloadMp4').addEventListener('click', () => {
                    this.downloadVideo('mp4');
                });
                
                document.getElementById('downloadMp3').addEventListener('click', () => {
                    this.downloadVideo('mp3');
                });
            } else {
                this.showNoVideo();
            }
        } catch (error) {
            console.error('Error initializing extension:', error);
            this.showNoVideo();
        }
    }

    isSupportedUrl(url) {
        const supportedPatterns = [
            /youtube\.com\/watch\?v=/,
            /youtu\.be\//,
            /facebook\.com\/.*\/videos\//,
            /fb\.watch\//
        ];
        
        return supportedPatterns.some(pattern => pattern.test(url));
    }

    showVideoFound() {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('noVideo').style.display = 'none';
        document.getElementById('videoFound').style.display = 'block';
        
        // Display shortened URL
        const urlDisplay = document.getElementById('currentUrl');
        const url = new URL(this.currentUrl);
        urlDisplay.textContent = `${url.hostname}${url.pathname.substring(0, 30)}...`;
    }

    showNoVideo() {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('videoFound').style.display = 'none';
        document.getElementById('noVideo').style.display = 'block';
    }

    downloadVideo(format) {
        const webAppUrl = 'https://ytht.tmsht.com'; // Change to local for development
        const downloadUrl = `${webAppUrl}/?url=${encodeURIComponent(this.currentUrl)}&format=${format}`;
        
        // Open in new tab
        chrome.tabs.create({ url: downloadUrl });
        
        // Close popup
        window.close();
    }
}

// Initialize extension when popup loads
document.addEventListener('DOMContentLoaded', () => {
    new YTHTExtension();
});