<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage(
        'local_zendesk_modal', 
        get_string('pluginname', 'local_zendesk_modal')
    );
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_heading(
        'local_zendesk_modal_hooks', 
        '', 
        get_string('hooks', 'local_zendesk_modal')
    ));

    $settings->add(new admin_setting_configcheckbox(
        'local_zendesk_modal/before_footer', 
        get_string('before_footer', 'local_zendesk_modal'), 
        '', 
        1
    ));

    // pre-body text
    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/prebody_text', 
        get_string('prebody_text', 'local_zendesk_modal'), 
        '', 
        'This is the pre-body text.'
    ));

    // pre-body alt text
    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/prebody_text_alt', 
        get_string('prebody_text_alt', 'local_zendesk_modal'), 
        '', 
        'This is the pre-body alt text.'
    ));

    // after-body text
    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/afterbody_text', 
        get_string('afterbody_text', 'local_zendesk_modal'), 
        '', 
        'This is the after-body text.'
    ));

    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/modalbody_scd', 
        get_string('modalbody_scd', 'local_zendesk_modal'), 
        '', 
        'This is the body of the modal for scd.'
    ));

    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/modalbody_rfd', 
        get_string('modalbody_rfd', 'local_zendesk_modal'), 
        '', 
        'This is the body of the modal for rfd.'
    ));

    // modalbody for guest
    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/modalbody_guest', 
        get_string('modalbody_guest', 'local_zendesk_modal'), 
        '', 
        'This is the body of the modal for guest.'
    ));

    // modalbody for admin
    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/modalbody_admin', 
        get_string('modalbody_admin', 'local_zendesk_modal'), 
        '', 
        'This is the body of the modal for admin.'
    ));

    // default text modalbody
    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/modalbody_default', 
        get_string('modalbody_default', 'local_zendesk_modal'), 
        '', 
        'This is the body of the modal.'
    ));

    // pre-body text standard
    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/prebody_text_std', 
        get_string('prebody_text_std', 'local_zendesk_modal'), 
        '', 
        'This is the pre-body text standard.'
    ));

    // after-body text standard
    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/afterbody_text_std', 
        get_string('afterbody_text_std', 'local_zendesk_modal'), 
        '', 
        'This is the after-body text standard.'
    ));

    // modalbody standard
    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/modalbody_std', 
        get_string('modalbody_std', 'local_zendesk_modal'), 
        '', 
        'This is the body of the modal standard.'
    ));

    // pre-body text standard for guest
    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/prebody_text_std_guest', 
        get_string('prebody_text_std_guest', 'local_zendesk_modal'), 
        '', 
        'This is the pre-body text standard for guest.'
    ));

    // after-body text standard for guest
    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/afterbody_text_std_guest', 
        get_string('afterbody_text_std_guest', 'local_zendesk_modal'), 
        '', 
        'This is the after-body text standard for guest.'
    ));

    // modalbody standard for guest
    $settings->add(new admin_setting_configtextarea(
        'local_zendesk_modal/modalbody_std_guest', 
        get_string('modalbody_std_guest', 'local_zendesk_modal'), 
        '', 
        'This is the body of the modal standard for guest.'
    ));
}