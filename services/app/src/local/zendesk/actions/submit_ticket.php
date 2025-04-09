<?php
require_once(__DIR__ . '/../../../config.php'); // Includi il file di configurazione di Moodle
require_login();

$context = context_system::instance();
// require_capability('moodle/site:config', $context); // Assicurati che l'utente abbia i permessi necessari

require_login();
if (!isloggedin() || $USER->username == 'guest') {
    echo "Errore: Utente non loggato.";
    exit;
}

// Verifica la sesskey per prevenire attacchi CSRF
require_sesskey();

$api_token = get_config('local_zendesk', 'api_token');
$zendesk_email = get_config('local_zendesk', 'zendesk_email');
$form_id = get_config('local_zendesk', 'form_id');
$subdomain = get_config('local_zendesk', 'subdomain');
$user_group_field_id = get_config('local_zendesk', 'user_group_field_id');
$username_field_id = get_config('local_zendesk', 'username_field_id');
$name_field_id = get_config('local_zendesk', 'name_field_id');

$subject = required_param('subject', PARAM_TEXT);
$description = required_param('description', PARAM_TEXT);
$name = required_param('name', PARAM_TEXT);
$email = required_param('email', PARAM_EMAIL);
$username_text = required_param('username', PARAM_TEXT);
$user_groups_text = required_param('groups', PARAM_TEXT);

$area_tematica = optional_param('area_tematica', null, PARAM_TEXT);
$altra_area_tematica = optional_param('altra_area_tematica', null, PARAM_TEXT);

$upload_token = optional_param('upload_tokens', null, PARAM_RAW);
$upload_token = json_decode($upload_token, true);

$custom_fields = [
    [
        'id' => $username_field_id,
        'value' => (string)$username_text
    ],
    [
        'id' => $name_field_id,
        'value' => (string)$name
    ],
    [
        'id' => $user_group_field_id,
        'value' => $user_groups_text
    ]
];

if ($area_tematica) {
    $custom_fields[] = [
        'id' => get_config('local_zendesk', 'area_tematica_field_id'),
        'value' => $area_tematica
    ];
}

if ($altra_area_tematica) {
    $custom_fields[] = [
        'id' => get_config('local_zendesk', 'altra_area_tematica_field_id'),
        'value' => $altra_area_tematica
    ];
}

// Codice per inviare un ticket a Zendesk
$ticket_data = [
    'ticket' => [
        'subject' => $subject,
        'description' => urldecode($description),
        'ticket_form_id' => $form_id,
        'requester' => [
            'name' => $name,
            'email' => $email
        ],
        'custom_fields' => $custom_fields,
        'comment' => [
            'html_body' => urldecode($description),
            'uploads' => $upload_token
        ]
    ]
];

// var_dump($ticket_data);
// exit;

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