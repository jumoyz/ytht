<?php
declare(strict_types=1);

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once __DIR__ . '/../src/Config.php';
require_once __DIR__ . '/../src/Validator.php';
require_once __DIR__ . '/../src/Downloader.php';

// Language detection
$supported_languages = ['en', 'fr', 'ht', 'es'];
$default_language = 'en';

$language = $_GET['lang'] ?? ($_COOKIE['language'] ?? $default_language);
if (!in_array($language, $supported_languages, true)) {
    $language = $default_language;
}
setcookie('language', $language, time() + (365 * 24 * 60 * 60), '/');

// Translations (could be externalized)
$translations = [
    'en' => [
        'title' => 'YTHT Downloader',
        'description' => 'Download YouTube and Facebook videos as MP4 or MP3',
        'placeholder' => 'Paste YouTube or Facebook URL here...',
        'download_mp4' => 'Download MP4',
        'download_mp3' => 'Download MP3',
        'generate_lyrics' => 'Generate Lyrics',
        'download_lyrics' => 'Download Lyrics', 
        'loading' => 'Processing...',
        'error' => 'Error',
        'success' => 'Success',
        'get_extension' => 'Get Extension',
        'install_pwa' => 'Install App',
        'account' => 'Account',
        'welcome' => 'Welcome',
        'login' => 'Login',
        'register' => 'Register',
        'logout' => 'Logout',
        'about' => 'About',
        'contact' => 'Contact',
        'privacy_policy' => 'Privacy Policy',
        'terms_of_service' => 'Terms of Service',
        'all_rights_reserved' => ' All rights reserved.' 
    ],
    'fr' => [
        'title' => 'YTHT Téléchargeur',
        'description' => 'Téléchargez des vidéos YouTube et Facebook en MP4 ou MP3',
        'placeholder' => 'Collez l\'URL YouTube ou Facebook ici...',
        'download_mp4' => 'Télécharger MP4',
        'download_mp3' => 'Télécharger MP3',
        'generate_lyrics' => 'Generate Lyrics',
        'download_lyrics' => 'Download Lyrics', 
        'loading' => 'Traitement...',
        'error' => 'Erreur',
        'success' => 'Succès',
        'get_extension' => 'Obtenir l\'extension',
        'install_pwa' => 'Installer l\'application',
        'account' => 'Compte',
        'welcome' => 'Bienvenue',
        'login' => 'Se connecter',
        'register' => 'S\'inscrire',
        'logout' => 'Se déconnecter',
        'about' => 'A propos',
        'contact' => 'Contact',
        'privacy_policy' => 'Politique de confidentialité',
        'terms_of_service' => 'Conditions d\'utilisation',
        'all_rights_reserved' => 'Tous droits réservés.'
    ],
    'ht' => [
        'title' => 'YTHT Downloader',
        'description' => 'Telechaje videyo YouTube ak Facebook an MP4 oswa MP3',
        'placeholder' => 'Kole URL YouTube oswa Facebook isit la...',
        'download_mp4' => 'Telechaje MP4',
        'download_mp3' => 'Telechaje MP3',
        'generate_lyrics' => 'Generate Lyrics',
        'download_lyrics' => 'Download Lyrics', 
        'loading' => 'Pwosesis...',
        'error' => 'Erè',
        'success' => 'Siksè',
        'get_extension' => 'Jwenn ekstansyon an',
        'install_pwa' => 'Enstale aplikasyon an',
        'account' => 'Kont',
        'welcome' => 'Byenvenue',
        'login' => 'Konekte',
        'register' => 'Enskri',
        'logout' => 'Dekonekte',
        'about' => 'Kiyes nou ye',
        'contact' => 'Kontak',
        'privacy_policy' => 'Règleman sou enfòmasyon prive',
        'terms_of_service' => 'Kondisyon Sèvis yo',
        'all_rights_reserved' => 'Tout dwa rezève.'
    ],
    'es' => [
        'title' => 'YTHT Descargador',
        'description' => 'Descarga videos de YouTube y Facebook en MP4 o MP3',
        'placeholder' => 'Pega la URL de YouTube o Facebook aquí...',
        'download_mp4' => 'Descargar MP4',
        'download_mp3' => 'Descargar MP3',
        'generate_lyrics' => 'Generate Lyrics',
        'download_lyrics' => 'Download Lyrics', 
        'loading' => 'Procesando...',
        'error' => 'Error',
        'success' => 'Éxito',
        'get_extension' => 'Obtener la extensión',
        'install_pwa' => 'Instalar la aplicación',
        'account' => 'Cuenta',
        'welcome' => 'Bienvenido',
        'login' => 'Iniciar sesión',
        'register' => 'Registrarse',
        'logout' => 'Cerrar sesión',
        'about' => 'Acerca de',
        'contact' => 'Contacto',
        'privacy_policy' => 'Política de privacidad',
        'terms_of_service' => 'Condiciones de servicio',
        'all_rights_reserved' => 'Reservados todos los derechos.'
    ]
];

$t = $translations[$language];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($language) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($t['title']) ?></title>
  <meta name="description" content="<?= htmlspecialchars($t['description']) ?>">
  <link rel="manifest" href="manifest.json">
  <meta name="theme-color" content="#dc3545">

  <!-- Icons -->
  <link rel="apple-touch-icon" sizes="180x180" href="assets/icons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/icons/favicon-16x16.png">

  <!-- CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">

  <!-- Google OAuth -->
  <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body class="bg-light">

<?php include __DIR__ . '/../views/header.php'; ?>

<main class="container-fluid py-4">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <h1 class="card-title text-center mb-3">
            <i class="fas fa-download text-danger me-2"></i><?= htmlspecialchars($t['title']) ?>
          </h1>
          <p class="text-muted text-center mb-4"><?= htmlspecialchars($t['description']) ?></p>

          <!-- URL Input -->
          <form id="downloadForm" class="mb-4">
            <div class="input-group input-group-lg">
              <input type="url"
                     id="videoUrl"
                     class="form-control"
                     placeholder="<?= htmlspecialchars($t['placeholder']) ?>"
                     required>
              <button type="button" class="btn btn-outline-danger" id="clearUrl" aria-label="Clear URL">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <div class="mt-3 d-flex justify-content-center flex-wrap gap-2">
              <button type="submit" class="btn btn-success btn-lg flex-fill" data-format="mp4">
                <i class="fas fa-film me-2"></i><?= htmlspecialchars($t['download_mp4']) ?>
              </button>
              <button type="submit" class="btn btn-primary btn-lg flex-fill" data-format="mp3">
                <i class="fas fa-music me-2"></i><?= htmlspecialchars($t['download_mp3']) ?>
              </button>

              <div class="form-check text-center mt-3">
                <input class="form-check-input" type="checkbox" id="generateLyrics" value="1">
                <label class="form-check-label" for="generateLyrics">
                  <i class="fas fa-file-alt me-1"></i>
                  <?= htmlspecialchars($t['generate_lyrics']) ?>
                </label>
                <small class="text-muted d-block text-center mt-1">
                  (MP3 required for lyrics generation)
                </small>
              </div>
            </div>
          </form>

          <!-- Progress -->
          <div id="progressSection" class="d-none">
            <div class="progress mb-3">
              <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%"></div>
            </div>
            <p id="progressText" class="text-center text-muted"><?= htmlspecialchars($t['loading']) ?></p>
          </div>

          <!-- Result -->
          <div id="resultSection" class="d-none">
            <div id="downloadLinks" class="text-center mb-3"></div>
            <div id="lyricsSection" class="text-center"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include __DIR__ . '/../views/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>