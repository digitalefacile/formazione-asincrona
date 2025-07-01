<?php
require('../../config.php');
require_login();
require_capability('local/createuserqueue:view', context_system::instance());

$PAGE->set_url(new moodle_url('/local/createuserqueue/view_errors.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Errori importazione utenti');
$PAGE->set_heading('Errori importazione utenti');

echo $OUTPUT->header();

// Ottieni errori direttamente dalla tabella errori
$records = $DB->get_records('local_createuserqueue_errors', null, 'timecreated DESC');

if ($records) {
    echo html_writer::start_tag('table', ['class' => 'generaltable']);
    echo html_writer::start_tag('tr');
    echo '<th>Nome</th><th>Cognome</th><th>Codice Fiscale</th><th>Email</th><th>Errore</th><th>Creato</th>';
    echo html_writer::end_tag('tr');

    foreach ($records as $r) {
        echo html_writer::start_tag('tr');
        echo "<td>{$r->firstname}</td>";
        echo "<td>{$r->lastname}</td>";
        echo "<td>{$r->codicefiscale}</td>";
        echo "<td>{$r->email}</td>";
        echo "<td>{$r->errormessage}</td>";
        echo "<td>" . date('Y-m-d H:i', $r->timecreated) . "</td>";
        echo html_writer::end_tag('tr');
    }

    echo html_writer::end_tag('table');
} else {
    echo $OUTPUT->notification('Nessun errore registrato.', 'notifymessage');
}

echo $OUTPUT->footer();
