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

// Leggi il payload JSON o x-www-form-urlencoded
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Se non è JSON, prova da $_POST
if (!$data) {
    $data = $_POST;
}

// Valida i parametri richiesti
$required = ['nome', 'cognome', 'cf', 'email'];
$missing = array_filter($required, fn($f) => empty($data[$f]));

if (!empty($missing)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Parametri mancanti: ' . implode(', ', $missing)]);
    exit;
}

// Prepara dati
$record = (object)[
    'firstname' => trim($data['nome']),
    'lastname' => trim($data['cognome']),
    'codicefiscale' => strtoupper(trim($data['cf'])),
    'email' => trim($data['email']),
    'status' => 'queued',
    'startedat' => null,
    'retries' => 0,
    'timecreated' => time(),
];

// Inserisci nella tabella
global $DB;
try {
    $DB->insert_record('local_createuserqueue_queue', $record);
    echo json_encode(['status' => 'ok', 'message' => 'Utente messo in coda']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Errore interno: ' . $e->getMessage()]);
}
