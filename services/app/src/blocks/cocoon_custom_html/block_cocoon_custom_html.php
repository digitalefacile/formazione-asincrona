<?php
global $CFG;
require_once($CFG->dirroot. '/theme/edumy/ccn/block_handler/ccn_block_handler.php');
class block_cocoon_custom_html extends block_base
{
    // Declare first
    public function init()
    {
        $this->title = get_string('cocoon_custom_html', 'block_cocoon_custom_html');

    }

    // Declare second
    public function specialization()
    {
        // $this->title = isset($this->config->title) ? format_string($this->config->title) : '';
        global $CFG;
        include($CFG->dirroot . '/theme/edumy/ccn/block_handler/specialization.php');

    }

    function applicable_formats() {
      $ccnBlockHandler = new ccnBlockHandler();
      return $ccnBlockHandler->ccnGetBlockApplicability(array('trulyAll'));
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function get_content()
    {  
      $div="display: flex;
      justify-content: center;";
      $title="    max-width: 654px;
      height: 66px;
      color: #FFF;
      text-align: center;
      font-family: Titillium Web;
      font-size: 32px;
      font-style: normal;
      font-weight: 700;
      line-height: 32px;";
      $paragraph="    color: #FFF;
      text-align: center;
      font-family: Titillium Web;
      font-size: 24px;
      font-style: normal;
      font-weight: 400;
      line-height: 32px;
      max-width: 780px;
      ";
      $strong="color: #FFF;
      font-family: Titillium Web;
      font-size: 24px;
      font-style: normal;
      font-weight: 700!important;
      line-height: 32px!important;";
      $customLink="border-radius: 4px;
      border: 2px solid #0065CC;
      width: 291px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      color: #FFF;
      font-weight: bold;
      font-size: 18px;
      font-style: normal;
      font-weight: 700;
      line-height: 28px;
      ";
      $this->content->text .= '
      <div class="container-fluid container_custom_block">
        <div style="'.$div.'">
          <h3 style="'.$title.'">Le aree tematiche dei corsi</h3>
        </div>
        <div style="'.$div.'">
          <p  style="'.$paragraph.'">
          Perfeziona le tue competenze nelle 
          <strong style="'.$strong.'"> 7 aree tematiche disponibili</strong>: <strong style="'.$strong.'"> 5 aree di competenza digitale </strong> riferite al quadro europeo DigComp 2.2, un’<strong style="'.$strong.'">area di competenze metodologiche </strong> e un’<strong style="'.$strong.'">area di competenze digitali per la PA</strong>. 
          </p>
        </div>
        <div style="'.$div.'margin-top: 14px;">
        <a  aria-label="Consulta DigComp 2.2 in PDF" href="https://repubblicadigitale.innovazione.gov.it/assets/docs/DigComp-2_2-Italiano-marzo.pdf" style="'.$customLink.'"target="_blank">
        Consulta DigComp 2.2 in PDF
        <svg xmlns="http://www.w3.org/2000/svg" style="    margin-left: 4px;
        margin-bottom: 5px;" width="25" height="24" viewBox="0 0 25 24" fill="none" >
            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.5 4H14C13.7239 4 13.5 4.22386 13.5 4.5C13.5 4.77614 13.7239 5 14 5H18.6996L10.3307 13.3689C10.1355 13.5642 10.1355 13.8808 10.3307 14.076C10.526 14.2713 10.8426 14.2713 11.0378 14.076L19.5 5.61385V10.5C19.5 10.7761 19.7239 11 20 11C20.2761 11 20.5 10.7761 20.5 10.5V5C20.5 4.44772 20.0523 4 19.5 4ZM17.51 12.5C17.51 12.2239 17.7339 12 18.01 12C18.2784 12.0053 18.4947 12.2216 18.5 12.49V18C18.5 19.6569 17.1569 21 15.5 21H6.5C4.84315 21 3.5 19.6569 3.5 18V9C3.5 7.34315 4.84315 6 6.5 6H11.5C11.7739 6.00532 11.9947 6.22609 12 6.5C12 6.77614 11.7761 7 11.5 7H6.5C5.39543 7 4.5 7.89543 4.5 9V18C4.5 19.1046 5.39543 20 6.5 20H15.51C16.6146 20 17.51 19.1046 17.51 18V12.5Z" fill="#FFF"/>
        </svg>
        
        </a>
   

        </div>
      </div>';
       
        return $this->content;
    }
    public function html_attributes() {
      global $CFG;
      $attributes = parent::html_attributes();
      include($CFG->dirroot . '/theme/edumy/ccn/block_handler/attributes.php');
      return $attributes;
    }
}
