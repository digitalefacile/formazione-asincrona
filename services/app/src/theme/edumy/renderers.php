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
defined('MOODLE_INTERNAL') || die();

require_once('renderers/blog_renderer.php');
require_once($CFG->dirroot.'/question/engine/renderer.php');
require_once($CFG->dirroot.'/question/type/rendererbase.php');
require_once($CFG->dirroot.'/question/type/multichoice/renderer.php');
class theme_edumy_core_question_renderer extends core_question_renderer {

    protected string|null $qnumber;
    
    /**
     * Generate the display of a question in a particular state, and with certain
     * display options. Normally you do not call this method directly. Intsead
     * you call {@link question_usage_by_activity::render_question()} which will
     * call this method with appropriate arguments.
     *
     * @param question_attempt $qa the question attempt to display.
     * @param qbehaviour_renderer $behaviouroutput the renderer to output the behaviour
     *      specific parts.
     * @param qtype_renderer $qtoutput the renderer to output the question type
     *      specific parts.
     * @param question_display_options $options controls what should and should not be displayed.
     * @param string|null $number The question number to display. 'i' is a special
     *      value that gets displayed as Information. Null means no number is displayed.
     * @return string HTML representation of the question.
     */
    public function question(question_attempt $qa, qbehaviour_renderer $behaviouroutput,
            qtype_renderer $qtoutput, question_display_options $options, $number) {
        
        global $PAGE;
        $quizoutput = $PAGE->get_renderer('mod_quiz');
        $quizattempt = quiz_attempt::create_from_usage_id($qa->get_usage_id());
        $this->qnumber = $number;

        // If not already set, record the questionidentifier.
        $options = clone($options);
        if (!$options->has_question_identifier()) {
            $options->questionidentifier = $this->question_number_text($number);
        }

        $output = '';
        $output .= html_writer::start_tag('div', array(
            'id' => $qa->get_outer_question_div_unique_id(),
            'class' => implode(' ', array(
                'que d-flex flex-row',
                $qa->get_question(false)->get_type_name(),
                $qa->get_behaviour_name(),
                $qa->get_state_class($options->correctness && $qa->has_marks()),
            ))
        ));

        // $output .= html_writer::tag('div',
        //         $this->info($qa, $behaviouroutput, $qtoutput, $options, $number),
        //         array('class' => 'info align-self-start'));

        $output .= html_writer::start_tag('div', array('class' => 'content flex-grow-1 m-0 p-4 d-flex flex-row qa-flex-container'));

        $output .= html_writer::tag('div',
                $this->add_part_heading($qtoutput->formulation_heading(),
                    $this->formulation($qa, $behaviouroutput, $qtoutput, $options, $number)),
                array('class' => 'formulation clearfix'));
                $output .= html_writer::end_tag('div');
        $output .= html_writer::nonempty_tag('div',
                $this->add_part_heading(get_string('feedback', 'question'),
                    $this->outcome($qa, $behaviouroutput, $qtoutput, $options)),
                array('class' => 'outcome clearfix'));
        $output .= html_writer::nonempty_tag('div',
                $this->add_part_heading(get_string('comments', 'question'),
                    $this->manual_comment($qa, $behaviouroutput, $qtoutput, $options)),
                array('class' => 'comment clearfix'));
        $output .= html_writer::nonempty_tag('div',
                $this->response_history($qa, $behaviouroutput, $qtoutput, $options),
                array('class' => 'history clearfix border p-2'));


        //$output .= $quizoutput->countdown_timer($quizattempt, time());
        // $output .= html_writer::end_tag('div'); -- NB: spostato per separare e affiancare domande e risposte
        // chiuso in theme_edumy_qtype_multichoice_single_renderer::formulation_and_controls()
        $output .= html_writer::end_tag('div');

        return $output;
    }

    /**
     * Generate the display of the formulation part of the question. This is the
     * area that contains the quetsion text, and the controls for students to
     * input their answers. Some question types also embed feedback, for
     * example ticks and crosses, in this area.
     *
     * @param question_attempt $qa the question attempt to display.
     * @param qbehaviour_renderer $behaviouroutput the renderer to output the behaviour
     *      specific parts.
     * @param qtype_renderer $qtoutput the renderer to output the question type
     *      specific parts.
     * @param question_display_options $options controls what should and should not be displayed.
     * @param string|null number The question number to display. 'i' is a special
     * @return HTML fragment.
     */
    protected function formulation(question_attempt $qa, qbehaviour_renderer $behaviouroutput,
            qtype_renderer $qtoutput, question_display_options $options) {

        // get total number of questions
        $quizattempt = quiz_attempt::create_from_usage_id($qa->get_usage_id()); 
        $totNumber = count($quizattempt->get_slots()); 

        $output = '';
        $output .= $this->number($this->qnumber, $totNumber);
        $output .= html_writer::empty_tag('input', array(
                'type' => 'hidden',
                'name' => $qa->get_control_field_name('sequencecheck'),
                'value' => $qa->get_sequence_check_count()));
        $output .= $qtoutput->formulation_and_controls($qa, $options);
        if ($options->clearwrong) {
            $output .= $qtoutput->clear_wrong($qa);
        }
        $output .= html_writer::nonempty_tag('div',
                $behaviouroutput->controls($qa, $options), array('class' => 'im-controls'));
        return $output;
    }

    /**
     * Generate the display of the question number.
     * @param string|null $number The question number to display. 'i' is a special
     *      value that gets displayed as Information. Null means no number is displayed.
     * @return HTML fragment.
     */
    protected function number($number,$totNumber) {

        if (trim($number ?? '') === '') {
            return '';
        }
        if (trim($number) === 'i') {
            $numbertext = get_string('information', 'question');
        } else {
            $numbertext = get_string('questionx', 'question',
                    html_writer::tag('span', s($number.' '.get_string('of', 'feedback').' '.$totNumber), array('class' => 'qno')));
        }
        return html_writer::tag('h3', $numbertext, array('class' => 'no mb-4'));
    }

}

/**
 * Base class for generating the bits of output common to multiple choice
 * single and multiple questions.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_edumy_qtype_multichoice_single_renderer extends qtype_multichoice_single_renderer {

    public function formulation_and_controls(question_attempt $qa,
            question_display_options $options) {

        $question = $qa->get_question();
        $response = $question->get_response($qa);

        $inputname = $qa->get_qt_field_name('answer');
        $inputattributes = array(
            'type' => $this->get_input_type(),
            'name' => $inputname,
        );

        if ($options->readonly) {
            $inputattributes['disabled'] = 'disabled';
        }

        $radiobuttons = array();
        $feedbackimg = array();
        $feedback = array();
        $classes = array();
        foreach ($question->get_order($qa) as $value => $ansid) {
            $ans = $question->answers[$ansid];
            $inputattributes['name'] = $this->get_input_name($qa, $value);
            $inputattributes['value'] = $this->get_input_value($value);
            $inputattributes['id'] = $this->get_input_id($qa, $value);
            $inputattributes['aria-labelledby'] = $inputattributes['id'] . '_label';
            $isselected = $question->is_choice_selected($response, $value);
            if ($isselected) {
                $inputattributes['checked'] = 'checked';
            } else {
                unset($inputattributes['checked']);
            }
            $hidden = '';
            if (!$options->readonly && $this->get_input_type() == 'checkbox') {
                $hidden = html_writer::empty_tag('input', array(
                    'type' => 'hidden',
                    'name' => $inputattributes['name'],
                    'value' => 0,
                ));
            }

            $choicenumber = '';
            if ($question->answernumbering !== 'none') {
                $choicenumber = html_writer::span(
                        $this->number_in_style($value, $question->answernumbering), 'answernumber');
            }
            $choicetext = $question->format_text($ans->answer, $ans->answerformat, $qa, 'question', 'answer', $ansid);
            $choice = html_writer::div($choicetext, 'ml-1');

            $radiobuttons[] = $hidden . html_writer::empty_tag('input', $inputattributes) .
                    html_writer::div($choicenumber . $choice, 'd-flex w-auto', [
                        'id' => $inputattributes['id'] . '_label',
                        'data-region' => 'answer-label',
                    ]);

            // Param $options->suppresschoicefeedback is a hack specific to the
            // oumultiresponse question type. It would be good to refactor to
            // avoid refering to it here.
            if ($options->feedback && empty($options->suppresschoicefeedback) &&
                    $isselected && trim($ans->feedback)) {
                $feedback[] = html_writer::tag('div',
                        $question->make_html_inline($question->format_text(
                                $ans->feedback, $ans->feedbackformat,
                                $qa, 'question', 'answerfeedback', $ansid)),
                        array('class' => 'specificfeedback'));
            } else {
                $feedback[] = '';
            }
            $class = 'r' . ($value % 2);
            if ($options->correctness && $isselected) {
                // Feedback images will be rendered using Font awesome.
                // Font awesome icons are actually characters(text) with special glyphs,
                // so the icons cannot be aligned correctly even if the parent div wrapper is using align-items: flex-start.
                // To make the Font awesome icons follow align-items: flex-start, we need to wrap them inside a span tag.
                $feedbackimg[] = html_writer::span($this->feedback_image($this->is_right($ans)), 'ml-1');
                $class .= ' ' . $this->feedback_class($this->is_right($ans));
            } else {
                $feedbackimg[] = '';
            }
            $classes[] = $class;
        }

        $result = '';
        $result .= html_writer::tag('div', $question->format_questiontext($qa),
                array('class' => 'qtext'));
        $result .= html_writer::end_tag('div');
        $result .= html_writer::start_tag('div',['class' => 'answer-wrapper flex-fill']);
        $result .= html_writer::start_tag('fieldset', array('class' => 'ablock no-overflow visual-scroll-x'));
        if ($question->showstandardinstruction == 1) {
            $legendclass = '';
            $questionnumber = $options->add_question_identifier_to_label($this->prompt(), true, true);
        } else {
            $questionnumber = $options->add_question_identifier_to_label(get_string('answer'), true, true);
            $legendclass = 'sr-only';
        }
        $legendattrs = [
            'class' => 'prompt h6 font-weight-normal ' . $legendclass,
        ];
        $result .= html_writer::tag('legend', $questionnumber, $legendattrs);
        $result .= html_writer::start_tag('div', array('class' => 'answer'));
        $result .= html_writer::span('<strong>Seleziona la risposta corretta:</strong>','select-correct-answer');
        foreach ($radiobuttons as $key => $radio) {
            $result .= html_writer::tag('div', $radio . ' ' . $feedbackimg[$key] . $feedback[$key],
                    array('class' => $classes[$key])) . "\n";
        }
        $result .= html_writer::end_tag('div'); // Chiude il div formulation per affiancare domande e risposte

        // Load JS module for the question answers.
        $this->page->requires->js_call_amd('qtype_multichoice/answers', 'init',
            [$qa->get_outer_question_div_unique_id()]);
        $result .= $this->after_choices($qa, $options);

        $result .= html_writer::end_tag('fieldset'); // Ablock.
        $result .= html_writer::end_tag('div'); // Answer-wrapper.

        if ($qa->get_state() == question_state::$invalid) {
            $result .= html_writer::nonempty_tag('div',
                    $question->get_validation_error($qa->get_last_qt_data()),
                    array('class' => 'validationerror'));
        }

        return $result;
    }
}

