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

// Aggiungi gestione eliminazione singola riga
if ($delete_userid = optional_param('delete_userid', 0, PARAM_INT)) {
    if (confirm_sesskey() && $DB->record_exists('user', ['id' => $delete_userid])) {
        delete_user($DB->get_record('user', ['id' => $delete_userid]));
        $DB->delete_records('local_createuserqueue_log', ['userid' => $delete_userid]);
        redirect($PAGE->url, "Utente eliminato con successo.", 2, \core\output\notification::NOTIFY_SUCCESS);
    }
}

// Aggiungi filtro di ricerca
$search_firstname = optional_param('search_firstname', '', PARAM_TEXT);
$search_lastname = optional_param('search_lastname', '', PARAM_TEXT);
$search_email = optional_param('search_email', '', PARAM_TEXT);
$search_username = optional_param('search_username', '', PARAM_TEXT);

// Costruisci i criteri di ricerca
$search_conditions = [];
if (!empty($search_firstname)) {
    $search_conditions[] = $DB->sql_like('firstname', ':firstname', false);
}
if (!empty($search_lastname)) {
    $search_conditions[] = $DB->sql_like('lastname', ':lastname', false);
}
if (!empty($search_email)) {
    $search_conditions[] = $DB->sql_like('email', ':email', false);
}
if (!empty($search_username)) {
    $search_conditions[] = $DB->sql_like('username', ':username', false);
}

$search_params = [
    'firstname' => "%$search_firstname%",
    'lastname' => "%$search_lastname%",
    'email' => "%$search_email%",
    'username' => "%$search_username%",
];

$search_sql = !empty($search_conditions) ? implode(' AND ', $search_conditions) : '1=1';

// Ottieni il numero totale di record con filtro
$total_records = $DB->count_records_select('local_createuserqueue_log', $search_sql, $search_params);

// Definisci il numero di record per pagina
$records_per_page = 20;

// Aggiorna il numero di record per pagina in base alla selezione
$records_per_page = optional_param('records_per_page', 20, PARAM_INT);
if (!in_array($records_per_page, [20, 50, 100])) {
    $records_per_page = 20;
}

// Calcola il numero totale di pagine
$total_pages = ceil($total_records / $records_per_page);

// Ottieni la pagina corrente dai parametri URL (default: 1)
$current_page = optional_param('page', 1, PARAM_INT);
if ($current_page < 1) {
    $current_page = 1;
} elseif ($current_page > $total_pages) {
    $current_page = $total_pages;
}

// Calcola l'offset per la query
$offset = ($current_page - 1) * $records_per_page;

// Ottieni i record per la pagina corrente con filtro
$records = $DB->get_records_select('local_createuserqueue_log', $search_sql, $search_params, 'timecreated DESC', '*', $offset, $records_per_page);

echo $OUTPUT->header();

// Link di navigazione
echo '<div style="margin-bottom: 20px;">';
echo '<a href="view_queue.php" class="btn btn-secondary">← Visualizza Coda</a> ';
echo '<a href="view_errors.php" class="btn btn-secondary">Visualizza Errori</a>';
echo '</div>';

// Mostra le statistiche in alto
if ($total_records > 0) {
    echo '<div style="margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">';
    echo '<h4>📊 Statistiche</h4>';
    echo '<p><strong>Totale utenti importati:</strong> ' . $DB->count_records('local_createuserqueue_log') . '</p>';
    if (!empty($search_conditions)) {
        echo '<p><strong>Utenti trovati nella ricerca:</strong> ' . $total_records . '</p>';
    }
    echo '<p><strong>Scopo:</strong> Questa tabella ti permette di visualizzare e gestire facilmente gli studenti importati dal sistema Moodle.</p>';
    echo '</div>';
}

// Mostra il modulo di ricerca
echo '<form method="get" action="' . $PAGE->url . '" style="margin-bottom: 15px;">';
echo '<div class="row">';
echo '<div class="col">';
echo '<input type="text" name="search_firstname" class="form-control" placeholder="Nome" value="' . s($search_firstname) . '">';
echo '</div>';
echo '<div class="col">';
echo '<input type="text" name="search_lastname" class="form-control" placeholder="Cognome" value="' . s($search_lastname) . '">';
echo '</div>';
echo '<div class="col">';
echo '<input type="text" name="search_email" class="form-control" placeholder="Email" value="' . s($search_email) . '">';
echo '</div>';
echo '<div class="col">';
echo '<input type="text" name="search_username" class="form-control" placeholder="Username" value="' . s($search_username) . '">';
echo '</div>';
echo '</div>';
echo '<div class="row mt-2">';
echo '<div class="col-2">';
echo '<button type="submit" class="btn btn-primary btn-lg w-100">Cerca</button>';
echo '</div>';
echo '<div class="col-2">';
echo '<a href="' . $PAGE->url . '" class="btn btn-secondary btn-lg w-100">Reset Ricerca</a>';
echo '</div>';
echo '</div>';
echo '</form>';

// Mostra la paginazione in alto con una select per il numero di record per pagina
if ($total_pages > 1 || $records_per_page != 20 || !empty($search_conditions)) {
    echo '<div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">';

    // Select per il numero di record per pagina con stile Bootstrap
    echo '<form method="get" action="' . $PAGE->url . '" style="margin: 0;">';
    echo '<input type="hidden" name="search_firstname" value="' . s($search_firstname) . '">';
    echo '<input type="hidden" name="search_lastname" value="' . s($search_lastname) . '">';
    echo '<input type="hidden" name="search_email" value="' . s($search_email) . '">';
    echo '<input type="hidden" name="search_username" value="' . s($search_username) . '">';
    echo '<label for="records_per_page" class="form-label" style="margin-right: 10px;">Record per pagina:</label>';
    echo '<select id="records_per_page" name="records_per_page" class="form-select" style="width: auto; display: inline-block;" onchange="this.form.submit()">';
    foreach ([20, 50, 100] as $option) {
        $selected = ($records_per_page == $option) ? 'selected' : '';
        echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
    }
    echo '</select>';
    echo '</form>';

    // Paginazione
    echo '<nav aria-label="Paginazione">';
    echo '<ul class="pagination" style="margin: 0;">';

    // Link alla pagina precedente
    if ($current_page > 1) {
        $prev_page = $current_page - 1;
        echo '<li class="page-item"><a class="page-link" href="' . $PAGE->url . '?page=' . $prev_page . '&records_per_page=' . $records_per_page . '&search_firstname=' . s($search_firstname) . '&search_lastname=' . s($search_lastname) . '&search_email=' . s($search_email) . '&search_username=' . s($search_username) . '">Precedente</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Precedente</span></li>';
    }

    // Link alle pagine
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="' . $PAGE->url . '?page=' . $i . '&records_per_page=' . $records_per_page . '&search_firstname=' . s($search_firstname) . '&search_lastname=' . s($search_lastname) . '&search_email=' . s($search_email) . '&search_username=' . s($search_username) . '">' . $i . '</a></li>';
        }
    }

    // Link alla pagina successiva
    if ($current_page < $total_pages) {
        $next_page = $current_page + 1;
        echo '<li class="page-item"><a class="page-link" href="' . $PAGE->url . '?page=' . $next_page . '&records_per_page=' . $records_per_page . '&search_firstname=' . s($search_firstname) . '&search_lastname=' . s($search_lastname) . '&search_email=' . s($search_email) . '&search_username=' . s($search_username) . '">Successivo</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Successivo</span></li>';
    }

    echo '</ul>';
    echo '</nav>';
    echo '</div>';
}

// Aggiorna il numero di record per pagina in base alla selezione
$records_per_page = optional_param('records_per_page', $records_per_page, PARAM_INT);
if (!in_array($records_per_page, [20, 50, 100])) {
    $records_per_page = 20;
}

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
    echo '<th>ID Utente</th><th>Username</th><th>Nome</th><th>Cognome</th><th>Email</th><th>Importato il</th><th>Azioni</th>';
    echo html_writer::end_tag('tr');

    // Modifica la tabella per invertire i pulsanti e rendere il pulsante 'Impostazioni' più piccolo
    foreach ($records as $r) {
        echo html_writer::start_tag('tr');
        echo "<td>{$r->userid}</td>";
        echo "<td>{$r->username}</td>";
        echo "<td>{$r->firstname}</td>";
        echo "<td>{$r->lastname}</td>";
        echo "<td>{$r->email}</td>";
        echo "<td>" . date('Y-m-d H:i', $r->timecreated) . "</td>";
        echo '<td>';

        // Aggiungi il pulsante per accedere alle impostazioni utente
        $user_settings_url = new moodle_url('/user/editadvanced.php', ['id' => $r->userid]);
        echo '<a href="' . $user_settings_url . '" class="btn btn-primary btn-sm" style="margin-right: 5px; padding: .25rem .5rem; font-size: .8203125rem; line-height: 1.5;">Impostazioni</a>';

        // Aggiungi il pulsante di eliminazione
        echo '<form method="post" action="' . $PAGE->url . '" style="display:inline;">';
        echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
        echo '<input type="hidden" name="delete_userid" value="' . $r->userid . '">';
        echo '<input type="submit" value="Elimina UTENTE" class="btn btn-danger btn-sm" onclick="return confirm(\'Sei sicuro di voler eliminare questo utente?\');">';
        echo '</form>';

        echo '</td>';
        echo html_writer::end_tag('tr');
    }

    echo html_writer::end_tag('table');
    
} else {
    echo $OUTPUT->notification('Nessun utente importato registrato.', 'notifymessage');
}

echo $OUTPUT->footer();
