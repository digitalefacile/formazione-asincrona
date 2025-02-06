<?php
require_once(__DIR__ . '/../../config.php');
require_login();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/zendesk/index.php');
$PAGE->set_title(get_string('pluginname', 'local_zendesk'));
$PAGE->set_heading(get_string('pluginname', 'local_zendesk'));

// Includi il file JavaScript
$PAGE->requires->js('/local/zendesk/submit_ticket.js');

$output = $PAGE->get_renderer('local_zendesk');
echo $output->header();
echo $output->render(new \local_zendesk\output\ticket_form());
echo $output->footer();
?>
