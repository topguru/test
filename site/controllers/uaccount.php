<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/awardpackages.php';
require_once JPATH_COMPONENT . '/helpers/awardpackage.php';
class AwardPackageControllerUaccount extends JControllerLegacy {

	
	function getMainPage(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getProfile');
			$view->display('profile');
		}
	}

	function getProfile(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getProfile');
			$view->display('profile');
		}
	}
	
	function next_profile(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'NextProfile');
			$view->display('next_profile');
		}
	}

public function save_profile(){	
		$username = trim(JRequest::getVar('username'));
		$password = JRequest::getVar('password1');
		$confirmpasw = trim(JRequest::getVar('confirmpasw'));
		$email = trim(JRequest::getVar('email'));
		$firstname = JRequest::getVar('firstname');
		$lastname = JRequest::getVar('lastname');
		$gender = JRequest::getVar('gender');
		$country = JRequest::getVar('country');
		$paypal_account = JRequest::getVar('paypal_account');
		$package_id = JRequest::getVar('paypal_account');
		$userId = JRequest::getVar('accountId');

 
		$data = array(
			"userId" => $userId,
       		"username" => $username,
        	"pasw" => $confirmpasw,
		 	"email" => $email,			
			"firstname" => $firstname,			
			"lastname" => $lastname,			
			"gender" => $gender,			
			"country" => $country,	
			"paypal_account" => $paypal_account,			
			"activation" => '1'
			);
		$model = JModelLegacy::getInstance('useraccount','AwardpackageUsersModel');
			if ($password != $confirmpasw){
				$msg = JText::_('Password is not same');
				if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
					session_start();
					$_SESSION['useraccount'] = $data;
				}
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=uaccount&task=uaccount.getProfile&accountId='.JRequest::getVar('accountId').'&package_id='.JRequest::getVar('package_id').''),$msg);
			}else{
				$msg = JText::_('Date updated');
				if($model->edit_save($data)){		
		             if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
						session_start();
						$_SESSION['useraccount'] = $data;
					}
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getProfile');
			$view->display('next_profile');
					//$this->setRedirect('index.php?option=com_awardpackage&view=usersearch&task=usersearch.registration_next', $msg);
				}else{
					$msg = JText::_('data already exist');

						if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
						session_start();
						$_SESSION['useraccount'] = $data;
					}
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getProfile');
			$view->display('profile');
//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usersearch&task=usersearch.get_profile&accountId='.$accountId.'&package_id='.$package_id.''),$msg);				
}
			}

	}
		
		
	function edit(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getProfile');
			$view->display('profile');
		}
	}
	
		function updateInfo(){
		$model = JModelLegacy::getInstance('useraccount','AwardpackageUsersModel');
		$app = JFactory::getApplication();
		$userId = trim(JRequest::getVar('accountId'));
		$birthdate = trim(JRequest::getVar('birthdate'));
		$street = trim(JRequest::getVar('street'));
		$phone = trim(JRequest::getVar('phone'));
		

		$data = array(
			"userId" => $userId,		
			"username" => $username,
			"email" => $email,       		
			"firstname" => $firstname,
			"lastname" => $lastname,
			"birthdate" => $birthdate,
		    "gender" => $gender,
			"street" => $street,
		   	"city" => $city,
		    "state" => $state,
		    "post_code" => $post_code,
		    "country" => $country,
		    "phone" => $phone,
		    "paypal_account" => $paypal_account
		);

		$model = JModelLegacy::getInstance('useraccount','AwardpackageUsersModel');
		if(!$model->updateData($data)) {
			if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
				session_start();
				$_SESSION['useraccount'] = $data;
			}
$msg = "Your account updated";
					$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=uaccount&task=uaccount.getProfile'),$msg);
		} else {
$msg = "Your account updated";
					$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=uaccount&task=uaccount.getProfile'),$msg);		}
	}
	
	function getTransaction(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getTransaction');
			$view->display('transaction');
		}
	}
	
	function getDonation(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getDonation');
			$view->display('donation');
		}
	}

function getFunds(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getFunds');
			$view->display('funds');
		}
	}
	
	function getShoppingCredit(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getShoppingCredit');
			$view->display('shopping');
		}
	}
	
	function getShoppingCreditBusiness(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getShoppingCreditBusiness');
			$view->display('shopping_business');
		}
	}
	
	function getAwardSymbol(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getAwardSymbol');
			$view->display('award_symbol');
		}
	}
	
	
	function getPrize(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getPrize');
			$view->display('prize');
		}
	}
	
	function onSelectFilter(){
		$filter = JRequest::getVar('filter');
		$data = $this->isExistSession();
		$id = $data->id;
		$package_id = $data->package_id;
		$view = $this->getView('uaccount', 'html');
		switch ($filter) {
			case 'all' :
				break;
			case 'award_symbol' :
				break;
			case 'shopping_credits' :
				break;
		}
		$view->assign('action', 'main_page');
		$view->display();
	}
}
