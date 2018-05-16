<?php
/**
 * @version		$Id: controller.php 01 2011-11-08 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.admin
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageControllerSurveys extends JControllerAdmin {
	
	function __construct() {
		
		parent::__construct();
		
		$this->registerDefaultTask('get_survey_list');
		$this->registerTask('surveys.publish','publish_list');
		$this->registerTask('surveys.unpublish','unpublish_list');
		$this->registerTask('publish_item','publish_item');
		$this->registerTask('unpublish_item','unpublish_item');
		$this->registerTask('delete','delete_surveys');
		$this->registerTask('set_private','set_private_survey');
		$this->registerTask('unset_private','unset_private_survey');
		$this->registerTask('set_anonymous','set_anonymous_survey');
		$this->registerTask('unset_anonymous','unset_anonymous_survey');
		$this->registerTask('set_publicperms','set_public_permissions');
		$this->registerTask('unset_publicperms','unset_public_permissions');
		$this->registerTask('refresh','refresh_counters');
		$this->registerTask('responses','get_responses');
		$this->registerTask('responses.delete','delete_responses');
		$this->registerTask('copy','copy_surveys');
		$this->registerTask('add','');
		$this->registerTask('edit','edit_survey');

		//$lang = JFactory::getLanguage();
		//$lang->load(S_APP_NAME, JPATH_ROOT);
		
		JLoader::import('joomla.application.component.model');
		//JLoader::import('survey', JPATH_ROOT.DS.'components'.DS.S_APP_NAME.DS.'models');
	}

	function get_survey_list(){
		
		$view = $this->getView('surveys', 'html');
		$model = $this->getModel('surveys');
		$view->setModel($model, true);
		$view->display();
	}
	
	function get_responses(){
		
		$app = JFactory::getApplication();
		$id = $app->input->getInt('id', 0);
		
		if($id){
			
			$view = $this->getView('responses', 'html');
			$model = $this->getModel('surveys');
			$view->setModel($model, true);
			$view->display();
		}else{			
			$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys', JText::_('COM_COMMUNITYSURVEYS_MSG_INVALID_ID'));
		}
	}

	function publish_list(){		
		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));		
		if(empty($ids['cid'])){			
			$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys&package_id=' . JRequest::getVar('package_id'), JText::_('COM_COMMUNITYSURVEYS_MSG_INVALID_ID'));
		} 		
		if(!JFactory::getUser()->authorise('core.edit.state', S_APP_NAME)){			
			return CJFunctions::throw_error(JText::_('COM_COMMUNITYSURVEYS_NOT_AUTHORIZED'), 403);
		}else{			
			$model = $this->getModel('surveys');
			JArrayHelper::toInteger($ids['cid']);
			$model->set_status($ids['cid'], 'published', true);			
			$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys&package_id=' . JRequest::getVar('package_id'), JText::_('COM_COMMUNITYSURVEYS_MSG_SUCCESS'));
		}
	}

	function unpublish_list(){
		
		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		
		if(empty($ids['cid'])){
			
			$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys&package_id=' . JRequest::getVar('package_id'), JText::_('COM_COMMUNITYSURVEYS_MSG_INVALID_ID'));
		} 
		
		if(!JFactory::getUser()->authorise('core.edit.state', S_APP_NAME)){
			
			return CJFunctions::throw_error(JText::_('COM_COMMUNITYSURVEYS_NOT_AUTHORIZED'), 403);
		}else{
			
			$model = $this->getModel('surveys');
			JArrayHelper::toInteger($ids['cid']);
			$model->set_status($ids['cid'], 'published', false);
			
			$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys&package_id=' . JRequest::getVar('package_id'), JText::_('COM_COMMUNITYSURVEYS_MSG_SUCCESS'));
		}
	}

	public function publish_item(){
	
		$return = $this->change_state('published', true);
	
		if($return == 1){
				
			echo json_encode(array('data'=>1));
		} else if(!JFactory::getUser()->authorise('core.edit.state', S_APP_NAME)){
			
			echo json_encode(array('error'=>JText::_('COM_COMMUNITYSURVEYS_NOT_AUTHORIZED')));
		} else {
				
			$msg = $return == 0 ? JText::_('COM_COMMUNITYSURVEYS_MSG_ERROR') : JText::_('COM_COMMUNITYSURVEYS_MSG_NO_ITEM_SELECTED');
			echo json_encode(array('error'=>$msg));
		}
	
		jexit();
	}

	public function unpublish_item(){

		$return = $this->change_state('published', false);
	
		if($return == 1){
	
			echo json_encode(array('data'=>1));
		} else if(!JFactory::getUser()->authorise('core.edit.state', S_APP_NAME)){
			
			echo json_encode(array('error'=>JText::_('COM_COMMUNITYSURVEYS_NOT_AUTHORIZED')));
		} else {
	
			$msg = $return == 0 ? JText::_('COM_COMMUNITYSURVEYS_MSG_ERROR') : JText::_('COM_COMMUNITYSURVEYS_MSG_NO_ITEM_SELECTED');
			echo json_encode(array('error'=>$msg));
		}
	
		jexit();
	}

	function set_private_survey(){

		$return = $this->change_state('private_survey', true);
		
		if($return == 1){
		
			echo json_encode(array('data'=>1));
		} else {
		
			$msg = $return == 0 ? JText::_('COM_COMMUNITYSURVEYS_MSG_ERROR') : JText::_('COM_COMMUNITYSURVEYS_MSG_NO_ITEM_SELECTED');
			echo json_encode(array('error'=>$msg));
		}
		
		jexit();
	}
	
	function unset_private_survey(){

		$return = $this->change_state('private_survey', false);
		
		if($return == 1){
		
			echo json_encode(array('data'=>1));
		} else {
		
			$msg = $return == 0 ? JText::_('COM_COMMUNITYSURVEYS_MSG_ERROR') : JText::_('COM_COMMUNITYSURVEYS_MSG_NO_ITEM_SELECTED');
			echo json_encode(array('error'=>$msg));
		}
		
		jexit();
	}

	function set_anonymous_survey(){

		$return = $this->change_state('anonymous', true);
		
		if($return == 1){
		
			echo json_encode(array('data'=>1));
		} else {
		
			$msg = $return == 0 ? JText::_('COM_COMMUNITYSURVEYS_MSG_ERROR') : JText::_('COM_COMMUNITYSURVEYS_MSG_NO_ITEM_SELECTED');
			echo json_encode(array('error'=>$msg));
		}
		
		jexit();
	}
	
	function unset_anonymous_survey(){

		$return = $this->change_state('anonymous', false);
		
		if($return == 1){
		
			echo json_encode(array('data'=>1));
		} else {
		
			$msg = $return == 0 ? JText::_('COM_COMMUNITYSURVEYS_MSG_ERROR') : JText::_('COM_COMMUNITYSURVEYS_MSG_NO_ITEM_SELECTED');
			echo json_encode(array('error'=>$msg));
		}
		
		jexit();
	}

	function set_public_permissions(){

		$return = $this->change_state('public_permissions', true);

		if($return == 1){

			echo json_encode(array('data'=>1));
		} else {

			$msg = $return == 0 ? JText::_('COM_COMMUNITYSURVEYS_MSG_ERROR') : JText::_('COM_COMMUNITYSURVEYS_MSG_NO_ITEM_SELECTED');
			echo json_encode(array('error'=>$msg));
		}

		jexit();
	}
	
	function unset_public_permissions(){

		$return = $this->change_state('public_permissions', false);

		if($return == 1){

			echo json_encode(array('data'=>1));
		} else {

			$msg = $return == 0 ? JText::_('COM_COMMUNITYSURVEYS_MSG_ERROR') : JText::_('COM_COMMUNITYSURVEYS_MSG_NO_ITEM_SELECTED');
			echo json_encode(array('error'=>$msg));
		}

		jexit();
	}

	public function change_state($column, $state){
	
		$app = JFactory::getApplication();
		$id = $app->input->getInt('cid');

		if($id){

			$model = $this->getModel('surveys');

			if($model->set_status($id, $column, $state)){

				return 1;
			} else {

				return 0;
			}
		}

		return -1;
	}

	function delete_surveys(){
		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		if(count($ids['cid']) == 0){
			$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys&package_id='.JRequest::getVar('package_id'), JText::_('COM_COMMUNITYSURVEYS_MSG_INVALID_ID'));
		} if(!JFactory::getUser()->authorise('core.delete', S_APP_NAME)){			
			return CJFunctions::throw_error(JText::_('COM_COMMUNITYSURVEYS_NOT_AUTHORIZED'), 403);
		} else{
			JArrayHelper::toInteger($ids['cid']);
			$model = $this->getModel('surveys');
			$model->delete_surveys($ids['cid']);
			foreach ($model->getErrors() as $error){
				$app->enqueueMessage($error, 'error');
			}
			$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys&package_id='.JRequest::getVar('package_id'), JText::_('COM_COMMUNITYSURVEYS_MSG_SUCCESS'));
		}
	}

	function delete_responses(){

		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		$id = $app->input->getInt('id', 0);

		if(count($ids['cid']) == 0){

			$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys&task=responses&id='.$id, JText::_('COM_COMMUNITYSURVEYS_MSG_INVALID_ID'));
		} if(!JFactory::getUser()->authorise('core.delete', S_APP_NAME)){
			
			return CJFunctions::throw_error(JText::_('COM_COMMUNITYSURVEYS_NOT_AUTHORIZED'), 403);
		} else{
			
			JArrayHelper::toInteger($ids['cid']);
			$cids = implode(',', $ids['cid']);

			$model = $this->getModel('surveys');

			if(!$model->remove_survey_responses($cids, $id)){
			
				foreach ($model->getErrors() as $error){
					
					$app->enqueueMessage($error, 'error');
				}
			}

			$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys&task=responses&id='.$id, JText::_('COM_COMMUNITYSURVEYS_MSG_SUCCESS'));
		}
	}
	
	function copy_surveys(){
		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		if(count($ids['cid']) == 0){
			$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys&package_id=' . JRequest::getVar('package_id'), JText::_('COM_COMMUNITYSURVEYS_MSG_INVALID_ID'));
		} if(!JFactory::getUser()->authorise('core.create', S_APP_NAME)){
			return CJFunctions::throw_error(JText::_('COM_COMMUNITYSURVEYS_NOT_AUTHORIZED'), 403);
		} else{
			JArrayHelper::toInteger($ids['cid']);
			$model = JModelLegacy::getInstance( 'surveys', 'Awardpackagemodel' );			
			foreach ($ids['cid'] as $cid){				
				if(!$model->copy_survey($cid)){						
					foreach ($model->getErrors() as $error){
						$app->enqueueMessage($error, 'error');
					}
				}
			}
			$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys&package_id=' . JRequest::getVar('package_id'), JText::_('COM_COMMUNITYSURVEYS_MSG_SUCCESS'));
		}
	}
	
	function add_survey(){		
		$app = JFactory::getApplication();
		$uniq_id = md5(uniqid(rand(), true));
		$id = $app->input->getString('package_id', '0');
		$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveysetting&task=surveysetting.do_first&package_id='.$id.'&uniq_id='.$uniq_id, null);
	}

	function edit_survey(){	
		$app = JFactory::getApplication();
		$id = $app->input->getInt('id', 0);		
		$this->setRedirect('index.php?option='.S_APP_NAME.'&view=form&task=form&id='.$id, null);
	}
	
	function refresh_counters(){		
		$model = $this->getModel('surveys');
		$model->refresh_response_counters();

		$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys', JText::_('COM_COMMUNITYSURVEYS_MSG_SUCCESS'));
	}
}
?>