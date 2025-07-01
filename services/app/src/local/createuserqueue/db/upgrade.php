<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_local_createuserqueue_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    // Upgrade to version 2025062601: aggiunta nuovi campi alla tabella della coda
    if ($oldversion < 2025062601) {

        // Tabella della coda
        $table = new xmldb_table('local_createuserqueue_queue');

        // Campo status
        if (!$dbman->field_exists($table, 'status')) {
            $field = new xmldb_field('status', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, 'queued', 'email');
            $dbman->add_field($table, $field);
        }

        // Campo startedat
        if (!$dbman->field_exists($table, 'startedat')) {
            $field = new xmldb_field('startedat', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'status');
            $dbman->add_field($table, $field);
        }

        // Campo retries
        if (!$dbman->field_exists($table, 'retries')) {
            $field = new xmldb_field('retries', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, 0, 'startedat');
            $dbman->add_field($table, $field);
        }

        // Campo timecreated se manca
        if (!$dbman->field_exists($table, 'timecreated')) {
            $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0, 'retries');
            $dbman->add_field($table, $field);
        }

        // Tabella errori
        $errortable = new xmldb_table('local_createuserqueue_errors');

        if (!$dbman->table_exists($errortable)) {
            $errortable->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $errortable->add_field('queueid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $errortable->add_field('errormessage', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
            $errortable->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0);
            $errortable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $dbman->create_table($errortable);
        }

        // Upgrade completo
        upgrade_plugin_savepoint(true, 2025062601, 'local', 'createuserqueue');
    }

    // Upgrade to version 2025062611: ricreazione completa tabelle
    if ($oldversion < 2025062611) {
        
        // Tabella della coda
        $table = new xmldb_table('local_createuserqueue_queue');
        
        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('firstname', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
            $table->add_field('lastname', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
            $table->add_field('codicefiscale', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
            $table->add_field('email', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
            $table->add_field('status', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, 'queued');
            $table->add_field('startedat', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
            $table->add_field('retries', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            
            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            
            $dbman->create_table($table);
        }
        
        // Tabella errori
        $errortable = new xmldb_table('local_createuserqueue_errors');
        
        if (!$dbman->table_exists($errortable)) {
            $errortable->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $errortable->add_field('queueid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $errortable->add_field('errormessage', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
            $errortable->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            
            $errortable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            
            $dbman->create_table($errortable);
        }
        
        upgrade_plugin_savepoint(true, 2025062611, 'local', 'createuserqueue');
    }

    // Upgrade to version 2025062614: aggiornamento tabella errori con campi diretti
    if ($oldversion < 2025062614) {
        
        $errortable = new xmldb_table('local_createuserqueue_errors');
        
        // Aggiungi i nuovi campi alla tabella errori se non esistono
        if (!$dbman->field_exists($errortable, 'firstname')) {
            $field = new xmldb_field('firstname', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, '', 'id');
            $dbman->add_field($errortable, $field);
        }
        
        if (!$dbman->field_exists($errortable, 'lastname')) {
            $field = new xmldb_field('lastname', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, '', 'firstname');
            $dbman->add_field($errortable, $field);
        }
        
        if (!$dbman->field_exists($errortable, 'codicefiscale')) {
            $field = new xmldb_field('codicefiscale', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, '', 'lastname');
            $dbman->add_field($errortable, $field);
        }
        
        if (!$dbman->field_exists($errortable, 'email')) {
            $field = new xmldb_field('email', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '', 'codicefiscale');
            $dbman->add_field($errortable, $field);
        }
        
        // Rimuovi il campo queueid se esiste (non serve più)
        if ($dbman->field_exists($errortable, 'queueid')) {
            $field = new xmldb_field('queueid');
            $dbman->drop_field($errortable, $field);
        }
        
        upgrade_plugin_savepoint(true, 2025062614, 'local', 'createuserqueue');
    }

    // Upgrade to version 2025062615: rimozione definitiva originalqueueid
    if ($oldversion < 2025062615) {
        
        $errortable = new xmldb_table('local_createuserqueue_errors');
        
        // Rimuovi originalqueueid se esiste
        if ($dbman->field_exists($errortable, 'originalqueueid')) {
            $field = new xmldb_field('originalqueueid');
            $dbman->drop_field($errortable, $field);
        }
        
        upgrade_plugin_savepoint(true, 2025062615, 'local', 'createuserqueue');
    }

    return true;
}
