<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.controller');

class AwardPackageControllerUserquiz extends JControllerLegacy {
	
	function __construct(){

		parent::__construct();

		$this->registerDefaultTask('get_my_quizzes');
		$this->registerTask('my_responses', 'get_my_responses');
		$this->registerTask('my_subscriptions', 'get_my_subscriptions');
		$this->registerTask('unsubscribe', 'unsubscribe_all');
	}

	public function get_my_quizzes(){

		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$view = $this->getView('userquiz', 'html');

		$view->setModel($model, true);
		$view->assign('action', 'quizzes');
		$view->display();
	}

	public function get_my_responses(){

		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$view = $this->getView('userquiz', 'html');

		$view->setModel($model, true);
		$view->assign('action', 'responses');
		$view->display('responses');
	}

	public function get_my_subscriptions(){

		$model = & JModelLegacy::getInstance('users','AwardpackageUsersModel');
		$view = $this->getView('userquiz', 'html');

		$view->setModel($model, true);
		$view->assign('action', 'subscriptions');
		$view->display('subscriptions');
	}

	public function unsubscribe_all(){

		$app = JFactory::getApplication();
		$model = & JModelLegacy::getInstance('users','AwardpackageUsersModel');

		$subid = $app->input->get('subid');
		$itemid = CJFunctions::get_active_menu_id(true, 'index.php?option='.A_APP_NAME.'&view=answers');

		if(!empty($subid) && $model->delete_subscriptions($subid)){

			$this->setRedirect(JRoute::_('index.php?option='.A_APP_NAME.'&view=answers'.$itemid), JText::_('MSG_UNSUBSCRIBED_ALL'));
		} else {

			$this->setRedirect(JRoute::_('index.php?option='.A_APP_NAME.'&view=answers'.$itemid), JText::_('MSG_UNSUBSCRIBED_ALL_ERROR'));
		}
	}
}
