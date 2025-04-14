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
    if (!$rolename) {
        $rolename = 'guest';
    }

    $prebodyText = get_config('local_zendesk_modal', 'prebody_text');
    $prebodyTextAlt = get_config('local_zendesk_modal', 'prebody_text_alt');
    $afterbodyText = get_config('local_zendesk_modal', 'afterbody_text');
    $modalbodyText = get_config('local_zendesk_modal', 'modalbody_default');
    $modalbodyTextAdmin = get_config('local_zendesk_modal', 'modalbody_admin');
    $modalbodyTextGuest = get_config('local_zendesk_modal', 'modalbody_guest');
    $modalbodyTextSCD = get_config('local_zendesk_modal', 'modalbody_scd');
    $modalbodyTextRFD = get_config('local_zendesk_modal', 'modalbody_rfd');

    switch ($rolename) {
        case 'rfd':
            $prebodyHTML = "<div class='prebody-text'>" . $prebodyText . "</div>";
            $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextRFD) . "</div>";
            $afterbodyHTML = "<div class='afterbody-text'>" . $afterbodyText . "</div>";
            break;
        case 'scd':
            $prebodyHTML = "<div class='prebody-text'>" . $prebodyText . "</div>";
            $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextSCD) . "</div>";
            $afterbodyHTML = "<div class='afterbody-text'>" . $afterbodyText . "</div>";
            break;
        case 'guest':
            $prebodyHTML = "<div class='prebody-text'>" . $prebodyTextAlt . "</div>";
            $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextGuest) . "</div>";
            $afterbodyHTML = "<div class='afterbody-text'>&nbsp;</div>";
            break;
        case 'editingteacher':
            $prebodyHTML = "<div class='prebody-text'>" . $prebodyTextAlt . "</div>";
            $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextAdmin) . "</div>";
            $afterbodyHTML = "<div class='afterbody-text alt'>&nbsp;</div>";
            break;
        case 'coursecreator':
            $prebodyHTML = "<div class='prebody-text'>" . $prebodyTextAlt . "</div>";
            $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextAdmin) . "</div>";
            $afterbodyHTML = "<div class='afterbody-text alt'>&nbsp;</div>";
            break;
        default:
            // default is guest
            $prebodyHTML = "<div class='prebody-text'>" . $prebodyTextAlt . "</div>";
            $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextGuest) . "</div>";
            $afterbodyHTML = "<div class='afterbody-text alt'>&nbsp;</div>";
            break;
    }
    // $prebody = "<div class='prebody-text'>" . get_config('local_zendesk_modal', 'prebody_text') . "</div>";
    // $afterbody = "<div class='afterbody-text'>" . get_config('local_zendesk_modal', 'afterbody_text') . "</div>";
    // $modalbody = "<div class='modal-inner-body'>" . nl2br($modalbody) . "</div>";
    // $totalbody = $prebody . $modalbody . $afterbody;
    $totalbody = $prebodyHTML . $modalbodyHTML . $afterbodyHTML;

    echo html_writer::tag('div', $totalbody, array(
        'id' => 'zendesk-modal-body', 
        'style' => 'display:none;',
        'rolename' => $rolename,
    ));

}
