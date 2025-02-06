<?php
require_once(__DIR__ . '/../../../config.php'); // Includi il file di configurazione di Moodle
require_login();

$context = context_system::instance();
require_capability('moodle/site:config', $context); // Assicurati che l'utente abbia i permessi necessari

// Verifica la sesskey per prevenire attacchi CSRF
if (!confirm_sesskey()) {
    echo json_encode(['success' => false, 'response' => 'Sesskey non valida']);
    exit;
}

$api_token = get_config('local_zendesk', 'api_token');
$zendesk_email = get_config('local_zendesk', 'zendesk_email');
$form_id = get_config('local_zendesk', 'form_id');
$subdomain = get_config('local_zendesk', 'subdomain');

$subject = required_param('subject', PARAM_TEXT);
$description = required_param('description', PARAM_TEXT);
$attachment = $_FILES['attachment'] ?? null;

$upload_token = null;

if ($attachment) {
    switch ($attachment['error']) {
        case UPLOAD_ERR_OK:
            $file_path = $attachment['tmp_name'];
            $file_name = $attachment['name'];
            $file_data = file_get_contents($file_path);
            $file_type = mime_content_type($file_path);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://$subdomain.zendesk.com/api/v2/uploads.json?filename=" . urlencode($file_name) );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $file_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: ' . $file_type,
                'Authorization: Basic ' . base64_encode("$zendesk_email/token:$api_token")
            ]);

            $upload_response = curl_exec($ch);
            $upload_httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($upload_httpcode == 201) {
                $upload_data = json_decode($upload_response, true);
                $upload_token = $upload_data['upload']['token'];
                echo json_encode([
                    'success' => true, 
                    'response' => 'Allegato caricato con successo',
                    'upload_token' => $upload_token, 
                    'upload_url' => $upload_data['upload']['attachment']['content_url'],
                    'upload_response' => json_decode($upload_response)
                ]);
                exit;
            } else {
                echo json_encode(['success' => false, 'response' => $upload_response]);
                exit;
            }
            break;

        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            echo json_encode(['success' => false, 'response' => 'File troppo grande.']);
            break;

        case UPLOAD_ERR_PARTIAL:
            echo json_encode(['success' => false, 'response' => 'Il file è stato caricato solo parzialmente.']);
            break;

        case UPLOAD_ERR_NO_FILE:
            echo json_encode(['success' => false, 'response' => 'Nessun file caricato.']);
            break;

        case UPLOAD_ERR_NO_TMP_DIR:
            echo json_encode(['success' => false, 'response' => 'Cartella temporanea mancante.']);
            break;

        case UPLOAD_ERR_CANT_WRITE:
            echo json_encode(['success' => false, 'response' => 'Errore di scrittura su disco.']);
            break;

        case UPLOAD_ERR_EXTENSION:
            echo json_encode(['success' => false, 'response' => 'Caricamento del file bloccato da un\'estensione PHP.']);
            break;

        default:
            echo json_encode(['success' => false, 'response' => 'Errore sconosciuto durante il caricamento del file.']);
            break;
    }
    exit;
}

// // return json of data anyway
// echo json_encode(['success' => false, 'upload_token' => $upload_token, 'response' => 'Nessun allegato caricato']);
// exit;