<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT . '/helpers/awardpackage.php';
class AwardPackageControllerUdonation extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}
	function getMainPage(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('udonation', 'html');
			$view->assign('action', 'main_page');
			$view->display();
		} else {
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}
	}
	function doCalculate(){
		$user = JFactory::getUser();
		if($user->guest) {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage'));
		} else {
			$model = & JModelLegacy::getInstance( 'udonation', 'AwardpackageUsersModel' );
			//$model_funding = & JModelLegacy::getInstance( 'ufunding', 'AwardpackageUsersModel' );
			$users = AwardPackageHelper::getUserData();
			$data['package_id'] = $users->package_id;
			$data['user_id'] = $users->id;

			$categories = JRequest::getVar('category');
			$settingId = JRequest::getVar('settingId');
			$colors = JRequest::getVar('colors');
			$times = JRequest::getVar('times');
			$donations = JRequest::getVar('donations');
			$results = array();
			$i = 0;
			$amount = 0;
			foreach ($categories as $i=>$category) {
				$data['category_id'] = $category;
				$data['color'] = $colors[$i];
 				$quantity = empty($times[$i]) || $times[$i] == '' ? 0 : $times[$i];
				if (!empty($times[$i])){
				$category_id = $category;
				$setting_id = $settingId[$i];
				$kali = $category;
				}				
				$data['quantity'] = $quantity;
				$amount = $amount + (float)$donations[$i] * $quantity;
				$data['donation_amount'] = (float)$donations[$i] * $quantity;
				$data['donate'] = $donations[$i];
				$results[] = $data;
				$i++;
			}			
			
			for ($i = 1; $i < $kali; $i++){
				$giftcode = $model->getGiftcode($setting_id, $i, $kali);	
				 foreach ($giftcode as $row){
					$gcid = $row->id ;
					}
				$model->savegiftcode($users->id, $setting_id, $gcid+1)	;	
				}
				
			$view = $this->getView('udonation', 'html');
			$view->assign('action', 'do_calculate');
			$view->assignRef('results', $results);
			$view->display('do_calculate');
		}
	}
	function selectPayment(){
		$view = $this->getView('udonation', 'html');
		$view->assign('action', 'select_payment');
		$view->assignRef('amount', JRequest::getVar('amount'));
		$view->assignRef('user_id', JRequest::getVar('user_id'));
		$view->assignRef('package_id', JRequest::getVar('package_id'));
		$view->display('select_payment');
	}	
	
	function addShoppingCredit(){
		$view = $this->getView('udonation', 'html');
		$view->assign('action', 'shopping_credit');
		$view->display('shopping_credit');
	}
	
	function addShoppingCreditConfirm(){
		$view = $this->getView('udonation', 'html');
		$view->assign('action', 'shopping_credit_confirm');
		$view->display('shopping_credit_confirm');
	}
	
	function confirm(){
		$user = JFactory::getUser();
		if($user->guest) {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage'));
		} else {
			$users = AwardPackageHelper::getUserData();
			$rChoice = JRequest::getVar('rChoice');
			
			$model = & JModelLegacy::getInstance( 'udonation', 'AwardpackageUsersModel' );
			$data = array();
			$data['user_id'] = JRequest::getVar('user_id');			
			$data['package_id'] = JRequest::getVar('package_id');
			
			
		if($model->saveFunding($data)) {
			$model->updateHistory(JRequest::getVar('user_id'), JRequest::getVar('package_id'),JRequest::getVar('difference'),'donation', $rChoice, $users->username);
			$total_donation = JRequest::getVar('difference') + JRequest::getVar('totalsc');
			$result = $model->getShoppingCredit(JRequest::getVar('package_id'),$total_donation);
			$usersc = $model->getUserShoppingCredit($users->id);
				foreach ($result as $row) {
				    $fee = $row->fee;	
				 	$refund =  $row->refund * ( JRequest::getVar('difference') + JRequest::getVar('totalsc'));
					
					$fee = number_format($fee,2);	
					$refund = number_format($refund/100,2);	
						}				
					if ($refund > 0){
					$model->updateHistory(JRequest::getVar('user_id'), JRequest::getVar('package_id'), $refund ,'refund' , 0, $users->username);
  					$model->updateHistory(JRequest::getVar('user_id'), JRequest::getVar('package_id'), $fee ,'fee' , 0, $users->username);					
					$model->updateShoppingCredit($users->id, $users->package_id, $refund, $method);	
					}
					if (JRequest::getVar('totalsc') > 0){					
					$model->updateHistory($users->id, $users->package_id, JRequest::getVar('totalsc'), 'shopping', $method, $users->username);
					$model->UpdateShoppingCredit_2($users->id, $users->package_id, JRequest::getVar('scremain'), 'shopping', $method, $users->username);
					}
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=udonation&task=udonation.getMainPage'));

			}			
		}
	}
}
