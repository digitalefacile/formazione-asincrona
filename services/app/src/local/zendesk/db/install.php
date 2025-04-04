<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_zendesk_install() {
    set_config('api_token', '', 'local_zendesk');  // Token API Zendesk
    set_config('zendesk_email', '', 'local_zendesk'); // Email associata a Zendesk
    set_config('form_id', '', 'local_zendesk'); // ID del form
    set_config('subdomain', '', 'local_zendesk'); // Subdomain di Zendesk
    set_config('user_group_field_id', '', 'local_zendesk'); // ID del campo per il gruppo utente
    set_config('custom_fields', '', 'local_zendesk'); // Campi personalizzati
    set_config('username_field_id', '', 'local_zendesk'); // ID del campo per il nome utente
    set_config('duplicate_upload', 0, 'local_zendesk'); // Abilita il duplice upload degli allegati
}
