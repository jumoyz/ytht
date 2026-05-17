<?php
/**
 * Downloader class for YTHT Downloader
 * Handles video/audio downloading using yt-dlp or youtube-dl
 * Validates URLs, manages output paths, and executes download commands
 */
class Downloader {
    private $url;
    private $format;
    private $outputDir;

    public function __construct($url, $format) {
        $this->url = $url;
        $this->format = $format;
        $this->outputDir = Config::getDownloadPath();

        // Ensure download directory exists
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0755, true);
        }
    }
    /*
    public function download() {
        try {
            // Validate URL first
            Validator::validateUrl($this->url);

            // Check if yt-dlp or youtube-dl exists
            $binary = $this->getBinaryPath();

            // Get video info
            $info = $this->getVideoInfo($binary);

            // Generate output filename
            $filename = $this->generateFilename($info);
            $outputPath = $this->outputDir . $filename;

            // Build command
            $command = $this->buildCommand($binary, $outputPath);

            // Execute download
            $result = $this->executeCommand($command);

            // Verify file was created
            if (!file_exists($outputPath)) {
                throw new Exception('Download failed - file not created');
            }

            // Check file size
            $fileSize = filesize($outputPath);
            if ($fileSize === 0) {
                unlink($outputPath);
                throw new Exception('Download failed - empty file');
            }

            if ($fileSize > Config::getMaxFileSize()) {
                unlink($outputPath);
                throw new Exception('File too large');
            }

            return [
                'success' => true,
                'filename' => $filename,
                'filepath' => $outputPath,
                'filesize' => $this->formatFileSize($fileSize),
                'title' => $info['title'] ?? 'download'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    */
    public function download() {
        try {
            // Validate URL first
            Validator::validateUrl($this->url);

            // Check if yt-dlp or youtube-dl exists
            $binary = $this->getBinaryPath();

            // Get video info (yt-dlp -J returns JSON metadata)
            $info = $this->getVideoInfo($binary);

            // Generate output filename
            $filename = $this->generateFilename($info);
            $outputPath = $this->outputDir . $filename;

            // Build command
            $command = $this->buildCommand($binary, $outputPath);

            // Execute download
            $result = $this->executeCommand($command);

            // Verify file was created
            if (!file_exists($outputPath)) {
                throw new Exception('Download failed - file not created');
            }

            // Check file size
            $fileSize = filesize($outputPath);
            if ($fileSize === 0) {
                unlink($outputPath);
                throw new Exception('Download failed - empty file');
            }

            if ($fileSize > Config::getMaxFileSize()) {
                unlink($outputPath);
                throw new Exception('File too large');
            }

            // Return enriched metadata
            return [
                'success'     => true,
                'filename'    => $filename,
                'filesize'    => $this->formatFileSize($fileSize),
                'title'       => $info['title'] ?? 'Unknown',
                'description' => isset($info['description']) ? mb_strimwidth($info['description'], 0, 150, '...') : '',
                'thumbnail'   => $info['thumbnail'] ?? '',
                'channel'     => $info['uploader'] ?? 'Unknown',
                'publishDate' => isset($info['upload_date']) ? date("Y-m-d", strtotime($info['upload_date'])) : '',
                'duration'    => isset($info['duration']) ? gmdate("H:i:s", $info['duration']) : '',
                'filepath'    => $outputPath,
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage()
            ];
        }
    }
    /*
    private function getBinaryPath() {
        $ytDlp = __DIR__ . '/../services/yt-dlp.exe';
        $youtubeDl = __DIR__ . '/../services/youtube-dl.exe';

        if (file_exists($ytDlp)) {
            return escapeshellarg($ytDlp);
        } elseif (file_exists($youtubeDl)) {
            return escapeshellarg($youtubeDl);
        } else {
            throw new Exception('Neither yt-dlp nor youtube-dl found in /services/');
        }
    }
    */
    private function getBinaryPath() {
        // Detect OS
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        if ($isWindows) {
            // Windows executables
            $ytDlp = __DIR__ . '/../services/yt-dlp.exe';
            $youtubeDl = __DIR__ . '/../services/youtube-dl.exe';
        } else {
            // Linux binaries (no .exe)
            $ytDlp = __DIR__ . '/../services/yt-dlp';
            $youtubeDl = __DIR__ . '/../services/youtube-dl';
        }

        if (file_exists($ytDlp)) {
            return escapeshellarg($ytDlp);
        }

        if (file_exists($youtubeDl)) {
            return escapeshellarg($youtubeDl);
        }

        $globalYtDlp = $this->findExecutable('yt-dlp');
        if ($globalYtDlp) {
            return escapeshellarg($globalYtDlp);
        }

        $globalYoutubeDl = $this->findExecutable('youtube-dl');
        if ($globalYoutubeDl) {
            return escapeshellarg($globalYoutubeDl);
        }

        throw new Exception('Neither yt-dlp nor youtube-dl found in /services/ or globally');
    }
    
    /*
    private function getVideoInfo($binary) {
        $command = $binary . ' --dump-json ' . escapeshellarg($this->url) . ' 2>&1';
        $output = shell_exec($command);

        if (!$output) {
            throw new Exception('Could not retrieve video information');
        }

        return json_decode($output, true);
    }
    */
    
    private function getVideoInfo($binary) {
        $command = $binary . ' -J --no-warnings --no-update ' . escapeshellarg($this->url) . ' 2>&1';
        $output = shell_exec($command);
        if (!$output) {
            throw new Exception('Failed to retrieve video info');
        }

        $info = json_decode($output, true);
        if (!is_array($info)) {
            throw new Exception('Failed to parse video info: ' . trim($output));
        }

        return $info;
    }
    /*
    private function buildCommand($binary, $outputPath) {
        $command = $binary . ' -o ' . escapeshellarg($outputPath);

        if ($this->format === 'mp3') {
            $command .= ' --extract-audio --audio-format mp3 --audio-quality 0';
        } else {
            $command .= ' --format "best[height<=720]"'; // Limit to 720p
        }

        $command .= ' ' . escapeshellarg($this->url);
        return $command;
    }
    */
    private function buildCommand($binary, $outputPath) {
        $ffmpeg = $this->resolveFfmpeg();

        // Keep yt-dlp in charge of provider quirks. Forcing a single YouTube
        // player client can make normal videos report "No video formats found".
        $command = $binary . ' --no-warnings --no-update --socket-timeout 30 --retries 3';
        $command .= ' -o ' . escapeshellarg($outputPath);

        if (!empty($ffmpeg['locationOption'])) {
            $command .= $ffmpeg['locationOption'];
        }

        if ($this->format === 'mp3') {
            if (!$ffmpeg['available']) {
                throw new Exception('FFmpeg is required for MP3 conversion. Install ffmpeg or set FFMPEG_PATH.');
            }

            $command .= ' --extract-audio --audio-format mp3 --audio-quality 0';
        } elseif ($ffmpeg['available']) {
            $command .= ' --format "bestvideo[height<=720][ext=mp4]+bestaudio[ext=m4a]/best[height<=720][ext=mp4]/best[height<=720]" --merge-output-format mp4';
        } else {
            $command .= ' --format "best[height<=720][ext=mp4][vcodec!=none][acodec!=none]/best[height<=720][vcodec!=none][acodec!=none]"';
        }

        $command .= ' ' . escapeshellarg($this->url);
        return $command;
    }

    private function resolveFfmpeg() {
        $envFfmpeg = Config::getFfmpegPath();
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $ffmpegBin = $isWindows ? 'ffmpeg.exe' : 'ffmpeg';
        $bundledFfmpegPaths = [
            __DIR__ . '/../services/' . $ffmpegBin,
            __DIR__ . '/../services/ffmpeg/bin/' . $ffmpegBin,
            __DIR__ . '/../services/ffmpeg/' . $ffmpegBin,
        ];

        if ($envFfmpeg) {
            return [
                'available' => true,
                'locationOption' => ' --ffmpeg-location ' . escapeshellarg($envFfmpeg),
            ];
        }

        foreach ($bundledFfmpegPaths as $bundledFfmpeg) {
            if (file_exists($bundledFfmpeg)) {
                return [
                    'available' => true,
                    'locationOption' => ' --ffmpeg-location ' . escapeshellarg(realpath($bundledFfmpeg)),
                ];
            }
        }

        return [
            'available' => (bool)$this->findExecutable($ffmpegBin),
            'locationOption' => '',
        ];
    }

    private function findExecutable($name) {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $command = $isWindows ? 'where ' . escapeshellarg($name) : 'command -v ' . escapeshellarg($name);
        $nullDevice = $isWindows ? 'NUL' : '/dev/null';
        $output = [];
        $returnCode = 0;

        @exec($command . ' 2>' . $nullDevice, $output, $returnCode);
        if ($returnCode !== 0 || empty($output)) {
            return null;
        }

        $path = trim($output[0]);
        return $path !== '' ? $path : null;
    }

    private function executeCommand($command) {
        $output = [];
        $returnCode = 0;

        exec($command . ' 2>&1', $output, $returnCode);

        if ($returnCode !== 0) {
            $errorMessage = $this->parseDownloadError($output);
            throw new Exception($errorMessage);
        }

        return $output;
    }

    /**
     * Parse yt-dlp output and extract meaningful error messages
     */
    private function parseDownloadError($output) {
        // Look for ERROR lines first
        $errorLines = [];
        foreach ($output as $line) {
            if (strpos($line, 'ERROR:') !== false) {
                $errorLines[] = trim(str_replace('ERROR:', '', $line));
            }
        }

        if (!empty($errorLines)) {
            $mainError = implode(' | ', $errorLines);
            
            // Provide helpful context for common errors
            if (strpos($mainError, '403') !== false) {
                return 'Access denied (HTTP 403). The video may be private, geoblocked, or YouTube blocked the request. Try updating yt-dlp: yt-dlp -U';
            }
            if (strpos($mainError, '404') !== false) {
                return 'Video not found (HTTP 404). The video may have been deleted or the URL is invalid.';
            }
            if (strpos($mainError, 'unable to download') !== false) {
                return 'Download failed: ' . $mainError . '. Try updating yt-dlp: yt-dlp -U';
            }
            if (strpos($mainError, 'SABR') !== false || strpos($mainError, 'streaming') !== false) {
                return 'YouTube is blocking this download. Update yt-dlp and try again: yt-dlp -U';
            }
            
            return 'Download failed: ' . $mainError;
        }

        // If no ERROR lines, look for last few meaningful lines
        $lastLines = array_filter(array_slice($output, -5), function($line) {
            return !empty(trim($line)) && strpos($line, 'WARNING') === false;
        });

        if (!empty($lastLines)) {
            return 'Download failed: ' . implode(' | ', $lastLines);
        }

        return 'Download failed: Unknown error. Check yt-dlp is installed and updated.';
    }

    private function generateFilename($info) {
        $title = $info['title'] ?? 'video';
        $sanitizedTitle = Validator::sanitizeFilename($title);
        $extension = $this->format === 'mp3' ? 'mp3' : 'mp4';

        return $sanitizedTitle . '_' . time() . '.' . $extension;
    }

    private function formatFileSize($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
?>
