<?php

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot. '/theme/edumy/ccn/block_handler/ccn_block_handler.php');
require_once($CFG->dirroot. '/course/renderer.php');
require_once($CFG->dirroot. '/theme/edumy/ccn/course_handler/ccn_course_handler.php');

class block_cocoon_courses_grid extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_cocoon_courses_grid');
    }

    public function hide_header() {
        return true;
    }

    function specialization() {
        global $CFG, $DB;

        $ccnCourseHandler = new ccnCourseHandler();
        $ccnCourses = $ccnCourseHandler->ccnGetExampleCoursesIds(8);

        include($CFG->dirroot . '/theme/edumy/ccn/block_handler/specialization.php');
        if (empty($this->config)) {
          $this->config = new \stdClass();
          $this->config->title = 'Browse Our Top Courses';
          $this->config->subtitle = '';
          $this->config->hover_text = 'Preview Course';
          $this->config->hover_accent = 'Top Seller';
          $this->config->button_text = 'View all courses';
          $this->config->button_link = $CFG->wwwroot . '/course';
          $this->config->course_image = '1';
          $this->config->description = '0';
          $this->config->price = '1';
          $this->config->enrol_btn = '0';
          $this->config->enrol_btn_text = 'Buy Now';
          $this->config->courses = $ccnCourses;
          $this->config->columns = 3;
        }
    }

    public function get_content() {
        global $CFG, $DB, $COURSE, $USER, $PAGE;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';
        $this->content->text = '';
        if(!empty($this->config->title)){$this->content->title = $this->config->title;} else {$this->content->title = '';}
        if(!empty($this->config->subtitle)){$this->content->subtitle = $this->config->subtitle;} else {$this->content->subtitle = '';}
        if(!empty($this->config->hover_text)){$this->content->hover_text = $this->config->hover_text;} else {$this->content->hover_text = '';}
        if(!empty($this->config->hover_accent)){$this->content->hover_accent = $this->config->hover_accent;} else {$this->content->hover_accent = '';}
        if(!empty($this->config->description)){$this->content->description = $this->config->description;} else {$this->content->description = '0';}
        if(!empty($this->config->course_image)){$this->content->course_image = $this->config->course_image;} else {$this->content->course_image = '';}
        if(!empty($this->config->price)){$this->content->price = $this->config->price;} else {$this->content->price = '0';}
        if(!empty($this->config->enrol_btn)){$this->content->enrol_btn = $this->config->enrol_btn;} else {$this->content->enrol_btn = '0';}
        if(!empty($this->config->enrol_btn_text)){$this->content->enrol_btn_text = $this->config->enrol_btn_text;} else {$this->content->enrol_btn_text = '';}

        // Columns config: 2 → col-md-6, 3 → col-md-4, 4 → col-md-3.
        $columns = !empty($this->config->columns) ? (int)$this->config->columns : 3;
        $colSize = 12 / $columns;
        $colClass = 'col-md-' . $colSize;

        if(
          isset($this->content->description) &&
          $this->content->description != '0'
        ) {
          $ccnBlockShowDesc = 1;
        } else {
          $ccnBlockShowDesc = 0;
        }

        if(
          isset($this->content->course_image) &&
          $this->content->course_image == '1'
        ){
          $ccnBlockShowImg = 1;
        } else {
          $ccnBlockShowImg = 0;
        }
        if(
          isset($this->content->enrol_btn) &&
          isset($this->content->enrol_btn_text) &&
          $this->content->enrol_btn == '1'
        ){
          $ccnBlockShowEnrolBtn = 1;
        } else {
          $ccnBlockShowEnrolBtn = 0;
        }
        if(
          isset($this->content->price) &&
          $this->content->price == '1'
        ) {
          $ccnBlockShowPrice = 1;
        } else {
          $ccnBlockShowPrice = 0;
        }

        if(
          $PAGE->theme->settings->coursecat_enrolments != 1 ||
          $PAGE->theme->settings->coursecat_announcements != 1 ||
          isset($this->content->price) ||
          isset($this->content->enrol_btn_text) &&
          ($this->content->price == '1' || $this->content->enrol_btn == '1')
        ){
          $ccnBlockShowBottomBar = 1;
          $topCoursesClass = 'ccnWithFoot';
        } else {
          $ccnBlockShowBottomBar = 0;
          $topCoursesClass = '';
        }

        if(!empty($this->content->description) && $this->content->description == '7'){
          $maxlength = 500;
        } elseif(!empty($this->content->description) && $this->content->description == '6'){
          $maxlength = 350;
        } elseif(!empty($this->content->description) && $this->content->description == '5'){
          $maxlength = 200;
        } elseif(!empty($this->content->description) && $this->content->description == '4'){
          $maxlength = 150;
        } elseif(!empty($this->content->description) && $this->content->description == '3'){
          $maxlength = 100;
        } elseif(!empty($this->content->description) && $this->content->description == '2'){
          $maxlength = 50;
        } else {
          $maxlength = null;
        }

        if(!empty($this->config->courses)){
          $coursesArr = $this->config->courses;
          $courses = new stdClass();
          foreach ($coursesArr as $key => $course) {
            $courseObj = new stdClass();
            $courseObj->id = $course;
            $courses->$course = $courseObj;
          }
        }

        if (!empty($this->config->style) && $this->config->style == 1) {  // Background style
          $this->content->text .= '
            <section class="popular-courses bgc-thm2">
          		<div class="container-fluid style2">
          			<div class="row">
          				<div class="col-lg-12">
          					<div class="main-title text-center">';
                    $this->content->text .='<h3 class="mt0 color-white" data-ccn="title">'.format_text($this->content->title, FORMAT_HTML, array('filter' => true)).'</h3>';
                    if(!empty($this->content->subtitle)){
                      $this->content->text .='<p class="color-white" data-ccn="subtitle">'.format_text($this->content->subtitle, FORMAT_HTML, array('filter' => true)).'</p>';
                    }
                    $this->content->text .='
          					</div>
          				</div>
          			</div>
          			<div class="row">';
                    if(!empty($this->config->courses)){
                    $chelper = new coursecat_helper();
                    foreach ($courses as $course) {
                      if ($DB->record_exists('course', array('id' => $course->id))) {

                        $ccnCourseHandler = new ccnCourseHandler();
                        $ccnCourse = $ccnCourseHandler->ccnGetCourseDetails($course->id);
                        $ccnCourseDescription = $ccnCourseHandler->ccnGetCourseDescription($course->id, $maxlength);

                      $this->content->text .='
                        <div class="'.$colClass.'">
            							<div class="top_courses home2 mb0 '.$topCoursesClass.'">';
                          if($ccnBlockShowImg){
                            $this->content->text .='
            								<div class="thumb">
            									'.$ccnCourse->ccnRender->coverImage.'
            									<a class="overlay" href="'. $ccnCourse->url .'">';
                              if($this->content->hover_accent){
                               $this->content->text .='<div class="tag" data-ccn="hover_accent">'.format_text($this->content->hover_accent, FORMAT_HTML, array('filter' => true)).'</div>';
                             }
                             if($this->content->hover_text){
                              $this->content->text .='  <div class="tc_preview_course" data-ccn="hover_text" href="'. $ccnCourse->url .'">'.format_text($this->content->hover_text, FORMAT_HTML, array('filter' => true)).'</div>';
                             }
            									$this->content->text .='
                              </a>
            								</div>';
                          }
                          $this->content->text .='
            								<div class="details">
            									<div class="tc_content">';
                              $this->content->text .= $ccnCourse->ccnRender->updatedDate;
                              $this->content->text .=  $ccnCourse->ccnRender->title;
                              if($ccnBlockShowDesc){
                                $this->content->text .='<p>'.$ccnCourseDescription.'</p>';
                              }
                              $this->content->text .= $ccnCourse->ccnRender->starRating;

                              $this->content->text .='
            									</div>
                              </div>';
                              if($ccnBlockShowBottomBar == 1){
                              $this->content->text .='
            									<div class="tc_footer">
                              <ul class="tc_meta float-left">'.$ccnCourse->ccnRender->enrolmentIcon . $ccnCourse->ccnRender->announcementsIcon.'</ul>';

                                if($ccnBlockShowEnrolBtn){
                                  $this->content->text .='<a href="'.$ccnCourse->enrolmentLink.'" class="tc_enrol_btn data-ccn="enrol_btn_text" float-right">'.format_text($this->content->enrol_btn_text, FORMAT_HTML, array('filter' => true)).'</a>';
                                }
                                if($ccnBlockShowPrice){
                                  $this->content->text .= '<div class="tc_price float-right">'.$ccnCourse->price.'</div>';
                                }
                                $this->content->text .='
            									</div>';
                            }
                            $this->content->text .='
            							</div>
            						</div>';
                      }
                      }
                    }
                      $this->content->text .= '
                         </div>
    	                 </div>
    	                 </section>';

                    } else { // Standard style
                      $this->content->text .= '
        <section class="features-course pb20">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="main-title text-center" id="sezione-1">';
        if(!empty($this->content->title)){
          $this->content->text .='<h2 class="mb0 mt0" style="width:100%;" data-ccn="title">'. format_text($this->content->title, FORMAT_HTML, array('filter' => true)) .'</h2>';
        }
        if(empty($this->content->subtitle)){
          $this->content->text .='<p data-ccn="subtitle" style="width:100%;">Segui i corsi dedicati alle <strong>5 aree tematiche del DigComp 2.2,</strong> il quadro europeo delle competenze digitali dei cittadini. </p>';
        } else {
          $this->content->text .='<p data-ccn="subtitle">'. format_text($this->content->subtitle, FORMAT_HTML, array('filter' => true)) .'</p>';
        }
       $this->content->text .='
        </div>
      </div>
    </div>
    <div class="ccn-courses-grid">
    <div class="row">';

        if(!empty($this->config->courses)){
        $chelper = new coursecat_helper();
        foreach ($courses as $course) {
          if ($DB->record_exists('course', array('id' => $course->id))) {

            $ccnCourseHandler = new ccnCourseHandler();
            $ccnCourse = $ccnCourseHandler->ccnGetCourseDetails($course->id);
            $ccnCourseDescription = $ccnCourseHandler->ccnGetCourseDescription($course->id, $maxlength);

            $this->content->text .='
                      <div class="'.$colClass.' ccn_grid_card">
           							<div class="top_courses '.$topCoursesClass.'">
                        <span class="sr-only">Inizio card corso</span>';
                         if($ccnBlockShowImg){
                           $this->content->text .='
           								<div class="thumb">
           									'.$ccnCourse->ccnRender->coverImage.'
           								</div>';
                         }
                         $this->content->text .='
           								<div class="details">
           									<div class="tc_content">
                                <div class="container_cip_category">
                                  <div class="cip_category '.$ccnCourse->idNumber.'">
                                    '.$ccnCourse->categoryName.'
                                  </div>
                                 </div>
                                <div class="content_title_card_slider">
                                  '.  $ccnCourse->fullName.'
                                </div>
                            </div>
                          </div>';
                             if($ccnBlockShowBottomBar == 1){
                               $this->content->text .='
                                <div class="footer_card_slider">
                                  <a href="'. $ccnCourse->url.'" class="btn btn-primary btn-lg"
                                  aria-label="Vai al corso '.  $ccnCourse->fullName.'"
                                  >Vai al corso </a>
                                </div>';
                           }
                          $this->content->text .='
           							</div>
                         <span class="sr-only">Fine card corso</span>
           						</div>
                       ';
          }
        }
      }
$this->content->text .= '
    </div>
    </div>
  </div>
</section>';
  }

        return $this->content;
    }

    function applicable_formats() {
      $ccnBlockHandler = new ccnBlockHandler();
      return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }
    public function html_attributes() {
      global $CFG;
      $attributes = parent::html_attributes();
      include($CFG->dirroot . '/theme/edumy/ccn/block_handler/attributes.php');
      return $attributes;
    }

    public function instance_allow_multiple() {
          return true;
    }

    public function has_config() {
        return false;
    }

    public function cron() {
        return true;
    }

}
