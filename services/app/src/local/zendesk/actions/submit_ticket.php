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
$attachment = $_FILES['attachment'] ?? null;

$upload_token = null;

if ($attachment && $attachment['error'] == UPLOAD_ERR_OK) {
    $file_path = $attachment['tmp_name'];
    $file_name = $attachment['name'];
    $file_data = file_get_contents($file_path);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://$subdomain.zendesk.com/api/v2/uploads.json?filename=" . urlencode($file_name));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $file_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/binary',
        'Authorization: Basic ' . base64_encode("$zendesk_email/token:$api_token")
    ]);

    $upload_response = curl_exec($ch);
    $upload_httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($upload_httpcode == 201) {
        $upload_data = json_decode($upload_response, true);
        $upload_token = $upload_data['upload']['token'];
    } else {
        echo json_encode(['success' => false, 'response' => $upload_response]);
        exit;
    }
}

// Codice per inviare un ticket a Zendesk
$ticket_data = [
    'ticket' => [
        'subject' => $subject,
        'description' => $description,
        'priority' => 'high',
        'ticket_form_id' => $form_id,
        'requester' => [
            'name' => 'Nome Richiedente',
            'email' => $zendesk_email
        ],
        'custom_fields' => [
            // Aggiungi qui i campi personalizzati, se necessario
        ]
    ]
];

if ($upload_token) {
    $ticket_data['ticket']['comment'] = [
        'body' => $description,
        'uploads' => [$upload_token]
    ];
}

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
echo $response;
?>