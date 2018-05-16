<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
class AwardpackageControllerUserSearch extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}
	
	public function user_list(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'list');
		$view->display();
	}
	
	public function get_profile(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'get_profile');
		$view->display('profile');
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
			$model = & JModelLegacy::getInstance( 'userlist', 'AwardpackageModel' );
			if ($password != $confirmpasw){
				$msg = JText::_('Password is not same');
				if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
					session_start();
					$_SESSION['useraccount'] = $data;
				}
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usersearch&task=usersearch.get_profile&accountId='.JRequest::getVar('accountId').'&package_id='.JRequest::getVar('package_id').''),$msg);
			}else{
				$msg = JText::_('Date updated');
				if($model->edit_save($data)){		
		             if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
						session_start();
						$_SESSION['useraccount'] = $data;
					}
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'save_profile');
		$view->display('next_profile');
					//$this->setRedirect('index.php?option=com_awardpackage&view=usersearch&task=usersearch.registration_next', $msg);
				}else{
					$msg = JText::_('data already exist');

						if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
						session_start();
						$_SESSION['useraccount'] = $data;
					}
							$view = $this->getView('usersearch', 'html');
							$view->assign('action', 'get_profile');
							$view->display('profile');
//$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=usersearch&task=usersearch.get_profile&accountId='.$accountId.'&package_id='.$package_id.''),$msg);				
}
			}

	}
		
	
		function save_next_profile(){
			$model = & JModelLegacy::getInstance( 'userlist', 'AwardpackageModel' );
		$app = JFactory::getApplication();
		/*$userId = trim(JRequest::getVar('userId'));
		$email = trim(JRequest::getVar('email'));
		$firstname = trim(JRequest::getVar('firstname'));
		$lastname = trim(JRequest::getVar('lastname'));*/
		$birthdate = trim(JRequest::getVar('birthdate'));
		/*$gender = JRequest::getVar('gender');*/
		$street = trim(JRequest::getVar('street'));
		$city = trim(JRequest::getVar('city'));
		$state = trim(JRequest::getVar('state'));
		$postCode = trim(JRequest::getVar('postCode'));
		/*$country = JRequest::getVar('country');*/
		$phone = trim(JRequest::getVar('phone'));
		$paypal_account = trim(JRequest::getVar('paypal_account'));
		$userId = JRequest::getVar('accountId');



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
		    "postCode" => $postCode,
		    "country" => $country,
		    "phone" => $phone,
		    "paypal_account" => $paypal_account
		);
			$model = & JModelLegacy::getInstance( 'userlist', 'AwardpackageModel' );
		if(!$model->updateInfo($data)) {
			if(!isset($_SESSION['useraccount']) || empty($_SESSION['useraccount'])) {
				session_start();
				$_SESSION['useraccount'] = $data;
			}
$msg = "You account is complete";
							$view = $this->getView('usersearch', 'html');
							$view->assign('action', 'get_profile');
							$view->display('profile');
		} else {
$msg = "You account is complete";
							$view = $this->getView('usersearch', 'html');
							$view->assign('action', 'get_profile');
							$view->display('profile');
					}
	}
	
		public function get_transaction(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'get_transaction');
		$view->display('transaction');
	}
	
	public function get_all_transactions(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'all_transactions');
		$view->display('all_transactions');
	}
	
	public function get_all_funds(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'all_funds');
		$view->display('all_funds');
	}
	
	public function get_all_donation(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'all_donation');
		$view->display('all_donation');
	}
	
	public function get_symbol_queue(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'symbol_queue_of');
		$view->display('symbol_queue_of');
	}
	
	public function get_symbol_queue_detail(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'symbol_queue_detail');
		$view->display('symbol_queue_detail');
	}
	
	public function get_presentation(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'presentation_of');
		$view->display('presentation_of');
	}
	
	public function get_prize_status(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'prize_status');
		$view->display('prize_status');
	}
	
	public function get_distribute_prize(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'distribute_prize');
		$view->display('distribute_prize');
	}
	
	public function get_shopping_credit(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'shopping_credit');
		$view->display('shopping_credit');
	}
	
	public function get_quizzes(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'get_quizzes');
		$view->display('get_quizzes');
	}
	
	public function get_surveys(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'get_surveys');
		$view->display('get_surveys');
	}

	public function get_giftcode(){
		$view = $this->getView('usersearch', 'html');
		$view->assign('action', 'get_giftcode');
		$view->display('get_giftcode');
	}	
	
}