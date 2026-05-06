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
 * Uninstall script for local_corsi.
 *
 * Removes "Corsi" from the primary navigation custom menu.
 *
 * @package    local_corsi
 * @copyright  2026 DTD
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_local_corsi_uninstall() {
    // Remove "Corsi" from custom menu items.
    $currentmenu = get_config('core', 'custommenuitems');
    if (!empty($currentmenu)) {
        $lines = explode("\n", $currentmenu);
        $lines = array_filter($lines, function($line) {
            return strpos($line, '/local/corsi/') === false;
        });
        set_config('custommenuitems', implode("\n", $lines));
    }

    return true;
}
