<?php

defined('MOODLE_INTERNAL') || die();

function local_zendesk_modal_extend_navigation(global_navigation $nav) {
    $node = $nav->add(get_string('pluginname', 'local_zendesk_modal'), new moodle_url('/local/zendesk_modal/index.php'));
    $node->showinflatnavigation = true;
}

function local_zendesk_modal_before_standard_html_head() {
    global $PAGE;

    $before_footer = (bool) get_config('local_zendesk_modal', 'before_footer');
    if ($before_footer) {
        $PAGE->requires->css('/local/zendesk_modal/assets/zendesk-modal.css');
    }
}

function local_zendesk_modal_before_footer() {
    global $PAGE, $USER, $DB;

    $before_footer = (bool) get_config('local_zendesk_modal', 'before_footer');
    if ($before_footer) {
        $PAGE->requires->js('/local/zendesk_modal/assets/zendesk-modal.js');
    }
    
    $userid = $USER->id;
    $roleid = $DB->get_field('role_assignments', 'roleid', array('userid' => $userid));
    $rolename = $DB->get_field('role', 'shortname', array('id' => $roleid));

    switch ($rolename) {
        case 'rfd':
            $modalbody = get_config('local_zendesk_modal', 'modalbody_rfd');
            break;
        case 'scd':
            $modalbody = get_config('local_zendesk_modal', 'modalbody_scd');
            break;
        default:
            $modalbody = get_config('local_zendesk_modal', 'modalbody_default');
            break;
    }
    $prebody = "<div class='prebody-text'>" . get_config('local_zendesk_modal', 'prebody_text') . "</div>";
    $afterbody = "<div class='afterbody-text'>" . get_config('local_zendesk_modal', 'afterbody_text') . "</div>";
    $modalbody = "<div class='modal-inner-body'>" . nl2br($modalbody) . "</div>";
    $totalbody = $prebody . $modalbody . $afterbody;

    echo html_writer::tag('div', $totalbody, array('id' => 'zendesk-modal-body', 'style' => 'display:none;'));

}
