// Content script to detect video pages and communicate with extension

class VideoDetector {
    constructor() {
        this.detectVideo();
    }

    detectVideo() {
        // YouTube detection
        if (window.location.hostname.includes('youtube')) {
            this.detectYouTube();
        }
        // Facebook detection
        else if (window.location.hostname.includes('facebook')) {
            this.detectFacebook();
        }
    }

    detectYouTube() {
        const videoId = this.getYouTubeVideoId();
        if (videoId) {
            this.notifyExtension({
                type: 'VIDEO_DETECTED',
                videoId: videoId,
                url: window.location.href,
                platform: 'youtube'
            });
        }
    }

    detectFacebook() {
        // Facebook video detection
        const videoUrl = window.location.href;
        if (videoUrl.includes('/videos/') || videoUrl.includes('fb.watch')) {
            this.notifyExtension({
                type: 'VIDEO_DETECTED',
                url: videoUrl,
                platform: 'facebook'
            });
        }
    }

    getYouTubeVideoId() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('v');
    }

    notifyExtension(message) {
        // Store in localStorage for popup to read
        localStorage.setItem('ytht_current_video', JSON.stringify(message));
    }
}

// Initialize detector
new VideoDetector();

// Also listen for URL changes (SPA navigation)
let lastUrl = location.href;
new MutationObserver(() => {
    const url = location.href;
    if (url !== lastUrl) {
        lastUrl = url;
        setTimeout(() => new VideoDetector(), 1000);
    }
}).observe(document, { subtree: true, childList: true });