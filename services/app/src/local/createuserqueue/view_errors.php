<?php
require('../../config.php');
require_login();
require_capability('local/createuserqueue:view', context_system::instance());

$PAGE->set_url(new moodle_url('/local/createuserqueue/view_errors.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Errori importazione utenti');
$PAGE->set_heading('Errori importazione utenti');

// Gestisci eliminazione singolo errore
$delete_id = optional_param('delete_id', 0, PARAM_INT);
if ($delete_id && confirm_sesskey()) {
    $DB->delete_records('local_createuserqueue_errors', ['id' => $delete_id]);
    redirect($PAGE->url, "Errore eliminato con successo.", 2, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();

// Ottieni errori direttamente dalla tabella errori
$records = $DB->get_records('local_createuserqueue_errors', null, 'timecreated DESC');

if ($records) {
    echo html_writer::start_tag('table', ['class' => 'generaltable']);
    echo html_writer::start_tag('tr');
    echo '<th>Nome</th><th>Cognome</th><th>Codice Fiscale</th><th>Email</th><th>Errore</th><th>Creato</th><th>Azioni</th>';
    echo html_writer::end_tag('tr');

    foreach ($records as $r) {
        echo html_writer::start_tag('tr');
        echo "<td>{$r->firstname}</td>";
        echo "<td>{$r->lastname}</td>";
        echo "<td>{$r->codicefiscale}</td>";
        echo "<td>{$r->email}</td>";
        echo "<td>{$r->errormessage}</td>";
        echo "<td>" . date('Y-m-d H:i', $r->timecreated) . "</td>";
        
        // Colonna azioni con icona elimina
        $delete_url = new moodle_url($PAGE->url, ['delete_id' => $r->id, 'sesskey' => sesskey()]);
        $delete_icon = $OUTPUT->pix_icon('t/delete', 'Elimina errore', 'core', ['class' => 'iconsmall']);
        echo '<td><a href="' . $delete_url . '" onclick="return confirm(\'Sei sicuro di voler eliminare questo errore?\');" title="Elimina errore">' . $delete_icon . '</a></td>';
        
        echo html_writer::end_tag('tr');
    }

    echo html_writer::end_tag('table');
} else {
    echo $OUTPUT->notification('Nessun errore registrato.', 'notifymessage');
}

echo $OUTPUT->footer();
