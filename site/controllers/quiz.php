<?php
/**
 * @version		$Id: quiz.php 01 2012-06-30 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined( '_JEXEC' ) or die();
jimport('joomla.application.component.controller');
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT_SITE.'/helpers/helper.php';
require_once JPATH_COMPONENT . '/helpers/awardpackage.php';
class AwardPackageControllerQuiz extends JControllerLegacy {

	function __construct() {

		parent::__construct();

		$this->registerDefaultTask('get_latest_quizzes');

		$this->registerTask('latest', 'get_latest_quizzes');
		$this->registerTask('popular', 'get_popular_quizzes');
		$this->registerTask('toprated', 'get_top_rated_quizzes');
		$this->registerTask('search', 'redirect_to_search');
		$this->registerTask('my_quizzes', 'get_user_quizzes');
		$this->registerTask('my_responses', 'get_user_responses');
		$this->registerTask('tag', 'get_tagged_quizzes');

		$this->registerTask('subscribe', 'subscribe_category_feed');
		$this->registerTask('unsubscribe', 'unsubscribe_category_feed');

		$this->registerTask('feed', 'get_rss_feed');
	}

	function get_latest_quizzes(){
		$user = JFactory::getUser();
		if($user->guest) {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage'));
		} else {
			$view = $this->getView('quiz', 'html');
			$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
			$users_model = & JModelLegacy::getInstance('users', 'AwardpackageUsersModel');
			$view->setModel($model, true);
			$view->setModel($users_model, false);
			$view->assign('action', 'latest_quizzes');
			$view->display();
		}
	}

	function get_top_rated_quizzes(){
		$view = $this->getView('quiz', 'html');
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$users_model = & JModelLegacy::getInstance('users','AwardpackageUsersModel');

		$view->setModel($model, true);
		$view->setModel($users_model, false);
		$view->assign('action', 'top_rated_quizzes');

		$view->display();
	}

	function get_user_quizzes(){

		$view = $this->getView('quiz', 'html');
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$users_model = & JModelLegacy::getInstance('users','AwardpackageUsersModel');

		$view->setModel($model, true);
		$view->setModel($users_model, false);
		$view->assign('action', 'user_quizzes');

		$view->display();
	}

	function get_user_responses(){

		$view = $this->getView('quiz', 'html');
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$users_model = & JModelLegacy::getInstance('users','AwardpackageUsersModel');

		$view->setModel($model, true);
		$view->setModel($users_model, false);
		$view->assign('action', 'user_responses');

		$view->display();
	}

	function get_popular_quizzes(){

		$view = $this->getView('quiz', 'html');
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$users_model = & JModelLegacy::getInstance('users','AwardpackageUsersModel');

		$view->setModel($model, true);
		$view->setModel($users_model, false);
		$view->assign('action', 'popular_quizzes');

		$view->display();
	}

	public function redirect_to_search(){

		$app = JFactory::getApplication();
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );

		$search_keywords = $app->input->getString('q', null);
		$search_username = $app->input->getString('u', null);

		if(empty($search_keywords) && empty($search_username)){

			$view = $this->getView('search', 'html');

			$view->setModel($model, true);
			$view->display();
		} else {
			$users_model = & JModelLegacy::getInstance('users','AwardpackageUsersModel');
			$view = $this->getView('quiz', 'html');

			$view->setModel($model, true);
			$view->setModel($users_model, false);
			$view->assign('action', 'search');
			$view->display();
		}
	}

	function get_tagged_quizzes(){

		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$users_model = & JModelLegacy::getInstance('users','AwardpackageUsersModel');
		$view = $this->getView('quiz', 'html');

		$view->setModel($model, true);
		$view->setModel($users_model, false);
		$view->assign('action', 'tagged_quizzes');

		$view->display();
	}

	function get_tags(){

		$user = JFactory::getUser();

		if(!$user->authorise('quiz.create', Q_APP_NAME) && !$user->authorise('quiz.manage', Q_APP_NAME)){

			echo json_encode(array('error'=>JText::_('NOT_AUTHORIZED')));
		}else{

			$app = JFactory::getApplication();
			$term = trim($app->input->getString('term', null));

			if(!empty($term) && strlen($term) > 1){

				$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
				$tags = $model->search_tags($term);
				$result = array();

				if(!empty($tags)){

					foreach ($tags as $tag){

						$result[] = $tag->tag_text;
					}
				}

				echo json_encode($result);
			}
		}

		jexit();
	}

	public function get_tags_listing(){

		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$view = $this->getView('tags', 'html');

		$view->setModel($model, true);
		$view->display();
	}

	public function get_tag_details(){

		$user = JFactory::getUser();

		if(!$user->authorise('quiz.manage', Q_APP_NAME)){

			echo json_encode(array('error'=>JText::_('JERROR_ALERTNOAUTHOR')));
		} else {

			$app = JFactory::getApplication();
			$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );

			$tag_id = $app->input->getInt('tagid');

			if($tag_id){

				$tag = $model->get_tag_details($tag_id);
				echo json_encode(array('tag'=>$tag));
			} else {

				echo json_encode(array('error'=>JText::_('MSG_MISSING_REQUIRED')));
			}
		}

		jexit();
	}

	public function delete_tag(){

		$user = JFactory::getUser();

		if($user->guest || !$user->authorise('quiz.manage', Q_APP_NAME)){

			echo json_encode(array('error'=>JText::_('JERROR_ALERTNOAUTHOR')));
		} else {

			$app = JFactory::getApplication();
			$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );

			$tag_id = $app->input->getInt('tagid', 0);

			if($tag_id > 0){

				if($model->delete_tag($tag_id)){

					echo json_encode(array('data'=>1));
				} else {

					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
				}
			} else {

				echo json_encode(array('error'=>JText::_('MSG_MISSING_REQUIRED')));
			}
		}

		jexit();
	}

	public function subscribe_category_feed(){

		$user = JFactory::getUser();

		if(!$user->guest && $user->authorise('quiz.subscrcat', Q_APP_NAME)){

			$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
			$app = JFactory::getApplication();

			$catid = $app->input->getInt('id', 0);
			$type = $catid > 0 ? 2 : 3;

			if($model->subscribe($type, $user->id, $catid)){

				echo json_encode(array('error'=>($type == 2 ? JText::_('MSG_CATEGORY_SUBSCRIBED') : JText::_('MSG_ALL_CATEGORIES_SUBSCRIBED'))));
			} else {

				echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
			}
		} else {

			echo json_encode(array('error'=>JText::_('JERROR_ALERTNOAUTHOR')));
		}

		jexit();
	}

	public function unsubscribe_category_feed(){

		$user = JFactory::getUser();

		if(!$user->guest && $user->authorise('quiz.subscrcat', Q_APP_NAME)){

			$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
			$app = JFactory::getApplication();

			$catid = $app->input->getInt('id', 0);
			$type = $catid > 0 ? 2 : 3;

			if($model->unsubscribe($type, $user->id, $catid)){

				echo json_encode(array('error'=>($type == 2 ? JText::_('MSG_CATEGORY_UNSUBSCRIBED') : JText::_('MSG_ALL_CATEGORIES_UNSUBSCRIBED'))));
			} else {

				echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
			}
		} else {

			echo json_encode(array('error'=>JText::_('JERROR_ALERTNOAUTHOR')));
		}

		jexit();
	}

	public function get_rss_feed(){

		$app = JFactory::getApplication();
		$view = $this->getView('quiz', 'feed');
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );

		$app->input->set('format', 'feed');
		$app->input->set('type', 'rss');
		$view->setModel($model, true);
		$view->display();
	}
}
?>
