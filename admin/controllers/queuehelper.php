<?php 
//resdirect
defined('_JEXEC') or die('Restricted access');

//import library
jimport('joomla.application.component.controller');

class AwardPackageControllerQueueHelper extends JControllerLegacy {

	function __construct($config = array()) {
        parent::__construct($config);
        $this->package_id = JRequest::getInt('package_id');
		$this->presentation_id = JRequest::getInt('presentation_id');
    }
	
	public function close(){
		$this->setRedirect('index.php?option=com_awardpackage&view=processsymbol&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id);
	}
}
?>