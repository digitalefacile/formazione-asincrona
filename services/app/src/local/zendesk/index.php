<?php
require_once(__DIR__ . '/../../config.php');
require_login();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/zendesk/index.php');
$PAGE->set_title(get_string('pluginname', 'local_zendesk'));
$PAGE->set_heading(get_string('pluginname', 'local_zendesk'));

// Verifica se l'utente è loggato
if (isguestuser() || !isloggedin()) {
    print_error('accessdenied', 'admin');
}

// Includi il file CSS
$PAGE->requires->css('/local/zendesk/assets/styles.css');

// Includi il file JavaScript
$PAGE->requires->js('/local/zendesk/assets/scripts.js');

$output = $PAGE->get_renderer('local_zendesk');
echo $output->header();
echo $output->render(new \local_zendesk\output\ticket_form());
echo $output->footer();
?>
