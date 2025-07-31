<?php
namespace local_createuserqueue\task;

defined('MOODLE_INTERNAL') || die();

class simple_task extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('simpletaskname', 'local_createuserqueue');
    }

    public function execute() {
        // Qui metti il codice che vuoi venga eseguito
        // Per ora lasciamo solo un log semplice
        mtrace('Esecuzione semplice task di createuserqueue');
    }
}
