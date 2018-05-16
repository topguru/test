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
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageControllerQuizzes extends JControllerLegacy {

	function __construct(){

		parent::__construct();
		
		$this->registerDefaultTask('get_quizzes_list');
		$this->registerTask('publish_item', 'publish_item');
		$this->registerTask('unpublish_item', 'unpublish_item');
		$this->registerTask('publish', 'publish_list');
		$this->registerTask('unpublish', 'unpublish_list');
		$this->registerTask('remove', 'delete');
		$this->registerTask('refresh', 'refresh');
		$this->registerTask('edit', 'edit_quiz');
		$this->registerTask('save', 'save_quiz');
		$this->registerTask('cancel', 'cancel');
		$this->registerTask('copy','copy_quiz');
		$this->registerTask('add','add_quiz');
		$this->registerTask('edit','edit_quiz');
	}
	
	public function get_quizzes_list(){
		$view = $this->getView('quizzes', 'html');
		$model = $this->getModel('quiz');
		$category_model = $this->getModel('categories');
		$users_model = $this->getModel('users');
		
		$view->setModel($model, true);
		$view->setModel($category_model, false);
		$view->setModel($users_model, false);
		$view->assign('action', 'list');
		$view->display();
	}

	public function edit_quiz(){
	
		$view = $this->getView('quizzes', 'html');
		$model = $this->getModel('quiz');
		$category_model = $this->getModel('categories');
	
		$view->setModel($model, true);
		$view->setModel($category_model, false);
		$view->assign('action', 'form');
		$view->display('form');
	}
	
	public function save_quiz(){
		
		$app = JFactory::getApplication();
		$model = $this->getModel('quiz');
		$item = new stdClass();
		
    	$item->id = $app->input->getInt('id', 0);
		$item->title = $app->input->getString('title');
		$item->alias = $app->input->getString('alias');
		$item->description = CJFunctions::get_clean_var('description', true);
		$item->catid = $app->input->getInt('catid', 0);
		$item->published = $app->input->getInt('published', 0);
		$item->duration = $app->input->post->getInt('duration', 0);
		$item->skip_intro = $app->input->post->getInt('skip_intro', 0);
		$item->multiple_responses = $app->input->post->getInt('multiple_responses', 0);
		$item->show_answers = $app->input->post->getInt('show_answers', 0);
		$item->show_template = $app->input->post->getInt('show_template', 0);
		
		if($item->id && $item->catid && !empty($item->title) && $model->save_quiz($item)){			
			$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_SUCCESS'));
		} else {

			$app->enqueueMessage(JText::_('MSG_ERROR'), 'error');
			
			$view = $this->getView('quizzes', 'html');
			$category_model = $this->getModel('categories');
				
			$view->setModel($model, true);
			$view->setModel($category_model, false);
			$view->assign('action', 'form');
			$view->assign('item', $item);
			$view->display('form');
		}
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
		
		$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function unpublish_list(){
		
		$return = $this->change_state(0);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));
		
		$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function change_state($state){

		$app = JFactory::getApplication();
		$ids = $app->input->getArray(array('cid'=>'array'));
		
		if(!empty($ids['cid'])){
				
			$model = $this->getModel('quiz');
			JArrayHelper::toInteger($ids['cid']);
			$id = implode(',', $ids['cid']);

			if($model->set_status($id, $state)){
		
				return 1;
			} else {

				return 0;
			}
		}
		
		return -1;
	}
	
	public function delete(){

		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		
		if(!empty($ids['cid'])){
		
			$model = $this->getModel('quiz');
			JArrayHelper::toInteger($ids['cid']);
		
			if($model->delete_quizzes($ids['cid'])){
		
				$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_SUCCESS'));
			} else {
		
				$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_ERROR'));
			}
		} else {
		
			$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_NO_ITEM_SELECTED'));
		}
	}
	
	public function refresh(){
		$model = $this->getModel('categories');
		$model->refresh_categories();
		
		$model = $this->getModel('users');
		$model->refresh_user_stats();
		
		$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_SUCCESS'));
	}
	
	public function cancel(){
		
		$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_CANCELLED'));
	}
	
	public function copy_quiz(){

		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		
		if(count($ids['cid']) == 0){		
			$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar("package_id"), JText::_('MSG_NO_ITEM_SELECTED'));
		} if(!JFactory::getUser()->authorise('quiz.create', Q_APP_NAME)){		
			return CJFunctions::throw_error(JText::_('MSG_ERROR'), 403);
		} else{		
			JArrayHelper::toInteger($ids['cid']);
			$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageModel' );			
			foreach ($ids['cid'] as $cid){		
				if(!$model->copy_quiz($cid)){		
					foreach ($model->getErrors() as $error){		
						$app->enqueueMessage($error, 'error');
					}
				}
			}
		
			$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar("package_id"), JText::_('MSG_SUCCESS'));
		}
	}

	function edit_survey(){
	
		$app = JFactory::getApplication();
		$id = $app->input->getInt('id', 0);
	
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=form&task=form&id='.$id, null);
	}
	
	function add_quiz(){		
		$uniq_id = md5(uniqid(rand(), true));		
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=form&task=form.create_edit_quiz&package_id='.JRequest::getVar("package_id").'&uniq_id='.$uniq_id, null);
	}
}