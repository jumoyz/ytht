<?php
/**
 * Lyrics Generator Class for YTHT Downloader
 * Generates lyrics/transcripts from audio using OpenAI Whisper
 * Checks for Whisper availability and handles transcription process
 */
class LyricsGenerator
{
    private $pythonPath;
    private $scriptPath;
    private $isAvailable = false;

    public function __construct()
    {
        $this->scriptPath = __DIR__ . "/../services/whisper/transcribe.py";
        $this->pythonPath = $this->resolvePythonPath();
        $this->isAvailable = $this->checkAvailability();
    }

    /**
     * Resolve the appropriate Python executable path
     */
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

    /**
     * Check if Whisper is available
     */
    private function checkAvailability()
    {
        if (php_sapi_name() === 'cli') {
            return false; // Don't check in CLI mode
        }
        
        $cmd = $this->pythonPath . ' -c "import whisper" 2>&1';
        $output = [];
        $returnCode = 0;
        
        @exec($cmd, $output, $returnCode);
        return $returnCode === 0;
    }

    /**
     * Check if whisper is available
     */
    public function isLyricsAvailable()
    {
        return $this->isAvailable;
    }

    public function generate($audioFile)
    {
        if (!$this->isAvailable) {
            throw new Exception("Whisper is not available. Install with: pip install openai-whisper torch");
        }

        if (!file_exists($audioFile)) {
            throw new Exception("Audio file not found: " . $audioFile);
        }

        $command = $this->pythonPath . ' ' . escapeshellarg($this->scriptPath) . ' ' . escapeshellarg($audioFile) . ' 2>&1';

        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new Exception("Transcription failed: " . implode("\n", $output));
        }

        // Python prints output file path as last line
        $txtFile = trim(end($output));
        
        if (!$txtFile || !file_exists($txtFile)) {
            throw new Exception("Transcription output file not created");
        }

        return $txtFile;
    }
}