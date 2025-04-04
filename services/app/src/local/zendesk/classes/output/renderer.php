<?php
namespace local_zendesk\output;

defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;

class renderer extends plugin_renderer_base {
    public function render_ticket_form(ticket_form $form) {
        return $this->render_from_template('local_zendesk/ticket_form', $form->export_for_template($this));
    }
}
