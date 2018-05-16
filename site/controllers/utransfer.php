<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.controller');
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT . '/helpers/awardpackage.php';

class AwardPackageControllerUtransfer extends JControllerLegacy {
	function __construct(){
		parent::__construct();
		AwardPackageHelper::checkUser();
	}
	function getMainPage(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			/*temporary by aditya*/
			$view = $this->getView('utransfer', 'html');
			$view->assign('action', 'main_page');
			$view->display();
		} else	
		{
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}
	}
	
	function addTransfer(){
		$view = $this->getView('utransfer', 'html');
		$view->assign('action', 'add_transfer');
		$view->display('add_transfer');
	}
	function addTransferConfirm(){
		$view = $this->getView('utransfer', 'html');
		$view->assign('action', 'add_transfer_confirm');
		$view->display('add_transfer_confirm');
	}
	function addTransferComplete(){
		$view = $this->getView('utransfer', 'html');
		$view->assign('action', 'add_transfer_complete');
		$view->display('add_transfer_complete');
	}

   function addConvert(){
		$view = $this->getView('utransfer', 'html');
		$view->assign('action', 'add_convert');
		$view->display('add_convert');
	}
	function addConvertConfirm(){
		$view = $this->getView('utransfer', 'html');
		$view->assign('action', 'add_convert_confirm');
		$view->display('add_convert_confirm');
	}
	function addConvertComplete(){
		$view = $this->getView('utransfer', 'html');
		$view->assign('action', 'add_convert_complete');
		$view->display('add_convert_complete');
	}
	
}