<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/adminlib.php'); // Includi adminlib.php per admin_setting_configtextarea

class admin_setting_configjson extends admin_setting_configtextarea {

    // Scrive il valore nel database
    public function write_setting($data) {
        $data = trim($data);
        
        if ($data === '') {
            return parent::write_setting($data);
        }

        // Tentativo di decodificare il JSON
        $decoded = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return get_string('invalidjson', 'local_zendesk');
        }

        // Serializza l'array e salva nel database
        $serialized = serialize($decoded);
        return parent::write_setting($serialized);
    }

    // Recupera il valore dal database e lo converte in JSON
    public function get_setting() {
        $stored = parent::get_setting();
        
        if (empty($stored)) {
            return '';
        }

        // Deserializza il valore salvato
        $unserialized = unserialize($stored);
        if ($unserialized === false && $stored !== 'b:0;') { // Verifica che la deserializzazione sia riuscita
            return get_string('invalidserialized', 'local_zendesk');
        }

        // Converte l'array in JSON formattato
        return json_encode($unserialized, JSON_PRETTY_PRINT);
    }
}
