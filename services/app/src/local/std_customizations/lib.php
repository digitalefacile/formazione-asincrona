<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Library functions for local_std_customizations plugin.
 *
 * @package    local_std_customizations
 * @copyright  2025 STD
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Hook to add custom content to the footer of every page.
 *
 * This function adds a hidden div containing the user's group shortname
 * to the footer of every Moodle page.
 *
 * @return string HTML content to be added to the footer
 */
function local_std_customizations_before_footer() {
    global $PAGE, $USER, $DB;
    $PAGE->requires->js('/local/std_customizations/assets/js/user_roles.js');

    // Check if the user is logged in and not a guest
    if (!isloggedin() || isguestuser()) {
        return '';
    }

    // Retrieve the user's roles
    $userid = $USER->id;
    $roles = [];

    $roleassignments = $DB->get_records('role_assignments', ['userid' => $userid]);
    if ($roleassignments) {
        foreach ($roleassignments as $assignment) {
            $rolename = $DB->get_field('role', 'shortname', ['id' => $assignment->roleid]);
            if ($rolename && !in_array($rolename, $roles)) {
                $roles[] = $rolename;
            }
        }
    }

    // Encode roles as JSON
    $jsonRoles = json_encode($roles, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);

    // Return the HTML with embedded JSON
    return '<div id="user-group-shortnames" style="display: none;">
                <script type="application/json" id="user-roles-data">' . $jsonRoles . '</script>
            </div>';
}