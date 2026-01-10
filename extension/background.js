// Background script for extension
chrome.runtime.onInstalled.addListener(() => {
    console.log('YTHT Downloader extension installed');
});

// Handle messages from content scripts
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    if (request.type === 'VIDEO_DETECTED') {
        // Update extension icon badge
        chrome.action.setBadgeText({
            text: '✓',
            tabId: sender.tab.id
        });
        chrome.action.setBadgeBackgroundColor({
            color: '#28a745',
            tabId: sender.tab.id
        });
    }
});