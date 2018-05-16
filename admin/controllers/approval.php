<?php
/**
 * @version		$Id: quizzes.php 01 2011-12-12 11:37:09Z maverick $
 * @package		CoreJoomla.Quizzes
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper_quiz.php';
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
class AwardpackageControllerApproval extends JControllerLegacy {

	function __construct(){

		parent::__construct();
		
		$this->registerDefaultTask('get_quizzes_list');
		$this->registerTask('publish_item', 'publish_item');
		$this->registerTask('unpublish_item', 'unpublish_item');
		$this->registerTask('publish', 'publish_list');
		$this->registerTask('unpublish', 'unpublish_list');
		$this->registerTask('cancel', 'cancel');
		$this->registerTask('preview', 'preview_quiz');
	}
	
	public function get_quizzes_list(){
		
		$view = $this->getView('approval', 'html');
		$model = $this->getModel('quiz');
		$category_model = $this->getModel('categories');
		$users_model = $this->getModel('users');
		
		$view->setModel($model, true);
		$view->setModel($category_model, false);
		$view->setModel($users_model, false);
		$view->assign('action', 'list');
		$view->display();
	}

	public function preview_quiz(){
	
		$view = $this->getView('approval', 'html');
		$model = $this->getModel('quiz');
		$category_model = $this->getModel('categories');
		$users_model = $this->getModel('users');
	
		$view->setModel($model, true);
		$view->setModel($category_model, false);
		$view->setModel($users_model, false);
		$view->assign('action', 'preview');
		$view->display('preview');
	}
	
	public function publish_item(){
		
		$return = $this->change_state(1);
		
		if($return == 1){
			
			echo json_encode(array('data'=>1));
		} else {
			
			$msg = $return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED');
			echo json_encode(array('error'=>$msg));
		}
		
		jexit();
	}
	
	public function unpublish_item(){
		
		$return = $this->change_state(0);
		
		if($return == 1){
			
			echo json_encode(array('data'=>1));
		} else {
			
			$msg = $return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED');
			echo json_encode(array('error'=>$msg));
		}
		
		jexit();
	}
	
	public function publish_list(){
		
		$return = $this->change_state(1);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));
		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=approval&task=approval.get_quizzes_list&package_id='.JRequest::getVar('package_id'), false), msg);		
	}
	
	public function unpublish_list(){
		
		$return = $this->change_state(0);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));
		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=approval&task=approval.get_quizzes_list&package_id='.JRequest::getVar('package_id'), false), msg);
	}
	
	public function change_state($state){

		$app = JFactory::getApplication();
		$ids = $app->input->getArray(array('cid'=>'array'));
		
		if(!empty($ids['cid'])){
				
			$model = $this->getModel('quiz');
			JArrayHelper::toInteger($ids['cid']);
			$id = implode(',', $ids['cid']);

			if($model->set_status($id, $state)){

				if($state == 1){
					
					$return = $model->get_quizzes($ids['cid']);
					$quizzes = $return['quizzes'];
					$config = JComponentHelper::getParams(Q_APP_NAME);

					foreach ($quizzes as $quiz){
						
						$info = $quiz->title;
						QuizHelper::award_points($config, $quiz->created_by, 1, $quiz->id, $info);
						
						CJFunctions::stream_activity(
							$config->get('activity_stream_type', 'none'),
							$user->id,
							array(
								'command' => CQ_JSP_NEW_QUIZ,
								'component' => Q_APP_NAME,
								'title' => JText::sprintf('TXT_CREATED_QUIZ', $quiz->title),
								'href' => $link,
								'description' => $quiz->description,
								'length' => $config->get('stream_character_limit', 256),
								'icon' => 'components/'.Q_APP_NAME.'/assets/images/icon-16-quiz.png',
								'group' => 'Quiz'
							));
					}
				}
				
				return 1;
			} else {

				return 0;
			}
		}
		
		return -1;
	}
	
	public function cancel(){
		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=approval&task=approval.get_quizzes_list&package_id='.JRequest::getVar('package_id'), false), JText::_('MSG_CANCELLED'));
	}
}