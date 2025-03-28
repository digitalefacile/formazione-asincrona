<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_local_zendesk_modal_install() {
    set_config('modalbody_scd', 'This is the body of the modal for scd.', 'local_zendesk_modal');
    set_config('modalbody_rfd', 'This is the body of the modal for rfd.', 'local_zendesk_modal');
    set_config('modalbody_default', 'This is the body of the modal.', 'local_zendesk_modal');
}
