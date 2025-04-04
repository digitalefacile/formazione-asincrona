<?php
defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/local/zendesk/lib.php'); // Includi la classe personalizzata

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_zendesk', get_string('pluginname', 'local_zendesk'));

    $settings->add(new admin_setting_configtext(
        'local_zendesk/zendesk_email',
        get_string('zendesk_email', 'local_zendesk'),
        get_string('zendesk_email_desc', 'local_zendesk'),
        '',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configtext(
        'local_zendesk/api_token',
        get_string('api_token', 'local_zendesk'),
        get_string('api_token_desc', 'local_zendesk'),
        '',
        PARAM_RAW
    ));

    $settings->add(new admin_setting_configtext(
        'local_zendesk/form_id',
        get_string('form_id', 'local_zendesk'),
        get_string('form_id_desc', 'local_zendesk'),
        '',
        PARAM_INT,
        30
    ));

    $settings->add(new admin_setting_configtext(
        'local_zendesk/subdomain',
        get_string('subdomain', 'local_zendesk'),
        get_string('subdomain_desc', 'local_zendesk'),
        '',
        PARAM_TEXT
    ));

    // configtext field id for user group
    $settings->add(new admin_setting_configtext(
        'local_zendesk/user_group_field_id',
        get_string('user_group_field_id', 'local_zendesk'),
        get_string('user_group_field_id_desc', 'local_zendesk'),
        '',
        PARAM_INT,
        30
    ));

    $settings->add(new admin_setting_configtext(
        'local_zendesk/username_field_id',
        get_string('username_field_id', 'local_zendesk'),
        get_string('username_field_id_desc', 'local_zendesk'),
        '',
        PARAM_INT,
        30
    ));

    // new int field for area_tematica
    $settings->add(new admin_setting_configtext(
        'local_zendesk/area_tematica_field_id',
        get_string('area_tematica_field_id', 'local_zendesk'),
        get_string('area_tematica_field_id_desc', 'local_zendesk'),
        '',
        PARAM_INT,
        30
    ));

    // new int field for altra_area_tematica
    $settings->add(new admin_setting_configtext(
        'local_zendesk/altra_area_tematica_field_id',
        get_string('altra_area_tematica_field_id', 'local_zendesk'),
        get_string('altra_area_tematica_field_id_desc', 'local_zendesk'),
        '',
        PARAM_INT,
        30
    ));

    // // Aggiungi un campo di testo per i campi dinamici in formato JSON
    // $settings->add(new admin_setting_configjson(
    //     'local_zendesk/custom_fields',
    //     get_string('custom_fields', 'local_zendesk'),
    //     get_string('custom_fields_desc', 'local_zendesk'),
    //     '',
    //     PARAM_RAW
    // ));

    $settings->add(new admin_setting_configcheckbox(
        'local_zendesk/duplicate_upload',
        get_string('duplicate_upload', 'local_zendesk'),
        get_string('duplicate_upload_desc', 'local_zendesk'),
        0
    ));

    $ADMIN->add('localplugins', $settings);
}
?>
