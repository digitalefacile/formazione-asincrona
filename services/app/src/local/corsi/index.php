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
require_once($CFG->dirroot . '/theme/edumy/ccn/course_handler/ccn_course_handler.php');

require_login();

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/corsi/index.php'));
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title(get_string('pagetitle', 'local_corsi') . ' - ' . $SITE->fullname);
$PAGE->set_heading($SITE->fullname);
$PAGE->blocks->add_region('corsi-content');
echo $OUTPUT->header();
?>

<div class="corsi-page">

    <!-- Header -->
    <section class="corsi-header">
        <div class="container">
            <div class="main-title text-center">
                <h2 class="mb0 mt0"><?php echo get_string('pagetitle', 'local_corsi'); ?></h2>
                <p><?php echo get_string('pagesubtitle', 'local_corsi'); ?></p>
            </div>
        </div>
    </section>

    <!-- Featured Content Strip: 3 most recently added visible courses -->
    <section class="corsi-featured-strip">
        <div class="container">
            <div class="main-title text-center">
                <h2 class="mb0 mt0"><?php echo get_string('featured', 'local_corsi'); ?></h2>
            </div>
            <div class="ccn-courses-grid">
                <div class="row">
                    <?php
                    $recentcourses = $DB->get_records_sql(
                        'SELECT id FROM {course} WHERE id != :siteid AND visible = 1 ORDER BY timecreated DESC',
                        ['siteid' => SITEID],
                        0,
                        3
                    );
                    $ccnCourseHandler = new ccnCourseHandler();
                    foreach ($recentcourses as $rc) {
                        $ccnCourse = $ccnCourseHandler->ccnGetCourseDetails($rc->id);
                        ?>
                        <div class="col-md-4 ccn_grid_card">
                            <div class="top_courses ccnWithFoot">
                                <span class="sr-only">Inizio card corso</span>
                                <div class="thumb">
                                    <?php echo $ccnCourse->ccnRender->coverImage; ?>
                                </div>
                                <div class="details">
                                    <div class="tc_content">
                                        <div class="container_cip_category">
                                            <div class="cip_category <?php echo $ccnCourse->idNumber; ?>">
                                                <?php echo $ccnCourse->categoryName; ?>
                                            </div>
                                        </div>
                                        <div class="content_title_card_slider">
                                            <?php echo $ccnCourse->fullName; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer_card_slider">
                                    <a href="<?php echo $ccnCourse->url; ?>" class="btn btn-primary btn-lg"
                                       aria-label="Vai al corso <?php echo $ccnCourse->fullName; ?>">Vai al corso</a>
                                </div>
                                <span class="sr-only">Fine card corso</span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Course sections: cocoon_courses_grid block instances in corsi-content region -->
    <?php echo $OUTPUT->blocks_for_region('corsi-content'); ?>

    <!-- Final CTA Banner -->
    <section class="discover-courses-section">
        <div class="title"><?php echo get_string('cta_title', 'local_corsi'); ?></div>
        <div class="subtitle"><?php echo get_string('cta_subtitle', 'local_corsi'); ?></div>
        <div class="cta">
            <a href="<?php echo new moodle_url('/my/courses.php'); ?>">
                <?php echo get_string('cta_button', 'local_corsi'); ?>
            </a>
        </div>
    </section>

</div>

<?php
echo $OUTPUT->footer();
