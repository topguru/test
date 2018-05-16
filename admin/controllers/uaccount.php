<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/awardpackages.php';
require_once JPATH_COMPONENT . '/helpers/awardpackage.php';
class AwardPackageControllerUaccount extends JControllerLegacy {

	function __construct(){
		parent::__construct();
	}

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

			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getFunds');
			$view->display('funds');
	}
	
	function getShoppingCredit(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('uaccount', 'html');
			$view->assign('action', 'getShoppingCredit');
			$view->display('shopping');
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
