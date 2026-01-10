<?php
session_start();
require_once '../src/Config.php';
require_once '../src/Validator.php';

// Language setup (same as index.php)
$supported_languages = ['en', 'fr', 'ht', 'es'];
$default_language = 'en';
$language = isset($_GET['lang']) && in_array($_GET['lang'], $supported_languages) 
    ? $_GET['lang'] 
    : (isset($_COOKIE['language']) ? $_COOKIE['language'] : $default_language);

setcookie('language', $language, time() + (365 * 24 * 60 * 60), '/');

$translations = [
    'en' => [
        'title' => 'Contact Us - YTHT Downloader',
        'heading' => 'Contact Us',
        'description' => 'Get in touch with our support team',
        'name' => 'Your Name',
        'email' => 'Email Address',
        'subject' => 'Subject',
        'message' => 'Your Message',
        'submit' => 'Send Message',
        'success' => 'Message sent successfully!',
        'error' => 'Error sending message. Please try again.',
        'contact_info' => 'Contact Information',
        'support_email' => 'Support Email',
        'office_hours' => 'Office Hours',
        'response_time' => 'Response Time'
    ],
    'fr' => [
        'title' => 'Contactez-Nous - YTHT Téléchargeur',
        'heading' => 'Contactez-Nous',
        'description' => 'Contactez notre équipe de support',
        'name' => 'Votre Nom',
        'email' => 'Adresse Email',
        'subject' => 'Sujet',
        'message' => 'Votre Message',
        'submit' => 'Envoyer le Message',
        'success' => 'Message envoyé avec succès!',
        'error' => 'Erreur lors de l\'envoi du message. Veuillez réessayer.',
        'contact_info' => 'Informations de Contact',
        'support_email' => 'Email de Support',
        'office_hours' => 'Heures de Bureau',
        'response_time' => 'Temps de Réponse'
    ],
    'ht' => [
        'title' => 'Kontakte Nou - YTHT Downloader',
        'heading' => 'Kontakte Nou',
        'description' => 'Pran kontak ak ekip sipò nou an',
        'name' => 'Non Ou',
        'email' => 'Adrès Imèl',
        'subject' => 'Sijè',
        'message' => 'Mesaj Ou',
        'submit' => 'Voye Mesaj',
        'success' => 'Mesaj voye avèk siksè!',
        'error' => 'Erè lè w ap voye mesaj. Tanpri eseye ankò.',
        'contact_info' => 'Enfòmasyon Kontak',
        'support_email' => 'Imèl Sipò',
        'office_hours' => 'Lè Biwo',
        'response_time' => 'Tan Repons'
    ],
    'es' => [
        'title' => 'Contáctenos - YTHT Descargador',
        'heading' => 'Contáctenos',
        'description' => 'Póngase en contacto con nuestro equipo de soporte',
        'name' => 'Su Nombre',
        'email' => 'Correo Electrónico',
        'subject' => 'Asunto',
        'message' => 'Su Mensaje',
        'submit' => 'Enviar Mensaje',
        'success' => '¡Mensaje enviado con éxito!',
        'error' => 'Error al enviar el mensaje. Por favor, inténtelo de nuevo.',
        'contact_info' => 'Información de Contacto',
        'support_email' => 'Correo de Soporte',
        'office_hours' => 'Horario de Oficina',
        'response_time' => 'Tiempo de Respuesta'
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
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h1 class="card-title text-center mb-4">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <?php echo $t['heading']; ?>
                        </h1>
                        
                        <p class="text-muted text-center mb-5">
                            <?php echo $t['description']; ?>
                        </p>

                        <div class="row">
                            <div class="col-md-12">
                                <form id="contactForm">
                                    <div class="mb-3">
                                        <label for="name" class="form-label"><?php echo $t['name']; ?></label>
                                        <input type="text" class="form-control" id="name" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label"><?php echo $t['email']; ?></label>
                                        <input type="email" class="form-control" id="email" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="subject" class="form-label"><?php echo $t['subject']; ?></label>
                                        <select class="form-select" id="subject" required>
                                            <option value=""><?php echo $t['subject']; ?></option>
                                            <option value="support">Technical Support</option>
                                            <option value="feature">Feature Request</option>
                                            <option value="bug">Bug Report</option>
                                            <option value="partnership">Partnership</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="message" class="form-label"><?php echo $t['message']; ?></label>
                                        <textarea class="form-control" id="message" rows="5" required></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        <?php echo $t['submit']; ?>
                                    </button>
                                </form>
                                
                                <div id="formMessage" class="mt-3"></div>
                            </div>
                            <!--
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <?php echo $t['contact_info']; ?>
                                        </h5>
                                        
                                        <div class="mb-3">
                                            <h6><i class="fas fa-envelope me-2 text-primary"></i><?php echo $t['support_email']; ?></h6>
                                            <p class="mb-0">support@ytht.tmsht.com</p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <h6><i class="fas fa-clock me-2 text-success"></i><?php echo $t['office_hours']; ?></h6>
                                            <p class="mb-0">Mon - Fri: 9:00 AM - 6:00 PM</p>
                                            <p class="mb-0">Sat: 10:00 AM - 2:00 PM</p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <h6><i class="fas fa-reply me-2 text-info"></i><?php echo $t['response_time']; ?></h6>
                                            <p class="mb-0">Within 24 hours</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mt-3">
                                    <div class="card-body text-center">
                                        <h6>Follow Us</h6>
                                        <div class="d-flex justify-content-center gap-3 mt-2">
                                            <a href="#" class="text-primary"><i class="fab fa-facebook fa-2x"></i></a>
                                            <a href="#" class="text-info"><i class="fab fa-twitter fa-2x"></i></a>
                                            <a href="#" class="text-danger"><i class="fab fa-youtube fa-2x"></i></a>
                                            <a href="#" class="text-dark"><i class="fab fa-github fa-2x"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include '../views/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formMessage = document.getElementById('formMessage');
            formMessage.innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $t['success']; ?>
                </div>
            `;
            
            // Reset form
            this.reset();
            
            // Scroll to message
            formMessage.scrollIntoView({ behavior: 'smooth' });
        });
    </script>
</body>
</html>