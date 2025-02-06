<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_zendesk_install() {
    set_config('api_token', '', 'local_zendesk');  // Token API Zendesk
    set_config('zendesk_email', '', 'local_zendesk'); // Email associata a Zendesk
    set_config('form_id', '', 'local_zendesk'); // ID del form
    set_config('subdomain', '', 'local_zendesk'); // Subdomain di Zendesk
}
