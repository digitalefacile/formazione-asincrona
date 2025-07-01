<?php
// db/tasks.php

defined('MOODLE_INTERNAL') || die();

// $tasks = [
//     [
//         'classname' => 'local_createuserqueue\task\simple_task',
//         'blocking' => 0,
//         'minute' => '*/5', // Ogni 5 minuti
//         'hour' => '*',
//         'day' => '*',
//         'dayofweek' => '*',
//         'month' => '*',
//         'disabled' => 0,
//     ],
// ];

$tasks = [
    [
        'classname' => 'local_createuserqueue\task\import_users',
        'blocking' => 0,
        'minute' => '*',
        'hour' => '*',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*',
    ],
];
