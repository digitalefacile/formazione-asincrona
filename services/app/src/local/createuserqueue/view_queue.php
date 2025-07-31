<?php
require('../../config.php');
require_login();
require_capability('local/createuserqueue:view', context_system::instance());

$PAGE->set_url(new moodle_url('/local/createuserqueue/view_queue.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Coda utenti');
$PAGE->set_heading('Coda utenti');

echo $OUTPUT->header();

// Ottieni il numero totale di record
$total_records = $DB->count_records('local_createuserqueue_queue');

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

// Ottieni i record per la pagina corrente
$records = $DB->get_records('local_createuserqueue_queue', null, 'timecreated DESC', '*', $offset, $records_per_page);

// Mostra le statistiche in alto
if ($total_records > 0) {
    echo '<div style="margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">';
    echo '<h4>📊 Statistiche</h4>';
    echo '<p><strong>Totale utenti in coda:</strong> ' . $total_records . '</p>';
    echo '<p><strong>Scopo:</strong> Questa tabella ti permette di visualizzare facilmente gli utenti in coda per l\'importazione.</p>';
    echo '</div>';
}

// Mostra la paginazione in alto con una select per il numero di record per pagina
if ($total_pages > 1 || $records_per_page != 20) {
    echo '<div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">';

    // Select per il numero di record per pagina con stile Bootstrap
    echo '<form method="get" action="' . $PAGE->url . '" style="margin: 0;">';
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
        echo '<li class="page-item"><a class="page-link" href="' . $PAGE->url . '?page=' . $prev_page . '&records_per_page=' . $records_per_page . '">Precedente</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Precedente</span></li>';
    }

    // Link alle pagine
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="' . $PAGE->url . '?page=' . $i . '&records_per_page=' . $records_per_page . '">' . $i . '</a></li>';
        }
    }

    // Link alla pagina successiva
    if ($current_page < $total_pages) {
        $next_page = $current_page + 1;
        echo '<li class="page-item"><a class="page-link" href="' . $PAGE->url . '?page=' . $next_page . '&records_per_page=' . $records_per_page . '">Successivo</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Successivo</span></li>';
    }

    echo '</ul>';
    echo '</nav>';
    echo '</div>';
}

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
