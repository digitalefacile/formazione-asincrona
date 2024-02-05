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
 * Contains the default activity item from a section.
 *
 * @package   core_courseformat
 * @copyright 2020 Ferran Recio <ferran@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core_courseformat\output\local\content\section;

use cm_info;
use core\output\named_templatable;
use core_courseformat\base as course_format;
use core_courseformat\output\local\courseformat_named_templatable;
use renderable;
use renderer_base;
use section_info;
use stdClass;
use completion_info;
use mod_quiz\completion;
// needed for certificate download
use moodle_url;
use context_module;
use core\oauth2\rest;
use single_button;
use core_completion_external;

require_once($CFG->dirroot.'/mod/quiz/accessmanager.php');
/**
 * Base class to render a section activity in the activities list.
 *
 * @package   core_courseformat
 * @copyright 2020 Ferran Recio <ferran@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cmitem implements named_templatable, renderable {

    use courseformat_named_templatable;

    /** @var course_format the course format class */
    protected $format;

    /** @var section_info the course section class */
    protected $section;

    /** @var cm_info the course module to display */
    protected $mod;

    /** @var array optional display options */
    protected $displayoptions;

    /** @var string the cm output class name */
    protected $cmclass;

    /**
     * Constructor.
     *
     * @param course_format $format the course format
     * @param section_info $section the section info
     * @param cm_info $mod the course module ionfo
     * @param array $displayoptions optional extra display options
     */
    public function __construct(course_format $format, section_info $section, cm_info $mod, array $displayoptions = []) {
        $this->format = $format;
        $this->section = $section;
        $this->mod = $mod;
        $this->displayoptions = $displayoptions;

        // Get the necessary classes.
        $this->cmclass = $format->get_output_classname('content\\cm');
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output typically, the renderer that's calling this function
     * @return stdClass data context for a mustache template
     */
    public function export_for_template(\renderer_base $output): stdClass {

        global $USER, $DB;

        $format = $this->format;
        $course = $format->get_course();
        $mod = $this->mod;

	    $data = new stdClass();
        $data->cms = [];

        $completionenabled = $course->enablecompletion == COMPLETION_ENABLED;
        $showactivityconditions = $completionenabled && $course->showcompletionconditions == COMPLETION_SHOW_CONDITIONS;
        $showactivitydates = !empty($course->showactivitydates);

        // This will apply styles to the course homepage when the activity information output component is displayed.
        $hasinfo = $showactivityconditions || $showactivitydates;

        $item = new $this->cmclass($format, $this->section, $mod, $this->displayoptions);

        // Predispone le CTA necessarie per l'accessibilità:
        // 1. Vai al modulo / Scopri di più / Vai al test (quiz)
        // 2. Scarica il certificato
        // 3. Vai al test iniziale

        // 1. CTA per accedere al modulo, in base al fatto che l'utente abbia già visualizzato o meno l'attività
        $params = array(
            'contextinstanceid' => $mod->id,
            'userid' => $USER->id,
            'eventname' => '\mod_'.$mod->modname.'\event\course_module_viewed',
        );

        // Interroga il log degli eventi
        $events = $DB->get_records('logstore_standard_log', $params);
        $hasviewed = count($events) ? true : false;
        $hascta = !in_array($mod->modname,['label']); // INSERIRE QUI i tipi di modulo per cui _non_ vanno mostrate le CTA

	    // Dati per aria-label
	    $ariacoursetitle = $course->fullname;
	    $ariamoduletitle = $mod->get_name();
        if($mod->modname == 'customcert'){
            $stringaAttestato="Visualizza l'attestato";
            $arialabel = 'Scarica il certificato '.$ariamoduletitle.' del corso '.$ariacoursetitle;
            $cta = '<a href="'.$mod->url.'&downloadown=1" class="m-2 btn btn-primary" aria-label="'.$arialabel.'" 
            style="display: flex;
            align-items: center;
            justify-content: space-between;"> 

            '.$stringaAttestato.'
            &nbsp;
            <span class="svg_certificate_container">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.5 2H14.7L19 6.3V20.5C19 21.3284 18.3284 22 17.5 22H6.5C5.67157 22 5 21.3284 5 20.5V3.5C5 2.67157 5.67157 2 6.5 2ZM17.3 6L15 3.7V5.5C15 5.77614 15.2239 6 15.5 6H17.3ZM17.5 21H6.5C6.22386 21 6 20.7761 6 20.5V3.5C6 3.22386 6.22386 3 6.5 3H14V5.5C14 6.32843 14.6716 7 15.5 7H18V20.5C18 20.7761 17.7761 21 17.5 21ZM16 9H8V10H16V9ZM8 11H16V12H8V11ZM12 13H8V14H12V13Z" fill="white" ></path></svg>
            </span>
            </a>';
        }else{

            if(!$hasviewed) {

                if($mod->modname == 'quiz') {

                    $arialabel = 'Vai al test '.$ariamoduletitle.' del corso '.$ariacoursetitle;
                    $cta = '<a href="'.$mod->url.'" class="m-2 btn btn-primary" aria-label="'.$arialabel.'"> Vai al test </a>';

                } else {

                    $arialabel = 'Vai al modulo '.$ariamoduletitle.' del corso '.$ariacoursetitle;
                    $cta = '<a href="'.$mod->url.'" class="m-2 btn btn-primary"  aria-label="' . $arialabel . '"> Vai al modulo </a>';

                }
            } else {
                $arialabel = 'Scopri di più sul modulo '.$ariamoduletitle.' del corso '.$ariacoursetitle;
                // Forzato allineamento a destra ed eliminato margini dx e sx sul CTA
                $cta = '<a href="'.$mod->url.'" class="mt-2 mb-2 btn btn-link btn-sm float-right"  aria-label="' . $arialabel . '"><strong>Scopri di più 
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M13.9 5L13.2 5.7L18.5 11.1H3V12.1H18.5L13.2 17.5L13.9 18.2L20.5 11.6L13.9 5Z" fill="#0066CC"/>
                </svg>
                </strong></a>';
            }
        }

        // 2. CTA per i quiz che abilitano attestato
        $dlCertCta = null;
        if( $mod->modname == 'quiz' && $mod->completion == 2)  { // 2 = attività superata (COMPLETION_COMPLETE_PASS)
        // @TODO: spostare a monte la mappatura quiz->attestato, per evitare di fare il giro a ogni modulo del corso
        // @TODO: utilizzare la lib/completionlib.php e mod_quiz\completion\activity_custom_completion
            
            //CONTROLLO BOTTONE QUIZ SOLO SE HAI UN VOTO ADEGUATO
            $quizId = $mod->instance;
            $grades = grade_get_grades($course->id, 'mod','quiz', $quizId, $USER->id);
            $gradepass = $grades->items[0]->gradepass; // Valore di gradepass
            $grade = $grades->items[0]->grades[$USER->id]->grade;
            
            $customcertModIds = $DB->get_records('modules', array('name' => 'customcert'));           
            if(count($customcertModIds) > 0) {
                $customcertModId = array_keys($customcertModIds)[0];

            } else {
                $customcertModId = null;
            }

            if(null !== $customcertModId) {
                // get all customcert modules in the course
                $customcerts = $DB->get_records('course_modules', array('module' => $customcertModId, 'course' => $course->id));
                if(count($customcerts) > 0) {

                    global $OUTPUT;

                    // for each customcert module, get access restrictions from its settings
                    foreach($customcerts as $customcert) {
                        
                        // get access restrictions for customcert
                        $certAvail = json_decode($customcert->availability);

                        // Check permissions to download the customcert.
                        $context_module = context_module::instance($mod->id);
                        $canreceive = has_capability('mod/customcert:receiveissue', $context_module);
                       
                        foreach($certAvail->c as $avc) {
                            if($avc->type == 'completion' && $avc->cm == (int)$mod->id && $canreceive) {
                                
                                // Create the button to download the customcert.
                                // Code taken from mod/customcert/view.php, there is no unique method to do this
                                $linkname = get_string('getcustomcert', 'customcert');
                                $link = new moodle_url('/mod/customcert/view.php', array('id' => $customcert->id, 'downloadown' => true));
                                $downloadbutton = new single_button($link, $linkname, 'get', true);
                                $downloadbutton->class .= ' m-b-1';  // Seems a bit hackish, ahem.
                             
                                if ($grade >= $gradepass || is_siteadmin($USER->id)) {
                                    $quizpassed = true;
                                    $dlCertCta = $OUTPUT->render($downloadbutton);
                                } else {
                                    $quizpassed = false;
                                }
                                
                                //$dlCertCta = '<a href="#" class="btn btn-primary">Scarica certificato</a>';
                            }
                        }
                    }

                }

            }

        }

        // 3. CTA per test iniziale (attività di tipo scorm con titolo definito)
        if($mod->modname == 'scorm' && $mod->name == 'Test iniziale') {

            if($mod->completion == '2') {

                 // Definizione dell livello di superamento del test
                 $scormId = $mod->instance;
                 $grades = grade_get_grades($course->id, 'mod','scorm', $scormId, $USER->id);
                 $grade = $grades->items[0]->grades[$USER->id]->grade; // NB: vscode segnala errore, ma funziona
                 if($grade > 80) {
                     $testInizialeLevel = 'avanzato';
                 } elseif($grade > 40) {
                     $testInizialeLevel = 'intermedio';
                 } else {
                     $testInizialeLevel = 'base';
                 }
 
            }

        }

        // Se siamo al quiz finale, prepara i dati dei tentativi precedenti
        if($mod->modname == 'quiz' && $mod->name == 'Test finale') {
            $quiz = $DB->get_record('quiz', array('id' => $quizId));
            
            if($quiz) {
                $hasattempts = false;
                $attemptobjs = [];
                $userattempts = quiz_get_user_attempts($mod->instance, $USER->id);
                if($userattempts) {
                    foreach ($userattempts as $userattempt) {
                        $attemptobjs[] = new \quiz_attempt($userattempt, $quiz, $mod, $course, false);
                    }
                    if(count($attemptobjs) > 0) {
                        $hasattempts = true;
                        $attempts = [];
                        foreach($attemptobjs as $attemptobj) {
                            if (!$attemptobj->is_finished()) { // Visualizza solo i tentativi completati
                                continue;
                            }                            
                            $attemptno = $attemptobj->get_attempt_number();
                            $usergrade = quiz_rescale_grade($attemptobj->get_sum_marks(), $quiz, false);
                            $feedbacks = $DB->get_records('quiz_feedback', array('quizid' => $quiz->id));
                            $attemptfeedback = '';
                            foreach ($feedbacks as $feedback) {
                                if ($usergrade >= $feedback->mingrade && $usergrade <= $feedback->maxgrade) {
                                    $fullattemptfeedback = $feedback->feedbacktext;
                                    // NB: utilizzato il messaggio configurato nelle impostazioni del quiz, che però è composto da più righe, mentre qui serve solo la prima
                                    // Eventualmente sostituire con stringa apposita nel file di lingua
                                    $attemptfeedback = strip_tags(explode("\r", $fullattemptfeedback)[0]);
                                    break;
                                }
                            }
            
                            $attempts[] = [
                                'attemptno' => $attemptno,
                                'grade' => $usergrade,
                                'feedback' => $attemptfeedback,
                                'reviewurl' => new \moodle_url('/mod/quiz/review.php',['attempt' => $attemptobj->get_attemptid(), 'cmid' => $mod->id, 'secondreview' => '1']),
                            ];
                            
                        }
                    }
                    // Check if the current user has any attempts left
                    $quizobj = \quiz::create($mod->instance, $USER->id);
                    $accessmanager = new \quiz_access_manager($quizobj, time(),has_capability('mod/quiz:ignoretimelimits', $mod->context, null, false));
                    $moreattempts = true;
                    if($hasattempts && $accessmanager->is_finished(count($userattempts), end($userattempts))) {
                        $moreattempts = false;
                    }
                }
            }
        }

        return (object)[
            'id' => $mod->id,
            'anchor' => "module-{$mod->id}",
            'module' => $mod->modname,
            'extraclasses' => $mod->extraclasses,
            'cmformat' => $item->export_for_template($output),
            'hasinfo' => $hasinfo,
            'indent' => ($format->uses_indentation()) ? $mod->indent : 0,

            'hascta' => $hascta,
            'cta' => $cta,
            'dlCertCta' => $dlCertCta,
            'quizpassed' => $quizpassed,

            'testinizialelevel' => $testInizialeLevel,
            'hasattempts' => $hasattempts,
            'attempts' => $attempts,
            'moreattempts' => $moreattempts,
        ];
    }
}
