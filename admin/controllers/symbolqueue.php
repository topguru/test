<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class AwardpackageControllerSymbolQueue extends JControllerLegacy {

	function __construct(){
		parent::__construct();
	}

	public function get_symbolqueue(){	
		$view = $this->getView('symbolqueue', 'html');
		$view->assign('action', 'list');
		$view->display();
	}
	
	public function view_symbolqueue(){	
		$view = $this->getView('symbolqueue', 'html');
		$view->assign('action', 'view');
		$view->display('view');
	}
	
	public function view_userlist(){	
		$view = $this->getView('symbolqueue', 'html');
		$view->assign('action', 'userlist');
		$view->display('userlist');
	}
	
	public function save_shuffle(){
		$model = & JModelLegacy::getInstance( 'symbolqueue', 'AwardpackageModel' );
		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		$shufle = JRequest::getVar('shufle');
		$processId = JRequest::getVar('processId');
		if(!empty($ids['cid'])){
			JArrayHelper::toInteger($ids['cid']);
			$model->save_shuffle($ids['cid'],$shufle,$processId );
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.get_symbolqueue&groupId='.JRequest::getVar("groupId").'&package_id='.JRequest::getVar("package_id"), false), JText::_('Assigned'));
		}
		else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.get_symbolqueue&groupId='.JRequest::getVar("groupId").'&package_id='.JRequest::getVar("package_id"), false), JText::_('Select item first'));
		}
		
		
		
	}	
	
	public function save_userlist(){
		$model = & JModelLegacy::getInstance( 'symbolqueue', 'AwardpackageModel' );
		$app = JFactory::getApplication();
		$id = JRequest::getVar('id');
		$userid = JRequest::getVar('userid');
		if(!empty($id)){
			$model->save_userid($id,$userid);
		}
		else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.get_symbolqueue&package_id='.JRequest::getVar("package_id"), false), JText::_('Select item first'));
		}
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.get_symbolqueue&package_id='.JRequest::getVar("package_id"), false), JText::_('Assigned'));
		
	}	
	
	public function create_update(){
		$view = $this->getView('symbolqueue', 'html');		
		$view->assign('action', 'create');
		$view->display('create');
	}
	
	public function save_create(){
	$app = JFactory::getApplication();
		$ids = $app->input->getArray(array('cid'=>'array'));
		$id = implode(',', $ids['cid']);
		$package_id = JRequest::getVar('package_id');
		$processId = JRequest::getVar('processId');
		$groupId = JRequest::getVar('groupId');
		$jml = JRequest::getVar('jml');
		foreach(($ids['cid']) as $val){
        	$result +=intval($val);
		}
		
		$model = & JModelLegacy::getInstance( 'symbolqueue', 'AwardpackageModel' );
		for($i = 0; $i < $jml; $i++) {
			$model->save_update_category($groupId);
		}
		
			$this->setRedirect('index.php?option=com_awardpackage&view=symbolqueuegroup&task=symbolqueuegroup.get_symbolqueuegroup&groupId='.JRequest::getVar("groupId").'&package_id='.JRequest::getVar('package_id'), JText::_('MSG_SUCCESS'));
			//$this->setRedirect('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id=' . JRequest::getVar('package_id') );
			
	}
	
	public function publish_list(){
		$return = $this->change_state(1);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.get_symbolqueue&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function unpublish_list(){
		$return = $this->change_state(0);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.get_symbolqueue&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function delete_symbolqueue(){
		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		if(!empty($ids['cid'])){
			$model = & JModelLegacy::getInstance( 'symbolqueue', 'AwardpackageModel' );
			JArrayHelper::toInteger($ids['cid']);
			if($model->delete_categories($ids['cid'])){
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.get_symbolqueue&groupId='.JRequest::getVar("groupId").'&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_SUCCESS'));
			} else {
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.get_symbolqueue&groupId='.JRequest::getVar("groupId").'&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_ERROR'));
			}
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.get_symbolqueue&groupId='.JRequest::getVar("groupId").'&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_NO_ITEM_SELECTED'));
		}
	}
	
	public function add_symbolqueue(){
		$this->setRedirect('index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.create_update&processId='.JRequest::getVar("processId").'&groupId='.JRequest::getVar("groupId").'&package_id='.JRequest::getVar("package_id"), null);
	}
	
	public function change_state($state){
		$app = JFactory::getApplication();
		$ids = $app->input->getArray(array('cid'=>'array'));		
		if(!empty($ids['cid'])){				
			$model = & JModelLegacy::getInstance( 'symbolqueue', 'AwardpackageModel' );
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