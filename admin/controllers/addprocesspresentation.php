<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class AwardpackageControlleraddprocesspresentation extends JControllerLegacy {

	function __construct(){
		parent::__construct();
	}

	public function get_addprocesspresentation(){	
		$view = $this->getView('addprocesspresentation', 'html');
		$view->assign('action', 'list');
		$view->display();
	}
	
	public function create_update(){
		$view = $this->getView('addprocesspresentation', 'html');		
		$view->assign('action', 'create');
		$view->display('create');
	}
	
	public function save_create(){
		$package_id = JRequest::getVar('package_id');
		$sname = JRequest::getVar('sname');
		$description = JRequest::getVar('description');
		$value_from = JRequest::getVar('value_from');
		$process_id = JRequest::getVar('process_id');
		$model = & JModelLegacy::getInstance( 'addprocesspresentation', 'AwardpackageModel' );
		if($model->save_update_category($package_id, $sname,$description,$value_from,$id)) {
		     
			$this->setRedirect('index.php?option=com_awardpackage&aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar('package_id').'&idProcess='.JRequest::getVar('sname').'&idProcessValue='.JRequest::getVar('value_from').'&command=1'.'&process_id='.$process_id, JText::_('MSG_SUCCESS'));
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=addprocesspresentation&task=addprocesspresentation.get_addprocesspresentation&package_id='.JRequest::getVar('package_id'), JText::_('Error'));
		}
	}
	
	public function publish_list(){
		$return = $this->change_state(1);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=addprocesspresentation&task=addprocesspresentation.get_addprocesspresentation&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function unpublish_list(){
		$return = $this->change_state(0);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=addprocesspresentation&task=addprocesspresentation.get_addprocesspresentation&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function delete_addprocesspresentation(){
		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		if(!empty($ids['cid'])){
			$model = & JModelLegacy::getInstance( 'addprocesspresentation', 'AwardpackageModel' );
			JArrayHelper::toInteger($ids['cid']);
			if($model->delete_categories($ids['cid'])){
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=addprocesspresentation&task=addprocesspresentation.get_addprocesspresentation&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_SUCCESS'));
			} else {
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=addprocesspresentation&task=addprocesspresentation.get_addprocesspresentation&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_ERROR'));
			}
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=addprocesspresentation&task=addprocesspresentation.get_addprocesspresentation&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_NO_ITEM_SELECTED'));
		}
	}
	
	public function add_addprocesspresentation(){
		$this->setRedirect('index.php?option=com_awardpackage&view=addprocesspresentation&task=addprocesspresentation.create_update&package_id='.JRequest::getVar("package_id"), null);
	}
	
	public function change_state($state){
		$app = JFactory::getApplication();
		$ids = $app->input->getArray(array('cid'=>'array'));		
		if(!empty($ids['cid'])){				
			$model = & JModelLegacy::getInstance( 'addprocesspresentation', 'AwardpackageModel' );
			JArrayHelper::toInteger($ids['cid']);
			$id = implode(',', $ids['cid']);
			$ret = $model->set_status($id, $state);
			if($model->set_status($id, $state)){		
				return 1;
			} else {
				return 0;
			}
		}
		
		return -1;
	}
	
	public function save_and_close(){
		$this->setRedirect('index.php?option=com_awardpackage&package_id='.JRequest::getVar("package_id"), null);
	}
}