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
        'title' => 'Privacy Policy - YTHT Downloader',
        'heading' => 'Privacy Policy',
        'last_updated' => 'Last Updated',
        'introduction' => 'Your privacy is important to us. This policy explains how we handle your information.',
        'information_collection' => 'Information We Collect',
        'usage_information' => 'How We Use Information',
        'data_protection' => 'Data Protection',
        'cookies' => 'Cookies',
        'third_party' => 'Third-Party Services',
        'your_rights' => 'Your Rights',
        'changes' => 'Changes to This Policy'
    ],
    'fr' => [
        'title' => 'Politique de Confidentialité - YTHT Téléchargeur',
        'heading' => 'Politique de Confidentialité',
        'last_updated' => 'Dernière Mise à Jour',
        'introduction' => 'Votre vie privée est importante pour nous. Cette politique explique comment nous traitons vos informations.',
        'information_collection' => 'Informations que Nous Collectons',
        'usage_information' => 'Comment Nous Utilisons les Informations',
        'data_protection' => 'Protection des Données',
        'cookies' => 'Cookies',
        'third_party' => 'Services Tiers',
        'your_rights' => 'Vos Droits',
        'changes' => 'Modifications de Cette Politique'
    ],
    'ht' => [
        'title' => 'Règleman Konfidansyalite - YTHT Downloader',
        'heading' => 'Règleman Konfidansyalite',
        'last_updated' => 'Dènye Mizajou',
        'introduction' => 'Vi prive ou enpòtan pou nou. Règleman sa a eksplike kijan nou trete enfòmasyon ou yo.',
        'information_collection' => 'Enfòmasyon Nou Kolekte',
        'usage_information' => 'Kijan Nou Itilize Enfòmasyon',
        'data_protection' => 'Pwoteksyon Done',
        'cookies' => 'Cookies',
        'third_party' => 'Sèvis Twazyèm Pati',
        'your_rights' => 'Dwa Ou',
        'changes' => 'Chanje nan Règleman Sa a'
    ],
    'es' => [
        'title' => 'Política de Privacidad - YTHT Descargador',
        'heading' => 'Política de Privacidad',
        'last_updated' => 'Última Actualización',
        'introduction' => 'Su privacidad es importante para nosotros. Esta política explica cómo manejamos su información.',
        'information_collection' => 'Información que Recopilamos',
        'usage_information' => 'Cómo Usamos la Información',
        'data_protection' => 'Protección de Datos',
        'cookies' => 'Cookies',
        'third_party' => 'Servicios de Terceros',
        'your_rights' => 'Sus Derechos',
        'changes' => 'Cambios en Esta Política'
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
                                <i class="fas fa-shield-alt text-primary me-2"></i>
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

                        <div class="privacy-content">
                            <!-- Information Collection -->
                            <section class="mb-5">
                                <h3 class="text-primary">1. <?php echo $t['information_collection']; ?></h3>
                                <p>YTHT Downloader is committed to protecting your privacy. We collect minimal information to provide our services:</p>
                                <ul>
                                    <li><strong>Video URLs:</strong> We process the video URLs you provide for conversion</li>
                                    <li><strong>Usage Data:</strong> We may collect information about how you use our service</li>
                                    <li><strong>Technical Information:</strong> Browser type, IP address, and access times</li>
                                    <li><strong>Cookies:</strong> We use cookies to store your language preference</li>
                                </ul>
                                <p>We do <strong>NOT</strong> collect:</p>
                                <ul>
                                    <li>Personal identification information (unless you register)</li>
                                    <li>Payment information (service is free)</li>
                                    <li>Sensitive personal data</li>
                                </ul>
                            </section>

                            <!-- Usage Information -->
                            <section class="mb-5">
                                <h3 class="text-primary">2. <?php echo $t['usage_information']; ?></h3>
                                <p>We use the collected information for:</p>
                                <ul>
                                    <li>Processing your video download requests</li>
                                    <li>Improving our service quality</li>
                                    <li>Understanding how users interact with our website</li>
                                    <li>Maintaining service security</li>
                                    <li>Remembering your language preference</li>
                                </ul>
                            </section>

                            <!-- Data Protection -->
                            <section class="mb-5">
                                <h3 class="text-primary">3. <?php echo $t['data_protection']; ?></h3>
                                <p>We implement appropriate security measures to protect your information:</p>
                                <ul>
                                    <li>Video URLs are processed temporarily and not stored long-term</li>
                                    <li>Downloaded files are automatically deleted after a short period</li>
                                    <li>We use secure protocols (HTTPS) for data transmission</li>
                                    <li>Regular security monitoring and updates</li>
                                </ul>
                            </section>

                            <!-- Cookies -->
                            <section class="mb-5">
                                <h3 class="text-primary">4. <?php echo $t['cookies']; ?></h3>
                                <p>We use cookies to enhance your experience:</p>
                                <ul>
                                    <li><strong>Language Preference:</strong> Remembers your selected language</li>
                                    <li><strong>Session Cookies:</strong> Maintain your current session</li>
                                    <li><strong>Analytics Cookies:</strong> Help us understand website usage (if applicable)</li>
                                </ul>
                                <p>You can disable cookies in your browser settings, but this may affect some functionality.</p>
                            </section>

                            <!-- Third Party -->
                            <section class="mb-5">
                                <h3 class="text-primary">5. <?php echo $t['third_party']; ?></h3>
                                <p>We may use third-party services that have their own privacy policies:</p>
                                <ul>
                                    <li><strong>YouTube/Facebook:</strong> We interact with these platforms to download content</li>
                                    <li><strong>Analytics Services:</strong> To understand website traffic</li>
                                    <li><strong>Hosting Providers:</strong> Our service is hosted on secure servers</li>
                                </ul>
                            </section>

                            <!-- Your Rights -->
                            <section class="mb-5">
                                <h3 class="text-primary">6. <?php echo $t['your_rights']; ?></h3>
                                <p>You have the right to:</p>
                                <ul>
                                    <li>Access any personal information we hold about you</li>
                                    <li>Request correction of inaccurate information</li>
                                    <li>Request deletion of your information</li>
                                    <li>Object to processing of your information</li>
                                    <li>Request data portability</li>
                                </ul>
                                <p>To exercise these rights, please contact us using the information below.</p>
                            </section>

                            <!-- Changes -->
                            <section class="mb-5">
                                <h3 class="text-primary">7. <?php echo $t['changes']; ?></h3>
                                <p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last Updated" date.</p>
                            </section>

                            <!-- Contact -->
                            <section class="mb-5">
                                <h3 class="text-primary">8. Contact Us</h3>
                                <p>If you have any questions about this Privacy Policy, please contact us:</p>
                                <ul>
                                    <li>Email: privacy@ytht.tmsht.com</li>
                                    <li>Contact Form: <a href="contact.php">Contact Page</a></li>
                                </ul>
                            </section>
                        </div>

                        <div class="alert alert-warning mt-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Disclaimer:</strong> This is a demo privacy policy. For a real application, consult with legal professionals to create an appropriate privacy policy that complies with applicable laws (GDPR, CCPA, etc.).
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