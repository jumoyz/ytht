Let me dig into the codebase to understand what's going on.

Now let me check the Validator class and look at recent git changes:

Let me check the .env file and look at the git log differently:

Now I'll provide a comprehensive analysis:

Now let me look at the full `Downloader.php` to see the exact state of the code:

Found it. There are two clear issues. Let me also check the `getVideoInfo` method more carefully and the `.env` file for any ffmpeg config:

I have a clear picture now. Here are the issues I found and the fixes:

**Issue 1 — Hardcoded ffmpeg path (breaks everything except one dev machine)**

In `buildCommand()`, the ffmpeg path is hardcoded to `C:/Users/jumoy/Downloads/ffmpeg/bin`. This breaks MP3 on any other machine, and on Linux entirely.

**Issue 2 — `escapeshellcmd()` wrapping an already-escaped binary path**

In `getVideoInfo()`, `escapeshellcmd("$binary -J ...")` is called where `$binary` is already wrapped in `escapeshellarg()`. This can mangle the quotes and cause the command to fail.

Let me fix both:

Now fix the hardcoded ffmpeg path to be OS-aware and use the bundled binary or fall back to the system install:

Now let me also add a `FFMPEG_PATH` option to `.env` and `Config.php` so it can be overridden per environment without touching code:

Now update `buildCommand` to also check `Config::getFfmpegPath()` as a higher-priority option:

Now remove the duplicate `$isWindows` declaration since it's now set in the ffmpeg block:

I can see there's a duplicate `$isWindows` line. Let me clean that up:

Now add `FFMPEG_PATH` to `.env` so it's documented and easy to configure per environment:

Here's a summary of what was broken and what was fixed:

**Root cause 1 — hardcoded ffmpeg path**
`buildCommand()` had `$ffmpegPath = "C:/Users/jumoy/Downloads/ffmpeg/bin"` baked in. This broke MP3 downloads on every machine except that one dev box, and completely on Linux. Now it resolves ffmpeg in priority order: `FFMPEG_PATH` env var → bundled `services/ffmpeg[.exe]` → system PATH.

**Root cause 2 — double-escaping the binary path**
`getVideoInfo()` was wrapping the binary in both `escapeshellarg()` (in `getBinaryPath()`) and then `escapeshellcmd()` again. That mangled the quotes around the path, causing the command to fail silently. Fixed to just concatenate directly like `buildCommand()` already did.

**What you need to do per environment:**
- On Linux: make sure `ffmpeg` is installed (`apt install ffmpeg` or similar), or drop an `ffmpeg` binary into `services/`
- On Windows: either install ffmpeg and add it to PATH, or set `FFMPEG_PATH=C:/your/ffmpeg/bin` in `.env`
- MP4 downloads don't need ffmpeg at all, so those should work immediately


======

No 24-hour auto-delete. The file is deleted immediately after it's streamed to the user via `unlink($filepath)` at the end. So the lifecycle is: convert → save to `downloads/` → user hits `download.php` → file streams → file is deleted right away.

There's no scheduled cleanup or time-based expiry anywhere. If a user never clicks the download link, the file just sits in `downloads/` forever. You'd need a cron job (Linux) or scheduled task (Windows) to handle that case, something like:

```bash
# Linux cron - delete files older than 24h in downloads/
0 * * * * find /path/to/public/downloads/ -type f -mmin +1440 -delete
```

Or you could handle it in PHP by adding a cleanup sweep at the top of `convert.php` before each new download runs.