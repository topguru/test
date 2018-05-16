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
class AwardPackageControllerResponse extends JControllerLegacy {

	function __construct() {

		parent::__construct();

		$this->registerDefaultTask('quiz_intro');

		/* Responses */
		$this->registerTask('respond', 'quiz_intro');
		$this->registerTask('response_form', 'response_form');
		$this->registerTask('save_response', 'save_response');
		$this->registerTask('previous_page', 'previous_page');

		$this->registerTask('ajx_save_response', 'ajx_save_response');
	}

	function quiz_intro() {

		$user = JFactory::getUser();
		$itemid = CJFunctions::get_active_menu_id();

		//if(!$user->authorise('quiz.quiz_intro', Q_APP_NAME)){
		//	$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid.'&task=quiz.get_latest_quizzes', false), JText::_('MSG_NOT_ALLOWED_TO_RESPOND'));
		//}else{
		$app = JFactory::getApplication();
		$id = $app->input->getInt('id', 0);
		//if(!$id) CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		$view = $this->getView('response', 'html');
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$quiz = $model->get_quiz_details($id);
			
		if(!empty($quiz) && $quiz->id) {
			if($quiz->skip_intro == 1){
				$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=response&task=response.response_form&id='.$quiz->id, false));
			} else {
					
				$view->setModel($model, true);
				$view->assign('action','quiz_intro');
				$view->assignRef('item', $quiz);

				$view->display();
			}
		} else {

			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}
		//}
	}

	function response_form(){
		$user = JFactory::getUser();
		//if(!$user->authorise('response.response_form', Q_APP_NAME)){
		//	CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		//}else{
		$view = $this->getView('response', 'html');
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$quiz = $model->do_create_update_response();

		if(!empty($quiz) && $quiz->id && $quiz->response_id){
			if($quiz->duration > 0){
				$created = JFactory::getDate($quiz->response_created);
				$now = JFactory::getDate();
				$timeleft = ($quiz->duration * 60) - ($now->toUnix() - $created->toUnix());
				if($timeleft <= 0){
					return $this->finalize_response($quiz, $quiz->response_id);
				}
			}
			$this->display_to_quiz_form($model, $quiz);
		} else {
			$msg = $model->getError();
			if(empty($msg)){
				$msg = JText::_('MSG_UNAUTHORIZED');
			}
			$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_latest_quizzes.'.$itemid, false));
		}
		//}
	}

	function previous_page(){

		$app = JFactory::getApplication();
		$id = $app->input->getInt('id', 0);
		$current = $app->input->getInt('current', 0);
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );

		$result = $model->get_previous_page($id, $current);
		$pageno = $app->input->post->getInt('pageno', 0);

		$app->input->set('current', (isset($result[1]) ? $result[1]->sort_order : 0));
		$app->input->set('finalize', 0);
		$app->input->set('pageno', $pageno > 1 ? $pageno - 2 : 0);

		return $this->save_response();
	}

	function save_response(){

		$user = JFactory::getUser();

		//if(!$user->authorise('response.quiz_intro', Q_APP_NAME)){
		//	CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		//}else{

		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$app = JFactory::getApplication();
		$itemid = CJFunctions::get_active_menu_id();

		$id = $app->input->getInt('id', 0);
		$rid = $app->input->getInt('rid', 0);
		$pid = $app->input->getInt('pid', 0);
		$finalize = $app->input->getInt('finalize', 0);

		$quiz = $model->get_quiz_details($id, true, true);
		$quiz->response_id = $rid;

		if($model->save_response($quiz->id, $pid, $rid)){
			if($finalize > 0 || $model->is_response_expired($quiz->id, $rid)){
				return $this->finalize_response($quiz, $rid);
			}else{
				$this->display_to_quiz_form($model, $quiz);
			}
		} else {
			$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=response&task=response.response_form&id='.$quiz->id.$itemid, false), JText::_('MSG_ERROR_PROCESSING'));
		}
		//}
	}

	function display_to_quiz_form($model, $quiz){
		$app = JFactory::getApplication();
		$pageno = $app->input->getInt('pageno', 0);
		$current = $app->input->getInt('current', 0);

		$result = $model->get_next_page($quiz->id, $current);
		
		if(empty($result) || empty($result[0])){

			if(Q_DEBUG_ENABLED){

				$app->enqueueMessage($model->getError());
			}

			$this->finalize_response($quiz, $quiz->response_id);
		}else{

			$quiz->pid = $result[0]->id;
			$quiz->current = $result[0]->sort_order;
			$quiz->finalize = isset($result[1]) ? 0 : 1;
			$quiz->start = $current > 0 ? false : true;
			$quiz->questions = $model->get_questions($quiz->id, $quiz->pid);
			$quiz->pageno = $pageno + 1;
			$quiz->response_created = isset($quiz->response_created) ? $quiz->response_created : $model->get_response_created_time($quiz->response_id);
			$responses = $model->get_response_details($quiz->response_id, $quiz->id, $quiz->pid, false);

			foreach ($quiz->questions as $question){

				$question->responses = array();

				foreach ($responses as $response){

					if($question->id == $response->question_id){

						$question->responses[] = $response;
					}
				}
			}

			$view = $this->getView('response', 'html');
			$view->setModel($model, true);
			$view->assign('task','response_form');
			$view->assignRef('item', $quiz);

			$view->display('form');
		}
	}

	function finalize_response($quiz, $response_id){
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$itemid = CJFunctions::get_active_menu_id();
		$model = JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		
		//$model->update_fund_history($user->id, $quiz->id, $response_id);
		
		$quiz = $model->getQuiz($quiz->id);
		if($quiz != null){
			$createdby = $quiz->created_by;
		}
		
		if ($createdby != $user->id){
			$model->update_fund_history($createdby, $quiz->id, $response_id);
			$model->update_giftcode_history($user->id,  $quiz->id, $response_id);			
		}
		
		$score = $model->finalize_response($quiz->id, $response_id);

		if($score !== false){
			$params = JComponentHelper::getParams(Q_APP_NAME);
			$menuid = CJFunctions::get_active_menu_id(true, 'index.php?option='.Q_APP_NAME.'&view=quiz');
			$link = JRoute::_('index.php?option='.Q_APP_NAME.'&view=response&task=response.quiz_intro&id='.$quiz->id);
			$userdisplayname = $params->get('user_display_name', 'name');

			/*****************************************************/
			/******************** EMAILS *************************/
			if($params->get('new_response_notification', 1) == 1 || $params->get('admin_new_response_notification', 1) == 1){
					
				$from = $app->getCfg('mailfrom' );
				$fromname = $app->getCfg('fromname' );
				$results_url = JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_quiz_results&id='.$quiz->id.'&rid='.$response_id.$itemid, false, -1);
				$subject = JText::sprintf('MSG_MAIL_NEW_RESPONSE_SUB', $quiz->title);

				if($params->get('new_response_notification', 1) == 1){

					$body = JText::sprintf('MSG_MAIL_NEW_RESPONSE_BODY', $quiz->username, $user->$userdisplayname, $quiz->title, $results_url);
					//CJFunctions::send_email($from, $fromname, $quiz->email, $subject, $body, 1);
				}

				if($params->get('admin_new_response_notification', 1) == 1){

					$admin_emails = $model->get_admin_emails($params->get('admin_user_groups', 8));
					$username = $user->guest ? JText::_('LBL_GUEST') : $user->name.'('.$user->username.')';
					$body = JText::sprintf('MSG_MAIL_NEW_RESPONSE_BODY', 'Administrator', $username, $quiz->title, $results_url);

					if(!empty($admin_emails)){

						CJFunctions::send_email($from, $fromname, $admin_emails, $subject, $body, 1);
					}
				}
			}
			/******************** EMAILS *************************/
			/*****************************************************/

			// Award points
			QuizHelper::award_points($params, $user->id, 2, $quiz->id, JText::sprintf('TXT_RESPONDED_QUIZ', $user->$userdisplayname, $link, $quiz->title));

			// Stream activity
			CJFunctions::stream_activity(
			$params->get('activity_stream_type', 'none'),
			$user->id,
			array(
					'command' => CQ_JSP_QUIZ_RESPONSE,
					'component' => Q_APP_NAME,
					'title' => JText::sprintf('TXT_RESPONDED_QUIZ', '{actor}', $link, $quiz->title),
					'href' => $link,
					'description' => $quiz->description,
					'length' => $params->get('stream_character_limit', 256),
					'icon' => 'components/'.Q_APP_NAME.'/assets/images/icon-16-quiz.png',
					'group' => 'Quiz'
					));

					// Trigger badges
					$badge_system = $params->get('badge_system', 'none');

					if($badge_system == 'cjblog' && file_exists(JPATH_ROOT.'/components/com_cjblog/api.php')){

						require_once JPATH_ROOT.'/components/com_cjblog/api.php';
							
						// trigger badges rule for awarding for posting n number of responses
						$my_responses = $model->get_user_responses($user->id);
						CjBlogApi::trigger_badge_rule('com_communityquiz.num_responses', array('num_responses'=>$my_responses, 'ref_id'=>$response_id), $user->id);

						// trigger badge for passing the quiz (if user passed)
						if($quiz->cutoff > 0 && $quiz->cutoff <= $score)
						{
							CjBlogApi::trigger_badge_rule('com_communityquiz.test_passed', array('num_responses'=>1, 'ref_id'=>$response_id), $user->id);
						}
					}

					//if($quiz->show_answers == '1' && $user->authorise('quiz.results', Q_APP_NAME)){
					if($quiz->show_answers == '1'){

						$this->setRedirect(
						JRoute::_('index.php?option='.Q_APP_NAME.'&view=reports&task=reports.get_quiz_results&id='.$quiz->id.'&rid='.$response_id.$itemid, false),
						JText::_('MSG_RESPONSE_SAVED'));
					}else{

						$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid, false), JText::_('MSG_RESPONSE_SAVED'));
					}
		}else{

			$msg = Q_DEBUG_ENABLED ? JText::_('MSG_ERROR_PROCESSING').$model->getError() : JText::_('MSG_ERROR_PROCESSING');
			$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz_intro&id='.$quiz->id.$itemid, false), $msg);
		}
	}

	public function ajx_save_response(){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );

		$id = $app->input->getInt('id');
		$rid = $app->input->getInt('rid', 0);
		$pid = $app->input->getInt('pid', 0);
		$key = $app->input->getCmd('key', '');

		$quiz = $model->get_quiz_details($id);

		//if(isset($quiz->catid) && !$user->authorise('response.quiz_intro', Q_APP_NAME)){
		if(isset($quiz->catid)){

			echo json_encode(array('error'=>JText::_('MSG_NOT_ALLOWED_TO_RESPOND')));
		} else if(!empty($quiz) && !empty($quiz->id)){

			if($rid == 0) {

				$quiz = $model->do_create_update_response();

				if(empty($quiz) || !$quiz->id || !$quiz->response_id){

					echo json_encode(array('error'=>$model->getError()));
					jexit();
				}

				$rid = $quiz->response_id;
			}

			if($rid > 0 && ($user->guest || $model->authorize_quiz_response($rid, $user->id))){

				$return = null;
				$data = new stdClass();
				$data->rid = $rid;
				$data->key = $key;
				$data->pid = 0;
				$data->finished = false;
				$data->lastpage = false;
				$score = 0;

				$pages = $model->get_pages($id);

				if(!empty($pages)) {

					if($pid > 0){

						$return = $model->save_response($id, $pid, $rid, true);

						if(!$return){

							echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(Q_DEBUG_ENABLED ? $model->getError().'| Error: 100' : '')));
							jexit();
						}
					}

					// now get the next page or finalize quiz
					foreach ($pages as $i=>$page){

						if($pid == 0 || $page == $pid) {

							if($pid > 0 && empty($pages[$i+1])){ // no more pages exist, last page reached.

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

								//if($quiz->show_answers == '1' && $user->authorise('quiz.results', Q_APP_NAME)){
								if($quiz->show_answers == '1'){

									$data->message = $this->get_quiz_result($questions);
								} else {

									$data->message = JText::_('MSG_THANK_YOU_FOR_TAKING_QUIZ');
								}

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

					if($data->finished){

						$score = $this->finalize_response($quiz, $data->rid, false);

						if($score === false){

							echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(Q_DEBUG_ENABLED ? $model->getError().'| Error: 101' : '')));
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

						$data->content = $this->get_quiz_form($questions);
					}

					echo json_encode(array('quiz'=>$data));
				} else {

					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(Q_DEBUG_ENABLED ? $model->getError().'| Error: 102' : '')));
				}
			} else {

				echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(Q_DEBUG_ENABLED ? $model->getError().'| Error: 103 - rid='.$rid.' key='.$key.' userid='.$user->id : '')));
			}
		} else {

			echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(Q_DEBUG_ENABLED ? $model->getError().'| Error: 104' : '')));
		}

		jexit();
	}

	private function get_quiz_form($questions){

		$user = JFactory::getUser();
		$options = JComponentHelper::getParams(Q_APP_NAME);

		$wysiwyg = $user->authorise('quiz.wysiwyg', Q_APP_NAME);
		$bbcode = $options->get('default_editor', 'bbcode') == 'bbcode' ? true : false;
		$content = $options->get('process_content_plugins', 0) == 1;

		require_once JPATH_ROOT.DS.'components'.DS.Q_APP_NAME.DS.'helpers'.DS.'formfields.php';
		$formfields = new QuizFormFields(true, $bbcode, $content);
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
					$content .= $formfields->get_image_radio_question($question, $class, CQ_IMAGES_URI);
					break;
				case 12:
					$content .= $formfields->get_image_checkbox_question($question, $class, CQ_IMAGES_URI);
					break;
				default: break;
			}
		}

		return $content;
	}

	private function get_quiz_result($questions){

		$user = JFactory::getUser();
		$params = JComponentHelper::getParams(Q_APP_NAME);

		$wysiwyg = $user->authorise('quiz.wysiwyg', Q_APP_NAME) ? true : true;
		$bbcode =  $params->get('default_editor', 'bbcode') == 'bbcode';
		$content = $params->get('process_content_plugins', 0) == 1;

		require_once JPATH_COMPONENT.DS.'helpers'.DS.'qnresults.php';
		$generator = new QuizQuestionResults($wysiwyg, $bbcode, $content);

		$html = '
		<div class="well well-transperant">
			<h4 class="page-header no-margin-top margin-bottom-10">'.JText::_('LBL_LEGEND').'</h4>
			<span class="margin-right-20"><i class="icon-hand-up"></i> '.JText::_('LBL_YOUR_ANSWER').'</span>
			<span class="margin-right-20"><i class="icon-check"></i> '.JText::_('LBL_CORRECT_ANSWER').'</span>
			<span class="margin-right-20"><i class="icon-ok"></i> '.JText::_('LBL_SELECTED_CORRECT').'</span>
			<span class="margin-right-20"><i class="icon-remove"></i> '.JText::_('LBL_SELECTED_WRONG').'</span>
			<span class="margin-right-20"><i class="icon-thumbs-down"></i> '.JText::_('LBL_NOT_SELECTED_CORRECT').'</span>
		</div>
				
		<div class="results-wrapper">';
		$class = '';
		foreach($questions as $item){
			switch ($item->question_type){
				case 1:
					$html .= $generator->get_page_header_question($item, $class);
					break;
				case 2:
				case 3:
				case 4:
					$html .= $generator->get_choice_question($item, $class);
					break;
				case 5:
				case 6:
					$html .= $generator->get_grid_question($item, $class);
					break;
				case 11:
				case 12:
					$html .= $generator->get_image_question($item, $class, CQ_IMAGES_URI);
					break;
			}
		}
		$html .= '
		</div>
		<div class="well" id="quiz-score">
			<p class="lead no-margin-bottom">'.JText::sprintf('TXT_YOUR_FINAL_SCORE', $generator->get_score(), $generator->get_count(), $generator->get_percentage()).'</p>
		</div>';

		return $html;
	}
}
?>
