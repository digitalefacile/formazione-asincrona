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
 * Version details.
 *
 * @package    format_flexsections
 * @copyright  2022 Marina Glancy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2023080800;             // The current plugin version (Date: YYYYMMDDXX).
$plugin->requires  = 2022041900.00;          // Requires Moodle 4.0 or above.
$plugin->release   = "4.0.5";
$plugin->maturity  = MATURITY_STABLE;
$plugin->component = 'format_flexsections';  // Full name of the plugin (used for diagnostics).
$plugin->supported = [400, 402];
