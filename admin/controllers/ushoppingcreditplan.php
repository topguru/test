<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT . '/helpers/awardpackage.php';
class AwardPackageControllerUshoppingcreditplan extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}
	function getMainPage(){
		$user = JFactory::getUser();
		if($user->guest) {
			/*temporary by aditya*/
			$view = $this->getView('ushoppingcreditplan', 'html');
			$view->assign('action', 'main_page');
			$view->display();
		} else {
			$view = $this->getView('ushoppingcreditplan', 'html');
			$view->assign('action', 'main_page');
			$view->display();
		} 
	}
	function showPlan(){
		$view = $this->getView('ushoppingcreditplan', 'html');
		$view->assign('action', 'show_plan');
		$view->display('show_plan');
	}

}