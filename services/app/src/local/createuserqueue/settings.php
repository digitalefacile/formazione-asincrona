<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_category('local_createuserqueue', get_string('pluginname', 'local_createuserqueue')));

    // Aggiunta delle pagine admin per coda ed errori
    $ADMIN->add('local_createuserqueue', new admin_externalpage(
        'local_createuserqueue_queue',
        get_string('queueview', 'local_createuserqueue'),
        new moodle_url('/local/createuserqueue/view_queue.php'),
        'local/createuserqueue:view'
    ));

    $ADMIN->add('local_createuserqueue', new admin_externalpage(
        'local_createuserqueue_errors',
        get_string('errorsview', 'local_createuserqueue'),
        new moodle_url('/local/createuserqueue/view_errors.php'),
        'local/createuserqueue:view'
    ));

    $ADMIN->add('local_createuserqueue', new admin_externalpage(
        'local_createuserqueue_imported',
        'Utenti importati (Debug)',
        new moodle_url('/local/createuserqueue/view_imported.php'),
        'local/createuserqueue:view'
    ));

    // Aggiunta della sezione impostazioni
    $settings = new admin_settingpage('local_createuserqueue_settings', get_string('settings', 'local_createuserqueue'));

    $settings->add(new admin_setting_configtext(
        'local_createuserqueue/croninterval',
        get_string('croninterval', 'local_createuserqueue'),
        get_string('croninterval_desc', 'local_createuserqueue'),
        1,
        PARAM_INT,
        1
    ));

    $settings->add(new admin_setting_configtext(
        'local_createuserqueue/batchsize',
        get_string('batchsize', 'local_createuserqueue'),
        get_string('batchsize_desc', 'local_createuserqueue'),
        10,
        PARAM_INT,
        1
    ));

    $ADMIN->add('local_createuserqueue', $settings);
}
