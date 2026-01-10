<header class="mb-4">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-download me-2"></i>
                <?php echo Config::APP_NAME; ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#aboutModal">
                            <i class="fas fa-info-circle me-1"></i>About
                        </a>
                    </li>
                    <!-- Login/Register Modal and User Avater Menu-->
                    
                </ul>
            </div>
        </div>
    </nav>
</header>

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