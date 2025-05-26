<?php

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\core\event\before_standard_html_head',
        'callback' => 'local_zendesk_modal_before_standard_html_head',
    ],
    [
        'eventname' => '\core\event\before_footer',
        'callback' => 'local_zendesk_modal_before_footer',
    ],
];
