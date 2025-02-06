<?php
require_once(__DIR__ . '/../../../config.php'); // Includi il file di configurazione di Moodle
require_login();

$context = context_system::instance();
require_capability('moodle/site:config', $context); // Assicurati che l'utente abbia i permessi necessari

// Verifica la sesskey per prevenire attacchi CSRF
require_sesskey();

$api_token = get_config('local_zendesk', 'api_token');
$zendesk_email = get_config('local_zendesk', 'zendesk_email');
$form_id = get_config('local_zendesk', 'form_id');
$subdomain = get_config('local_zendesk', 'subdomain');

$subject = required_param('subject', PARAM_TEXT);
$description = required_param('description', PARAM_TEXT);
$name = required_param('name', PARAM_TEXT);
$email = required_param('email', PARAM_EMAIL);

$upload_token = optional_param('upload_tokens', null, PARAM_RAW);
$upload_token = json_decode($upload_token, true);

// Codice per inviare un ticket a Zendesk
$ticket_data = [
    'ticket' => [
        'subject' => $subject,
        'description' => $description,
        'priority' => 'high',
        'ticket_form_id' => $form_id,
        'requester' => [
            'name' => $name,
            'email' => $email
        ],
        'custom_fields' => [
            // Aggiungi qui i campi personalizzati, se necessario
        ],
        'comment' => [
            'body' => $description,
            'uploads' => $upload_token
        ]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$subdomain.zendesk.com/api/v2/tickets.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ticket_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode("$zendesk_email/token:$api_token")
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

header('Content-Type: application/json');
echo json_encode([
    'success' => $httpcode == 201,
    'response' => json_decode($response, true),
    'httpcode' => $httpcode
]);
?>