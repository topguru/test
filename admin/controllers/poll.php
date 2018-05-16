<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
class AwardpackageControllerPoll extends JControllerLegacy
{

	function __construct(){
		parent::__construct();	
	}

	function save() 
	{
		$poll_model =& JModelLegacy::getInstance('Category','AwardpackageModel');
		$ids = JRequest::getVar('cid');
		foreach($ids as $id){
			$price = JRequest::getVar('poll_price_'.$id);
			$save = $poll_model->save_price($price,$id,'poll');
		}	 
		if($save){
			$this->setRedirect('index.php?option=com_awardpackage&view=poll&package_id='.JRequest::getVar('package_id'),JTEXT::_('Data has updated'));
		}
	}
	
	function publish(){
	
		$model =& JModelLegacy::getInstance('donation','AwardpackageModel');
		
		$model->update_category(JRequest::getVar('cid'),'publish');
		
		$this->setRedirect('index.php?option=com_awardpackage&view=poll&package_id='.JRequest::getVar('package_id'),JTEXT::_(PUBLISHED));	
	}
	
	function unpublish(){ 
		
		$model =& JModelLegacy::getInstance('donation','AwardpackageModel');
		
		$model->update_category(JRequest::getVar('cid'),'unpublish');
		
		$this->setRedirect('index.php?option=com_awardpackage&view=poll&package_id='.JRequest::getVar('package_id'),JTEXT::_(UNPUBLISHED));	
	}
}
