<?php
require('../../config.php');
require_login();
require_capability('local/createuserqueue:view', context_system::instance());

$PAGE->set_url(new moodle_url('/local/createuserqueue/view_imported.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Utenti importati (Debug)');
$PAGE->set_heading('Utenti importati (Debug)');

// Gestisci eliminazione di tutti gli utenti importati
if (optional_param('delete_all_imported', false, PARAM_BOOL) && confirm_sesskey()) {
    // Prima ottieni tutti gli userid da eliminare
    $imported_users = $DB->get_records('local_createuserqueue_log', null, '', 'userid');
    $userids = array_keys($imported_users);
    
    $deleted_count = 0;
    
    if (!empty($userids)) {
        // Elimina gli utenti dalla tabella user
        foreach ($userids as $userid) {
            if ($DB->record_exists('user', ['id' => $userid])) {
                delete_user($DB->get_record('user', ['id' => $userid]));
                $deleted_count++;
            }
        }
        
        // Pulisci la tabella degli importati
        $DB->delete_records('local_createuserqueue_log');
    }
    
    redirect($PAGE->url, "Eliminati {$deleted_count} utenti importati e pulita la tabella di debug.", 2, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();

// Link di navigazione
echo '<div style="margin-bottom: 20px;">';
echo '<a href="view_queue.php" class="btn btn-secondary">← Visualizza Coda</a> ';
echo '<a href="view_errors.php" class="btn btn-secondary">Visualizza Errori</a>';
echo '</div>';

// Ottieni utenti importati
$records = $DB->get_records('local_createuserqueue_log', null, 'timecreated DESC');

if ($records) {
    // Pulsante per eliminare tutti
    echo '<div style="margin-bottom: 15px;">';
    echo '<form method="post" action="' . $PAGE->url . '" style="display: inline;">';
    echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
    echo '<input type="submit" name="delete_all_imported" value="🗑️ Elimina TUTTI gli utenti importati" class="btn btn-danger" onclick="return confirm(\'ATTENZIONE: Questa azione eliminerà PERMANENTEMENTE tutti gli utenti importati dal sistema Moodle e pulirà la tabella di debug.\\n\\nSei ASSOLUTAMENTE sicuro di voler procedere?\');">';
    echo '</form>';
    echo '<p style="color: #dc3545; font-weight: bold; margin: 10px 0;">⚠️ Il pulsante sopra elimina definitivamente gli utenti da Moodle, non solo dalla tabella di debug!</p>';
    echo '</div>';
    
    echo html_writer::start_tag('table', ['class' => 'generaltable']);
    echo html_writer::start_tag('tr');
    echo '<th>ID Utente</th><th>Username</th><th>Nome</th><th>Cognome</th><th>Email</th><th>Importato il</th>';
    echo html_writer::end_tag('tr');

    foreach ($records as $r) {
        echo html_writer::start_tag('tr');
        echo "<td>{$r->userid}</td>";
        echo "<td>{$r->username}</td>";
        echo "<td>{$r->firstname}</td>";
        echo "<td>{$r->lastname}</td>";
        echo "<td>{$r->email}</td>";
        echo "<td>" . date('Y-m-d H:i', $r->timecreated) . "</td>";
        echo html_writer::end_tag('tr');
    }

    echo html_writer::end_tag('table');
    
    echo '<div style="margin-top: 15px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">';
    echo '<h4>📊 Statistiche</h4>';
    echo '<p><strong>Totale utenti importati:</strong> ' . count($records) . '</p>';
    echo '<p><strong>Scopo:</strong> Questa tabella è utilizzata per il debug e testing del plugin. Ti permette di eliminare facilmente tutti gli utenti importati durante i test.</p>';
    echo '</div>';
    
} else {
    echo $OUTPUT->notification('Nessun utente importato registrato.', 'notifymessage');
}

echo $OUTPUT->footer();
