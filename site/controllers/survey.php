<?php
/**
 * @version		$Id: survey.php 01 2013-02-01 11:37:09Z maverick $
 * @package		corejoomla.surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT_SITE.'/helpers/helperzs.php';
require_once JPATH_COMPONENT . '/helpers/awardpackage.php';

class AwardPackageControllerSurvey extends JControllerLegacy {
	
	function __construct(){
		
		parent::__construct();		
		$this->registerDefaultTask('get_latest_surveys');
		$this->registerTask('all', 'get_all_surveys');
		$this->registerTask('popular', 'get_popular_surveys');
		$this->registerTask('search', 'search_surveys');
	}

	function get_all_surveys() {

		$user = JFactory::getUser();
		
		if(!$user->guest) {		
			/*temporary by aditya*/
			$view = $this->getView('survey', 'html');
			$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
			$users_model = & JModelLegacy::getInstance( 'userszs', 'AwardpackageUsersModel' );
			$categories_model = & JModelLegacy::getInstance( 'categorieszs', 'AwardpackageUsersModel' );
		
			$view->setModel($model, true);
			$view->setModel($users_model, false);
			$view->setModel($categories_model, false);
			$view->assign('action', 'all_surveys');
			$view->display();
		} else {			
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}
	}
	
	function get_latest_surveys() {
		
		$view = $this->getView('survey', 'html');
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );		
		$users_model = & JModelLegacy::getInstance( 'userszs', 'AwardpackageUsersModel' );
		$categories_model = & JModelLegacy::getInstance( 'categorieszs', 'AwardpackageUsersModel' );		
		$view->setModel($model, true);		
		$view->setModel($users_model, false);
		$view->setModel($categories_model, false);		
		$view->assign('action', 'latest_surveys');
		$view->display();
	}
	
	function get_popular_surveys() {

		$view = $this->getView('survey', 'html');
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$users_model = & JModelLegacy::getInstance( 'userszs', 'AwardpackageUsersModel' );
		$categories_model = & JModelLegacy::getInstance( 'categorieszs', 'AwardpackageUsersModel' );
		
		$view->setModel($model, true);
		$view->setModel($users_model, false);
		$view->setModel($categories_model, false);
		$view->assign('action', 'popular_surveys');
		$view->display();
	}
	
	function search_surveys() {

		$view = $this->getView('survey', 'html');
		$model = JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$users_model = & JModelLegacy::getInstance( 'userszs', 'AwardpackageUsersModel' );
		$categories_model = & JModelLegacy::getInstance( 'categorieszs', 'AwardpackageUsersModel' );
		//var_dump(		$categories_model);
		$view->setModel($model, true);
		$view->setModel($users_model, false);
		$view->setModel($categories_model, false);
		$view->assign('action', 'search_surveys');
		$view->display();
	}
}