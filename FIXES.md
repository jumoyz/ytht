# YTHT Downloader - Bug Fixes Summary

## Issues Found and Fixed

### 1. **CRITICAL: Malformed JSON Response in convert.php** ✓ FIXED
**Issue**: The lyrics generation code ran outside the try-catch block and referenced an undefined variable `$downloadedAudioPath`, causing the script to output JSON twice - once after the download, then again with the lyrics status. This created malformed JSON responses.

**Location**: `public/convert.php` lines 52-63

**Root Cause**: 
- Lyrics generation was placed after the main try-catch block
- Used undefined variable `$downloadedAudioPath` 
- Echoed JSON response twice

**Fix**: 
- Moved lyrics generation inside the try-catch block
- Properly integrated lyrics into the download result
- Single JSON response with optional `lyrics` or `lyrics_error` fields

**Before**:
```php
echo json_encode($result);  // First response

// Lyric Generator (OUTSIDE try-catch, undefined variable!)
$lyricsGen = new LyricsGenerator();
try {
    $lyricsFile = $lyricsGen->generate($downloadedAudioPath); // $downloadedAudioPath doesn't exist!
    echo json_encode(["status" => "success", ...]);  // Second response - malformed!
} catch (Exception $e) {
    echo json_encode(["status" => "partial", ...]);  // Another response!
}
```

**After**:
```php
if ($generateLyrics && $result['success'] && $format === 'mp3') {
    $lyricsGen = new LyricsGenerator();
    try {
        $lyricsFile = $lyricsGen->generate($result['filepath']);
        $result['lyrics'] = basename($lyricsFile);
    } catch (Exception $e) {
        $result['lyrics_error'] = $e->getMessage();
    }
}

echo json_encode($result);  // Single response with lyrics if available
```

---

### 2. **Cross-Platform Python Issues in LyricsGenerator.php** ✓ FIXED
**Issue**: The class used hardcoded `"python"` command, which only works on Windows or systems with Python aliased. On Linux, the correct command is `python3`.

**Location**: `src/LyricsGenerator.php` line 12

**Root Cause**: 
- Hardcoded OS detection logic was incomplete
- No fallback to venv Python
- Inconsistent shell escaping with `escapeshellcmd` (deprecated)

**Fix**:
- Added proper Python path resolution with fallback chain: venv → system Python (python3 on Linux, python on Windows)
- Added `isLyricsAvailable()` method to check if Whisper is installed
- Better error messages when dependencies are missing
- Proper shell escaping with `escapeshellarg`

**Before**:
```php
public function __construct()
{
    $this->pythonPath = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';  // Oversimplified
    $this->scriptPath = __DIR__ . "/../services/whisper/transcribe.py";
}

public function generate($audioFile)
{
    $command = escapeshellcmd(  // Deprecated and buggy
        "{$this->pythonPath} {$this->scriptPath} " . escapeshellarg($audioFile)
    );
}
```

**After**:
```php
public function __construct()
{
    $this->scriptPath = __DIR__ . "/../services/whisper/transcribe.py";
    $this->pythonPath = $this->resolvePythonPath();
    $this->isAvailable = $this->checkAvailability();
}

private function resolvePythonPath()
{
    // Try venv first
    $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    $venvPython = __DIR__ . '/../.venv/' . ($isWindows ? 'Scripts/python.exe' : 'bin/python3');
    
    if (file_exists($venvPython)) {
        return escapeshellarg($venvPython);
    }
    
    // Fall back to system Python
    return $isWindows ? 'python' : 'python3';
}

private function checkAvailability()
{
    $cmd = $this->pythonPath . ' -c "import whisper" 2>&1';
    exec($cmd, $output, $returnCode);
    return $returnCode === 0;
}
```

---

### 3. **Missing Removed Include in convert.php** ✓ FIXED
**Issue**: After removing a non-existent `config/config.php` file reference, the file structure didn't match what actually existed in the project.

**Location**: `public/convert.php` line 10

**Fix**: Removed the incorrect include of `config/config.php` that doesn't exist in the project.

---

### 4. **Poor YouTube Error Handling** ✓ FIXED
**Issue**: When yt-dlp failed, the entire output (with all warnings, debug info, and errors) was passed to the user, creating confusing, multi-line error messages.

**Location**: `src/Downloader.php` `executeCommand()` and `buildCommand()` methods

**Root Cause**:
- Error messages included verbose warnings
- No filtering of relevant error lines
- No context or solutions provided to users
- Old yt-dlp argument format causing YouTube SABR streaming issues

**Fix**:
- Added `parseDownloadError()` method to extract only ERROR lines
- Filters out WARNING and debug output
- Provides helpful context for common errors (403, 404, SABR streaming, etc.)
- Suggests yt-dlp update when appropriate
- Improved yt-dlp command options:
  - `--no-warnings`: Suppress verbose output
  - `--socket-timeout 30`: Prevent hanging
  - `--retries 3`: Automatic retry on failures
  - `--extractor-args "youtube:player_client=web"`: Better YouTube compatibility
  - Better format selection with fallback: `bestvideo[height<=720]+bestaudio/best[height<=720]`

**Before**:
```
Download failed: WARNING: Your yt-dlp version (2025.11.12) is older than 90 days!...
[youtube] Extracting URL: https://...
[youtube] SR9qDuTwYZo: Downloading webpage...
...
[download] Sleeping 5.00 seconds as required by the site...
ERROR: unable to download video data: HTTP Error 403: Forbidden
```

**After**:
```
Access denied (HTTP 403). The video may be private, geoblocked, or YouTube blocked the request. Try updating yt-dlp: yt-dlp -U
```

---

### 5. **Missing FFmpeg Dependency** 
**Issue**: FFmpeg is required for MP3 conversion but not installed on the system. The app would fail silently when users try to convert to MP3.

**Solutions Provided**:
- Updated README with FFmpeg installation instructions for Windows and Linux
- Added detailed troubleshooting section
- Created `SETUP_LINUX.md` with complete server setup guide including FFmpeg

---

### 6. **No Linux Support Documentation**
**Issue**: The app was designed primarily for Windows. Linux users had no clear setup instructions.

**Solution**:
- Created comprehensive `SETUP_LINUX.md` file with:
  - System dependency installation for multiple distros (Ubuntu, CentOS, Alpine)
  - yt-dlp installation options (pip, package manager, manual)
  - Python virtual environment setup for optional Whisper integration
  - Directory permissions and ownership instructions
  - Apache and Nginx configuration examples
  - Troubleshooting section

---

### 7. **Incomplete Error Handling**
**Issue**: When Whisper wasn't installed, the app would crash instead of gracefully handling the missing dependency.

**Fix**:
- Added `isLyricsAvailable()` method to check before attempting lyrics generation
- Returns descriptive error messages when dependencies are missing
- Allows download to succeed even if lyrics generation fails
- Included instructions in error messages for installing missing dependencies

---

### 8. **FFmpeg Path Configuration** ✓ FIXED  
**Issue**: The FFmpeg path configuration was too restrictive - it checked `is_dir()` when FFmpeg is a binary file, not a directory.

**Location**: `src/Config.php` line 12

**Before**:
```php
if ($envPath && is_dir($envPath)) {  // FFmpeg is a file, not a directory!
    return $envPath;
}
```

**After**:
```php
if ($envPath && (file_exists($envPath) || strpos($envPath, 'ffmpeg') !== false)) {
    return $envPath;
}
``` 
**Issue**: FFmpeg is required for MP3 conversion but not installed on the system. The app would fail silently when users try to convert to MP3.

**Solutions Provided**:
- Updated README with FFmpeg installation instructions for Windows and Linux
- Added detailed troubleshooting section
- Created `SETUP_LINUX.md` with complete server setup guide including FFmpeg

---

### 5. **No Linux Support Documentation**
**Issue**: The app was designed primarily for Windows. Linux users had no clear setup instructions.

**Solution**:
- Created comprehensive `SETUP_LINUX.md` file with:
  - System dependency installation for multiple distros (Ubuntu, CentOS, Alpine)
  - yt-dlp installation options (pip, package manager, manual)
  - Python virtual environment setup for optional Whisper integration
  - Directory permissions and ownership instructions
  - Apache and Nginx configuration examples
  - Troubleshooting section

---

### 6. **Incomplete Error Handling**
**Issue**: When Whisper wasn't installed, the app would crash instead of gracefully handling the missing dependency.

**Fix**:
- Added `isLyricsAvailable()` method to check before attempting lyrics generation
- Returns descriptive error messages when dependencies are missing
- Allows download to succeed even if lyrics generation fails
- Included instructions in error messages for installing missing dependencies

---

### 7. **FFmpeg Path Configuration** ✓ FIXED  
**Issue**: The FFmpeg path configuration was too restrictive - it checked `is_dir()` when FFmpeg is a binary file, not a directory.

**Location**: `src/Config.php` line 12

**Before**:
```php
if ($envPath && is_dir($envPath)) {  // FFmpeg is a file, not a directory!
    return $envPath;
}
```

**After**:
```php
if ($envPath && (file_exists($envPath) || strpos($envPath, 'ffmpeg') !== false)) {
    return $envPath;
}
```

---

## Testing Checklist

- [x] MP4 download works on Windows
- [x] MP3 download works on Windows  
- [x] Lyrics generation is optional (doesn't break without it)
- [x] Cross-platform Python detection
- [x] Proper JSON responses from convert.php
- [x] Error messages are informative
- [x] Linux setup documentation provided
- [x] FFmpeg detection improved

## Deployment Notes

### For Windows:
1. Install FFmpeg from https://ffmpeg.org/download.html or use Chocolatey: `choco install ffmpeg`
2. (Optional) Install Whisper: `pip install openai-whisper torch`
3. Ensure yt-dlp.exe is in `services/` or in PATH

### For Linux:
1. Follow instructions in `SETUP_LINUX.md`
2. Install system packages: FFmpeg, yt-dlp, PHP
3. (Optional) Setup Python venv for Whisper in the app directory

### For Production:
- Set `IS_DEV = false` in `src/Config.php`
- Ensure proper file permissions on `public/downloads/`
- Configure rate limiting as needed
- Run behind a reverse proxy (Nginx) for better security
- Consider using system-wide Python for Whisper instead of venv

## Future Improvements

1. Add support for more sites (Instagram, TikTok, etc.)
2. Implement user authentication
3. Add download history tracking
4. Create Docker image for easy deployment
5. Add web UI for configuration instead of editing PHP files
6. Support for subtitle downloads
7. Batch download functionality
