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
 * Override the renderer for the quiz module.
 *
 * @package   mod_quiz
 * @copyright 2011 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_edumy\output;

use \html_writer;
use moodle_url;
use quiz_attempt;

defined('MOODLE_INTERNAL') || die();

require_once( $CFG->dirroot . '/mod/quiz/renderer.php' );
require_once( $CFG->dirroot . '/theme/edumy/ccn/mdl_handler/ccn_mdl_handler.php' );


/**
 * The renderer for the quiz module.
 *
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_quiz_renderer extends \mod_quiz_renderer {

    protected quiz_attempt $attemptobj;


    /**
     * Builds the review page
     *
     * @param quiz_attempt $attemptobj an instance of quiz_attempt.
     * @param array $slots an array of intgers relating to questions.
     * @param int $page the current page number
     * @param bool $showall whether to show entire attempt on one page.
     * @param bool $lastpage if true the current page is the last page.
     * @param mod_quiz_display_options $displayoptions instance of mod_quiz_display_options.
     * @param array $summarydata contains all table data
     * @return $output containing html data.
     */
    public function review_page(\quiz_attempt $attemptobj, $slots, $page, $showall,
                                $lastpage, \mod_quiz_display_options $displayoptions,
                                $summarydata) {

            $secondreview = isset($_GET['secondreview']) && $_GET['secondreview'] == 1;

            $output = '';
            $output .= $this->header();

            if($secondreview) {
                $output .= $this->review_summary_table($summarydata, $page);
                $output .= $this->review_form($page, $showall, $displayoptions,
                        $this->questions($attemptobj, true, $slots, $page, $showall, $displayoptions),
                        $attemptobj);

                $output .= $this->review_next_navigation($attemptobj, $page, $lastpage, $showall);                
            } else {
                $output .= $this->first_review_page($attemptobj, $slots, $page, $showall, $lastpage, $displayoptions, $summarydata);
            }

            $output .= $this->footer();

        return $output;
    }

    /**
     * Builds the review page
     *
     * @param quiz_attempt $attemptobj an instance of quiz_attempt.
     * @param array $slots an array of intgers relating to questions.
     * @param int $page the current page number
     * @param bool $showall whether to show entire attempt on one page.
     * @param bool $lastpage if true the current page is the last page.
     * @param mod_quiz_display_options $displayoptions instance of mod_quiz_display_options.
     * @param array $summarydata contains all table data
     * @return $output containing html data.
     */
    public function first_review_page(\quiz_attempt $attemptobj, $slots, $page, $showall,
                                $lastpage, \mod_quiz_display_options $displayoptions,
                                $summarydata) {
        
        $output = '';
        $output .= html_writer::start_tag('div', array('class' => 'first-review-container p-4 d-flex flex-column'));
        $output .= html_writer::start_tag('h3', array('class' => 'first-review-header text-white'));
        $output .= 'TEST COMPLETATO';
        $output .= html_writer::end_tag('h3');
        $output .= html_writer::div($summarydata['feedback']['content'],'first-review-feedback text-center');

        $secondreviewurl = new \moodle_url('/mod/quiz/review.php',
            ['attempt' => $attemptobj->get_attemptid(), 'cmid' => $attemptobj->get_cmid(), 'secondreview' => 1]
        );
        $output .= html_writer::start_tag('div',array('class' => 'first-review-button text-center'));
        $output .= html_writer::link($secondreviewurl, 'Consulta il risultato', ['class' => 'btn btn-secondary']); 
        $output .= html_writer::end_tag('div');

        $output .= html_writer::end_tag('div');

        return $output;
    }

   /**
    * Outputs the navigation block panel
    *
    * @param quiz_nav_panel_base $panel instance of quiz_nav_panel_base
    */
    public function navigation_panel(\quiz_nav_panel_base $panel) {
        $output = '';
        return $output;
    }

    /**
     * Attempt Page
     *
     * @param quiz_attempt $attemptobj Instance of quiz_attempt
     * @param int $page Current page number
     * @param quiz_access_manager $accessmanager Instance of quiz_access_manager
     * @param array $messages An array of messages
     * @param array $slots Contains an array of integers that relate to questions
     * @param int $id The ID of an attempt
     * @param int $nextpage The number of the next page
     * @return string HTML to output.
     */
    public function attempt_page($attemptobj, $page, $accessmanager, $messages, $slots, $id,
            $nextpage) {

        $this->attemptobj = $attemptobj; // Needed for summary_page_controls

        $output = '';
        $output .= $this->header();
        //$output .= $this->during_attempt_tertiary_nav($attemptobj->view_url());
        $output .= $this->quiz_notices($messages);
        $output .= $this->countdown_timer($attemptobj, time());
        $output .= $this->attempt_form($attemptobj, $page, $slots, $id, $nextpage);
        $output .= $this->footer();
        return $output;
    }

    /**
     * Display the prev/next buttons that go at the bottom of each page of the attempt.
     *
     * @param int $page the page number. Starts at 0 for the first page.
     * @param bool $lastpage is this the last page in the quiz?
     * @param string $navmethod Optional quiz attribute, 'free' (default) or 'sequential'
     * @return string HTML fragment.
     */
    protected function attempt_navigation_buttons($page, $lastpage, $navmethod = 'free') {
        $output = '';

        $output .= html_writer::start_tag('div', array('class' => 'submitbtns'));
        if ($page > 0 && $navmethod == 'free') {
            $output .= html_writer::start_tag('button', array('type' => 'submit', 'name' => 'previous',
                    'value' => get_string('navigateprevious', 'quiz'), 'class' => 'mod_quiz-prev-nav btn btn-secondary',
                    'id' => 'mod_quiz-prev-nav'));
                    $output .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M6.73333 12.667L7.2 12.2003L3.66667 8.60032L14 8.60033L14 7.93366L3.66667 7.93366L7.2 4.33366L6.73333 3.86699L2.33333 8.26699L6.73333 12.667Z" fill="#0065CC"/>
                    </svg>&nbsp;Indietro';
          
            $output .= html_writer::end_tag('button');
            $this->page->requires->js_call_amd('core_form/submit', 'init', ['mod_quiz-prev-nav']);
        }
        if ($lastpage) {
            $output .= $this->summary_page_controls($this->attemptobj);
        } else {
            $nextlabel = 'Conferma &nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M9.26667 3.33301L8.8 3.79967L12.3333 7.39967L2 7.39967V8.06634H12.3333L8.8 11.6663L9.26667 12.133L13.6667 7.73301L9.26667 3.33301Z" fill="white"/>
            </svg>&nbsp;';
            # MODIFIED  11/10/23: input diventa button per poter inserire l'icona nell'etichetta di testo
            $output .= html_writer::start_tag('button', array('type' => 'submit', 'name' => 'next',
            'value' =>get_string('navigatenext', 'quiz'), 'class' => 'mod_quiz-next-nav btn btn-primary',
            'id' => 'mod_quiz-next-nav'));
            $output .= $nextlabel;
            $output .= html_writer::end_tag('button');
        }
        $output .= html_writer::end_tag('div');
        $this->page->requires->js_call_amd('core_form/submit', 'init', ['mod_quiz-next-nav']);

        return $output;
    }

   /*
    * View Page
    */
   /**
    * Generates the view page
    *
    * @param stdClass $course the course settings row from the database.
    * @param stdClass $quiz the quiz settings row from the database.
    * @param stdClass $cm the course_module settings row from the database.
    * @param context_module $context the quiz context.
    * @param mod_quiz_view_object $viewobj
    * @return string HTML to display
    */
   public function view_page($course, $quiz, $cm, $context, $viewobj) {
        global $CFG,$DB;

        $output = '';
        // Show activity title
        $output .= $this->heading(format_string($quiz->name),2,'quiz-title');

        //per prendere la categoria da mostrare come chip
        $category = $DB->get_record('course_categories',array('id'=>$course->category));

        // Show course title
        $output .= $this->heading(format_string($course->fullname),3,'course-title');

        // Show course shortname
        $output .= $this->heading(format_string($category->name),4,'course-shortname');

        // Show intro text - NB: messo in template apposito per semplificare le modifiche
        $output .= $this->render_from_template('mod_quiz/quiz_intro',$context);

        // $output .= $this->view_information($quiz, $cm, $context, $viewobj->infomessages);
    //    $output .= $this->view_table($quiz, $context, $viewobj);
    //    $output .= $this->view_result_info($quiz, $context, $cm, $viewobj);
    //    $output .= $this->box($this->view_page_buttons($viewobj), 'quizattempt');
       $output .= $this->view_page_tertiary_nav($viewobj);
       return $output;
   }

    /**
     * Creates any controls a the page should have.
     *
     * @param quiz_attempt $attemptobj
     */
    public function summary_page_controls($attemptobj) {
        $output = '';

        // Return to place button.
        // NB: questo bottone viene nascosto con css perché se viene tolto qui per qualche motivo non funziona più l'invio diretto del tentativo
        if ($attemptobj->get_state() == quiz_attempt::IN_PROGRESS) {
            $button = new \single_button(
                    new moodle_url($attemptobj->attempt_url(null, $attemptobj->get_currentpage())),
                    get_string('returnattempt', 'quiz'));
            $output .= $this->container($this->container($this->render($button),
                    'controls'), 'submitbtn mdl-align');
        }

        // Finish attempt button.
        $options = array(
            'attempt' => $attemptobj->get_attemptid(),
            'finishattempt' => 1,
            'timeup' => 0,
            'slots' => '',
            'cmid' => $attemptobj->get_cmid(),
            'sesskey' => sesskey(),
        );

        $label = 'Conferma e concludi';
        $button = new \single_button(
                new moodle_url($attemptobj->processattempt_url(), $options),
                $label);
        $button->id = 'responseform';
        $button->class = 'btn-finishattempt';
        $button->formid = 'frm-finishattempt';
        if ($attemptobj->get_state() == quiz_attempt::IN_PROGRESS) {
            $totalunanswered = 0;
            if ($attemptobj->get_quiz()->navmethod == 'free') {
                // Only count the unanswered question if the navigation method is set to free.
                $totalunanswered = $attemptobj->get_number_of_unanswered_questions();
            }
            //TODO ISSUE #139
            // $this->page->requires->js_call_amd('mod_quiz/submission_confirmation', 'init', [$totalunanswered]);
        }
        $button->primary = true;

        // $duedate = $attemptobj->get_due_date();
        // $message = '';
        // if ($attemptobj->get_state() == quiz_attempt::OVERDUE) {
        //     $message = get_string('overduemustbesubmittedby', 'quiz', userdate($duedate));

        // } else if ($duedate) {
        //     $message = get_string('mustbesubmittedby', 'quiz', userdate($duedate));
        // }

        // $output .= $this->countdown_timer($attemptobj, time());
        $output .= $this->container($this->container(
                $this->render($button), 'controls'), 'submitbtn mdl-align');

        return $output;
    }

}
