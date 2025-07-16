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

// Aggiungi filtro di ricerca
$search_firstname = optional_param('search_firstname', '', PARAM_TEXT);
$search_lastname = optional_param('search_lastname', '', PARAM_TEXT);
$search_email = optional_param('search_email', '', PARAM_TEXT);
$search_codicefiscale = optional_param('search_codicefiscale', '', PARAM_TEXT);

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
if (!empty($search_codicefiscale)) {
    $search_conditions[] = $DB->sql_like('codicefiscale', ':codicefiscale', false);
}

$search_params = [
    'firstname' => "%$search_firstname%",
    'lastname' => "%$search_lastname%",
    'email' => "%$search_email%",
    'codicefiscale' => "%$search_codicefiscale%",
];

$search_sql = !empty($search_conditions) ? implode(' AND ', $search_conditions) : '1=1';

// Ottieni il numero totale di record con filtro
$total_records = $DB->count_records_select('local_createuserqueue_errors', $search_sql, $search_params);

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
$records = $DB->get_records_select('local_createuserqueue_errors', $search_sql, $search_params, 'timecreated DESC', '*', $offset, $records_per_page);

echo $OUTPUT->header();

// Mostra le statistiche in alto
if ($total_records > 0) {
    echo '<div style="margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">';
    echo '<h4>📊 Statistiche</h4>';
    echo '<p><strong>Totale errori registrati:</strong> ' . $total_records . '</p>';
    echo '<p><strong>Scopo:</strong> Questa tabella ti permette di visualizzare e gestire facilmente gli errori di importazione degli utenti.</p>';
    echo '</div>';
}

// Mostra la paginazione in alto con una select per il numero di record per pagina
if ($total_pages > 1 || $records_per_page != 20 || !empty($search_conditions)) {
    echo '<div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">';

    // Select per il numero di record per pagina con stile Bootstrap
    echo '<form method="get" action="' . $PAGE->url . '" style="margin: 0;">';
    echo '<input type="hidden" name="search_firstname" value="' . s($search_firstname) . '">';
    echo '<input type="hidden" name="search_lastname" value="' . s($search_lastname) . '">';
    echo '<input type="hidden" name="search_email" value="' . s($search_email) . '">';
    echo '<input type="hidden" name="search_codicefiscale" value="' . s($search_codicefiscale) . '">';
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
        echo '<li class="page-item"><a class="page-link" href="' . $PAGE->url . '?page=' . $prev_page . '&records_per_page=' . $records_per_page . '&search_firstname=' . s($search_firstname) . '&search_lastname=' . s($search_lastname) . '&search_email=' . s($search_email) . '&search_codicefiscale=' . s($search_codicefiscale) . '">Precedente</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Precedente</span></li>';
    }

    // Link alle pagine
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="' . $PAGE->url . '?page=' . $i . '&records_per_page=' . $records_per_page . '&search_firstname=' . s($search_firstname) . '&search_lastname=' . s($search_lastname) . '&search_email=' . s($search_email) . '&search_codicefiscale=' . s($search_codicefiscale) . '">' . $i . '</a></li>';
        }
    }

    // Link alla pagina successiva
    if ($current_page < $total_pages) {
        $next_page = $current_page + 1;
        echo '<li class="page-item"><a class="page-link" href="' . $PAGE->url . '?page=' . $next_page . '&records_per_page=' . $records_per_page . '&search_firstname=' . s($search_firstname) . '&search_lastname=' . s($search_lastname) . '&search_email=' . s($search_email) . '&search_codicefiscale=' . s($search_codicefiscale) . '">Successivo</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Successivo</span></li>';
    }

    echo '</ul>';
    echo '</nav>';
    echo '</div>';
}

// Modifica il modulo di ricerca per mettere i pulsanti a capo, renderli più larghi e rinominare il pulsante di reset
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
echo '<input type="text" name="search_codicefiscale" class="form-control" placeholder="Codice Fiscale" value="' . s($search_codicefiscale) . '">';
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
        
        // Colonna azioni con pulsante rosso
        echo '<td><form method="post" action="' . $PAGE->url . '" style="display:inline;">';
        echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
        echo '<input type="hidden" name="delete_id" value="' . $r->id . '">';
        echo '<input type="submit" value="Elimina Errore" class="btn btn-danger btn-sm" onclick="return confirm(\'Sei sicuro di voler eliminare questo errore?\');">';
        echo '</form></td>';
        
        echo html_writer::end_tag('tr');
    }

    echo html_writer::end_tag('table');
} else {
    echo $OUTPUT->notification('Nessun errore da visualizzare.', 'notifymessage');
}

echo $OUTPUT->footer();
