<?php
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
                'description' => $info['description'] ?? '',
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
        $command = escapeshellcmd("$binary -J " . escapeshellarg($this->url));
        $output = shell_exec($command);
        if (!$output) {
            throw new Exception('Failed to retrieve video info');
        }
        return json_decode($output, true);
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
        // Adjust this path to wherever ffmpeg is installed
        $ffmpegPath = "C:/Users/jumoy/Downloads/ffmpeg/bin";

        // Base command with extractor args to silence JS runtime warnings
        $command = $binary . ' --extractor-args "youtube:player_client=default" -o ' . escapeshellarg($outputPath);

        if ($this->format === 'mp3') {
            // Audio-only download with conversion to MP3
            $command .= ' --ffmpeg-location ' . escapeshellarg($ffmpegPath)
                    . ' --extract-audio --audio-format mp3 --audio-quality 0';
        } else {
            // Standard video download, limited to 720p
            $command .= ' --format "best[height<=720]"';
        }

        $command .= ' ' . escapeshellarg($this->url);
        return $command;
    }

    private function executeCommand($command) {
        $output = [];
        $returnCode = 0;

        exec($command . ' 2>&1', $output, $returnCode);

        if ($returnCode !== 0) {
            throw new Exception('Download failed: ' . implode("\n", $output));
        }

        return $output;
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