<?php
require_once(__DIR__ . '/../../config.php');
require_login();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/zendesk/index.php');
$PAGE->set_title(get_string('page_title', 'local_zendesk'));
$PAGE->set_heading(get_string('page_title', 'local_zendesk'));

// Verifica se l'utente è loggato
if (isguestuser() || !isloggedin()) {
    print_error('accessdenied', 'admin');
}

// Includi il file CSS
$PAGE->requires->css('/local/zendesk/assets/styles.css');

// Includi il file JavaScript
$PAGE->requires->js('/local/zendesk/assets/scripts.js');

$attobuttons = 'style1 = title, bold, italic, underline, strike, subscript, superscript' . PHP_EOL .
               'list = unorderedlist, orderedlist' . PHP_EOL .
            //    'indent = indent, outdent' . PHP_EOL .
            //    'align = alignleft, aligncenter, alignright, alignjustify' . PHP_EOL .
            //    'insert = link, image, media, equation, charmap, table' . PHP_EOL .
            //    'tools = code, html, clear' . PHP_EOL .
               'other = undo, redo';

$editor = editors_get_preferred_editor(FORMAT_HTML);
$editor->use_editor('description', [
    'context' => $context,
    'autosave' => false,
    'atto:toolbar' => $attobuttons,
]);

$output = $PAGE->get_renderer('local_zendesk');
echo $output->header();
echo $output->render(new \local_zendesk\output\ticket_form());
echo $output->footer();
?>
