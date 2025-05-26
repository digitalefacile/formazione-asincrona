<?php
require_once(__DIR__ . '/../../../config.php'); // Includi il file di configurazione di Moodle
require_login();

$context = context_system::instance();
// require_capability('moodle/site:config', $context); // Assicurati che l'utente abbia i permessi necessari

require_login();
if (!isloggedin() || $USER->username == 'guest') {
    echo json_encode(['success' => false, 'error' => 'Errore: Utente non loggato.']);
    exit;
}

// Verifica la sesskey per prevenire attacchi CSRF
require_sesskey();

$api_token = get_config('local_zendesk', 'api_token');
$zendesk_email = get_config('local_zendesk', 'zendesk_email');
$subdomain = get_config('local_zendesk', 'subdomain');

$ticket_id = required_param('ticket_id', PARAM_INT);
$ticket_subject = required_param('ticket_subject', PARAM_TEXT);

// Codice per aggiornare il soggetto di un ticket su Zendesk
$update_data = [
    'ticket' => [
        'subject' => $ticket_subject
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$subdomain.zendesk.com/api/v2/tickets/$ticket_id.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($update_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode("$zendesk_email/token:$api_token")
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

header('Content-Type: application/json');
echo json_encode([
    'success' => $httpcode == 200,
    'response' => json_decode($response, true),
    'httpcode' => $httpcode
]);
?>
