<footer class="mt-5 py-4 text-center text-white">
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> YTHT Downloader. All rights reserved.</p>
        <p class="small">
            <a href="#" class="text-white text-decoration-none me-3" data-bs-toggle="modal" data-bs-target="#aboutModal">
                About
            </a>
            <a href="#" class="text-white text-decoration-none me-3" data-bs-toggle="modal" data-bs-target="#contactModal">
                Conatct
            </a>
            <a href="#" class="text-white text-decoration-none me-3" data-bs-toggle="modal" data-bs-target="#privacyModal">
                Privacy Policy
            </a>
            <a href="#" class="text-white text-decoration-none" data-bs-toggle="modal" data-bs-target="#termsModal">
                Terms of Service
            </a>
        </p>
    </div>
</footer>

<!-- About Modal -->
<div class="modal fade" id="aboutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">About YTHT Downloader</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Version:</strong> <?php echo Config::VERSION; ?></p>
                <p>A simple tool to download YouTube and Facebook videos as MP4 or MP3 files.</p>
                <p><small class="text-muted">This tool is for personal use only. Please respect copyright laws.</small></p>
            </div>
        </div>
    </div>
</div>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                            
            </div>
        </div>
    </div>
</div>

<!-- Privacy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Privacy Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>We respect your privacy. This application:</p>
                <ul>
                    <li>Does not store your downloaded videos on our servers</li>
                    <li>Only processes URLs you provide</li>
                    <li>Does not collect personal information</li>
                    <li>Uses temporary files that are automatically deleted</li>
                </ul>
                <p>View our full <a href="privacy.php" target="_blank">Privacy Policy</a> and <a href="terms.php" target="_blank">Terms of Service</a></p>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms of Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>By using this service, you agree to:</p>
                <ul>
                    <li>Use it for personal, non-commercial purposes only</li>
                    <li>Respect copyright laws and platform terms of service</li>
                    <li>Not abuse the service with excessive requests</li>
                    <li>Accept that we're not responsible for misuse</li>
                </ul>
                <p>View our full <a href="privacy.php" target="_blank">Privacy Policy</a> and <a href="terms.php" target="_blank">Terms of Service</a></p>
            </div>
        </div>
    </div>
</div>