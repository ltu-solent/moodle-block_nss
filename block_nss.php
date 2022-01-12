<?php
class block_nss extends block_base {
    public function init() {
        $this->title = get_string('nss', 'block_nss');
    }

    function has_config() {
      return true;
    }

    public function hide_header() {
      return true;
    }

    public function get_content() {
        if ($this->content !== null) {
          return $this->content;
        }

        global $USER, $DB;
        $this->content = new stdClass;

        //Check date and which banner should be shown
        $time = new DateTime("now", core_date::get_user_timezone_object());
        $now = $time->getTimestamp();

        if(($now > get_config('block_nss', 'displayfrom')) && ($now < get_config('block_nss', 'displayto'))){
            // Display banner including tracking
            if($DB->record_exists_sql('SELECT studentid FROM {nss} WHERE studentid = ?', array($USER->idnumber))){
                $this->content->text = '<a href="https://www.thestudentsurvey.com/" target="_blank" ' .
                'onClick="gtag(\'event\', \'click\', { \'event_category\': \'Survey Banner\', \'event_action\': \'Click\', \'event_label\': \'NSS\'});">' .
                '<img src="/blocks/nss/images/nss.png" alt="Final year students: we want to know how you\'ve found your time at Solent - ' .
                'complete the Nation Student Survey by Saturday 30th April and you could win a £300 Currys PC World voucher or one of ten £25 Amazon vouchers."' .
                ' class="img_nss"></a>';
            }
        }else{
            $this->content->text = '';
        }

        return $this->content;
    }
}
