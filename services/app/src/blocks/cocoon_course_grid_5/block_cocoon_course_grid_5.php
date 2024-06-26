<?php
defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot. '/course/renderer.php');
require_once($CFG->dirroot. '/theme/edumy/ccn/course_handler/ccn_course_handler.php');
require_once($CFG->dirroot. '/theme/edumy/ccn/block_handler/ccn_block_handler.php');

class block_cocoon_course_grid_5 extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_cocoon_course_grid_5');
    }

    function specialization() {
        global $CFG, $DB;

        $ccnCourseHandler = new ccnCourseHandler();
        $ccnCourses = $ccnCourseHandler->ccnGetExampleCoursesIds(8);
        // print_object($ccnCourses[0]->courseId);

        include($CFG->dirroot . '/theme/edumy/ccn/block_handler/specialization.php');
        if (empty($this->config)) {
          $this->config = new \stdClass();
          $this->config->title = 'Browse Our Top Courses';
          $this->config->subtitle = 'Cum doctus civibus efficiantur in imperdiet deterruisset.';
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
          $this->config->group = '1';

          $this->config->color_bg = 'rgb(244, 245, 247)';
          $this->config->color_title = 'rgb(32, 51, 103)';
          $this->config->color_subtitle = 'rgb(111, 112, 116)';
          $this->config->color_course_card = 'rgb(255, 255, 255)';
          $this->config->color_course_title = 'rgb(32, 51, 103)';
          $this->config->color_course_price = 'rgb(199, 85, 51)';
          $this->config->color_course_enrol_btn = '#79b530';

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
        if(!empty($this->config->button_text)){$this->content->button_text = $this->config->button_text;} else {$this->content->button_text = '';}
        if(!empty($this->config->button_link)){$this->content->button_link = $this->config->button_link;} else {$this->content->button_link = '';}
        if(!empty($this->config->hover_text)){$this->content->hover_text = $this->config->hover_text;} else {$this->content->hover_text = '';}
        if(!empty($this->config->hover_accent)){$this->content->hover_accent = $this->config->hover_accent;} else {$this->content->hover_accent = '';}
        if(!empty($this->config->description)){$this->content->description = $this->config->description;} else {$this->content->description = '0';}
        if(!empty($this->config->course_image)){$this->content->course_image = $this->config->course_image;} else {$this->content->course_image = '';}
        if(!empty($this->config->price)){$this->content->price = $this->config->price;} else {$this->content->price = '1';}
        if(!empty($this->config->enrol_btn)){$this->content->enrol_btn = $this->config->enrol_btn;} else {$this->content->enrol_btn = '0';}
        if(!empty($this->config->enrol_btn_text)){$this->content->enrol_btn_text = $this->config->enrol_btn_text;} else {$this->content->enrol_btn_text = '';}

        if(!empty($this->config->color_bg)){$this->content->color_bg = $this->config->color_bg;} else {$this->content->color_bg = 'rgb(244, 245, 247)';}
        if(!empty($this->config->color_title)){$this->content->color_title = $this->config->color_title;} else {$this->content->color_title = 'rgb(32, 51, 103)';}
        if(!empty($this->config->color_subtitle)){$this->content->color_subtitle = $this->config->color_subtitle;} else {$this->content->color_subtitle = 'rgb(111, 112, 116)';}
        if(!empty($this->config->color_course_card)){$this->content->color_course_card = $this->config->color_course_card;} else {$this->content->color_course_card = 'rgb(255, 255, 255)';}
        if(!empty($this->config->color_course_title)){$this->content->color_course_title = $this->config->color_course_title;} else {$this->content->color_course_title = 'rgb(32, 51, 103)';}
        if(!empty($this->config->color_course_price)){$this->content->color_course_price = $this->config->color_course_price;} else {$this->content->color_course_price = 'rgb(199, 85, 51)';}
        if(!empty($this->config->color_course_enrol_btn)){$this->content->color_course_enrol_btn = $this->config->color_course_enrol_btn;} else {$this->content->color_course_enrol_btn = '#79b530';}

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
        ) {
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
          (isset($this->content->price) && $this->content->price == '1') ||
          (isset($this->content->enrol_btn_text) && $this->content->enrol_btn == '1')
        ) {
          $ccnBlockShowBottomBar = 1;
          $topCoursesClass = 'ccnWithFoot';
        } else {
          $ccnBlockShowBottomBar = 0;
          $topCoursesClass = '';
        }

        if(!empty($this->config->group)){
          $filter = $this->config->group;
          $ccnClassMasonry_cont = 'ccn-masonry-grid-1';
          $ccnClassMasonry_opts = 'ccn-masonry-options';
          $ccnClassMasonry_grid = 'ccn-masonry-grid';
        } else {
          $filter = null;
          $ccnClassMasonry_cont = '';
          $ccnClassMasonry_opts = '';
          $ccnClassMasonry_grid = '';
        }
        if(!empty($this->config->courses)){
          $coursesArr = $this->config->courses;
          $courses = new stdClass();
          foreach ($coursesArr as $key => $course) {
            $courseObj = new stdClass();
            $courseObj->id = $course;
            $courseRecord = $DB->get_record('course', array('id' => $courseObj->id), 'category');
            $courseCategory = $DB->get_record('course_categories',array('id' => $courseRecord->category));
            $courseCategory = core_course_category::get($courseCategory->id);
            $courseObj->category = $courseCategory->id;
            $courseObj->category_name = $courseCategory->get_formatted_name();
            $courses->$course = $courseObj;
          }
          $categories = array();
          foreach ($courses as $key => $course) {
            $categories[$course->category] = $course->category_name;
          }
          $categories = array_unique($categories);
        }

        $this->content->text .= '
        <section id="our-top-courses" class="'.$ccnClassMasonry_cont.' our-courses ccn-courses-grid-block ccn-courses-grid-block-3" data-ccn-c="color_bg" data-ccn-co="bg" style="background-color: '.$this->content->color_bg.';">
          <div class="container">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <div class="main-title text-center">';
                if(!empty($this->content->title)){
                  $this->content->text .='<h3 data-ccn="title" data-ccn-c="color_title" data-ccn-co="content" style="color: '.$this->content->color_title.'; width:100%;" class="mt0">'. format_text($this->content->title, FORMAT_HTML, array('filter' => true)) .'</h3>';
                }
                
                 $this->content->text .=' <p data-ccn="subtitle" data-ccn-c="color_subtitle" data-ccn-co="content" class="subtitle_normal">
                 Perfeziona la tua formazione nell’ambito della facilitazione digitale: rafforza le tue  
                 <strong>competenze metodologiche</strong> e le tue conoscenze <strong>sulla Pubblica Amministrazione del futuro.</strong> </p>';
               
                $this->content->text .='</div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
         
              $this->content->text .='
                  <div class="two_card_center_soft_skill row '.$ccnClassMasonry_grid. ' id="'.$coursesContainerClass.'">';

        // $courses = self::get_featured_courses();
        // print_object($courses);
        // $courses = null;
      if(!empty($this->config->courses)){
        $chelper = new coursecat_helper();
        $total_courses = count($coursesArr);

        if($total_courses < 2) {
          $col_class = 'col-md-12';
        } else if($total_courses == 2) {
          $col_class = 'col-md-6';
        } else if($total_courses == 3) {
          $col_class = 'col-md-4';
        } else  {
          $col_class = 'col-md-6 col-lg-4 col-xl-3';
        }

        foreach ($courses as $course) {
          if ($DB->record_exists('course', array('id' => $course->id))) {

            $ccnCourseHandler = new ccnCourseHandler();
            $ccnCourse = $ccnCourseHandler->ccnGetCourseDetails($course->id);

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
            $ccnCourseDescription = $ccnCourseHandler->ccnGetCourseDescription($course->id, $maxlength);


            $this->content->text .='

            <div class="'.$col_class.' cat-'.$course->category.' card_soft_skill" >
							<div data-ccn-c="color_course_card" data-ccn-co="bg" style="background-color: '.$this->content->color_course_card.';" class="top_courses '.$topCoursesClass.'">';
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
                        <div class="cip_category">
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
                      <a href="'. $ccnCourse->url.'" class="btn btn-primary btn-lg" aria-label="Vai al corso '.  $ccnCourse->fullName.'">Vai al corso</a>
                    </div>';
                   }
                $this->content->text .='
							</div>
						</div>';
          }
        }
      }

        $this->content->text .='
 			</div>';

      if($filter == 1){
        $this->content->text .= '
</div>';
}
$this->content->text .='
        </div></div>';

      if(!empty($this->content->button_text) && !empty($this->content->button_link)){
      $this->content->text .='
      <div class="row">
      <div class="col-lg-6 offset-lg-3">
        <div class="courses_all_btn text-center">
          <a class="btn btn-transparent" data-ccn="button_text" href="'.format_text($this->content->button_link, FORMAT_HTML, array('filter' => true)).'">'.format_text($this->content->button_text, FORMAT_HTML, array('filter' => true)).'</a>
        </div>
      </div></div>'  ;
      }
$this->content->text .='

		</div>
	</section>

';

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
          return false;
    }

    public function has_config() {
        return false;
    }

    public function cron() {
        return true;
    }

    // public static function get_featured_courses() {
    //     global $DB;
    //
    //     $sql = 'SELECT c.id, c.shortname, c.fullname, fc.sortorder
    //               FROM {block_cocoon_course_grid_5} fc
    //               JOIN {course} c
    //                 ON (c.id = fc.courseid)
    //           ORDER BY sortorder';
    //     return $DB->get_records_sql($sql);
    // }

    // public static function delete_cocoon_featuredcourse($courseid) {
    //     global $DB;
    //     return $DB->delete_records('block_cocoon_course_grid_5', array('courseid' => $courseid));
    // }
}
