<?php
namespace local_zendesk\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use templatable;
use renderer_base;

class ticket_form implements renderable, templatable {
    public function export_for_template(renderer_base $output) {
        global $USER, $DB;

        // Ottieni i gruppi dell'utente
        $groups = groups_get_all_groups($USER->id);
        $group_names = array_map(function($group) {
            return $group->name;
        }, $groups);

        // Ottieni il ruolo dell'utente
        $roleid = $DB->get_field('role_assignments', 'roleid', array('userid' => $USER->id));
        $rolename = $DB->get_field('role', 'shortname', array('id' => $roleid));

        return [
            'actionurl' => new \moodle_url('/local/zendesk/actions/submit_ticket.php'),
            'uploadurl' => new \moodle_url('/local/zendesk/actions/upload_file.php'),
            'sesskey' => sesskey(), // Passa la sesskey al template
            'firstname' => $USER->firstname,
            'lastname' => $USER->lastname,
            'username' => $USER->username,
            'email' => $USER->email,
            'groups' => implode(', ', array_merge($group_names, [$rolename])),
            'success_message' => get_string('success', 'local_zendesk'),
        ];
    }
}
