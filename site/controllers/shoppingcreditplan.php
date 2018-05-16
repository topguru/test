<?php
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';

class AwardpackageControllerShoppingCreditPlan extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}
	
	public function get_shopping_credit_plan_list(){		
		$view = $this->getView('shoppingcreditplan', 'html');		
		$view->assign('action', 'list');
		$view->display();
	}
	
	public function add_shopping_credit_plan(){
		$package_id = JRequest::getVar('package_id');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		if($model->create_credit_plan($package_id)) {
			$this->setRedirect('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.get_shopping_credit_plan_list&package_id='.JRequest::getVar('package_id'), JText::_('MSG_SUCCESS'));
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.get_shopping_credit_plan_list&package_id='.JRequest::getVar('package_id'), JText::_('Shopping credit plan category has not been made'));
		}
	}
	
	public function create_update_plan(){
		$view = $this->getView('shoppingcreditplan', 'html');			
		$view->assign('action', 'create_update_plan');
		$view->display('plan');
	}
	
	public function create_update_notes(){
		$view = $this->getView('shoppingcreditplan', 'html');		
		$view->assign('action', 'create_update_notes');
		$view->display('notes');
	}
	
	public function publish_list(){
		$return = $this->change_state(1);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.get_shopping_credit_plan_list&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function unpublish_list(){
		$return = $this->change_state(0);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.get_shopping_credit_plan_list&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function change_state($state){
		$app = JFactory::getApplication();
		$ids = $app->input->getArray(array('cid'=>'array'));	
		$scc = sizeof($ids['cid']);	
		if ( $scc > 1 ){
		return 0;
		}
		if(!empty($ids['cid'])){				
			$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
			JArrayHelper::toInteger($ids['cid']);
			$id = implode(',', $ids['cid']);
			if($model->set_status($id, $state)){		
				return 1;
			} else {
				return 0;
			}
		}		
		return -1;
	}
	
	public function delete_shopping_credit_plan(){
		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		if(!empty($ids['cid'])){
			$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
			JArrayHelper::toInteger($ids['cid']);
			if($model->delete_plan($ids['cid'])){
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.get_shopping_credit_plan_list&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_SUCCESS'));
			} else {
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.get_shopping_credit_plan_list&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_ERROR'));
			}
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.get_shopping_credit_plan_list&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_NO_ITEM_SELECTED'));
		}
	}	
	public function add_contribution_range(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}		
		$package_id = JRequest::getVar('package_id');
		$min_amount = JRequest::getVar('min_amount');
		$max_amount = JRequest::getVar('max_amount');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		if(!$model->is_contribution_inrange($min_amount, $max_amount)) {
			if($model->insert_contribution_range($package_id, $min_amount, $max_amount)) {
				$this->on_select_contribution_range();		
				//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id').'&uniq_id='.JRequest::getVar('uniq_id'), false), JText::_('Success insert contribution range'));
			}
		} else{
			$this->on_select_contribution_range();
			//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false), JText::_('Contribution amount is in range'));	
		}
	}	
	public function delete_contribution_range(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$package_id = JRequest::getVar('package_id');
		$contrib_ranges = JRequest::getVar('contrib_range');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		if(!empty($contrib_ranges)) {
			$ids = implode(',', $contrib_ranges);
			if($model->delete_contribution_range($package_id, $ids)) {
				$this->on_select_contribution_range();
				//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false), JText::_('Success delete contribution range'));			
			} else{
				$this->on_select_contribution_range();
				//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false), JText::_('Error delete contribution range'));	
			}	
		} else {
			$this->on_select_contribution_range();
			//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false), JText::_('Please select contribution range first'));
		}		
	}
	public function add_progress_check(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$package_id = JRequest::getVar('package_id');
		$check_every = JRequest::getVar('check_every');
		$pc_type = JRequest::getVar('pc_type');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		if($model->insert_progress_check($package_id, $check_every, $pc_type)) {
			$this->on_select_contribution_range();
			//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false), JText::_('Success insert contribution range'));
		} else {
			$this->on_select_contribution_range();
			//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false), JText::_('Error when check progress check'));
		}
	}
	public function delete_progress_check(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$package_id = JRequest::getVar('package_id');
		$progress_checks = JRequest::getVar('progress_checks');		
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		if(!empty($progress_checks)) {
			$ids = implode(',', $progress_checks);
			if($model->delete_progress_check($package_id, $ids)){
				$this->on_select_contribution_range();
				//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false), JText::_('Success delete progress check'));
			} else {
				$this->on_select_contribution_range();
				//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false), JText::_('Error delete progress check'));
			}
		} else {
			$this->on_select_contribution_range();
			//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false), JText::_('Please select progress check first'));
		}
	}
	public function on_donation_fee(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$package_id = JRequest::getVar('package_id');
		$fee = JRequest::getVar('donation-fee-percent');
		$uniq_id = JRequest::getVar('uniq_id');
		$id = JRequest::getVar('id');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		$model->save_update_donation_fee($id, $package_id, $uniq_id, $fee, JRequest::getVar('contrib_radio'), JRequest::getVar('progress_radio'));
		$this->on_select_contribution_range();
		//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false));
	}
	public function on_donation_refunded(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$package_id = JRequest::getVar('package_id');
		$refunded = JRequest::getVar('donation-refunded-percent');
		$uniq_id = JRequest::getVar('uniq_id');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		$model->save_update_donation_refunded($package_id, $uniq_id, $refunded, JRequest::getVar('contrib_radio'), JRequest::getVar('progress_radio'));
		$this->on_select_contribution_range();
		//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false));
	}
	public function on_donation_unlock(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$package_id = JRequest::getVar('package_id');
		$unlock = JRequest::getVar('donation-refunded-unlock');
		$uniq_id = JRequest::getVar('uniq_id');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );		
		$model->save_update_donation_unlock($package_id, $uniq_id, $unlock, JRequest::getVar('contrib_radio'), JRequest::getVar('progress_radio'));
		$this->on_select_contribution_range();
		//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false));
	}
	public function on_donation_expiry(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$package_id = JRequest::getVar('package_id');
		$expire = JRequest::getVar('donation-refunded-expire');
		$uniq_id = JRequest::getVar('uniq_id');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		$model->save_update_donation_expire($package_id, $uniq_id, $expire, JRequest::getVar('contrib_radio'), JRequest::getVar('progress_radio'));
		$this->on_select_contribution_range();
		//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false));
	}
	public function on_award_fee(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$package_id = JRequest::getVar('package_id');
		$fee = JRequest::getVar('award-fee-percent');
		$uniq_id = JRequest::getVar('uniq_id');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		$model->save_update_award_fee($package_id, $uniq_id, $fee, JRequest::getVar('contrib_radio'), JRequest::getVar('progress_radio'));
		$this->on_select_contribution_range();
		//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false));
	}
	public function on_award_refund(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$package_id = JRequest::getVar('package_id');
		$refunded = JRequest::getVar('award-refunded-percent');
		$uniq_id = JRequest::getVar('uniq_id');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		$model->save_update_award_refund($package_id, $uniq_id, $refunded, JRequest::getVar('contrib_radio'), JRequest::getVar('progress_radio'));
		$this->on_select_contribution_range();
		//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false));
	}
	public function on_award_unlock(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$package_id = JRequest::getVar('package_id');
		$unlock = JRequest::getVar('award-refunded-unlock');
		$uniq_id = JRequest::getVar('uniq_id');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		$model->save_update_award_unlock($package_id, $uniq_id, $unlock, JRequest::getVar('contrib_radio'), JRequest::getVar('progress_radio'));
		$this->on_select_contribution_range();
		//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false));
	}
	public function on_award_expire(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$package_id = JRequest::getVar('package_id');
		$expire = JRequest::getVar('award-refunded-expire');
		$uniq_id = JRequest::getVar('uniq_id');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		$model->save_update_award_expire($package_id, $uniq_id, $expire, JRequest::getVar('contrib_radio'), JRequest::getVar('progress_radio'));
		$this->on_select_contribution_range();
		//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false));
	}
	public function on_giftcode(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$package_id = JRequest::getVar('package_id');
		$uniq_id = JRequest::getVar('uniq_id');
		$giftcode_id = JRequest::getVar('giftcode_id');
		$giftcode_quantity = JRequest::getVar('giftcode_quantity');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		$model->update_giftcode_quantity($package_id, $uniq_id, $giftcode_id, $giftcode_quantity);
		$this->on_select_contribution_range();
		//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false));
	}
	public function on_giftcode_fee(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$package_id = JRequest::getVar('package_id');
		$fee = JRequest::getVar('giftcode-fee-percent');
		$uniq_id = JRequest::getVar('uniq_id');
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		$model->update_giftcode_fee($package_id, $uniq_id, $fee);
		$this->on_select_contribution_range();
		//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.on_select_contribution_range&package_id='.JRequest::getVar("package_id") . '&id='.JRequest::getVar('id') . '&uniq_id='.JRequest::getVar('uniq_id'), false));
	}
	public function save_and_close(){
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );
		$model->save_shopping_credit_plan();
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.get_shopping_credit_plan_list&package_id='.JRequest::getVar("package_id"), false), JText::_('Successfull save shopping credit plan'));
		//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&package_id='.JRequest::getVar("package_id"), false));
	}
	
	public function close(){
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&package_id='.JRequest::getVar("package_id"), false));
	}
	
	public function on_select_contribution_range(){
		if(!isset($_SESSION['plan']) || empty($_SESSION['plan'])) {
			session_start();
			$_SESSION['plan'] = $_POST;
		}
		$view = $this->getView('shoppingcreditplan', 'html');		
		$view->assign('action', 'contribution_range_click');
		$view->display('plan');
	}
}