<?php
// Get current page for active navigation
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="mb-4">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-download me-2"></i>
                <?php echo Config::APP_NAME; ?>
                <small class="badge bg-danger ms-1">v<?php echo Config::VERSION; ?></small>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!--
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>" href="/">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'contact.php' ? 'active' : ''; ?>" href="contact.php">
                            <i class="fas fa-envelope me-1"></i>Contact
                        </a>
                    </li>
                </ul> -->
                
                <ul class="navbar-nav ms-auto">
                    <!-- Language Switcher -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-language me-1"></i><?php echo strtoupper($language); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?php echo $language === 'en' ? 'active' : ''; ?>" href="?lang=en"><i class="flag-icon flag-icon-us me-2"></i>English</a></li>
                            <li><a class="dropdown-item <?php echo $language === 'fr' ? 'active' : ''; ?>" href="?lang=fr"><i class="flag-icon flag-icon-fr me-2"></i>Français</a></li>
                            <li><a class="dropdown-item <?php echo $language === 'ht' ? 'active' : ''; ?>" href="?lang=ht"><i class="flag-icon flag-icon-ht me-2"></i>Kreyòl</a></li>
                            <li><a class="dropdown-item <?php echo $language === 'es' ? 'active' : ''; ?>" href="?lang=es"><i class="flag-icon flag-icon-es me-2"></i>Español</a></li>
                        </ul>
                    </li>
                    
                    <!-- Extension Download -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#extensionModal">
                            <i class="fas fa-puzzle-piece me-1"></i>Get Extension
                        </a>
                    </li>
                    
                    <!-- Login/Register -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>Account
                        </a>
                        <ul class="dropdown-menu">
                            <?php if(isset($_SESSION['user'])): ?>
                                <li><span class="dropdown-item-text">Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-history me-2"></i>Download History</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fas fa-sign-in-alt me-2"></i>Login</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#registerModal"><i class="fas fa-user-plus me-2"></i>Register</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Extension Download Modal -->
<div class="modal fade" id="extensionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-puzzle-piece me-2"></i>Browser Extension
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <div class="mb-4">
                            <i class="fas fa-chrome fa-3x text-primary mb-3"></i>
                            <h5>Chrome Extension</h5>
                            <p>One-click downloads from YouTube & Facebook</p>
                            <button class="btn btn-primary">
                                <i class="fas fa-download me-2"></i>Install for Chrome
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="mb-4">
                            <i class="fab fa-edge fa-3x text-info mb-3"></i>
                            <h5>Edge Extension</h5>
                            <p>Works on Microsoft Edge browser</p>
                            <button class="btn btn-info text-white">
                                <i class="fas fa-download me-2"></i>Install for Edge
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-info-circle me-2"></i>Features:</h6>
                    <ul class="mb-0">
                        <li>Detects videos automatically</li>
                        <li>One-click MP4/MP3 download</li>
                        <li>Works with YouTube and Facebook</li>
                        <li>Free and easy to use</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-sign-in-alt me-2"></i>Login to YTHT
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="loginEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="loginPassword" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                    
                    <div class="text-center">
                        <p class="mb-3">Or login with</p>
                        <button type="button" class="btn btn-outline-danger w-100" onclick="googleLogin()">
                            <i class="fab fa-google me-2"></i>Google
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <a href="#" class="text-decoration-none">Forgot password?</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Create Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="registerForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="registerEmail" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="registerEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="registerPassword" required>
                        <div class="form-text">Minimum 8 characters</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="acceptTerms" required>
                        <label class="form-check-label" for="acceptTerms">
                            I agree to the <a href="terms.php" target="_blank">Terms of Service</a> and <a href="privacy.php" target="_blank">Privacy Policy</a>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-success w-100 mb-3">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </button>
                    
                    <div class="text-center">
                        <p class="mb-3">Or register with</p>
                        <button type="button" class="btn btn-outline-danger w-100" onclick="googleLogin()">
                            <i class="fab fa-google me-2"></i>Google
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add flag icons CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">

<style>
.flag-icon {
    border-radius: 2px;
}
.navbar-dark {
    background: linear-gradient(135deg, #343a40 0%, #495057 100%) !important;
}
.dropdown-item.active {
    background-color: #dc3545;
    border-color: #dc3545;
}
</style>

<script>
function googleLogin() {
    // Simulate Google login - in production, use Google OAuth
    alert('Google login would open here. This is a demo feature.');
    
    // For demo purposes, simulate successful login
    setTimeout(() => {
        alert('Login successful! (Demo)');
        $('#loginModal').modal('hide');
        $('#registerModal').modal('hide');
        location.reload(); // Refresh to show logged-in state
    }, 1000);
}

// Handle login form
document.getElementById('loginForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Login functionality would be implemented here.');
});

// Handle register form
document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Registration functionality would be implemented here.');
});
</script>