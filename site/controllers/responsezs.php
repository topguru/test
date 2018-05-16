<?php
/**
 * @version		$Id: response.php 01 2012-06-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined( '_JEXEC' ) or die();
jimport('joomla.application.component.controller');
require_once JPATH_SITE.'/components/com_awardpackage/helpers/helperzs.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_SITE.'/helpers/awardpackage.php';
class AwardPackageControllerResponsezs extends JControllerLegacy {
	function __construct() {

		parent::__construct();

		$this->registerDefaultTask('survey_intro');

		/* Responses */
		$this->registerTask('take_survey', 'survey_intro');
		$this->registerTask('response_form', 'response_form');
		$this->registerTask('save_response', 'save_response');
		$this->registerTask('previous_page', 'previous_page');
		$this->registerTask('view_result', 'view_result');
		$this->registerTask('end_message', 'get_end_message');

		$this->registerTask('ajx_save_response', 'ajx_save_response');
	}

	public function survey_intro() {
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );

		$id = $app->input->getInt('id', 0);
						
		$create = $id > 0 ? true : false;
		$survey = $model->do_create_or_update_response($create); // do create if id exist, i.e. public survey

		if($survey->error){			
			return $this->do_survey_redirect($survey);
		}
		if($survey->skip_intro == 1){							
			$itemid = CJFunctions::get_active_menu_id();
			$url = 'index.php?option='.S_APP_NAME.'&view=survey&task=response_form&id='.$survey->id.':'.$survey->alias.($create ? '&key='.$survey->key : '').$itemid;
			$this->setRedirect(JRoute::_($url, false));
		} else {
			$view = $this->getView('responsezs', 'html');
			$view->setModel($model, true);
			$view->assign('action', 'survey_intro');
			$view->assignRef('item', $survey);
			$view->display();
		}
	}

	public function response_form(){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$survey = $model->do_create_or_update_response();
		if($survey->error){

			return $this->do_survey_redirect($survey);
		}

		$params = JComponentHelper::getParams(S_APP_NAME);
		$session = JFactory::getSession();
			
		if($session->get('captcha.'.$survey->id, 0) == 0 && $survey->private_survey == 0 && $survey->skip_intro != 1 && $params->get('enable_captcha', 0) == 1){

			JPluginHelper::importPlugin('captcha');
			$dispatcher = JDispatcher::getInstance();
			$result = $dispatcher->trigger('onCheckAnswer', $app->input->post->getString('recaptcha_response_field'));

			if(!$result[0]){

				$survey_itemid = CJFunctions::get_active_menu_id(true, 'index.php?option='.S_APP_NAME.'&view=survey');
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=take_survey&id='.$survey->id.':'.$survey->alias.$survey_itemid, false), JText::_('MSG_INVALID_CAPTCHA'));
				return;
			}

			$session->set('captcha.'.$survey->id, 1);
		}

		$this->display_to_survey_form($model, $survey);
	}

	public function previous_page(){

		$app = JFactory::getApplication();
		$id = $app->input->getInt('id', 0);
		$current = $app->input->getInt('current', 0);
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );

		$result = $model->get_previous_page($id, $current);
		$pageno = $app->input->post->getInt('pageno', 0);

		$app->input->set('current', (isset($result[1]) ? $result[1]->sort_order : 0));
		$app->input->set('finalize', 0);
		$app->input->set('pageno', $pageno > 1 ? $pageno - 2 : 0);

		return $this->save_response();
	}

	public function get_end_message(){
		$user = JFactory::getUser();
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$view = $this->getView('responsezs', 'html');
		$view->setModel($model, true);
		$view->assign('action', 'end_message');
		$view->display('message');
	}

	public function save_response(){

		$user = JFactory::getUser();
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$app = JFactory::getApplication();
		$itemid = CJFunctions::get_active_menu_id();

		$id = $app->input->getInt('id', 0);
		$rid = $app->input->getInt('rid', 0);
		$pid = $app->input->getInt('pid', 0);
		$finalize = $app->input->getInt('finalize', 0);

		$survey = $model->get_survey_details($id, 0, false, true, true);
		$survey->response_id = $rid;
		//if(isset($survey->catid) && !$user->authorise('core.respond', S_APP_NAME.'.category.'.$survey->catid)){
		if(isset($survey->catid) && 1!=1){
			$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$survey_itemid, false), JText::_('MSG_NOT_ALLOWED_TO_RESPOND'));
		} else if(!empty($survey) && ($result = $model->save_response($survey->id, $pid, $rid))){
				
			if($finalize > 0 || $result->finalize > 0 || $model->is_response_expired($survey->id, $rid)){
				if(S_DEBUG_ENABLED){						
					$app->enqueueMessage('ID: '.$id.'| Response ID: '.$rid.'| Page ID: '.$pid.'| Finalize: '.$finalize.'| Result Finalize: '.$result->finalize.'| Expired: '.$model->is_response_expired($survey->id, $rid));
				}
				return $this->finalize_response($survey, $rid);
			} else if($result->page_id > 0){
				$next_page = $model->get_next_page($id, 0, $result->page_id);
				$this->display_to_survey_form($model, $survey, $next_page);
			} else{
				$this->display_to_survey_form($model, $survey);
			}
		} else {
				
			$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=responsezs&task=responsezs.survey_intro&id='.$survey->id.$itemid, false), JText::_('MSG_ERROR_PROCESSING'));
		}
	}

	public function display_to_survey_form($model, $survey, $next_page = null){

		$app = JFactory::getApplication();
		$pageno = $app->input->getInt('pageno', 0);
		$current = $app->input->getInt('current', 0);

		if(!empty($next_page)){
				
				$next_page = $model->get_next_page($survey->id, $current);
			foreach ($survey->response_id as $i=>$page){

				if($page == $next_page[0]->id) {
						
					$pageno = $i;
					break;
				}
			}
		}

		if(!empty($next_page) || !empty($next_page[0])){

			if(S_DEBUG_ENABLED){

				$app->enqueueMessage($model->getError());
			}
				
			$this->finalize_response($survey, $survey->response_id);
		}else{

			$survey->pid = $next_page[0]->id;
			$survey->current = $next_page[0]->sort_order;
			$survey->finalize = isset($next_page[1]) ? 0 : 1;
			$survey->start = $current > 0 ? false : true;
			$survey->questions = $model->get_questions($survey->id, $survey->pid);
			$survey->pageno = $pageno + 1;
			$responses = $model->get_response_details($survey->response_id, $survey->id, $survey->pid, false);
				
			foreach ($survey->questions as $question){

				$question->responses = array();

				if(!empty($responses)){
						
					foreach ($responses as $response){

						if($question->id == $response->question_id){
								
							$question->responses[] = $response;
						}
					}
				}
			}
			
			
			$view = $this->getView('responsezs', 'html');
			$view->setModel($model, true);
			$view->assign('task','response_form');
			$view->assignRef('item', $survey);
			$view->display('form');
			
		}
	}

	private function finalize_response($survey, $response_id, $redirect = true){

		$user = JFactory::getUser();
		$itemid = CJFunctions::get_active_menu_id();
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$app = JFactory::getApplication();
		
		//$model->update_fund_history($user->id, $survey->id, $response_id);
		
		$survey = $model->getSurvey($survey->id);
		if($survey != null){
			$createdby = $survey->created_by;
		}
		
		if ($createdby != $user->id){
			$model->update_fund_history($createdby,  $survey->id, $response_id);
			$model->update_giftcode_history($user->id,  $survey->id, $response_id);
		}

		if($model->finalize_response($survey->id, $response_id)){

			$params = JComponentHelper::getParams(S_APP_NAME);
			$userdisplayname = $params->get('user_display_name', 'name');
				
			SurveyHelper::award_points($params, $survey->created_by, 2, $response_id, JText::sprintf('TXT_RESPONDED_SURVEY', $survey->username, $survey->title));
			SurveyHelper::award_points($params, $user->id, 3, $response_id, JText::sprintf('TXT_RESPONDED_SURVEY', $user->$userdisplayname, $survey->title));

			$menuid = CJFunctions::get_active_menu_id(true, 'index.php?option='.S_APP_NAME.'&view=survey');
			$link = $survey->private_survey == 1
			? $survey->title
			: JHtml::link(JRoute::_('index.php?option='.S_APP_NAME.'&view=responsezs&task=responsezs.take_survey&id='.$survey->id.":".$survey->alias.$menuid), $survey->title);
				
			CJFunctions::stream_activity(
			$params->get('activity_stream_type', 'none'),
			$user->id,
			array(
					'command' => 'com_communitysurveys.response',
					'component' => S_APP_NAME,
					'title' => JText::sprintf('TXT_RESPONDED_SURVEY', '{actor}', $link),
					'description' => $survey->introtext,
					'length' => $params->get('stream_character_limit', 256),
					'icon' => 'components/'.S_APP_NAME.'/assets/images/icon-16-surveys.png',
					'group' => 'Surveys'
					));

					// 			if($params->get('admin_new_response_notification', 1) == 1){
						
					// 				$link = JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=reports&id='.$survey->id.':'.$survey->alias.$itemid, false, -1);
					// 				$body = JText::sprintf('EMAIL_ADMIN_NEW_RESPONSE_BODY', $user->username, $survey->title, $link, $app->getCfg('sitename'));
					// 				CJFunctions::send_email($this->_config[S_SENDER_EMAIL], $this->_config[S_SENDER_NAME], $this->_config[S_ADMIN_EMAIL], JText::_('EMAIL_ADMIN_NEW_RESPONSE_TITLE'), $body, 1);
					// 			}

					if($params->get('new_response_notification', 1) == 1 || $params->get('admin_new_response_notification', 1) == 1){

						$from = $app->getCfg('mailfrom' );
						$fromname = $app->getCfg('fromname' );
						$link = JRoute::_('index.php?option='.S_APP_NAME.'&view=reportsz&task=reportsz.get_survey_reports&id='.$survey->id, false, -1);
						$body = '';

						if($survey->anonymous == 1){
								
							$body = JText::sprintf('EMAIL_NEW_RESPONSE_ANONYMOUS_BODY', $survey->username, $survey->title, $link, $app->getCfg('sitename'));
						} else {
								
							$body = JText::sprintf('EMAIL_NEW_RESPONSE_BODY', $survey->username, $user->username, $survey->title, $link, $app->getCfg('sitename'));
						}

						//CJFunctions::send_email($from, $fromname, $survey->email, JText::_('EMAIL_NEW_RESPONSE_TITLE'), $body, 1);
					}

					if($redirect){

						if(empty($survey->redirect_url)){

							if($survey->public_permissions == '1' && $user->authorise('core.results', S_APP_NAME)){
									
								$this->setRedirect(
								JRoute::_('index.php?option='.S_APP_NAME.'&view=responsezs&task=responsezs.survey_results&id='.$survey->id.'&rid='.$response_id.$itemid, false),
								JText::_('MSG_SURVEY_COMPLETE'));
							} else {
									
								$this->setRedirect(
								JRoute::_('index.php?option='.S_APP_NAME.'&view=responsezs&task=responsezs.end_message&id='.$survey->id, false),
								JText::_('MSG_SURVEY_COMPLETE'));
							}
						} else {
								
							$this->setRedirect(
								JRoute::_('index.php?option='.S_APP_NAME.'&view=reportszs&task=reportszs.get_survey_reports&id='.$survey->id.'&rid='.$response_id.$itemid, false),
								JText::_('MSG_SURVEY_COMPLETE'));
								
						}
					} else {

						return true;
					}
		}else{
				
			$msg = S_DEBUG_ENABLED ? JText::_('MSG_ERROR_PROCESSING').$model->getError() : JText::_('MSG_ERROR_PROCESSING');
				
			if($redirect){
					
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=responsezs&task=responsezs.survey_intro&id='.$survey->id.$itemid, false), $msg);
			} else {

				return false;
			}
		}
	}

	public function view_result(){

		$user = JFactory::getUser();

		if(!$user->authorise('core.results', S_APP_NAME)){

			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{

			$view = $this->getView('responsezs', 'html');
			$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
			$view->setModel($model, true);
			$view->assign('task', 'results');
			$view->display('result');
		}
	}

	public function ajx_save_response(){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );

		$id = $app->input->post->getInt('id');
		$rid = $app->input->post->getInt('rid', 0);
		$pid = $app->input->post->getInt('pid', 0);
		$key = $app->input->post->getCmd('key', '');

		$survey = $model->get_survey_details($id);

		if(isset($survey->catid) && !$user->authorise('core.respond', S_APP_NAME.'.category.'.$survey->catid)){
				
			echo json_encode(array('error'=>JText::_('MSG_NOT_ALLOWED_TO_RESPOND')));
		} else if(!empty($survey) && !empty($survey->id)){
				
			if($rid == 0) {

				$survey = $model->do_create_or_update_response();

				if($survey->error){
						
					echo json_encode(array('error'=>$this->do_survey_redirect($survey, true)));
					jexit();
				}

				$rid = $survey->response_id;
				$key = $survey->key;
			}
				
			if($rid > 0 && !empty($key) && ($user->guest || $model->authorize_survey_response($rid, $key, $user->id))){

				$return = null;
				$data = new stdClass();
				$data->rid = $rid;
				$data->key = $key;
				$data->pid = 0;
				$data->finished = false;
				$data->lastpage = false;

				$pages = $model->get_pages($id);

				if(!empty($pages)) {
						
					if($pid > 0){

						$return = $model->save_response($id, $pid, $rid, true);

						if(!$return){
								
							echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError().'| Error: 100' : '')));
							jexit();
						}
					}
						
					// now get the next page or finalize survey
					if(!empty($return) && $return->finalize > 0){

						$data->message = $survey->endtext;
						$data->finished = true;
					} else if (!empty($return) && $return->page_id > 0){

						$data->pid = $return->page_id;
					} else {

						foreach ($pages as $i=>$page){
								
							if($pid == 0 || $page == $pid) {

								if(empty($pages[$i+1])){ // no more pages exist, last page reached.
										
									$data->message = $survey->endtext;
									$data->finished = true;
								} else {
										
									$data->pid = $pid == 0 ? $page : $pages[$i+1];
										
									if(empty($pages[$i+2])){ // last page

										$data->lastpage = true;
									}
								}

								break;
							}
						}
					}
						
					if($data->finished){

						if(!$this->finalize_response($survey, $data->rid, false)){
								
							echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError().'| Error: 101' : '')));
							jexit();
						}
					}
						
					if($data->pid > 0){ // get the questions now and build response

						$questions = $model->get_questions($id, $data->pid);
						$responses = $model->get_response_details($data->rid, $id, $data->pid, false);
							
						foreach ($questions as &$question){
								
							$question->responses = array();
								
							foreach ($responses as $response){

								if($question->id == $response->question_id){
										
									$question->responses[] = $response;
								}
							}
						}

						$data->content = $this->get_survey_form($questions);
					}
						
					echo json_encode(array('survey'=>$data));
				} else {
						
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError().'| Error: 102' : '')));
				}
			} else {

				echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError().'| Error: 103 - rid='.$rid.' key='.$key.' userid='.$user->id : '')));
			}
		} else {
				
			echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError().'| Error: 104' : '')));
		}

		jexit();
	}

	private function get_survey_form($questions){

		$user = JFactory::getUser();
		$options = JComponentHelper::getParams(S_APP_NAME);

		$wysiwyg = $user->authorise('core.wysiwyg', S_APP_NAME);
		$bbcode = $options->get('default_editor', 'bbcode') == 'bbcode' ? true : false;
		$content = $options->get('process_content_plugins', 0) == 1;

		require_once JPATH_ROOT.DS.'components'.DS.S_APP_NAME.DS.'helpers'.DS.'formfields.php';
		$formfields = new SurveyFormFields($wysiwyg, $bbcode, $content);
		$class = '';
		$content = '';

		foreach ($questions as $qid=>$question){
				
			switch($question->question_type){
				case 1:
					$content .= $formfields->get_page_header_question($question, $class);
					break;
				case 2:
					$content .= $formfields->get_radio_question($question, $class);
					break;
				case 3:
					$content .= $formfields->get_checkbox_question($question, $class);
					break;
				case 4:
					$content .= $formfields->get_select_question($question, $class);
					break;
				case 5:
					$content .= $formfields->get_grid_radio_question($question, $class);
					break;
				case 6:
					$content .= $formfields->get_grid_checkbox_question($question, $class);
					break;
				case 7:
					$content .= $formfields->get_single_line_textbox_question($question, $class);
					break;
				case 8:
					$content .= $formfields->get_multiline_textarea_question($question, $class);
					break;
				case 9:
					$content .= $formfields->get_password_textbox_question($question, $class);
					break;
				case 10:
					$content .= $formfields->get_rich_textbox_question($question, $class);
					break;
				case 11:
					$content .= $formfields->get_image_radio_question($question, $class, S_IMAGES_URI);
					break;
				case 12:
					$content .= $formfields->get_image_checkbox_question($question, $class, S_IMAGES_URI);
					break;
				default: break;
			}
		}

		return $content;
	}

	private function do_survey_redirect($survey, $silent = false){

		$error_msg = '';
		$redirect = false;
		$itemid = CJFunctions::get_active_menu_id(true, 'index.php?option='.S_APP_NAME.'&view=survey');

		switch ($survey->error){
				
			case 1: // no survey found with the sid or surey key
				$error_msg = 'RC 1: '.JText::_('MSG_SURVEY_NOT_FOUND');
				break;
			case 2: // Looks like a deleted survey or some db error occurred
				$error_msg = 'RC 2: '.JText::_('MSG_ERROR_PROCESSING');
				break;
			case 3: // Accessing unpublished survey
				$error_msg = 'RC 3: '.JText::_('MSG_UNAUTHORIZED');
				break;
			case 4: // User is a guest and the user has no permission to respond
				$error_msg = 'RC 4: '.JText::_('MSG_LOGIN_TO_CONTINUE');
				$login_needed = true;
				break;
			case 5: // User is a registered user and has no permission to respond
				$error_msg = 'RC 5: '.JText::_('MSG_UNAUTHORIZED');
				break;
			case 6: // A private survey accessed without a private key/survey key
				$error_msg = 'RC 6: '.JText::_('MSG_PRIVATE_SURVEY_WITH_NO_KEY');
				break;
			case 7: // Survey accessed before it goes live, that is publish up date not yet reached
				$error_msg = 'RC 7: '.JText::_('MSG_SURVEY_NOT_YET_UP');
				break;
			case 8: // Survey accessed after it is closed, i.e. publish down date is over
				$error_msg = 'RC 8: '.JText::_('MSG_SURVEY_CLOSED');
				break;
			case 9: // The survey has already taken as found in response data in DB. The survey is accessed through key/unique url
				$error_msg = 'RC 9: '.JText::_('MSG_SURVEY_ALREADY_TAKEN');
				$redirect = true;
				break;
			case 10: // The survey has already taken as found in response data in DB. Survey key found in cookie.
				$error_msg = 'RC 10: '.JText::_('MSG_SURVEY_ALREADY_TAKEN');
				$redirect = true;
				break;
			case 11: // The survey has already taken as found in response data in DB. Response found with this IP.
				$error_msg = 'RC 11: '.JText::_('MSG_SURVEY_ALREADY_TAKEN');
				$redirect = true;
				break;
			case 12: // The survey has already taken as found in response data in DB. Response found with this user id.
				$error_msg = 'RC 12: '.JText::_('MSG_SURVEY_ALREADY_TAKEN');
				$redirect = true;
				break;
			case 13: // New response with unique key from invite page but already a response exist with this key. Not legimate access.
				$error_msg = 'RC 13: '.JText::_('MSG_ERROR_PROCESSING');
				break;
			case 14:// The number of responses recieved to the survey exceeded the allowed responses.
				$error_msg = 'RC 14: '.JText::_('MSG_EXCEED_RESPONSE_LIMIT');
				break;
			case 15: // There are no credits available to the survey owner to offer this response to the user.
				$error_msg = 'RC 15: '.JText::_('MSG_NO_CREDITS');
				break;
			case 16: // An error occurred while updating the newly create survey key for the response. DB Error.
				$error_msg = 'RC 16: ' .JText::_('MSG_ERROR_PROCESSING');
				break;
			case 17: // An error occurred while updating/inserting new response. DB Error.
				$error_msg = 'RC 17: ' . JText::_('MSG_ERROR_PROCESSING');
				break;
			case 18: // Something went wrong no survey object created
			default:
				$error_msg = 'RC 18: ' . JText::_('MSG_ERROR_PROCESSING');
				break;
		}

		if(S_DEBUG_ENABLED) {
				
			$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
			$error_msg = $error_msg . $model->getError();
		}

		if($silent){
				
			return $error_msg;
		} else if($login_needed){
				
			$activeitemid = CJFunctions::get_active_menu_id();
			$redirect = base64_encode(JURI::getInstance()->toString());
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login'.$activeitemid.'&return='.$redirect, false), $error_msg);
		} else if($redirect) {

			if(empty($survey->redirect_url)){

				if($survey->public_permissions == '1' && JFactory::getUser()->authorise('core.results', S_APP_NAME)){
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=responsezs&task=responsezs.view_result&id='.$survey->id.'&rid='.$survey->response_id.$itemid, false), $error_msg);
				} else {
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=responsezs&task=responsezs.get_end_message&id='.$survey->id, false), $error_msg);
				}
			} else {
					
				$this->setRedirect($survey->redirect_url, $error_msg);
			}
		} else {				
			$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=survey.get_all_surveys', false), $error_msg);
		}

		return;
	}
}
?>