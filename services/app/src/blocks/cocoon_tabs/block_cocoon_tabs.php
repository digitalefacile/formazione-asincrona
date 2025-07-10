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
require_once($CFG->dirroot . '/theme/edumy/ccn/block_handler/ccn_block_handler.php');

class block_cocoon_tabs extends block_base
{

    /**
     * Start block instance.
     */
    function init()
    {
        $this->title = get_string('pluginname', 'block_cocoon_tabs');
    }

    /**
     * The block is usable in all pages
     */
    function applicable_formats()
    {
        $ccnBlockHandler = new ccnBlockHandler();
        return $ccnBlockHandler->ccnGetBlockApplicability(array('all'));
    }


    /**
     * Customize the block title dynamically.
     */
    function specialization()
    {

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
    function instance_allow_multiple()
    {
        return true;
    }

    /**
     * Build the block content.
     */
    function get_content()
    {
        global $CFG, $PAGE, $DB, $USER;

        require_once($CFG->libdir . '/filelib.php');


        if ($this->content !== NULL) {
            return $this->content;
        }


        if (!empty($this->config) && is_object($this->config)) {
            $this->content = new \stdClass();
            if (!empty($this->config->title)) {
                $this->content->title = $this->config->title;
            }
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
            if (!empty($this->config->title)) {
                $text .= '  <h4 data-ccn="title">' . format_text($this->content->title, FORMAT_HTML, array('filter' => true)) . '</h4>';
            }
            $text .= '
                <div class="ui_kit_tab mt30">
                  <ul class="nav nav-tabs" aria-label="Elenco altre informazioni sul percorso formativo" id="myTab" style="    display: flex;
                  justify-content: center;    background-color: transparent; border-bottom:none;" role="tablist">';
            for ($i = 1; $i <= $data->slidesnumber; $i++) {
                $ccnTabTitle = 'title' . $i;
                $ccnTabLink = 'tab-' . $this->instance->id . $i;
                $ccnAriaSelected = 'false';
                $ccnClass = 'nav-link';

                if ($i == 1) {
                    $ccnAriaSelected = 'true';
                    $ccnClass .= ' active';
                }
                $text .= '<li class="nav-item">
                                <a data-ccn="' . $ccnTabTitle . '" aria-label="Naviga alla sezione '  . format_text($data->$ccnTabTitle, FORMAT_HTML, array('filter' => true)) .  '" class="' . $ccnClass . '" id="' . $ccnTabLink . '-tab" data-toggle="tab" href="#' . $ccnTabLink . '" role="tab" aria-controls="' . $ccnTabLink . '" aria-selected="true">' . format_text($data->$ccnTabTitle, FORMAT_HTML, array('filter' => true)) . '</a>
                              </li>';
            }
            $text .= '
                    </ul>
                    <div class="tab-content" id="myTabContent">';
            for ($i = 1; $i <= $data->slidesnumber; $i++) {
                $ccnTabBody = 'text' . $i;
                $ccnTabLink = 'tab-' . $this->instance->id . $i;
                $ccnBodyClass = 'tab-pane fade';
                $titleOfAccordion = '';
                $altText = '';

                $inputButton = '';

                if ($ccnTabBody == 'text1') {
                    $titleOfAccordion = 'Come completare la tua formazione';
                    //$altText = 'Fotografia di una postazione di studio con un computer e un blocco appunti';
                    $style = 'style="
                                       font-size: 16px;
                                       font-style: normal;
                                       font-weight: 700;
                                       line-height: 24px;"';
                    $inputButton = '
                                       <div class="find-out-more">
                                       <a id="scopriDipiùTab"  aria-label="Scopri di più" class="text_3_tabs" >Scopri di più 
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="margin-left: 5px;"viewBox="0 0 24 24" fill="none">
                                       <path d="M13.9 5L13.2 5.7L18.5 11.1H3V12.1H18.5L13.2 17.5L13.9 18.2L20.5 11.6L13.9 5Z" fill="#0066CC"/>
                                     </svg>
                                     </a>
                                   </div> ';
                } else if ($ccnTabBody == 'text2') {
                    $titleOfAccordion = 'Consulta la bacheca annunci';
                    //$altText = 'Fotografia di fogli e matita per appunti ';
                    $inputButton = '
                        <div class="find-out-more text_2_button_align">
                          <a  href="./blog"  aria-label="Entra nel forum" class="btn btn-primary text_2_tabs">Vai alla bacheca</a>
                        </div>';
                } else if ($ccnTabBody == 'text3') {
                    $titleOfAccordion = 'Conosci la Certificazione DigComp User?';
                    //$altText = 'Fotografia di persone che stanno seguendo un corso di formazione per una certificazione';
                    $inputButton = '
                        <div class="find-out-more">
                        <a href="./blocks/cocoon_tabs/BROCHURE-CERTIFICAZIONE-DIGCOMP_v2.pdf" download="BROCHURE.CERTIFICAZIONE.DIGCOMP.OK.pdf" aria-label="Apri il pdf DigComp User " class="text_3_tabs" >Scopri DigComp User - in pdf 
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" style="margin-left: 5px;" height="24" viewBox="0 0 24 24" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7 2H6.5C5.67157 2 5 2.67157 5 3.5V16H6V3.5C6 3.22386 6.22386 3 6.5 3H14V5.5C14 6.32843 14.6716 7 15.5 7H18V20.5C18 20.7761 17.7761 21 17.5 21H16V22H17.5C18.3284 22 19 21.3284 19 20.5V6.3L14.7 2ZM15 3.7L17.3 6H15.5C15.2239 6 15 5.77614 15 5.5V3.7ZM4.95717 20.5441H4.08036V22H3V17H4.95717C6.22019 17 6.85171 17.5784 6.85171 18.7353C6.85171 19.3186 6.68991 19.7672 6.36633 20.0809C6.04796 20.3897 5.57824 20.5441 4.95717 20.5441ZM4.08036 19.6765H4.94934C5.48691 19.6765 5.75569 19.3627 5.75569 18.7353C5.75569 18.4265 5.69045 18.2059 5.55998 18.0735C5.4295 17.9363 5.22595 17.8676 4.94934 17.8676H4.08036V19.6765ZM9.40281 22H7.61005V17H9.40281C9.8621 17 10.2405 17.0466 10.538 17.1397C10.8355 17.2279 11.0651 17.3775 11.2269 17.5882C11.3939 17.7941 11.5087 18.0392 11.5714 18.3235C11.634 18.6029 11.6653 18.9681 11.6653 19.4191C11.6653 19.8701 11.6366 20.2451 11.5792 20.5441C11.5218 20.8382 11.4122 21.1029 11.2504 21.3382C11.0938 21.5686 10.8642 21.7377 10.5615 21.8456C10.2587 21.9485 9.87253 22 9.40281 22ZM10.538 20.0147C10.5484 19.8578 10.5536 19.6422 10.5536 19.3676C10.5536 19.0882 10.5432 18.8652 10.5223 18.6985C10.5014 18.5319 10.4519 18.3799 10.3736 18.2426C10.2953 18.1054 10.1779 18.0123 10.0213 17.9632C9.86993 17.9093 9.66377 17.8824 9.40281 17.8824H8.6904V21.1176H9.40281C9.79425 21.1176 10.0787 21.0245 10.2561 20.8382C10.4075 20.6863 10.5014 20.4118 10.538 20.0147ZM12.6024 17V22H13.6827V20.2353H15.5773V19.3529H13.6827V17.8824H16V17H12.6024Z" />
                        </svg>
                      </a>
                    </div> ';
                }

                if ($ccnTabBody == 'text4') {
                    $titleOfAccordion = 'Impara a usare Facilita con le video lezioni';
                    $inputButton = '
                    <div class="find-out-more">
                        <a href="./facilita-redirect.php" id="scopriDipiùTab"  aria-label="Scopri di più" class="text_3_tabs" >Vai ai tutorial 
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="margin-left: 5px;"viewBox="0 0 24 24" fill="none">
                            <path d="M13.9 5L13.2 5.7L18.5 11.1H3V12.1H18.5L13.2 17.5L13.9 18.2L20.5 11.6L13.9 5Z" fill="#0066CC"/>
                            </svg>
                        </a>
                    </div> ';
                }

                if ($i == 1) {
                    $ccnBodyClass .= ' show active';
                }

                $standardTabText = $data->$ccnTabBody['text']; 
                // get user role shortname
                $userid = $USER->id;
                $roleid = $DB->get_field('role_assignments', 'roleid', array('userid' => $userid));
                if ($roleid) {
                    $rolename = $DB->get_field('role', 'shortname', array('id' => $roleid));
                    // var_dump($rolename);
                    // if rolename == std, title is placeholder, inputbutton is empty
                    if ($rolename && $rolename == 'std') {
                        $titleOfAccordion = 'Lorem Ipsum';
                        $inputButton = '';
                        $standardTabText = 'Standard tab text for STD users.';
                    }
                }

                $text .=
                    '<div data-ccn="'.$ccnTabBody.'" class="'.$ccnBodyClass.'" id="'.$ccnTabLink.'" role="tabpanel" aria-labelledby="'.$ccnTabLink.'-tab">
                      <div class="single-card single_card_tabs" >
                      <div class="col-12 col-md-6 p-0">
                        <div class="thumb h-100">
                          <img class="img_tabs" data-png="img-tab" src="./blocks/cocoon_tabs/'.$ccnTabBody.'.png" 
                          alt="' . $altText . '">
                        </div>
                      </div>
                      <div class="text-container col-12 col-md-6 text_container_tabs" >
                        <div class="text-content">
                          <div class="title">' . $titleOfAccordion . ' </div>
                          <div class="description">
                            ' . format_text($standardTabText, FORMAT_HTML, array('filter' => true, 'noclean' => true)) . '
                          </div>
                        </div>
                              ' . $inputButton . '
                      </div>
                    
                      </div>
                      </div>';
            }
            $text .= '
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
    public function instance_can_be_docked()
    {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }

    public function html_attributes()
    {
        global $CFG;
        $attributes = parent::html_attributes();
        include($CFG->dirroot . '/theme/edumy/ccn/block_handler/attributes.php');
        return $attributes;
    }

}
