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
    $roleassignments = $DB->get_records('role_assignments', array('userid' => $userid));
    $roles = [];
    if ($roleassignments) {
        foreach ($roleassignments as $assignment) {
            $rolename = $DB->get_field('role', 'shortname', array('id' => $assignment->roleid));
            if ($rolename && !in_array($rolename, $roles)) { // Controlla se il ruolo non è già presente
                $roles[] = $rolename;
            }
        }
    }

    $prebodyText = get_config('local_zendesk_modal', 'prebody_text');
    $prebodyTextAlt = get_config('local_zendesk_modal', 'prebody_text_alt');
    $afterbodyText = get_config('local_zendesk_modal', 'afterbody_text');
    $modalbodyText = get_config('local_zendesk_modal', 'modalbody_default');
    $modalbodyTextAdmin = get_config('local_zendesk_modal', 'modalbody_admin');
    $modalbodyTextGuest = get_config('local_zendesk_modal', 'modalbody_guest');
    $modalbodyTextSCD = get_config('local_zendesk_modal', 'modalbody_scd');
    $modalbodyTextRFD = get_config('local_zendesk_modal', 'modalbody_rfd');
    $modalbodyTextStd = get_config('local_zendesk_modal', 'modalbody_std');
    $modalbodyTextStdGuest = get_config('local_zendesk_modal', 'modalbody_std_guest');
    $prebodyTextStd = get_config('local_zendesk_modal', 'prebody_text_std');
    $prebodyTextStdGuest = get_config('local_zendesk_modal', 'prebody_text_std_guest');
    $afterbodyTextStd = get_config('local_zendesk_modal', 'afterbody_text_std');
    $afterbodyTextStdGuest = get_config('local_zendesk_modal', 'afterbody_text_std_guest');

    // Default (guest) logic
    $prebodyHTML = "<div class='prebody-text'>" . $prebodyTextAlt . "</div>";
    $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextGuest) . "</div>";
    $afterbodyHTML = "<div class='afterbody-text alt'>&nbsp;</div>";
    $rolename = 'guest';

    // Check for specific roles
    if (in_array('rfd', $roles)) {
        $prebodyHTML = "<div class='prebody-text'>" . $prebodyText . "</div>";
        $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextRFD) . "</div>";
        $afterbodyHTML = "<div class='afterbody-text'>" . $afterbodyText . "</div>";
        $rolename = 'rfd';
    }
    if (in_array('scd', $roles)) {
        $prebodyHTML = "<div class='prebody-text'>" . $prebodyText . "</div>";
        $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextSCD) . "</div>";
        $afterbodyHTML = "<div class='afterbody-text'>" . $afterbodyText . "</div>";
        $rolename = 'scd';
    }
    if (in_array('editingteacher', $roles)) {
        $prebodyHTML = "<div class='prebody-text'>" . $prebodyTextAlt . "</div>";
        $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextAdmin) . "</div>";
        $afterbodyHTML = "<div class='afterbody-text alt'>&nbsp;</div>";
        $rolename = 'editingteacher';
    }
    if (in_array('coursecreator', $roles)) {
        $prebodyHTML = "<div class='prebody-text'>" . $prebodyTextAlt . "</div>";
        $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextAdmin) . "</div>";
        $afterbodyHTML = "<div class='afterbody-text alt'>&nbsp;</div>";
        $rolename = 'coursecreator';
    }
    if (in_array('teacher', $roles)) {
        $prebodyHTML = "<div class='prebody-text'>" . $prebodyTextAlt . "</div>";
        $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextAdmin) . "</div>";
        $afterbodyHTML = "<div class='afterbody-text alt'>&nbsp;</div>";
        $rolename = 'teacher';
    }
    if (in_array('std', $roles)) {
        $prebodyHTML = "<div class='prebody-text'>" . $prebodyTextStd . "</div>";
        $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextStd) . "</div>";
        $afterbodyHTML = "<div class='afterbody-text'>" . $afterbodyTextStd . "</div>";
        $rolename = 'std';
    }

    // if roles empty AND page url contains home-std or privacy-std
    if (empty($roles) && (strpos($PAGE->url->out(), 'home-std') !== false || strpos($PAGE->url->out(), 'privacy-std') !== false)) {
        $prebodyHTML = "<div class='prebody-text'>" . $prebodyTextStdGuest . "</div>";
        $modalbodyHTML = "<div class='modal-inner-body'>" . nl2br($modalbodyTextStdGuest) . "</div>";
        $afterbodyHTML = "<div class='afterbody-text'>" . $afterbodyTextStdGuest . "</div>";
        $rolename = 'guest';
    }


    $totalbody = $prebodyHTML . $modalbodyHTML . $afterbodyHTML;

    echo html_writer::tag('div', $totalbody, array(
        'id' => 'zendesk-modal-body', 
        'style' => 'display:none;',
        'rolename' => $rolename,
    ));

}
