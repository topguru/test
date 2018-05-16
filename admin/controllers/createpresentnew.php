<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class AwardpackageControllerCreatepresentnew extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}
	
	function show_create_presentation(){
		$view = $this->getView('createpresentnew', 'html');		
		$view->assign('action', 'show_distribute_prize');
		$view->display();
	}
	
	function create(){
		$prize_id = JRequest::getVar('radio_prize');
		$symbol_id = JRequest::getVar('radio_symbol');
		$model =& JModelLegacy::getInstance('Createpresentationnew','AwardPackageModel');
		if($model->save_data($prize_id, $symbol_id)) {
			$this->setRedirect('index.php?option=com_awardpackage&view=createpresentnew&task=createpresentnew.show_create_presentation&presentation_id='.JRequest::getVar('presentation_id').'&package_id='.JRequest::getVar('package_id'),JTEXT::_('Data saved'));
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=createpresentnew&task=createpresentnew.show_create_presentation&package_id='.JRequest::getVar('package_id'),JTEXT::_('Error when save data'));
		}
	}
	
	function create_presentation(){
		$package_id = JRequest::getVar('package_id');
		$model =& JModelLegacy::getInstance('Createpresentationnew','AwardPackageModel');
		$presentation_id = $model->insert_new_presentation($package_id); 
		$this->setRedirect('index.php?option=com_awardpackage&view=createpresentnew&task=createpresentnew.show_create_presentation&presentation_id='.$presentation_id.'&package_id='.JRequest::getVar('package_id'),JTEXT::_('Data saved'));		 
	}
	
	function delete(){		
		$data = JRequest::getVar('cid');
		$model =& JModelLegacy::getInstance('Createpresentationnew','AwardPackageModel');
		foreach ($data as $id) {			
			$model->delete_data($id);
		}
		$this->setRedirect('index.php?option=com_awardpackage&view=createpresentnew&task=createpresentnew.show_create_presentation&package_id='.JRequest::getVar('package_id'),JTEXT::_('Data deleted'));		
	}
	
}