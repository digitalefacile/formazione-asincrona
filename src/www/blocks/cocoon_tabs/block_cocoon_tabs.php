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
require_once($CFG->dirroot. '/theme/edumy/ccn/block_handler/ccn_block_handler.php');
class block_cocoon_tabs extends block_base {

    /**
     * Start block instance.
     */
    function init() {
        $this->title = get_string('pluginname', 'block_cocoon_tabs');
    }

    /**
     * The block is usable in all pages
     */
     function applicable_formats() {
       $ccnBlockHandler = new ccnBlockHandler();
       return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
     }


    /**
     * Customize the block title dynamically.
     */
    function specialization() {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edumy/ccn/block_handler/specialization.php');
        if (empty($this->config)) {
          $this->config = new \stdClass();
          $this->config->slidesnumber = '3';
          $this->config->title = 'Frequently Asked Questions';
          $this->config->title1 = 'Percorso formativo';
          $this->config->title2 = 'Forum annunci';
          $this->config->title3 = 'Certificazioni Accredia';
          $this->config->text1['text'] = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.';
          $this->config->text2['text'] = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.';
          $this->config->text3['text'] = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.';
        }

    }

    /**
     * The block can be used repeatedly in a page.
     */
    function instance_allow_multiple() {
        return true;
    }

    /**
     * Build the block content.
     */
    function get_content() {
        global $CFG, $PAGE;

        require_once($CFG->libdir . '/filelib.php');


        if ($this->content !== NULL) {
            return $this->content;
        }


        if (!empty($this->config) && is_object($this->config)) {
            $this->content = new \stdClass();
            if(!empty($this->config->title)){$this->content->title = $this->config->title;}
            $data = $this->config;
            $data->slidesnumber = is_numeric($data->slidesnumber) ? (int)$data->slidesnumber : 3;
        } else {
            $data = new stdClass();
            $data->slidesnumber = '3';
        }

        $text = '';

        if ($data->slidesnumber > 0) {
            $text .= '
            <div class="shortcode_widget_tab">';
              if(!empty($this->config->title)){
                $text .='  <h4 data-ccn="title">'.format_text($this->content->title, FORMAT_HTML, array('filter' => true)).'</h4>';
              }
            $text .='
                <div class="ui_kit_tab mt30">
                  <ul class="nav nav-tabs" id="myTab" style="    display: flex;
                  justify-content: center;    background-color: transparent; border-bottom:none;" role="tablist">';
                  for ($i = 1; $i <= $data->slidesnumber; $i++) {
                    $ccnTabTitle = 'title' . $i;
                    $ccnTabLink = 'tab-'. $this->instance->id . $i;
                    $ccnAriaSelected = 'false';
                    $ccnClass = 'nav-link';
                
                    if($i == 1){
                      $ccnAriaSelected = 'true';
                      $ccnClass .= ' active';
                    }
                    $text .= '<li class="nav-item">
                                <a data-ccn="'.$ccnTabTitle.'" class="'.$ccnClass.'" id="'.$ccnTabLink.'-tab" data-toggle="tab" href="#'.$ccnTabLink.'" role="tab" aria-controls="'.$ccnTabLink.'" aria-selected="true">'.format_text($data->$ccnTabTitle, FORMAT_HTML, array('filter' => true)).'</a>
                              </li>';
                  }
                 $text .='
                    </ul>
                    <div class="tab-content" id="myTabContent">';
                    for ($i = 1; $i <= $data->slidesnumber; $i++) {
                      $ccnTabBody = 'text' . $i;
                      $ccnTabLink = 'tab-'. $this->instance->id . $i;
                      $ccnBodyClass = 'tab-pane fade';
                      $titleOfAccordion='';
                     ;
                      $inputButton='';
              
                      if( $ccnTabBody =='text1'){
                        $titleOfAccordion='Come completare la tua formazione';
                        $style='style="
                                       font-size: 16px;
                                       font-style: normal;
                                       font-weight: 700;
                                       line-height: 24px;"';
                        $inputButton='';
                      }else if( $ccnTabBody =='text2'){
                        $titleOfAccordion='Consulta il forum annunci';
                        $inputButton='
                        <div class="find-out-more text_2_button_align">
                          <a  href="./blog"  aria-label="Entra nel forum" class="btn btn-primary text_2_tabs">Entra nel forum</a>
                        </div>';
                      }else if ($ccnTabBody =='text3'){
                        $titleOfAccordion='Conosci la Certificazione DigComp User?';                  
                        $inputButton='
                        <div class="find-out-more" >
                          <a href="https://www.accredia.it/servizi-accreditati/certificazioni/" aria-label="Scopri DigComp User - in pdf " class="text_3_tabs" target="_blank" >Scopri DigComp User - in pdf 
                          <svg xmlns="http://www.w3.org/2000/svg" style="margin-left: 5px;" width="24" height="24" viewBox="0 0 24 24" fill="none">
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M19 4H13.5C13.2239 4 13 4.22386 13 4.5C13 4.77614 13.2239 5 13.5 5H18.1996L9.83073 13.3689C9.63547 13.5642 9.63547 13.8808 9.83073 14.076C10.026 14.2713 10.3426 14.2713 10.5378 14.076L19 5.61385V10.5C19 10.7761 19.2239 11 19.5 11C19.7761 11 20 10.7761 20 10.5V5C20 4.44772 19.5523 4 19 4ZM17.01 12.5C17.01 12.2239 17.2339 12 17.51 12C17.7784 12.0053 17.9947 12.2216 18 12.49V18C18 19.6569 16.6569 21 15 21H6C4.34315 21 3 19.6569 3 18V9C3 7.34315 4.34315 6 6 6H11C11.2739 6.00532 11.4947 6.22609 11.5 6.5C11.5 6.77614 11.2761 7 11 7H6C4.89543 7 4 7.89543 4 9V18C4 19.1046 4.89543 20 6 20H15.01C16.1146 20 17.01 19.1046 17.01 18V12.5Z" fill="#0066CC"/>
                          </svg>
                          </a>
                        </div> ';
                      }
                      if($i == 1){
                        $ccnBodyClass .= ' show active';
                      }
                 
                      $text .=
                      '<div data-ccn="'.$ccnTabBody.'" class="'.$ccnBodyClass.'" id="'.$ccnTabLink.'" role="tabpanel" aria-labelledby="'.$ccnTabLink.'-tab">
                      <div class="single-card single_card_tabs" >
                      <div class="col-12 col-md-6 p-0">
                        <div class="thumb h-100">
                          <img class="img_tabs" data-png="img-tab" src="./blocks/cocoon_tabs/'.$ccnTabBody.'.png" alt="Cosa fare in caso di incendio?">
                        </div>
                      </div>
                      <div class="text-container col-12 col-md-6 text_container_tabs" >
                        <div class="text-content">
                          <div class="title">'.$titleOfAccordion.' </div>
                          <div class="description">
                            '.format_text($data->$ccnTabBody['text'], FORMAT_HTML, array('filter' => true, 'noclean' => true)).'
                          </div>
                        </div>
                              '. $inputButton .'
                      </div>
                    
                      </div>
                      </div>';
                    }
              $text .='
                  </div>
                </div>
              </div>';
        }

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = $text;

        return $this->content;

  }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }
    public function html_attributes() {
      global $CFG;
      $attributes = parent::html_attributes();
      include($CFG->dirroot . '/theme/edumy/ccn/block_handler/attributes.php');
      return $attributes;
    }

}
