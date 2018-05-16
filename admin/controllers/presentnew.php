<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class AwardpackageControllerPresentnew extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}
	
	function show_presentation(){
		$view = $this->getView('presentnew', 'html');		
		$view->assign('action', 'show_presentation');
		$view->display();
	}
	
	function add_presentation_user_group(){
		$package_id = JRequest::getVar('package_id');
		$model = & JModelLegacy::getInstance( 'createpresentationnew', 'AwardpackageModel' );
		if($model->insert_new_usergroups($package_id)) {
			$this->setRedirect('index.php?option=com_awardpackage&view=presentnew&task=presentnew.show_presentation&package_id='.$package_id,JTEXT::_('New user group has been saved')); 
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=presentnew&task=presentnew.show_presentation&package_id='.$package_id,JTEXT::_('Error when save user groups data'));
		}	
	}	
	
	function add_presentation(){
		$package_id = JRequest::getVar('package_id');
		$model = & JModelLegacy::getInstance( 'createpresentationnew', 'AwardpackageModel' );
		if($model->insert_new_presentation($package_id)) {
			$this->setRedirect('index.php?option=com_awardpackage&view=presentnew&task=presentnew.show_presentation&package_id='.$package_id,JTEXT::_('New presentation has been saved')); 
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=presentnew&task=presentnew.show_presentation&package_id='.$package_id,JTEXT::_('Error when save presentation data'));
		}
	}
}
