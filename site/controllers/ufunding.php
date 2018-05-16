<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT . '/helpers/awardpackage.php';
class AwardPackageControllerUfunding extends JControllerLegacy {
	function __construct(){
		parent::__construct();
		AwardPackageHelper::checkUser();
	}
	function getMainPage(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			/*temporary by aditya*/
			$view = $this->getView('ufunding', 'html');
			$view->assign('action', 'main_page');
			$view->display();
		} else	
		{
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}
	}
	
	function addFunds(){
		$view = $this->getView('ufunding', 'html');
		$view->assign('action', 'add_funds');
		$view->display('add_funds');
	}
	function withdrawFunds(){
		$view = $this->getView('ufunding', 'html');
		$view->assign('action', 'withdraw_funds');
		$view->display('withdraw_funds');
	}
	function addFundsConfirm(){
		$view = $this->getView('ufunding', 'html');
		$view->assign('action', 'add_funds_confirm');
		$view->display('add_funds_confirm');
	}

	function addShoppingCredit(){
		$view = $this->getView('ufunding', 'html');
		$view->assign('action', 'shopping_credit');
		$view->display('shopping_credit');
	}
	
	function addShoppingCreditConfirm(){
		$view = $this->getView('ufunding', 'html');
		$view->assign('action', 'shopping_credit_confirm');
		$view->display('shopping_credit_confirm');
	}
	
	function confirm(){
		$user = JFactory::getUser();
		if($user->guest) {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage'));
		} else {
			$model = JModelLegacy::getInstance( 'ufunding', 'AwardpackageUsersModel' );
			$users = AwardPackageHelper::getUserData();
			$amount = JRequest::getVar('amount');
			$totalsc = JRequest::getVar('totalsc');
			$difference = JRequest::getVar('difference');
			$method = JRequest::getVar('rChoice');	

			$data['package_id'] = $users->package_id;
			$data['user_id'] = $users->id;
			if($model->saveFunding($data)) {
				$model->updateHistory($users->id, $users->package_id, $difference, 'funding', $method, $users->username);
				if ($totalsc > 0){
					$model->updateHistory($users->id, $users->package_id, $totalsc, 'shopping', $method, $users->username);
					$model->UpdateShoppingCredit_2($users->id, $users->package_id, JRequest::getVar('scremain'), 'shopping', $method, $users->username);
				}
				//updateHistory($userId, $packageId, $amount, $totalsc, $difference $type, $method, $username)
				//JFactory::getApplication()->enqueueMessage('successfull save new funding');
			} else {
				JError::raiseWarning( 100, 'error process' );
			}
			$view = $this->getView('ufunding', 'html');
			$view->assign('action', 'main_page');
			$view->display();
		}
		die();
	}
	
	function withdrawFundsConfirm(){
		$user = JFactory::getUser();
		if($user->guest) {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage'));
		} else {
			$model = & JModelLegacy::getInstance( 'ufunding', 'AwardpackageUsersModel' );
			$users = AwardPackageHelper::getUserData();
			$amount = JRequest::getVar('amount');				
			if($amount != '') {
				$data['package_id'] = $users->package_id;
				$data['user_id'] = $users->id;					
				if($model->saveFunding($data)) {
					//$model->updateHistory($users->ap_account_id, $users->package_id, $amount, 'withdraw', null);
					$model->updateHistory($users->id, $users->package_id, $amount, 'withdraw', 1, $users->username);
					JFactory::getApplication()->enqueueMessage('successfull save withdraw');
				} else {
					JError::raiseWarning( 100, 'error process' );
				}
			}				
			$view = $this->getView('ufunding', 'html');
			$view->assign('action', 'withdraw_funds_confirm');
			$view->display('withdraw_funds_confirm');
		}
	}
}