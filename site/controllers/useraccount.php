<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
class AwardpackageControllerUseraccount extends AwardpackageController
{
	function __construct(){
		parent::__construct();
	}
	 
	function edit(){
		JRequest::setVar('view','Address');
		JRequest::setVar('layout','edit');		
		parent::display();		
	}
	
	function save(){		
		$user 	= JFactory::getUser();		
		$model 	= JModelLegacy::getInstance('address','AwardpackageModel');		
		$data 	= JRequest::getVar('jform');		
		if($model->addItem($data)){				
			$msg = JTEXT::_('Data Saved');		
		}
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=welcome'),$msg);
	}
		
}
