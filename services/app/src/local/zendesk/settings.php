<?php
defined('MOODLE_INTERNAL') || die();

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

    // Aggiungi un campo di testo per i campi dinamici in formato JSON
    $settings->add(new admin_setting_configtextarea(
        'local_zendesk/custom_fields',
        get_string('custom_fields', 'local_zendesk'),
        get_string('custom_fields_desc', 'local_zendesk'),
        '',
        PARAM_RAW
    ));

    $ADMIN->add('localplugins', $settings);
}
?>
