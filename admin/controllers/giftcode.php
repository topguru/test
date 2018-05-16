<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
/**
 * General Controller of Donation component
 */
class AwardpackageControllerGiftcode extends JControllerLegacy
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function __construct(){		
		parent::__construct();	
	}
	 
	function display($cachable = false) 
	{		
		JRequest::setVar('view', JRequest::getCmd('view', 'Donationlist'));
		parent::display($cachable);
	}
	
	function edit(){
		
		$model =& JModelLegacy::getInstance('giftcode','AwardpackageModel');
		
		$cids = JRequest::getVar('cid');
		
		foreach($cids as $cid){
			//set redirect page if editing success
			if( $model->edit($cid)){
				$link ='index.php?option=com_awardpackage&view=giftcode&package_id='.JRequest::getVar('package_id');
			}
		}
		
		$this->setRedirect($link);
	}
	
	function save(){
		
		$i=0;
		
		$cid = JRequest::getVar('cid');
	
		$name = JRequest::getVar("name");
		
		$color_code = JRequest::getVar("color");
			
		foreach ($cid as $id) {
			
			$model =& JModelLegacy::getInstance('giftcode','AwardpackageModel');
			
			$update = $model->save($id, $name[$i], $color_code[$i]);
			
			$i++;
			
		}
		
		$msg = "Category has been saved and unlocked";
		
		$this->setRedirect('index.php?option=com_awardpackage&view=giftcode&package_id='.JRequest::getVar('package_id'), $msg);	
	}
	
	function publish() {
		
		$cid = JRequest::getVar("cid");
		
		switch($cid) {
		
		    case 0 :
			$msg = "No Category Published";
			break;
		    default :
			$msg = count($cid)." Categories Published";
			break;            
		}
		
		foreach ($cid as $id) {
			
		    $model =& JModelLegacy::getInstance('giftcode','AwardpackageModel');
		    
		    $update = $model->published($id);
		}
		
		$this->setRedirect('index.php?option=com_awardpackage&view=giftcode&package_id='.JRequest::getVar('package_id'), $msg);		
	}

	function unpublish() {
	    
	    $cid = JRequest::getVar("cid");
	    
	    switch($cid) {
		
		case 0 :
		    $msg = "No Category Unpublished";
		    break;
		default :
		    $msg = count($cid)." Categories Unpublished";
		    break;            
	    }
	    
	    foreach ($cid as $id) {
		$model =& JModelLegacy::getInstance('giftcode','AwardpackageModel');
		
		$update = $model->unpublished($id);
	    }
	    
	    $this->setRedirect('index.php?option=com_awardpackage&view=giftcode&package_id='.JRequest::getVar('package_id'), $msg);		
	}
	
	public function save_schedule(){
		 $model =& JModelLegacy::getInstance('giftcode','AwardpackageModel');
		 $days = array("sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday");
		 $package_id = JRequest::getVar('package_id');
		 $category_id = JRequest::getVar('category_id');
		 foreach($days as $day){
		 	$scheduleDay = JRequest::getVar($day);
			if($scheduleDay){
				$active=1;
			}else{
				$active=0;
			}
		 	$check = $model->checkSchedule($package_id,$category_id);
			if($check){ // update the row
				$save=$model->updateSchedule($package_id,$category_id,$day,$active);
			}else{ //insert 
				$save=$model->insertSchedule($package_id,$category_id,$day,$active);
			}
		 }
		 if($save){
		 	$link = 'index.php?option=com_awardpackage&view=award_category&layout=free&package_id='.$package_id;
			$msg  = 'Schedule has been saved';
			$this->setRedirect($link,$msg);
		 }
	}
	
	public function cancel_schedule(){
		 $package_id = JRequest::getVar('package_id');
		 $link = 'index.php?option=com_awardpackage&view=award_category&layout=free&package_id='.$package_id;
		 $this->setRedirect($link,$msg);
	}	
}
