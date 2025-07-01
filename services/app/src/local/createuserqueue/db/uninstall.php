<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_createuserqueue_uninstall() {
    global $DB;
    
    $dbman = $DB->get_manager();
    
    // Elimina tabelle se esistono
    $tables = ['local_createuserqueue_queue', 'local_createuserqueue_errors'];
    
    foreach ($tables as $tablename) {
        $table = new xmldb_table($tablename);
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }
    }
    
    return true;
}
