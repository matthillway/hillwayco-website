<?php
/**
 * Hillway Contact Form Handler with Anti-Spam Protection
 * Version 2.2 - Security hardened (Dec 2024)
 * - Email header injection prevention
 * - File-based rate limiting (session bypass protection)
 * - Input sanitization for all header contexts
 * - IP detection improved for proxied requests
 */

// Set timezone
date_default_timezone_set('Europe/London');

// Your email configuration
$to_email = 'hello@hillwayco.uk';
$from_email = 'noreply@hillwayco.uk';
$site_name = 'Hillway';

// Anti-Spam Configuration
$minimum_time_to_fill = 3; // Minimum seconds to fill form (bots fill instantly)
$maximum_submissions_per_ip = 3; // Max submissions per IP per hour
$blocked_keywords = ['viagra', 'casino', 'poker', 'cialis', 'loan', 'winner', 'prize', 'crypto', 'bitcoin', 'forex'];

// Rate limiting file path (server-side, more secure than session-only)
$rate_limit_file = sys_get_temp_dir() . '/hillway_contact_rate_limits.json';

// Start session for rate limiting
session_start();

// Process form only if it's a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // ============================================
    // ANTI-SPAM CHECK 1: Honeypot Field
    // ============================================
    // If the hidden 'website' field is filled, it's a bot
    if (!empty($_POST['website'])) {
        // Bot detected - silently redirect without processing
        header("Location: thank-you.html");
        exit();
    }
    
    // ============================================
    // ANTI-SPAM CHECK 2: Time-based Check
    // ============================================
    // Check if form was filled too quickly (bots fill instantly)
    if (isset($_POST['form_loaded_time'])) {
        $time_taken = time() - $_POST['form_loaded_time'];
        if ($time_taken < $minimum_time_to_fill) {
            // Form filled too quickly - likely a bot
            header("Location: thank-you.html");
            exit();
        }
    }
    
    // ============================================
    // ANTI-SPAM CHECK 3: Rate Limiting by IP (File-based + Session)
    // ============================================
    // Get real IP address (handle proxies/load balancers)
    $user_ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $forwarded_ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $user_ip = trim($forwarded_ips[0]);
    } elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
        $user_ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    // Validate IP format
    if (!filter_var($user_ip, FILTER_VALIDATE_IP)) {
        $user_ip = $_SERVER['REMOTE_ADDR'];
    }

    $current_time = time();

    // File-based rate limiting (persistent, cannot be bypassed by clearing cookies)
    $ip_rate_limits = [];
    if (file_exists($rate_limit_file)) {
        $ip_rate_limits = json_decode(file_get_contents($rate_limit_file), true) ?: [];
    }

    // Clean old entries (older than 1 hour)
    foreach ($ip_rate_limits as $ip => $timestamps) {
        $ip_rate_limits[$ip] = array_filter($timestamps, function($time) use ($current_time) {
            return ($current_time - $time) < 3600;
        });
        if (empty($ip_rate_limits[$ip])) {
            unset($ip_rate_limits[$ip]);
        }
    }

    // Check file-based rate limit
    if (isset($ip_rate_limits[$user_ip]) && count($ip_rate_limits[$user_ip]) >= $maximum_submissions_per_ip) {
        // Too many submissions from this IP
        header("Location: index.html?error=rate_limit#contact");
        exit();
    }

    // Also keep session-based rate limiting as secondary check
    if (!isset($_SESSION['form_submissions'])) {
        $_SESSION['form_submissions'] = [];
    }

    // Clean old submissions (older than 1 hour)
    $_SESSION['form_submissions'] = array_filter($_SESSION['form_submissions'], function($submission) use ($current_time) {
        return ($current_time - $submission['time']) < 3600;
    });

    // Count submissions from this IP in session
    $ip_submissions = array_filter($_SESSION['form_submissions'], function($submission) use ($user_ip) {
        return $submission['ip'] === $user_ip;
    });

    if (count($ip_submissions) >= $maximum_submissions_per_ip) {
        // Too many submissions from this IP
        header("Location: index.html?error=rate_limit#contact");
        exit();
    }
    
    // ============================================
    // COLLECT AND SANITIZE FORM DATA
    // ============================================
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $company = isset($_POST['company']) ? trim($_POST['company']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $gdpr_consent = isset($_POST['gdpr-consent']) ? true : false;
    $marketing_consent = isset($_POST['marketing-consent']) ? true : false;

    // ============================================
    // SECURITY: Email Sanitization & Validation
    // ============================================
    // Sanitize email to prevent header injection
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    // Remove any newlines or carriage returns (header injection prevention)
    $email = str_replace(["\r", "\n", "%0a", "%0d"], '', $email);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.html?error=invalid_email#contact");
        exit();
    }

    // ============================================
    // SECURITY: Sanitize name for use in headers/subject
    // ============================================
    // Remove any characters that could be used for header injection
    $name = str_replace(["\r", "\n", "%0a", "%0d"], '', $name);
    // Also sanitize for email header context
    $safe_name_for_header = preg_replace('/[^a-zA-Z0-9\s\-\.\'\,]/', '', $name);
    
    // ============================================
    // ANTI-SPAM CHECK 4: Content Filtering
    // ============================================
    $combined_content = strtolower($name . ' ' . $email . ' ' . $phone . ' ' . $company . ' ' . $message);
    foreach ($blocked_keywords as $keyword) {
        if (strpos($combined_content, $keyword) !== false) {
            // Spam keyword detected - silently redirect
            header("Location: thank-you.html");
            exit();
        }
    }
    
    // ============================================
    // ANTI-SPAM CHECK 5: URL Detection
    // ============================================
    // Check for excessive URLs (more than 2)
    $url_pattern = '/https?:\/\//i';
    preg_match_all($url_pattern, $message, $url_matches);
    if (count($url_matches[0]) > 2) {
        // Too many URLs - likely spam
        header("Location: thank-you.html");
        exit();
    }
    
    // ============================================
    // ANTI-SPAM CHECK 6: Email Domain Validation
    // ============================================
    $blocked_domains = ['tempmail.com', 'throwaway.email', 'guerrillamail.com', 'mailinator.com', '10minutemail.com'];
    $email_domain = substr(strrchr($email, "@"), 1);
    if (in_array(strtolower($email_domain), $blocked_domains)) {
        // Disposable email detected
        header("Location: index.html?error=invalid_email#contact");
        exit();
    }
    
    // ============================================
    // STANDARD VALIDATION
    // ============================================
    $errors = [];
    
    // Check required fields
    if (empty($name)) {
        $errors[] = 'Name is required';
    } elseif (strlen($name) < 2 || strlen($name) > 50) {
        $errors[] = 'Name must be between 2 and 50 characters';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($phone)) {
        $errors[] = 'Phone number is required';
    } elseif (!preg_match('/^[\d\s\+\-\(\)]+$/', $phone)) {
        $errors[] = 'Invalid phone number format';
    } elseif (strlen($phone) < 10 || strlen($phone) > 20) {
        $errors[] = 'Phone number must be between 10 and 20 characters';
    }
    
    if (empty($message)) {
        $errors[] = 'Message is required';
    } elseif (strlen($message) < 10) {
        $errors[] = 'Message must be at least 10 characters';
    } elseif (strlen($message) > 5000) {
        $errors[] = 'Message is too long';
    }
    
    if (!$gdpr_consent) {
        $errors[] = 'GDPR consent is required';
    }
    
    // If there are validation errors, redirect back with error
    if (!empty($errors)) {
        header("Location: index.html?error=missing_fields#contact");
        exit();
    }
    
    // ============================================
    // RECORD SUBMISSION FOR RATE LIMITING (Both Session + File)
    // ============================================
    $_SESSION['form_submissions'][] = [
        'ip' => $user_ip,
        'time' => $current_time
    ];

    // Update file-based rate limiting
    if (!isset($ip_rate_limits[$user_ip])) {
        $ip_rate_limits[$user_ip] = [];
    }
    $ip_rate_limits[$user_ip][] = $current_time;
    file_put_contents($rate_limit_file, json_encode($ip_rate_limits), LOCK_EX);
    
    // ============================================
    // PREPARE AND SEND EMAIL
    // ============================================
    $email_subject = "New Contact Form Submission from $site_name";
    
    // Create HTML email body
    $email_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #000; padding: 40px 20px; text-align: center; }
            .logo { max-width: 400px; width: 100%; height: auto; }
            .content { background: #f5f5f5; padding: 20px; margin-top: 20px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #000; }
            .value { margin-top: 5px; padding: 10px; background: #fff; border-left: 3px solid #000; }
            .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
            .consent { background: #e8f5e9; padding: 10px; margin-top: 20px; border-radius: 5px; }
            .security { background: #fff3cd; padding: 10px; margin-top: 10px; border-radius: 5px; font-size: 11px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <img src='https://www.hillwayco.uk/images/logo-email.png' alt='Hillway Property Consultants' class='logo' style='max-width: 400px; width: 100%; height: auto;'>
                <h2 style='color: white; margin-top: 20px; font-weight: normal;'>New Contact Form Submission</h2>
            </div>
            <div class='content'>
                <div class='field'>
                    <div class='label'>Name:</div>
                    <div class='value'>" . htmlspecialchars($name) . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Email:</div>
                    <div class='value'>" . htmlspecialchars($email) . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Phone:</div>
                    <div class='value'>" . htmlspecialchars($phone) . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Company:</div>
                    <div class='value'>" . (empty($company) ? 'Not provided' : htmlspecialchars($company)) . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Message:</div>
                    <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
                </div>
                
                <div class='consent'>
                    <strong>GDPR Consent:</strong> ✓ Given<br>
                    <strong>Marketing Consent:</strong> " . ($marketing_consent ? '✓ Given' : '✗ Not given') . "
                </div>
                
                <div class='security'>
                    <strong>Security Check:</strong> Passed all anti-spam filters<br>
                    <strong>Form filled in:</strong> " . gmdate("i:s", $time_taken) . " (min:sec)
                </div>
            </div>
            <div class='footer'>
                <p>Submitted on: " . date('d/m/Y at H:i') . "</p>
                <p>IP Address: " . htmlspecialchars($user_ip) . "</p>
                <p>This email was sent from the contact form at hillwayco.uk</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Also create a plain text version for better compatibility
    $plain_text_body = "New Contact Form Submission\n";
    $plain_text_body .= "=============================\n\n";
    $plain_text_body .= "Name: " . $name . "\n";
    $plain_text_body .= "Email: " . $email . "\n";
    $plain_text_body .= "Phone: " . $phone . "\n";
    $plain_text_body .= "Company: " . (empty($company) ? 'Not provided' : $company) . "\n\n";
    $plain_text_body .= "Message:\n" . $message . "\n\n";
    $plain_text_body .= "GDPR Consent: Given\n";
    $plain_text_body .= "Marketing Consent: " . ($marketing_consent ? 'Given' : 'Not given') . "\n\n";
    $plain_text_body .= "Submitted on: " . date('d/m/Y at H:i') . "\n";
    $plain_text_body .= "IP Address: " . $user_ip . "\n";

    // Set up email headers (sanitized email already validated above)
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . $site_name . " <" . $from_email . ">" . "\r\n";
    // Reply-To uses sanitized and validated email (injection-safe)
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send the email
    $mail_sent = mail($to_email, $email_subject, $email_body, $headers);
    
    // Send auto-reply to the user
    if ($mail_sent) {
        $auto_reply_subject = "Thank you for contacting Hillway";
        $auto_reply_body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #000; padding: 40px 30px; text-align: center; }
                .logo { max-width: 400px; width: 100%; height: auto; }
                .content { padding: 30px; }
                .footer { background: #f5f5f5; padding: 20px; text-align: center; font-size: 12px; color: #666; }
                a { color: #000; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <img src='https://www.hillwayco.uk/images/logo-email.png' alt='Hillway Property Consultants' class='logo' style='max-width: 400px; width: 100%; height: auto;'>
                </div>
                <div class='content'>
                    <p>Dear " . htmlspecialchars($name) . ",</p>
                    
                    <p>Thank you for contacting Hillway. We have received your message and appreciate your interest in our services.</p>
                    
                    <p>Our team will review your enquiry and get back to you within 1-2 business days. If your matter is urgent, please don't hesitate to call us directly on <strong>0333 404 0861</strong>.</p>
                    
                    <p><strong>Your message:</strong><br>
                    <em>" . nl2br(htmlspecialchars($message)) . "</em></p>
                    
                    <p>Best regards,<br>
                    <strong>The Hillway Team</strong></p>
                </div>
                <div class='footer'>
                    <p><strong>Hillway</strong><br>
                    Redefining Real Estate Through Digital Innovation<br>
                    <a href='https://www.hillwayco.uk'>www.hillwayco.uk</a> | <a href='mailto:hello@hillwayco.uk'>hello@hillwayco.uk</a><br>
                    Sheffield: Cubo, 38 Carver Street, S1 4FS<br>
                    Doncaster: First Floor, David House, 30 South Parade, Bawtry, DN10 6JH</p>
                    " . ($marketing_consent ? "" : "<p><small>You have not opted in to marketing communications. You will only receive a response to this enquiry.</small></p>") . "
                </div>
            </div>
        </body>
        </html>
        ";
        
        $auto_reply_headers = "MIME-Version: 1.0" . "\r\n";
        $auto_reply_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $auto_reply_headers .= "From: " . $site_name . " <" . $from_email . ">" . "\r\n";
        
        // Send auto-reply
        mail($email, $auto_reply_subject, $auto_reply_body, $auto_reply_headers);
    }
    
    // Redirect based on success or failure
    if ($mail_sent) {
        header("Location: thank-you.html");
        exit();
    } else {
        header("Location: index.html?error=send_failed#contact");
        exit();
    }
    
} else {
    // If someone tries to access this file directly, redirect to homepage
    header("Location: index.html");
    exit();
}
?>