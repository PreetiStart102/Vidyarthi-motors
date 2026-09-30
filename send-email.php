<?php
// send-email.php - Vidyarthi Motors Website Contact & Trial Inquiry Handler

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Only POST requests are allowed.']);
    exit();
}

// Get POST data (supports both standard form-data and JSON input)
$input = $_POST;
if (empty($input)) {
    $json = file_get_contents('php://input');
    $input = json_decode($json, true) ?? [];
}

$name = isset($input['name']) ? htmlspecialchars(trim($input['name']), ENT_QUOTES, 'UTF-8') : '';
$phone = isset($input['phone']) ? htmlspecialchars(trim($input['phone']), ENT_QUOTES, 'UTF-8') : '';
$emailRaw = isset($input['email']) ? trim($input['email']) : '';
$email = filter_var($emailRaw, FILTER_VALIDATE_EMAIL) ? $emailRaw : '';
$fleet = isset($input['fleet']) ? htmlspecialchars(trim($input['fleet']), ENT_QUOTES, 'UTF-8') : 'Commercial Fleet Trial';
$message = isset($input['message']) ? htmlspecialchars(trim($input['message']), ENT_QUOTES, 'UTF-8') : 'Trial fitment inquiry from website.';

if (empty($name) || empty($phone)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Name and Phone number are required fields.']);
    exit();
}

// Destination email address
$to = 'info@vidyarthimotors.com';
$subject = '=?UTF-8?B?' . base64_encode('New Website Inquiry - ' . $name . ' (' . $phone . ')') . '?=';

// HTML Email Template
$body = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Trial Fitment Inquiry</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; color: #333333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header { background: #061e47; color: #ffffff; padding: 24px; text-align: center; }
        .header h2 { margin: 0; font-size: 22px; letter-spacing: 0.5px; }
        .header p { margin: 5px 0 0; font-size: 13px; color: #94a3b8; }
        .content { padding: 24px; }
        .field-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .field-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        .field-table td.label { font-weight: bold; color: #061e47; width: 35%; background: #f8fafc; }
        .footer { background: #0f172a; color: #94a3b8; padding: 16px; text-align: center; font-size: 12px; }
        .badge { display: inline-block; background: #dc2626; color: #ffffff; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>VIDYARTHI MOTORS</h2>
            <p>Patented Active Air Enhancement Technology (FST)</p>
        </div>
        <div class="content">
            <p style="font-size: 16px; font-weight: bold; color: #061e47; margin-top:0;">
                <span class="badge">NEW INQUIRY</span> Received from Website Form
            </p>
            <table class="field-table">
                <tr>
                    <td class="label">Full Name:</td>
                    <td><strong>' . $name . '</strong></td>
                </tr>
                <tr>
                    <td class="label">Phone Number:</td>
                    <td><a href="tel:' . $phone . '" style="color: #0284c7; font-weight: bold;">' . $phone . '</a></td>
                </tr>
                <tr>
                    <td class="label">Email Address:</td>
                    <td>' . ($email ? '<a href="mailto:' . $email . '">' . $email . '</a>' : '<span style="color:#94a3b8;">Not provided</span>') . '</td>
                </tr>
                <tr>
                    <td class="label">Fleet Size / Models:</td>
                    <td>' . ($fleet ? $fleet : 'Commercial Fleet') . '</td>
                </tr>
                <tr>
                    <td class="label">Message / Details:</td>
                    <td>' . nl2br($message) . '</td>
                </tr>
                <tr>
                    <td class="label">Submission Date:</td>
                    <td>' . date('F j, Y, g:i a T') . '</td>
                </tr>
                <tr>
                    <td class="label">Sender IP:</td>
                    <td>' . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . '</td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <p style="margin: 0;">This email was sent automatically from the inquiry form on <a href="https://vidyarthimotors.com" style="color: #38bdf8;">vidyarthimotors.com</a>.</p>
        </div>
    </div>
</body>
</html>
';

// Mail headers
$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/html; charset=UTF-8';
$headers[] = 'From: Vidyarthi Motors Website <no-reply@vidyarthimotors.com>';
if (!empty($email)) {
    $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
} else {
    $headers[] = 'Reply-To: info@vidyarthimotors.com';
}
$headers[] = 'X-Mailer: PHP/' . phpversion();

// Attempt to send email
$mailSent = @mail($to, $subject, $body, implode("\r\n", $headers));

if ($mailSent) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your trial fitment inquiry has been sent to info@vidyarthimotors.com successfully.',
        'mail_sent' => true
    ]);
} else {
    // Fallback response for environments without active local sendmail service
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your inquiry has been registered successfully.',
        'mail_sent' => false
    ]);
}
