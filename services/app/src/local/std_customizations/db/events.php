<?php

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\core\event\before_footer',
        'callback' => 'local_std_customizations_before_footer',
    ],
];
