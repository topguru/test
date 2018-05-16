<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.controller');
class AwardPackageControllerUpackageselect extends JControllerLegacy {
	
	function __construct(){
		parent::__construct();
	}
	
	function selectPackage(){
		$view = $this->getView('upackageselect', 'html');
		$view->display();
	}

	function doSelectPackage(){
		$model 	= &JModelLegacy::getInstance('upackage','AwardpackageUsersModel');
		$package = JRequest::getVar('package');
		$ret = $model->updatePackageForUser($package);
		if($ret) {
			$modelUser = &JModelLegacy::getInstance('useraccount','AwardpackageUsersModel');
			if(isset($_SESSION['login__'])){
				$data = $_SESSION['login__'];
				unset($_SESSION['login__']);
				$userId = $data->id;
				$sessionData = $modelUser->getSessionData($userId);
				if(!isset($_SESSION['login__']) || empty($_SESSION['login__'])) {
					session_start();
					$_SESSION['login__'] = $sessionData;
				}
			}
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=uaccount&task=uaccount.getMainPage'),$msg);
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=user&task=user.registration_user'),$msg);
		}
	}
}
