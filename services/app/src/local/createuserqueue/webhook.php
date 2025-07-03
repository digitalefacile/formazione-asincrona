<?php
define('NO_DEBUG_DISPLAY', true);
define('NO_MOODLE_COOKIES', true);
require('../../config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Solo POST ammesso']);
    exit;
}

// Leggi il payload JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['data'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Formato JSON non valido o campo "data" mancante']);
    exit;
}

// Estrai i dati richiesti dal campo "data"
$requiredFields = [
    'Nome' => 'firstname',
    'Cognome' => 'lastname',
    'Codice fiscale' => 'codicefiscale',
    'Email' => 'email'
];

$record = [
    'status' => 'queued',
    'startedat' => null,
    'retries' => 0,
    'timecreated' => time()
];

foreach ($requiredFields as $fieldName => $dbField) {
    $fieldData = array_filter($data['data'], fn($item) => $item['name'] === $fieldName);
    if (empty($fieldData)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "Campo richiesto mancante: $fieldName"]);
        exit;
    }
    $record[$dbField] = trim(reset($fieldData)['value']);
}

// Inserisci nella tabella
global $DB;
try {
    $DB->insert_record('local_createuserqueue_queue', (object)$record);
    echo json_encode(['status' => 'ok', 'message' => 'Utente messo in coda']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Errore interno: ' . $e->getMessage()]);
}
