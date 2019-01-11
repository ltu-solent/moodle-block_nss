<?php
class block_nss extends block_base {
    public function init() {
        $this->title = get_string('nss', 'block_nss');
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

    $result = $DB->get_record_sql('SELECT nss FROM {nss} WHERE studentid = ?', array($USER->idnumber));
													 
	//Check date and which banner should be shown
	$time = new DateTime("now", core_date::get_user_timezone_object());
	$now = $time->getTimestamp();
	$displaynss = 0;
	$displayyourcourse = 0;
	
	//if(($now > 1546300800) && ($now < 1556668800)){ // NSS 01/01/19 - 30/04/19 TEST
	if(($now > 1548028800) && ($now < 1556668800)){ // NSS 21/01/19 - 30/04/19	
		$displaynss = 1; //nss	
	}
	
	//if(($now > 1546300800) && ($now < 1556668800)){ // Yourcourse 01/01/19 - 30/04/19 TEST
	if(($now > 1548979200) && ($now < 1556668800)){ // Yourcourse 01/02/19 - 30/04/19	
		$displayyourcourse = 1; //yourcourse
	}

// Display banner including tracking 
	if(isset($result->nss) && $result->nss == 1 && $displaynss == 1){
		$this->content->text = '<a href="http://www.thestudentsurvey.com/" target="_blank"  onClick="gtag(\'event\', \'click\', { event_category: \'Survey Banner\', event_action: \'Click\', event_label: \'NSS\'});"><img src="/blocks/nss/images/nss.png" alt="NSS banner"></a>';
	}else if(isset($result->nss) &&  $result->nss == 0 && $displayyourcourse == 1){
		$this->content->text = '<a href="http://learn.solent.ac.uk/yourcourse" target="_blank"><img src="/blocks/nss/images/yourcourse.png" alt="YourCourse banner" onClick="gtag(\'event\', \'click\', { event_category: \'Survey Banner\', event_action: \'Click\', event_label: \'YourCourse\'});"></a>';
	}else{
		$this->content->text = '';
	}

		return $this->content;
	}
}
