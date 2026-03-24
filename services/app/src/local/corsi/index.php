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
 * Corsi catalog page.
 *
 * @package    local_corsi
 * @copyright  2026 DTD
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

require_login();

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/corsi/index.php'));
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title(get_string('pagetitle', 'local_corsi') . ' - ' . $SITE->fullname);
$PAGE->set_heading($SITE->fullname);
echo $OUTPUT->header();
?>

<div class="corsi-page">

    <!-- Header -->
    <section class="corsi-header">
        <div class="container">
            <h1 class="title"><?php echo get_string('pagetitle', 'local_corsi'); ?></h1>
            <h2 class="subtitle"><?php echo get_string('pagesubtitle', 'local_corsi'); ?></h2>
        </div>
    </section>

    <!-- Featured Content Strip -->
    <section class="corsi-featured-strip">
        <div class="container">
            <h3><?php echo get_string('featured', 'local_corsi'); ?></h3>
            <div class="corsi-featured-cards">
                <a href="#sezione-digcomp" class="corsi-featured-card">
                    <span><?php echo get_string('digcomp', 'local_corsi'); ?></span>
                </a>
                <a href="#sezione-trasversali" class="corsi-featured-card">
                    <span><?php echo get_string('trasversali', 'local_corsi'); ?></span>
                </a>
                <a href="#sezione-altri" class="corsi-featured-card">
                    <span><?php echo get_string('altri', 'local_corsi'); ?></span>
                </a>
            </div>
        </div>
    </section>

    <!-- Course sections are rendered via cocoon_courses_grid block instances -->
    <!-- Add them via "Turn editing on" in the fullwidth-top / content regions -->

    <!-- Final CTA Banner -->
    <section class="corsi-cta-banner">
        <div class="container text-center">
            <h3><?php echo get_string('cta_title', 'local_corsi'); ?></h3>
            <p><?php echo get_string('cta_subtitle', 'local_corsi'); ?></p>
            <a href="<?php echo new moodle_url('/my/'); ?>" class="btn btn-primary btn-lg">
                <?php echo get_string('cta_button', 'local_corsi'); ?>
            </a>
        </div>
    </section>

</div>

<?php
echo $OUTPUT->footer();
