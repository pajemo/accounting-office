<?php
/**
 * SMTP Email Handler for MKM Biuro Rachunkowe
 * Complete solution - handles form submission and sends emails
 */

// Load PHPMailer first - CRITICAL: Must be loaded before any functions that use it
if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    // Try to load from vendor (Composer)
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
    }
    // Try to load manually
    elseif (file_exists('PHPMailer/src/PHPMailer.php')) {
        require_once 'PHPMailer/src/PHPMailer.php';
        require_once 'PHPMailer/src/SMTP.php';
        require_once 'PHPMailer/src/Exception.php';
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'PHPMailer not found. Please install it first.']);
        exit;
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// HTTP Headers for API response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Check if this is a form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleFormSubmission();
    exit;
}

// If not POST, show error
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
exit;

function handleFormSubmission() {
    // Debug: Log form submission attempt
    error_log("Form submission attempt at " . date('Y-m-d H:i:s') . " from IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN'));
    error_log("POST data: " . print_r($_POST, true));
    
    // Security: Check request origin and method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }
    
    // Security: Rate limiting check 
    $userIP = $_SERVER['REMOTE_ADDR'];
    if (!checkRateLimit($userIP, 5, 1800)) { // 5 submissions per 30 minutes (more reasonable)
        http_response_code(429);
        echo json_encode(['error' => 'Zbyt wiele próśb. Poczekaj 30 minut przed ponowną próbą.']);
        return;
    }
    
    // Security: Honeypot check
    if (!empty($_POST['website'])) {
        error_log("Honeypot triggered from IP: " . $userIP);
        http_response_code(400);
        echo json_encode(['error' => 'Wykryto podejrzaną aktywność.']);
        return;
    }
    
    // Security: CSRF token validation (basic check) - Re-enabled
    if (empty($_POST['csrf_token']) || strlen($_POST['csrf_token']) < 10) {
        http_response_code(400);
        echo json_encode(['error' => 'Nieprawidłowy token bezpieczeństwa.']);
        return;
    }
    
    // Security: reCAPTCHA v3 verification - Re-enabled
    $recaptchaSecret = getenv('RECAPTCHA_SECRET') ?: '';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    if (empty($recaptchaSecret)) {
        http_response_code(500);
        echo json_encode(['error' => 'Błąd konfiguracji serwera.']);
        return;
    }
    
    if (empty($recaptchaResponse)) {
        http_response_code(400);
        echo json_encode(['error' => 'Weryfikacja reCAPTCHA jest wymagana.']);
        return;
    }
    
    // Verify reCAPTCHA using cURL - Re-enabled
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'secret' => $recaptchaSecret,
        'response' => $recaptchaResponse,
        'remoteip' => $userIP
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $verifyResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200 || !$verifyResponse) {
        http_response_code(500);
        echo json_encode(['error' => 'Błąd weryfikacji bezpieczeństwa.']);
        return;
    }
    
    $responseData = json_decode($verifyResponse, true);
    if (!$responseData['success'] || ($responseData['score'] ?? 0) < 0.5) {
        error_log("reCAPTCHA failed for IP: " . $userIP . " Score: " . ($responseData['score'] ?? 'N/A'));
        http_response_code(400);
        echo json_encode(['error' => 'Weryfikacja reCAPTCHA nie powiodła się.']);
        return;
    }

    // Get and sanitize form data
    $firstName = sanitizeInput($_POST['firstName'] ?? '');
    $lastName = sanitizeInput($_POST['lastName'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $service = sanitizeInput($_POST['service'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');

    // Enhanced validation
    $errors = [];

    if (empty($firstName) || strlen($firstName) < 2 || strlen($firstName) > 50) {
        $errors[] = 'Imię musi mieć od 2 do 50 znaków';
    }

    if (empty($lastName) || strlen($lastName) < 2 || strlen($lastName) > 50) {
        $errors[] = 'Nazwisko musi mieć od 2 do 50 znaków';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $errors[] = 'Prawidłowy adres email jest wymagany';
    }

    if (empty($message) || strlen($message) < 10 || strlen($message) > 1000) {
        $errors[] = 'Wiadomość musi mieć od 10 do 1000 znaków';
    }
    
    // Phone validation (if provided)
    if (!empty($phone) && !preg_match('/^[\+]?[0-9\s\-\(\)]{9,20}$/', $phone)) {
        $errors[] = 'Nieprawidłowy format numeru telefonu';
    }
    
    // Security: Check for suspicious patterns
    $allInputs = $firstName . ' ' . $lastName . ' ' . $email . ' ' . $message . ' ' . $phone;
    if (containsSuspiciousContent($allInputs)) {
        error_log("Suspicious content detected from IP: " . $userIP);
        $errors[] = 'Wykryto niedozwolone znaki w formularzu';
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['error' => 'Błędy walidacji', 'details' => $errors]);
        return;
    }

    // Prepare form data
    $formData = [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'service' => $service,
        'message' => $message
    ];

    // Send email using SMTP
    try {
        $result = sendContactEmail($formData);
        
        if ($result['success']) {
            // Log successful submission
            error_log("Contact form submitted successfully from IP: " . $userIP . " Email: " . $email);
            
            echo json_encode([
                'success' => true,
                'message' => 'Wiadomość została wysłana pomyślnie! Otrzymasz potwierdzenie na email.'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'error' => $result['error'] ?? 'Błąd podczas wysyłania wiadomości.'
            ]);
        }
        
    } catch (Exception $e) {
        error_log("Contact form error: " . $e->getMessage() . " IP: " . $userIP);
        http_response_code(500);
        echo json_encode([
            'error' => 'Błąd serwera. Spróbuj ponownie później lub skontaktuj się telefonicznie: +48 664 767 930'
        ]);
    }
}

// Check if PHPMailer is available
if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    // Try to load from vendor (Composer)
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
    }
    // Try to load manually
    elseif (file_exists('PHPMailer/src/PHPMailer.php')) {
        require_once 'PHPMailer/src/PHPMailer.php';
        require_once 'PHPMailer/src/SMTP.php';
        require_once 'PHPMailer/src/Exception.php';
    } else {
        echo json_encode(['error' => 'PHPMailer not found. Please install it first.']);
        exit;
    }
}

class MKMMailer {
    private $mail;
    private $useBasicMail = false;

    private function env($key, $default = '') {
        $value = getenv($key);
        if ($value === false || $value === null || $value === '') {
            return $default;
        }
        return $value;
    }
    
    // SMTP Configuration - Verified working configurations only (tested Nov 2025)
    private $smtpConfig = [
        'primary' => [
            'host' => 'mail.biurorachunkowenysa.com',
            'port' => 465,
            'username' => '',
            'password' => '',
            'encryption' => PHPMailer::ENCRYPTION_SMTPS,
        ],
        'fallback1' => [
            'host' => 'mail.biurorachunkowenysa.com',
            'port' => 587,
            'username' => '',
            'password' => '',
            'encryption' => PHPMailer::ENCRYPTION_STARTTLS,
        ],
        'from_email' => '',
        'from_name' => 'MKM Biuro Rachunkowe',
        'reply_to' => '',
        'bounce_email' => ''
    ];
    
    public function __construct() {
        $smtpHost = $this->env('SMTP_HOST', 'mail.biurorachunkowenysa.com');
        $smtpUser = $this->env('SMTP_USERNAME');
        $smtpPass = $this->env('SMTP_PASSWORD');
        $fromEmail = $this->env('MAIL_FROM_EMAIL', $smtpUser);
        $replyTo = $this->env('MAIL_REPLY_TO', $fromEmail);
        $bounceEmail = $this->env('MAIL_BOUNCE_EMAIL', $fromEmail);

        $this->smtpConfig['primary']['host'] = $smtpHost;
        $this->smtpConfig['primary']['username'] = $smtpUser;
        $this->smtpConfig['primary']['password'] = $smtpPass;

        $this->smtpConfig['fallback1']['host'] = $smtpHost;
        $this->smtpConfig['fallback1']['username'] = $smtpUser;
        $this->smtpConfig['fallback1']['password'] = $smtpPass;

        $this->smtpConfig['from_email'] = $fromEmail;
        $this->smtpConfig['reply_to'] = $replyTo;
        $this->smtpConfig['bounce_email'] = $bounceEmail;

        $this->mail = new PHPMailer(true);
        $this->configureSMTP();
    }
    
    private function configureSMTP() {
        // Try only verified working configurations, then fallback to basic mail()
        $configs = ['primary', 'fallback1'];
        
        foreach ($configs as $configType) {
            try {
                $config = $this->smtpConfig[$configType];
                
                // Reset mail object
                $this->mail = new PHPMailer(true);
                
                // Server settings
                $this->mail->isSMTP();
                $this->mail->Host = $config['host'];
                $this->mail->SMTPAuth = true;
                $this->mail->Username = $config['username'];
                $this->mail->Password = $config['password'];
                $this->mail->SMTPSecure = $config['encryption'];
                $this->mail->Port = $config['port'];
                
                // Debug settings for troubleshooting
                $this->mail->SMTPDebug = 0;
                $this->mail->Debugoutput = 'error_log';
                
                // Optimized connection timeout settings for faster response
                $this->mail->Timeout = 10;
                $this->mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                
                // Encoding
                $this->mail->CharSet = 'UTF-8';
                $this->mail->Encoding = 'base64';
                
                // From address and bounce handling
                $this->mail->setFrom(
                    'biurarcc@biurorachunkowenysa.com', 
                    $this->smtpConfig['from_name']
                );
                $this->mail->Sender = 'bounces@biurorachunkowenysa.com';
                
                // Test authentication by attempting to connect - Optimized for speed
                try {
                    if ($this->mail->smtpConnect()) {
                        $this->mail->smtpClose();
                        error_log("SMTP authentication successful using {$configType} config (Port: {$config['port']}, User: {$config['username']})");
                        $this->useBasicMail = false;
                        return; // Success, exit function
                    } else {
                        throw new Exception("Connection test failed for {$configType}");
                    }
                } catch (Exception $connectError) {
                    // Quick connection test failed, continue to next config
                    throw $connectError;
                }
                
            } catch (Exception $e) {
                error_log("SMTP {$configType} config failed: " . $e->getMessage());
                continue; // Try next configuration
            }
        }
        
        // If all SMTP configurations failed, use basic mail() function
        error_log("All SMTP configurations failed. Falling back to basic mail() function.");
        $this->useBasicMail = true;
        $this->mail = new PHPMailer(true);
        $this->mail->isMail(); // Use PHP's mail() function
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Encoding = 'base64';
        $this->mail->setFrom($this->smtpConfig['from_email'], $this->smtpConfig['from_name']);
    }
    
    public function sendContactForm($formData) {
        try {
            // If using basic mail, use simplified sending method
            if ($this->useBasicMail) {
                return $this->sendWithBasicMail($formData);
            }
            
            // Clear any previous settings
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            
            // Recipients
            $this->mail->addAddress('malgorzatakrzyzowska@wp.pl', 'Małgorzata Krzyżowska');
            $this->mail->addReplyTo($formData['email'], "{$formData['firstName']} {$formData['lastName']}");
            
            // Content
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Nowa wiadomość z formularza - MKM Biuro Rachunkowe';
            
            // Service names mapping
            $serviceNames = [
                'business-registration' => 'Rejestracja Działalności',
                'tax-book' => 'Księga Podatkowa Przychodów i Kosztów',
                'commercial-book' => 'Księga Handlowa',
                'flat-rate-tax' => 'Ryczałt od Przychodów',
                'payroll' => 'Kadry i Płace',
                'consultation' => 'Konsultacja Ogólna'
            ];
            
            $serviceName = $serviceNames[$formData['service']] ?? 'Nie określono';
            $phone = !empty($formData['phone']) ? $formData['phone'] : 'Nie podano';

            // HTML Email Body - Simplified and optimized
            $this->mail->Body = "
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background: #b10a21; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                        .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 8px 8px; }
                        .field { margin-bottom: 15px; }
                        .label { font-weight: bold; color: #b10a21; }
                        .value { margin-left: 10px; }
                        .message-box { background: white; padding: 15px; border-left: 4px solid #b10a21; margin-top: 20px; border-radius: 4px; }
                        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>MKM Biuro Rachunkowe</h2>
                            <p>Nowa wiadomość z formularza kontaktowego</p>
                        </div>
                        
                        <div class='content'>
                            <div class='field'>
                                <span class='label'>Imię i nazwisko:</span>
                                <span class='value'>{$formData['firstName']} {$formData['lastName']}</span>
                            </div>
                            
                            <div class='field'>
                                <span class='label'>Email:</span>
                                <span class='value'><a href='mailto:{$formData['email']}'>{$formData['email']}</a></span>
                            </div>
                            
                            <div class='field'>
                                <span class='label'>Telefon:</span>
                                <span class='value'>{$phone}</span>
                            </div>
                            
                            <div class='field'>
                                <span class='label'>Zainteresowanie usługą:</span>
                                <span class='value'>{$serviceName}</span>
                            </div>
                            
                            <div class='message-box'>
                                <h4>Wiadomość:</h4>
                                <p>" . nl2br(htmlspecialchars($formData['message'])) . "</p>
                            </div>
                            
                            <div class='footer'>
                                <p>Wysłano: " . date('d-m-Y H:i:s') . " | Ze strony: biurorachunkowenysa.com</p>
                            </div>
                        </div>
                    </div>
                </body>
                </html>";

            // Plain text version
            $this->mail->AltBody = "
NOWA WIADOMOŚĆ - MKM Biuro Rachunkowe

DANE KONTAKTOWE:
Imię i nazwisko: {$formData['firstName']} {$formData['lastName']}
Email: {$formData['email']}
Telefon: {$phone}
Usługa: {$serviceName}

WIADOMOŚĆ:
{$formData['message']}

---
Wysłano: " . date('d-m-Y H:i:s') . "
Ze strony: biurorachunkowenysa.com";
            
            // Send email
            $this->mail->send();
            
            return [
                'success' => true,
                'message' => 'Wiadomość została wysłana pomyślnie!'
            ];
            
        } catch (Exception $e) {
            error_log("SMTP email sending failed: " . $e->getMessage() . " - Attempting fallback to basic mail()");
            
            // If SMTP fails, try basic mail() as fallback
            try {
                return $this->sendWithBasicMail($formData);
            } catch (Exception $fallbackError) {
                error_log("Both SMTP and basic mail() failed: " . $fallbackError->getMessage());
                return [
                    'success' => false,
                    'error' => 'Błąd podczas wysyłania wiadomości. Skontaktuj się telefonicznie: +48 664 767 930'
                ];
            }
        }
    }
    
    /**
     * Fallback method using basic mail() function
     */
    private function sendWithBasicMail($formData) {
        try {
            $to = 'malgorzatakrzyzowska@wp.pl';
            $subject = 'Nowa wiadomość z formularza - MKM Biuro Rachunkowe';
            
            // Service names mapping
            $serviceNames = [
                'business-registration' => 'Rejestracja Działalności',
                'tax-book' => 'Księga Podatkowa Przychodów i Kosztów',
                'commercial-book' => 'Księga Handlowa',
                'flat-rate-tax' => 'Ryczałt od Przychodów',
                'payroll' => 'Kadry i Płace',
                'consultation' => 'Konsultacja Ogólna'
            ];
            
            $serviceName = $serviceNames[$formData['service']] ?? 'Nie określono';
            $phone = !empty($formData['phone']) ? $formData['phone'] : 'Nie podano';
            
            // Create message body
            $message = "
NOWA WIADOMOŚĆ - MKM Biuro Rachunkowe
=====================================

DANE KONTAKTOWE:
Imię i nazwisko: {$formData['firstName']} {$formData['lastName']}
Email: {$formData['email']}
Telefon: {$phone}
Zainteresowanie usługą: {$serviceName}

WIADOMOŚĆ:
{$formData['message']}

---
Wysłano: " . date('d-m-Y H:i:s') . "
Ze strony: biurorachunkowenysa.com
Metoda: Basic mail() (SMTP fallback)
";
            
            // Headers
            $headers = array(
                'MIME-Version: 1.0',
                'Content-Type: text/plain; charset=UTF-8',
                'From: biurarcc@biurorachunkowenysa.com',
                'Reply-To: ' . $formData['email'],
                'X-Mailer: PHP/' . phpversion(),
                'X-Priority: 3'
            );
            
            // Send email using basic mail() function
            $result = mail($to, $subject, $message, implode("\r\n", $headers));
            
            if ($result) {
                error_log("Email sent successfully using basic mail() function as SMTP fallback");
                return [
                    'success' => true,
                    'message' => 'Wiadomość została wysłana pomyślnie!'
                ];
            } else {
                throw new Exception("Basic mail() function failed");
            }
            
        } catch (Exception $e) {
            error_log("Basic mail() fallback failed: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Send auto-reply to customer
     */
    public function sendAutoReply($customerEmail, $customerName) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($customerEmail, $customerName);
            
            $this->mail->Subject = 'Potwierdzenie otrzymania wiadomości - MKM Biuro Rachunkowe';
            $this->mail->isHTML(true);
            
            $this->mail->Body = "
            <html>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #b10a21; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                    .content { padding: 20px; background: #f9f9f9; border-radius: 0 0 8px 8px; }
                    .footer { background: #f5f5f5; padding: 15px; text-align: center; font-size: 12px; border-radius: 0 0 8px 8px; }
                    .contact-info { background: #fff; padding: 15px; margin: 15px 0; border-left: 4px solid #b10a21; border-radius: 4px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>MKM Biuro Rachunkowe</h2>
                        <p>Potwierdzenie otrzymania wiadomości</p>
                    </div>
                    
                    <div class='content'>
                        <p>Szanowny/a <strong>{$customerName}</strong>,</p>
                        
                        <p>Dziękujemy za skontaktowanie się z <strong>MKM Biuro Rachunkowe</strong> przez formularz na naszej stronie internetowej.</p>
                        
                        <p><strong>✅ Potwierdzamy otrzymanie Państwa wiadomości.</strong></p>
                        
                        <p>Nasz zespół przeanalizuje Państwa zapytanie i skontaktuje się w ciągu <strong>24 godzin</strong>.</p>
                        
                        <div class='contact-info'>
                            <p><strong>W pilnych sprawach prosimy o kontakt telefoniczny:</strong></p>
                            <p>📞 <strong>+48 664 767 930</strong></p>
                            <p>📧 <strong>malgorzatakrzyzowska@wp.pl</strong></p>
                            <p>🏢 <strong>Nysa, ul. Grunwaldzka 1</strong></p>
                        </div>
                        
                        <p>Z poważaniem,<br>
                        <strong>Zespół MKM Biuro Rachunkowe</strong><br>
                        <em>Profesjonalne usługi księgowe od 25+ lat</em></p>
                    </div>
                    
                    <div class='footer'>
                        <p><strong>MKM Biuro Rachunkowe</strong> | biurorachunkowenysa.com</p>
                        <p>Profesjonalne usługi księgowe w Nysie</p>
                    </div>
                </div>
            </body>
            </html>";
            
            $this->mail->send();
            
            return true;
            
        } catch (Exception $e) {
            error_log("Auto-reply error: " . $e->getMessage());
            return false;
        }
    }
}

// Function to easily send contact form emails
function sendContactEmail($formData) {
    try {
        $mailer = new MKMMailer();
        $result = $mailer->sendContactForm($formData);
        
        // Send auto-reply to customer
        if ($result['success']) {
            $mailer->sendAutoReply(
                $formData['email'], 
                $formData['firstName'] . ' ' . $formData['lastName']
            );
        }
        
        return $result;
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => 'Błąd konfiguracji email: ' . $e->getMessage()
        ];
    }
}

// Security: Rate limiting function
function checkRateLimit($ip, $limit = 3, $timeWindow = 3600) {
    $file = sys_get_temp_dir() . '/mkm_contact_rate_limit.json';
    $currentTime = time();
    $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    // Clean old entries
    foreach ($data as $storedIp => $timestamps) {
        $data[$storedIp] = array_filter($timestamps, fn($t) => ($currentTime - $t) < $timeWindow);
        if (empty($data[$storedIp])) unset($data[$storedIp]);
    }

    // Check current IP
    if (!isset($data[$ip])) $data[$ip] = [];
    if (count($data[$ip]) >= $limit) return false;

    // Add current timestamp
    $data[$ip][] = $currentTime;
    file_put_contents($file, json_encode($data), LOCK_EX);
    return true;
}

// Security: Input sanitization
function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

// Security: Check for suspicious content
function containsSuspiciousContent($input) {
    $suspiciousPatterns = [
        '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i',
        '/javascript:/i',
        '/vbscript:/i',
        '/onload\s*=/i',
        '/onerror\s*=/i',
        '/eval\s*\(/i',
        '/expression\s*\(/i',
        '/document\./i',
        '/window\./i',
        '/<iframe/i',
        '/<object/i',
        '/<embed/i',
        '/<form/i',
        '/data:text\/html/i',
        '/\bphp:/i',
        '/file:\/\//i'
    ];
    
    foreach ($suspiciousPatterns as $pattern) {
        if (preg_match($pattern, $input)) {
            return true;
        }
    }
    
    return false;
}
?>