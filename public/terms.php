<?php
session_start();
require_once '../src/Config.php';

// Language setup
$supported_languages = ['en', 'fr', 'ht', 'es'];
$default_language = 'en';
$language = isset($_GET['lang']) && in_array($_GET['lang'], $supported_languages) 
    ? $_GET['lang'] 
    : (isset($_COOKIE['language']) ? $_COOKIE['language'] : $default_language);

setcookie('language', $language, time() + (365 * 24 * 60 * 60), '/');

$translations = [
    'en' => [
        'title' => 'Terms of Service - YTHT Downloader',
        'heading' => 'Terms of Service',
        'last_updated' => 'Last Updated',
        'introduction' => 'Please read these terms carefully before using our service.',
        'acceptance' => 'By accessing or using YTHT Downloader, you agree to be bound by these Terms.',
        'service_description' => 'Service Description',
        'user_responsibilities' => 'User Responsibilities',
        'intellectual_property' => 'Intellectual Property',
        'limitation_liability' => 'Limitation of Liability',
        'termination' => 'Termination',
        'changes' => 'Changes to Terms'
    ],
    'fr' => [
        'title' => 'Conditions d\'Utilisation - YTHT Téléchargeur',
        'heading' => 'Conditions d\'Utilisation',
        'last_updated' => 'Dernière Mise à Jour',
        'introduction' => 'Veuillez lire attentivement ces conditions avant d\'utiliser notre service.',
        'acceptance' => 'En accédant ou utilisant YTHT Téléchargeur, vous acceptez d\'être lié par ces Conditions.',
        'service_description' => 'Description du Service',
        'user_responsibilities' => 'Responsabilités de l\'Utilisateur',
        'intellectual_property' => 'Propriété Intellectuelle',
        'limitation_liability' => 'Limitation de Responsabilité',
        'termination' => 'Résiliation',
        'changes' => 'Modifications des Conditions'
    ],
    'ht' => [
        'title' => 'Règleman Sèvis - YTHT Downloader',
        'heading' => 'Règleman Sèvis',
        'last_updated' => 'Dènye Mizajou',
        'introduction' => 'Tanpri li règ sa yo ak anpil atansyon anvan w itilize sèvis nou an.',
        'acceptance' => 'Lè w apseede oswa itilize YTHT Downloader, ou dakò pou w respekte Règleman sa yo.',
        'service_description' => 'Deskripsyon Sèvis',
        'user_responsibilities' => 'Responsablite Itilizatè',
        'intellectual_property' => 'Pwopriyete Entèlèktiyèl',
        'limitation_liability' => 'Limit Responsablite',
        'termination' => 'Revokasyon',
        'changes' => 'Chanje nan Règleman'
    ],
    'es' => [
        'title' => 'Términos de Servicio - YTHT Descargador',
        'heading' => 'Términos de Servicio',
        'last_updated' => 'Última Actualización',
        'introduction' => 'Por favor, lea estos términos cuidadosamente antes de usar nuestro servicio.',
        'acceptance' => 'Al acceder o usar YTHT Descargador, usted acepta estar sujeto a estos Términos.',
        'service_description' => 'Descripción del Servicio',
        'user_responsibilities' => 'Responsabilidades del Usuario',
        'intellectual_property' => 'Propiedad Intelectual',
        'limitation_liability' => 'Limitación de Responsabilidad',
        'termination' => 'Terminación',
        'changes' => 'Cambios en los Términos'
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include '../views/header.php'; ?>
    
    <main class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <h1 class="card-title">
                                <i class="fas fa-scale-balanced text-primary me-2"></i>
                                <?php echo $t['heading']; ?>
                            </h1>
                            <p class="text-muted">
                                <?php echo $t['last_updated']; ?>: <?php echo date('F j, Y'); ?>
                            </p>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php echo $t['introduction']; ?>
                        </div>

                        <div class="terms-content">
                            <!-- Introduction -->
                            <section class="mb-5">
                                <h3 class="text-primary">1. <?php echo $t['acceptance']; ?></h3>
                                <p>These Terms of Service govern your use of the YTHT Downloader website located at ytht.tmsht.com and any related services provided by YTHT Downloader.</p>
                            </section>

                            <!-- Service Description -->
                            <section class="mb-5">
                                <h3 class="text-primary">2. <?php echo $t['service_description']; ?></h3>
                                <p>YTHT Downloader is an online tool that allows users to download videos from YouTube and Facebook in MP4 (video) and MP3 (audio) formats. The service is provided "as is" and we make no warranties regarding its availability or performance.</p>
                            </section>

                            <!-- User Responsibilities -->
                            <section class="mb-5">
                                <h3 class="text-primary">3. <?php echo $t['user_responsibilities']; ?></h3>
                                <p>By using our service, you agree to:</p>
                                <ul>
                                    <li>Use the service only for personal, non-commercial purposes</li>
                                    <li>Respect copyright laws and platform terms of service</li>
                                    <li>Not use the service for illegal activities</li>
                                    <li>Not abuse the service with excessive requests</li>
                                    <li>Be responsible for the content you download</li>
                                </ul>
                            </section>

                            <!-- Intellectual Property -->
                            <section class="mb-5">
                                <h3 class="text-primary">4. <?php echo $t['intellectual_property']; ?></h3>
                                <p>YTHT Downloader respects intellectual property rights. The service is intended for downloading content that you have the right to access and download. We are not responsible for any copyright infringement by users.</p>
                            </section>

                            <!-- Limitation of Liability -->
                            <section class="mb-5">
                                <h3 class="text-primary">5. <?php echo $t['limitation_liability']; ?></h3>
                                <p>YTHT Downloader shall not be held liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses.</p>
                            </section>

                            <!-- Termination -->
                            <section class="mb-5">
                                <h3 class="text-primary">6. <?php echo $t['termination']; ?></h3>
                                <p>We may terminate or suspend your access to the service immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.</p>
                            </section>

                            <!-- Changes -->
                            <section class="mb-5">
                                <h3 class="text-primary">7. <?php echo $t['changes']; ?></h3>
                                <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. By continuing to access or use our service after those revisions become effective, you agree to be bound by the revised terms.</p>
                            </section>

                            <!-- Contact -->
                            <section class="mb-5">
                                <h3 class="text-primary">8. Contact Us</h3>
                                <p>If you have any questions about these Terms, please contact us at:</p>
                                <ul>
                                    <li>Email: legal@ytht.tmsht.com</li>
                                    <li>Contact Form: <a href="contact.php">Contact Page</a></li>
                                </ul>
                            </section>
                        </div>

                        <div class="alert alert-warning mt-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Disclaimer:</strong> This is a demo terms of service page. For a real application, consult with legal professionals to create appropriate terms for your specific service.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include '../views/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>