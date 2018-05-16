<?php
//no redirect
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class AwardPackageControllerGiftcode extends AwardPackageController
{
	function display() {
	   
		$view = $this->getView('giftcode', 'html');
		$model = & JModelLegacy::getInstance( 'giftcode', 'AwardpackageUsersModel' );
		$users_model = & JModelLegacy::getInstance('giftcode', 'AwardpackageUsersModel');		
		$view->setModel($model, true);
		$view->setModel($users_model, false);
		$view->assign('action', 'latest_quizzes');	
		$view->display();
		}
    
}

?>