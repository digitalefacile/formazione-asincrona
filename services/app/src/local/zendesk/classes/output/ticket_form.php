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

        // var $rolename_label, if rolename is 'rfd' then $rolename_label = 'facilitatore', if rolename is 'scd' then $rolename_label = 'volontario', if nothing else is an empty string
        $rolename_label = '';
        switch ($rolename) {
            case 'rfd':
                $rolename_label = 'facilitatore';
                break;
            case 'scd':
                $rolename_label = 'volontario';
                break;
            default:
                $rolename_label = '';
                break;
        }

        // var username str replace tinit- with "", and strtoupper
        $username = str_replace('tinit-', '', $USER->username);
        $username = strtoupper($username);

        // get array of custom fields
        // $custom_fields = unserialize(get_config('local_zendesk', 'custom_fields'));

        return [
            'actionurl' => new \moodle_url('/local/zendesk/actions/submit_ticket.php'),
            'uploadurl' => new \moodle_url('/local/zendesk/actions/upload_file.php'),
            'updatesubjecturl' => new \moodle_url('/local/zendesk/actions/update_ticket_subject.php'),
            'sesskey' => sesskey(), // Passa la sesskey al template
            'firstname' => $USER->firstname,
            'lastname' => $USER->lastname,
            'username' => $username,
            'email' => $USER->email,
            // 'groups' => implode(', ', array_merge($group_names, [$rolename])),
            'groups' => $rolename_label,
        ];
    }
}
