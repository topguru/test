<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
class AwardpackageControllerUsertransaction extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}
	
	public function doGetUserTransaction(){
		$view = $this->getView('usertransaction', 'html');		
		$view->display();
	}
}