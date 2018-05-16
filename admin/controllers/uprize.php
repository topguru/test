<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT . '/helpers/awardpackage.php';

class AwardPackageControllerUprize extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}
	
	function confirm(){		
		$user = JFactory::getUser();


		if($user->guest) {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage'));
		} else {
			$users = AwardPackageHelper::getUserData();
			$rChoice = 1;
			
			$model = & JModelLegacy::getInstance( 'udonation', 'AwardpackageUsersModel' );
			$data = array();			
			$data['user_id'] = $users->id;			
			$data['package_id'] = $users->package_id;
			if($model->saveFunding($data)) {
				//$model->updateHistory($users->id, $users->package_id, JRequest::getVar('prizevalue'), 'prize', $rChoice, $users->username);
				$model->ClaimedHistory($users->id, $users->package_id, JRequest::getVar('prizevalue'), JRequest::getVar('prizename'));
					
			}	
			
			}
			
		$view = $this->getView('prize', 'html');
		$view->assign('action', 'main_page');
		$view->display();
		}
		
	function save_symbol(){
		$model = & JModelLegacy::getInstance( 'ugiftcode', 'AwardpackageUsersModel' );

		$button_check = JRequest::getVar('button_check');
		$symbol_id = JRequest::getVar('prizeId');
		$giftcode_id = JRequest::getVar('giftcodeId');
		
		$view = $this->getView('ugiftcode', 'html');
		$view->assign('action', 'main_page');
		$view->display();
		}
	function getMainPage(){		
			$view = $this->getView('uprize', 'html');
			$view->assign('action', 'main_page');
			$view->display();
	}
}