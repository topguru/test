<?php
/**
 * @version		$Id: reports.php 01 2012-06-30 11:37:09Z maverick $
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
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageControllerReports extends JControllerLegacy {
	
	function __construct() {
		
		parent::__construct();
		$this->registerDefaultTask('get_quiz_reports');
		$this->registerTask('results', 'get_quiz_results');
		$this->registerTask('reports', 'get_quiz_reports');
		$this->registerTask('consolidated', 'get_consolidated_report');
		$this->registerTask('responses', 'get_responses_list');
		$this->registerTask('view_response', 'get_response_details');
		$this->registerTask('csvdownload', 'get_csv_report');
		$this->registerTask('remove_responses', 'remove_responses');
		$this->registerTask('location_report', 'get_location_report');
		$this->registerTask('device_report', 'get_device_report');
		$this->registerTask('os_report', 'get_os_report');

		$lang = JFactory::getLanguage();
		$lang->load(Q_APP_NAME, JPATH_ROOT);
		
		JLoader::import('joomla.application.component.model');
		JLoader::import('quiz', JPATH_ROOT.DS.'components'.DS.Q_APP_NAME.DS.'models');
	}

	function get_quiz_results(){
	
		$user = JFactory::getUser();
	
		if(!$user->authorise('quiz.results', Q_APP_NAME)){
	
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{
	
			$view = $this->getView('reports', 'html');
			$model = $this->getModel('quiz');
	
			$view->setModel($model, true);
			$view->assign('action', 'quiz_results');	
			$view->display('result');
		}
	}
	
	function get_quiz_reports(){
		
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('quiz');
		$id = $app->input->getInt('id', 0);		
		if(!$id || !$model->authorize_quiz($id)){			
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{			
			$view = $this->getView('reports', 'html');
			$view->setModel($model, true);
			$view->assign('action', 'quiz_reports');				
			$view->display();
		}
	}
	
	function get_consolidated_report(){

		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('quiz');
		$id = $app->input->getInt('id', 0);
		
		if(!$id || !$model->authorize_quiz($id)){		
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{
			$quiz = $model->get_consolidated_report($id);
			if(!empty($quiz)){				
				$view = $this->getView('reports', 'html');
				$view->setModel($model, true);
				$view->assign('action', 'consolidated_report');
				$view->assignRef('item', $quiz);
				
				$view->display('consolidated');
			} else {
				$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar('package_id'), false), JText::_('MSG_ERROR_PROCESSING'));
			}
		}
	}
	
	function get_responses_list(){

		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('quiz');
		$id = $app->input->getInt('id', 0);		
		if(!$id || !$model->authorize_quiz($id)){		
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{
			$view = $this->getView('reports', 'html');
			$view->setModel($model, true);
			$view->assign('action', 'quiz_responses');		
			$view->display('responses');
		}
	}
	
	function get_response_details(){
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('quiz');
		$id = $app->input->getInt('id', 0);
		
		if(!$id || !$model->authorize_quiz($id)){
		
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{

			$view = $this->getView('reports', 'html');
			$view->setModel($model, true);
			$view->assign('action', 'view_response');
			
			$view->display('response');
		}
	}
	
	function remove_responses(){
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('quiz');
		$id = $app->input->getInt('id', 0);
		
		if(!$id || !$model->authorize_quiz($id)){
		
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{
			
			$cids = $app->input->post->getArray(array('cid'=>'array'));
			$cids = $cids['cid'];
			JArrayHelper::toInteger($cids);
			$itemid = CJFunctions::get_active_menu_id();
			$msg = $model->remove_responses($id, $cids) ? JText::_('MSG_RESPONSES_DELETED_SUCCESSFULLY') : JText::_('MSG_ERROR_PROCESSING').(Q_DEBUG_ENABLED ? $model->getError() : '');
			
			$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=responses&id='.$id.$itemid), $msg);
		}
	}

	function get_location_report(){

		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('quiz');
		$id = $app->input->getInt('id', 0);
		
		if(!$id || !$model->authorize_quiz($id)){
		
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{		
			$view = $this->getView('reports', 'html');
			$view->setModel($model, true);
			$view->assign('action', 'location_report');		
			$view->display('locations');
		}
	}

	function get_device_report(){
	
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('quiz');
		$id = $app->input->getInt('id', 0);	
		if(!$id || !$model->authorize_quiz($id)){	
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{	
			$view = $this->getView('reports', 'html');
			$view->setModel($model, true);
			$view->assign('action', 'device_report');	
			$view->display('devices');
		}
	}

	function get_os_report(){
	
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$model = $this->getModel('quiz');
		$id = $app->input->getInt('id', 0);	
		if(!$id || !$model->authorize_quiz($id)){	
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{	
			$view = $this->getView('reports', 'html');
			$view->setModel($model, true);
			$view->assign('action', 'os_report');	
			$view->display('oses');
		}
	}
	
	function get_csv_report(){
	
		$itemid = CJFunctions::get_active_menu_id();
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$quiz_id = $app->input->getInt('id', 0);
	
		if($user->guest) {
				
			$redirect_url = base64_encode(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=reports&id='.$quiz_id.$itemid));
				
			$this->setRedirect(CJFunctions::get_login_url($redirect_url, $itemid), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
				
			if(!$user->authorise('quiz.create', Q_APP_NAME) && !$user->authorise('quiz.manage', Q_APP_NAME)){
	
				$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid), JText::_('MSG_UNAUTHORIZED'));
			}else{
	
				if(!$quiz_id) {
						
					$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid), JText::_('MSG_UNAUTHORIZED'));
				}else{
						
					$model = $this->getModel('quiz');
					$return = $model->get_reponse_data_for_csv($quiz_id);
						
					if(empty($return)){
	
						$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=reports&id='.$quiz_id.$itemid), JText::_('MSG_ERROR_PROCESSING').$model->getError());
					}else{
	
						$responses = array();
	
						foreach ($return->responses as $response){
								
							$responses[$response->id] = new stdClass();
							$responses[$response->id]->created_by = $response->created_by;
							$responses[$response->id]->created = $response->created;
							$responses[$response->id]->username = $response->username;
							$responses[$response->id]->name = $response->name;
							$responses[$response->id]->questions = array();
	
							foreach ($return->questions as $question){
	
								$responses[$response->id]->questions[$question->id] = new stdClass();
								$responses[$response->id]->questions[$question->id]->answer = '';
							}
						}
	
						if(!empty($return->entries)){
								
							foreach ($return->entries as $entry){
	
								if(isset($responses[$entry->response_id]) && isset($responses[$entry->response_id]->questions[$entry->question_id])){
										
									if(!empty($entry->answer)){
	
										if(empty($responses[$entry->response_id]->questions[$entry->question_id]->answer)){
												
											$responses[$entry->response_id]->questions[$entry->question_id]->answer = $entry->answer;
										}else{
												
											$responses[$entry->response_id]->questions[$entry->question_id]->answer .= ('|'.$entry->answer);
										}
									}
	
									if(!empty($entry->answer2)){
	
										if(empty($responses[$entry->response_id]->questions[$entry->question_id]->answer)){
												
											$responses[$entry->response_id]->questions[$entry->question_id]->answer = $entry->answer2;
										}else{
												
											$responses[$entry->response_id]->questions[$entry->question_id]->answer .= ('|'.$entry->answer2);
										}
									}
	
									if(!empty($entry->free_text)){
	
										if(empty($responses[$entry->response_id]->questions[$entry->question_id]->answer)){
												
											$responses[$entry->response_id]->questions[$entry->question_id]->answer = $entry->free_text;
										}else{
												
											$responses[$entry->response_id]->questions[$entry->question_id]->answer .= ('|'.$entry->free_text);
										}
									}
								}
							}
						}
	
						$csv_array = array();
						$string = 'Response ID, User ID, Response Date, Username, User Display Name';
	
						foreach ($return->questions as $question){
								
							$string = $string.',"'.str_replace('"', '""', $question->title).'"';
						}
	
						array_push($csv_array, $string);
	
						foreach ($responses as $id => $response){
								
							$string = $id.','.$response->created_by.','.$response->created.',"'.$response->username.'","'.$response->name.'"';
								
							foreach ($response->questions as $id=>$question){
	
								$string = $string.',"'.str_replace('"', '""', $question->answer).'"';
							}
								
							array_push($csv_array, $string);
						}
	
						$filename = 'Quiz_'.$quiz_id.'_'.date('d-m-Y').'.csv';
						$file = JPATH_ROOT.DS.'tmp'.DS.$filename;
	
						$exts = array('.php','.htm','.html','.ph4','.ph5');
						$found = false;
	
						foreach($exts as $l=>$ext){
								
							if (file_exists('index'.$ext)) {
	
								$found = true;
							}
						}
	
						if(!$found){
								
							$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=reports&id='.$quiz_id.$itemid), JText::_('MSG_ERROR_PROCESSING'));
						}else{
								
							$fh = fopen($file, 'w') or die("can't open ".$filename." file");
								
							foreach($csv_array as $line){
	
								fwrite($fh, $line."\n");
							}
								
							fclose($fh);
	
							if(!file_exists($file)) die("I'm sorry, the file doesn't seem to exist.");
	
							header('Content-type: text/csv');
							header('Content-Disposition: attachment;filename='.$filename);
								
							readfile($file);
						}
					}
				}
			}
		}
		
		jexit();
	}
}
?>
