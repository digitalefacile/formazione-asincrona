<?php
require('../../config.php');
require_login();
require_capability('local/createuserqueue:view', context_system::instance());

$PAGE->set_url(new moodle_url('/local/createuserqueue/view_queue.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Coda utenti');
$PAGE->set_heading('Coda utenti');

echo $OUTPUT->header();

$records = $DB->get_records('local_createuserqueue_queue');
if ($records) {
    echo html_writer::start_tag('table', ['class' => 'generaltable']);
    echo html_writer::start_tag('tr');
    echo '<th>Nome</th><th>Cognome</th><th>Codice Fiscale</th><th>Email</th><th>Creato</th><th>Status</th><th>Started At</th><th>Retries</th>';
    echo html_writer::end_tag('tr');

    foreach ($records as $r) {
        $startedat = $r->startedat ? date('Y-m-d H:i:s', $r->startedat) : '-';
        echo html_writer::start_tag('tr');
        echo "<td>{$r->firstname}</td>";
        echo "<td>{$r->lastname}</td>";
        echo "<td>{$r->codicefiscale}</td>";
        echo "<td>{$r->email}</td>";
        echo "<td>" . date('Y-m-d H:i', $r->timecreated) . "</td>";
        echo "<td>{$r->status}</td>";
        echo "<td>{$startedat}</td>";
        echo "<td>{$r->retries}</td>";
        echo html_writer::end_tag('tr');
    }

    echo html_writer::end_tag('table');
} else {
    echo $OUTPUT->notification('Nessun utente in coda.', 'notifymessage');
}

echo $OUTPUT->footer();
