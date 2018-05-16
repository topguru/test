<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
class AwardpackageControllerUserlist extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}
	
	public function user_list(){
		$view = $this->getView('userlist', 'html');
		$view->assign('action', 'list');
		$view->display();
	}
	
	public function get_all_transactions(){
		$view = $this->getView('userlist', 'html');
		$view->assign('action', 'all_transactions');
		$view->display('all_transactions');
	}
	
	public function get_all_funds(){
		$view = $this->getView('userlist', 'html');
		$view->assign('action', 'all_funds');
		$view->display('all_funds');
	}
	
	public function get_all_donation(){
		$view = $this->getView('userlist', 'html');
		$view->assign('action', 'all_donation');
		$view->display('all_donation');
	}
	
	public function get_symbol_queue(){
		$view = $this->getView('userlist', 'html');
		$view->assign('action', 'symbol_queue_of');
		$view->display('symbol_queue_of');
	}
	
	public function get_symbol_queue_detail(){
		$view = $this->getView('userlist', 'html');
		$view->assign('action', 'symbol_queue_detail');
		$view->display('symbol_queue_detail');
	}
	
	public function get_presentation(){
		$view = $this->getView('userlist', 'html');
		$view->assign('action', 'presentation_of');
		$view->display('presentation_of');
	}
	
	public function get_prize_status(){
		$view = $this->getView('userlist', 'html');
		$view->assign('action', 'prize_status');
		$view->display('prize_status');
	}
	
	public function get_distribute_prize(){
		$view = $this->getView('userlist', 'html');
		$view->assign('action', 'distribute_prize');
		$view->display('distribute_prize');
	}
	
	public function get_shopping_credit(){
		$view = $this->getView('userlist', 'html');
		$view->assign('action', 'shopping_credit');
		$view->display('shopping_credit');
	}
	
	public function get_quizzes(){
		$view = $this->getView('userlist', 'html');
		$view->assign('action', 'get_quizzes');
		$view->display('get_quizzes');
	}
	
	public function get_surveys(){
		$view = $this->getView('userlist', 'html');
		$view->assign('action', 'get_surveys');
		$view->display('get_surveys');
	}

	public function get_giftcode(){
		$view = $this->getView('userlist', 'html');
		$view->assign('action', 'get_giftcode');
		$view->display('get_giftcode');
	}	
	
}