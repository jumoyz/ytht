<?php
session_start();
require_once __DIR__ .'/../src/Config.php';
require_once __DIR__ .'/../src/Validator.php';
require_once __DIR__ .'/../src/Downloader.php';

// Language detection
$supported_languages = ['en', 'fr', 'ht', 'es'];
$default_language = 'en';
$language = isset($_GET['lang']) && in_array($_GET['lang'], $supported_languages) 
    ? $_GET['lang'] 
    : (isset($_COOKIE['language']) ? $_COOKIE['language'] : $default_language);

setcookie('language', $language, time() + (365 * 24 * 60 * 60), '/');

// Translations
$translations = [
    'en' => [
        'title' => 'YTHT Downloader',
        'description' => 'Download YouTube and Facebook videos as MP4 or MP3',
        'placeholder' => 'Paste YouTube or Facebook URL here...',
        'download_mp4' => 'Download MP4',
        'download_mp3' => 'Download MP3',
        'loading' => 'Processing...',
        'error' => 'Error',
        'success' => 'Success',
        'get_extension' => 'Get Extension',
        'install_pwa' => 'Install App',
        'account' => 'Account',
        'welcome' => 'Welcome',
        'login' => 'Login',
        'register' => 'Register',
        'logout' => 'Logout'
    ],
    'fr' => [
        'title' => 'YTHT Téléchargeur',
        'description' => 'Téléchargez des vidéos YouTube et Facebook en MP4 ou MP3',
        'placeholder' => 'Collez l\'URL YouTube ou Facebook ici...',
        'download_mp4' => 'Télécharger MP4',
        'download_mp3' => 'Télécharger MP3',
        'loading' => 'Traitement...',
        'error' => 'Erreur',
        'success' => 'Succès',
        'get_extension' => 'Obtenir l\'extension',
        'install_pwa' => 'Installer l\'application',
        'account' => 'Compte',
        'welcome' => 'Bienvenue',
        'login' => 'Se connecter',
        'register' => 'S\'inscrire',
        'logout' => 'Se déconnecter'
    ],
    'ht' => [
        'title' => 'YTHT Downloader',
        'description' => 'Telechaje videyo YouTube ak Facebook an MP4 oswa MP3',
        'placeholder' => 'Kole URL YouTube oswa Facebook isit la...',
        'download_mp4' => 'Telechaje MP4',
        'download_mp3' => 'Telechaje MP3',
        'loading' => 'Pwosesis...',
        'error' => 'Erè',
        'success' => 'Siksè'
    ],
    'es' => [
        'title' => 'YTHT Descargador',
        'description' => 'Descarga videos de YouTube y Facebook en MP4 o MP3',
        'placeholder' => 'Pega la URL de YouTube o Facebook aquí...',
        'loading' => 'Procesando...',
        'download_mp4' => 'Descargar MP4',
        'download_mp3' => 'Descargar MP3',
        'error' => 'Error',
        'success' => 'Éxito'
    ]
];

$t = $translations[$language];
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['title']; ?></title>
    <!-- Favicon links -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/icons/favicon-16x16.png">
    <link rel="shortcut icon" href="assets/icons/favicon.ico">
    <link rel="shortcut icon" href="assets/icons/favicon.png">
    <!-- fontawesome & bootstrap & custom css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#dc3545">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
    <?php include __DIR__ .'/../views/header.php'; ?>
    
    <main class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">
                            <i class="fas fa-download text-danger me-2"></i>
                            <?php echo $t['title']; ?>
                        </h1>
                        
                        <p class="text-muted text-center mb-4">
                            <?php echo $t['description']; ?>
                        </p>

                        <!-- Language Selector --
                        <div class="text-center mb-4">
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-language"></i> <?php echo strtoupper($language); ?>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="?lang=en">English</a></li>
                                    <li><a class="dropdown-item" href="?lang=fr">Français</a></li>
                                    <li><a class="dropdown-item" href="?lang=ht">Kreyòl</a></li>
                                    <li><a class="dropdown-item" href="?lang=es">Español</a></li>
                                </ul>
                            </div>
                        </div> -->

                        <!-- URL Input Form -->
                        <form id="downloadForm" class="mb-4">
                            <div class="input-group">
                                <input type="url" 
                                       id="videoUrl" 
                                       class="form-control" 
                                       placeholder="<?php echo $t['placeholder']; ?>"
                                       required>
                                <button type="button" class="btn btn-danger" onclick="clearUrl()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="mt-3 text-center">
                                <button type="submit" class="btn btn-success btn-lg me-2" data-format="mp4">
                                    <i class="fas fa-film me-2"></i>
                                    <?php echo $t['download_mp4']; ?>
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg" data-format="mp3">
                                    <i class="fas fa-music me-2"></i>
                                    <?php echo $t['download_mp3']; ?>
                                </button>
                            </div>
                        </form>

                        <!-- Progress Indicator -->
                        <div id="progressSection" class="d-none">
                            <div class="progress mb-3">
                                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                     style="width: 0%"></div>
                            </div>
                            <p id="progressText" class="text-center text-muted">
                                <?php echo $t['loading']; ?>
                            </p>
                        </div>

                        <!-- Result Section -->
                        <div id="resultSection" class="d-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ .'/../views/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>