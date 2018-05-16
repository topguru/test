<?php
// no direct access
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageControllerShoppingCreditCategory extends JControllerLegacy {

	function __construct(){
		parent::__construct();
	}

	public function get_shopping_credit_category_list(){	
		$view = $this->getView('shoppingcreditcategory', 'html');
		$view->assign('action', 'list');
		$view->display();
	}
	
	public function create_update(){
		$view = $this->getView('shoppingcreditcategory', 'html');		
		$view->assign('action', 'create');
		$view->display('create');
	}
	
	public function save_create(){
		$package_id = JRequest::getVar('package_id');
		$sname = JRequest::getVar('sname');
		$id = JRequest::getVar('id');
		$model = & JModelLegacy::getInstance( 'shoppingcreditcategory', 'AwardpackageModel' );
		if($model->save_update_category($package_id, $sname, $id)) {
			$this->setRedirect('index.php?option=com_awardpackage&view=shoppingcreditcategory&task=shoppingcreditcategory.get_shopping_credit_category_list&package_id='.JRequest::getVar('package_id'), JText::_('MSG_SUCCESS'));
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=shoppingcreditcategory&task=shoppingcreditcategory.get_shopping_credit_category_list&package_id='.JRequest::getVar('package_id'), JText::_('Error'));
		}
	}
	
	public function publish_list(){
		$app = JFactory::getApplication();
		$ids = $app->input->getArray(array('cid'=>'array'));
		$return = $this->change_state(1);
		$msg = !empty($ids['cid']) ? JText::_('MSG_SUCCESS') : JText::_('MSG_NO_ITEM_SELECTED');		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditcategory&task=shoppingcreditcategory.get_shopping_credit_category_list&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function unpublish_list(){
		$app = JFactory::getApplication();
		$ids = $app->input->getArray(array('cid'=>'array'));
		$return = $this->change_state(0);
		$msg = !empty($ids['cid']) ? JText::_('MSG_SUCCESS') : JText::_('MSG_NO_ITEM_SELECTED');		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditcategory&task=shoppingcreditcategory.get_shopping_credit_category_list&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function delete_shopping_credit_category(){
		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		if(!empty($ids['cid'])){
			$model = & JModelLegacy::getInstance( 'shoppingcreditcategory', 'AwardpackageModel' );
			JArrayHelper::toInteger($ids['cid']);
			if($model->delete_categories($ids['cid'])){
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditcategory&task=shoppingcreditcategory.get_shopping_credit_category_list&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_SUCCESS'));
			} else {
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditcategory&task=shoppingcreditcategory.get_shopping_credit_category_list&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_ERROR'));
			}
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditcategory&task=shoppingcreditcategory.get_shopping_credit_category_list&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_NO_ITEM_SELECTED'));
		}
	}
	
	public function add_shopping_credit_category(){
		$this->setRedirect('index.php?option=com_awardpackage&view=shoppingcreditcategory&task=shoppingcreditcategory.create_update&package_id='.JRequest::getVar("package_id"), null);
	}
	
	public function change_state($state){
		$app = JFactory::getApplication();
		$ids = $app->input->getArray(array('cid'=>'array'));		
		if(!empty($ids['cid'])){				
			$model = & JModelLegacy::getInstance( 'shoppingcreditcategory', 'AwardpackageModel' );
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