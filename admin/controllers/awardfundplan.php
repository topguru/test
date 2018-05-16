<?php
// no direct access

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class AwardpackageControllerAwardFundPlan extends JControllerLegacy {

	function __construct(){
		parent::__construct();
	}

	public function get_awardfundplan(){	
		$view = $this->getView('awardfundplan', 'html');
		$view->assign('action', 'list');
		$view->display();
	}
	
	public function create_update(){
		$view = $this->getView('awardfundplan', 'html');		
		$view->assign('action', 'create');
		$view->display('create');
	}
	
	public function save_create(){
		$package_id = JRequest::getVar('package_id');
		$sname = JRequest::getVar('sname');
		$description = JRequest::getVar('description');
		$rate = JRequest::getVar('rate');
		$remain = JRequest::getVar('remain');
		$spent = JRequest::getVar('spent');
		$amount = JRequest::getVar('amount');
		$total = JRequest::getVar('total');		
		$id = JRequest::getVar('id');
		$userid = JRequest::getVar('idUser');

		$model = & JModelLegacy::getInstance( 'awardfundplan', 'AwardpackageModel' );
		if($model->save_update_category($package_id, $sname,$description,$rate, $spent, $remain, $amount, $total, $id, $userid)) {
			$this->setRedirect('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id='.JRequest::getVar('package_id'), JText::_('MSG_SUCCESS'));
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id='.JRequest::getVar('package_id'), JText::_('Error'));
		}
	}
	
	public function save_speed(){
		$package_id = JRequest::getVar('package_id');
		$speed = JRequest::getVar('speed');

		$model = & JModelLegacy::getInstance( 'awardfundplan', 'AwardpackageModel' );
		if($model->save_speed($package_id, $speed)) {
			$this->setRedirect('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id='.JRequest::getVar('package_id'), JText::_('MSG_SUCCESS'));
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id='.JRequest::getVar('package_id'), JText::_('Error'));
		}
	}
	
	public function save_user(){
		$app = JFactory::getApplication();
		$id = JRequest::getVar('id');	
		$package_id = JRequest::getVar('package_id');
		$userid = JRequest::getVar('idUser');	
		var_dump($userid);
		break;
		if(!empty($id)){
			$model = & JModelLegacy::getInstance( 'awardfundplan', 'AwardpackageModel' );
			if($model->save_update_user($package_id, $userid, $id)){
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_SUCCESS'));
			} else {
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_ERROR'));
			}
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id='.JRequest::getVar("package_id"), false), JText::_('No Item Selected'));
		}
		
	}
	
	public function publish_list(){
		$return = $this->change_state(1);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function unpublish_list(){
		$return = $this->change_state(0);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function delete_awardfundplan(){
		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));

		if(!empty($ids['cid'])){
			$model = & JModelLegacy::getInstance( 'awardfundplan', 'AwardpackageModel' );
			JArrayHelper::toInteger($ids['cid']);
			if($model->delete_categories($ids['cid'])){
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_SUCCESS'));
			} else {
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_ERROR'));
			}
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id='.JRequest::getVar("package_id"), false), JText::_('No Item Selected'));
		}
	}
	
	public function add_awardfundplan(){
		$this->setRedirect('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.create_update&package_id='.JRequest::getVar("package_id"), null);
	}
	
	public function change_state($state){
		$app = JFactory::getApplication();
		$ids = $app->input->getArray(array('cid'=>'array'));		
		if(!empty($ids['cid'])){				
			$model = & JModelLegacy::getInstance( 'awardfundplan', 'AwardpackageModel' );
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