<?php
/**
 * @version		$Id: form.php 01 2012-06-30 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');
jimport( 'joomla.filesystem.file' );
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT_SITE.'/helpers/helperzs.php';
require_once JPATH_COMPONENT . '/helpers/awardpackage.php';

class AwardPackageControllerFormzs extends JControllerLegacy {
	function __construct() {
		parent::__construct();
		$this->registerDefaultTask('create_edit_survey');
		/* create survey */
		$this->registerTask('form', 'create_edit_survey');
		$this->registerTask('edit', 'edit_questions');
		$this->registerTask('save', 'save_survey');
		$this->registerTask('get_tags', 'get_tags');
		$this->registerTask('save_qn','save_question');
		$this->registerTask('delete_qn', 'delete_question');
		$this->registerTask('move_qn','move_question');
		$this->registerTask('new_page','new_page');
		$this->registerTask('delete_page','remove_page');
		$this->registerTask('finalize', 'finalize_survey');
		$this->registerTask('update_order', 'update_ordering');
		$this->registerTask('upload_answer_image', 'upload_answer_image');
		$this->registerTask('save_rule', 'save_conditional_rule');
		$this->registerTask('remove_rule', 'remove_conditional_rule');
		$this->registerTask('copy', 'copy_survey');
		$this->registerTask('rename_page', 'rename_page');
		$this->registerTask('reorder_pages', 'reorder_pages');
	}


	function add_new_survey(){
		$uniq_id = md5(uniqid(rand(), true));
		$users = AwardPackageHelper::getUserData();
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=formzs&task=formzs.do_first&package_id='.$users->package_id.'&uniq_id='.$uniq_id, null);
	}

	function create_edit_survey(){
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$user	= JFactory::getUser();	
		$survey = $model->get_survey_details(JRequest::getVar("id"));
				
		$pages =  $model->get_pages_list(JRequest::getVar("id"));

		if(!empty($pages)) {
			$page = $pages[0];
			$uniq_key = $page->uniq_key;
		}
		$users = AwardPackageHelper::getUserData();	
		$survey->package_id = $users->package_id;
		$survey->uniq_id = $uniq_key;
		$survey->giftcodeQuantityComplete = array(0=>'');
		$survey->costPerResponseComplete = array(0=>'');
		$survey->giftcodeQuantityIncomplete = array(0=>'');
		$survey->costPerResponseIncomplete = array(0=>'');
		$survey->categorySurveyComplete = array('New');
		$survey->categorySurveyIncomplete = array('New');
		$survey->surveyPages = $model->get_pages($survey->package_id, $survey->uniq_id);	
		$s = (count($survey->surveyPages) > 0 ? $survey->surveyPages[count($survey->surveyPages)-1] : null);					
		$survey->pageTitle = ($s != null ? $s->title : '');
		$survey->pageId = ($s != null ? $s->id : '1');					
		$survey->surveyPage = $survey->pageId;
		$s = $model->get_survey_question_giftcode($survey->package_id,
						$survey->id, $survey->surveyPage, '1', $uniq_key);

		if(empty($s)) {
			$pageId = $model->create_page(0, $survey->uniq_id);
			$model->insert_survey_question_giftcode($survey->package_id,
			'0', $pageId, '1', $survey->uniq_id);

			$survey->surveyPages = $model->get_pages($survey->package_id, $survey->uniq_id);					  					
			$s = (count($survey->surveyPages) > 0 ? $survey->surveyPages[count($survey->surveyPages)-1] : null);					
			$survey->pageTitle = ($s != null ? $s->title : '');
			$survey->pageId = ($s != null ? $s->id : '');					
			$survey->surveyPage = $survey->pageId;						
		}
		$data = $model->get_survey_question_giftcode2($survey->package_id,
			'0', $survey->surveyPage, $survey->uniq_id);
		$arrQuestion = array();
		$i=0;
		foreach ($data as $d) {
			$arrQuestion[$i] = $d->question_id;
			$i++;
		}
		$survey->questionSelectedId = $arrQuestion[0];
		$survey->questionId = $data;

		$view = $this->getView('formzs', 'html');
		$view->survey = $survey;
		$view->display('doFirst');
	}

	function getQuestion(){
		$user = JFactory::getUser();
		if(!$user->guest) {
		$view = $this->getView('formzs', 'html');
			$view->assign('action', 'list_question');
			$view->display('list_question');
		}
	}
	
	public function do_first() {
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$user	= JFactory::getUser();

		$survey = new stdClass();
		$survey->id = JRequest::getVar("id",0);
		$survey->created_by = $survey->catid = $survey->duration = $survey->skip_intro = $survey->max_responses =
		$survey->anonymous = $survey->public_permissions = $survey->published = 0;
		$survey->show_answers = $survey->display_template = $survey->multiple_responses = $survey->backward_navigation =
		$survey->private_survey = $survey->display_notice = $survey->display_progress = $survey->notification = 1;
		$survey->title = $survey->introtext = $survey->endtext = $survey->custom_header = $survey->alias = $survey->redirect_url = $survey->restriction = '';
		$survey->publish_up = $survey->publish_down = '0000-00-00 00:00:00';
		$survey->created_by = $user->id;
		$survey->username = $user->username;
		$survey->tags = array();
		$survey->package_id = JRequest::getVar("package_id");
		$survey->uniq_id = JRequest::getVar("uniq_id");
		$survey->giftcodeQuantityComplete = array(0=>'');
		$survey->costPerResponseComplete = array(0=>'');
		$survey->giftcodeQuantityIncomplete = array(0=>'');
		$survey->costPerResponseIncomplete = array(0=>'');
		$survey->categorySurveyComplete = array('New');
		$survey->categorySurveyIncomplete = array('New');
		$survey->surveyPages = $model->get_pages($survey->package_id, $survey->uniq_id);
		$s = (count($survey->surveyPages) > 0 ? $survey->surveyPages[count($survey->surveyPages)-1] : null);
		$survey->pageTitle = ($s != null ? $s->title : '');
		$survey->pageId = ($s != null ? $s->id : '1');
		$survey->surveyPage = $survey->pageId;
		$s = $model->get_survey_question_giftcode($survey->package_id,
						'0', $survey->surveyPage, '1', $survey->uniq_id);		
		if(empty($s)) {
			$pageId = $model->create_page(0, $survey->uniq_id);
			$model->insert_survey_question_giftcode($survey->package_id,
			'0', $pageId, '1', $survey->uniq_id);

			$survey->surveyPages = $model->get_pages($survey->package_id, $survey->uniq_id);
			$s = (count($survey->surveyPages) > 0 ? $survey->surveyPages[count($survey->surveyPages)-1] : null);
			$survey->pageTitle = ($s != null ? $s->title : '');
			$survey->pageId = ($s != null ? $s->id : '');
			$survey->surveyPage = $survey->pageId;
		}
		$data = $model->get_survey_question_giftcode2($survey->package_id,
			'0', $survey->surveyPage, $survey->uniq_id);
		$arrQuestion = array();
		$i=0;
		foreach ($data as $d) {
			$arrQuestion[$i] = $d->question_id;
			$i++;
		}
		$survey->questionSelectedId = $arrQuestion[0];
		$survey->questionId = $data;

		$view = $this->getView('formzs', 'html');
		$view->survey = $survey;
		$view->display('doFirst');
	}

	public function do_edit() {
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$user	= JFactory::getUser();
		$survey = $model->get_survey_details(JRequest::getVar("id"));
		$pages =  $model->get_pages_list(JRequest::getVar("id"));
		if(!empty($pages)) {
			$page = $pages[0];
			$uniq_key = $page->uniq_key;
		}
		$survey->package_id = JRequest::getVar("package_id");
		$survey->uniq_id = $uniq_key;
		$survey->giftcodeQuantityComplete = array(0=>'');
		$survey->costPerResponseComplete = array(0=>'');
		$survey->giftcodeQuantityIncomplete = array(0=>'');
		$survey->costPerResponseIncomplete = array(0=>'');
		$survey->categorySurveyComplete = array('New');
		$survey->categorySurveyIncomplete = array('New');
		$survey->surveyPages = $model->get_pages($survey->package_id, $survey->uniq_id);
		$s = (count($survey->surveyPages) > 0 ? $survey->surveyPages[count($survey->surveyPages)-1] : null);
		$survey->pageTitle = ($s != null ? $s->title : '');
		$survey->pageId = ($s != null ? $s->id : '1');
		$survey->surveyPage = $survey->pageId;
		$s = $model->get_survey_question_giftcode($survey->package_id,
						'0', $survey->surveyPage, '1', $survey->uniq_id);
		if(empty($s)) {
			$pageId = $model->create_page(0, $survey->uniq_id);
			$model->insert_survey_question_giftcode($survey->package_id,
			'0', $pageId, '1', $survey->uniq_id);

			$survey->surveyPages = $model->get_pages($survey->package_id, $survey->uniq_id);
			$s = (count($survey->surveyPages) > 0 ? $survey->surveyPages[count($survey->surveyPages)-1] : null);
			$survey->pageTitle = ($s != null ? $s->title : '');
			$survey->pageId = ($s != null ? $s->id : '');
			$survey->surveyPage = $survey->pageId;
		}
		$data = $model->get_survey_question_giftcode2($survey->package_id,
			'0', $survey->surveyPage, $survey->uniq_id);
		$arrQuestion = array();
		$i=0;
		foreach ($data as $d) {
			$arrQuestion[$i] = $d->question_id;
			$i++;
		}
		$survey->questionSelectedId = $arrQuestion[0];
		$survey->questionId = $data;

		$view = $this->getView('formzs', 'html');
		$view->survey = $survey;
		$view->display('doFirst');
	}

	function edit_questions(){

		$user = JFactory::getUser();

		if($user->guest) {

			$itemid = CJFunctions::get_active_menu_id();
			$redirect_url = base64_encode(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=survey.get_latest_surveys'.$itemid));
			$this->setRedirect(CJFunctions::get_login_url($redirect_url, $itemid), JText::_('MSG_NOT_LOGGED_IN'));
		}else {

			$app = JFactory::getApplication();
			$id = $app->input->getInt('id', 0);
			if(!$id){
				CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
			}else if(!$this->authorize_survey($id)) {
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=survey.get_latest_surveys'.$survey_itemid, false), JText::_('MSG_UNAUTHORIZED'));
			} else {
			if (isset($_SESSION['surveys'])) {
			unset($_SESSION['surveys']);
			}
			$id = JRequest::getVar("questionSelectedId");	//$_GET['id'];
			$model = JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
			$data = $model->get_question2($id);
			$sid = JRequest::getVar("id");	//$_GET['id'];

			foreach ($data as $d){
			$pageid = $d->page_number;
			$uniqkey = $d->uniq_key;
			$qid = $d->id;
			}
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=edit_questions&qid='.$sid.'&uniqkey='.$uniqkey.'&pageid='.$pageid.'&id='.$id.$itemid, false));
			}
		}
	}


	function update_ordering(){

		$user = JFactory::getUser();

		if($user->guest) {

			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {

			$app = JFactory::getApplication();
			$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
			$survey_id = $app->input->getInt('id', 0);

			if(!$this->authorize_survey($survey_id)) {

				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{

				$pid = $app->input->getInt('pid', 0);
				$ordering = $app->input->getArray(array('ordering'=>'array'));

				if(!$survey_id || !$pid || empty($ordering['ordering'])) {

					echo json_encode( array( 'error'=>JText::_('MSG_ERROR_PROCESSING').' Error Code: 105101') );
				}else {

					if($model->update_ordering($survey_id, $pid, $ordering['ordering'])) {

						echo json_encode(array('return'=>'1'));
					}else {

						echo json_encode( array( 'error'=>JText::_('MSG_ERROR_PROCESSING').' Error Code: 105102'.(S_DEBUG_ENABLED ? $model->getError() : '') ) );
					}
				}
			}
		}

		jexit();
	}

	function upload_answer_image(){
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		if(!$xhr) echo '<textarea>';
		$survey_id = $app->input->getInt('id', 0);
		if($this->authorize_survey($survey_id)) {
			$params = JComponentHelper::getParams(S_APP_NAME);
			$allowed_extensions = $params->get('allowed_image_types', 'jpg,png,gif');
			$allowed_size = ((int)$params->get('max_attachment_size', 256))*1024;
			if(!empty($allowed_extensions)){
				$tmp_file = $app->input->files->get('input-attachment');
				if($tmp_file['error'] > 0){
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
				} else {
					$temp_file_path = $tmp_file['tmp_name'];
					$temp_file_name = $tmp_file['name'];
					$temp_file_ext = JFile::getExt($temp_file_name);
					if (!in_array(strtolower($temp_file_ext), explode(',', strtolower($allowed_extensions)))){
						echo json_encode(array('error'=>JText::_('MSG_INVALID_FILETYPE')));
					} else if ($tmp_file['size'] > $allowed_size){
						echo json_encode(array('error'=>JText::_('MSG_MAX_SIZE_FAILURE')));
					} else {
						$file_name = CJFunctions::generate_random_key(25, 'abcdefghijklmnopqrstuvwxyz1234567890').'.'.$temp_file_ext;
						if(JFile::upload($temp_file_path, 'administrator/components/com_awardpackage/assets/images/'.$file_name)){
							echo json_encode(array('file_name'=>$file_name, 'url'=>CQ_IMAGES_URI_2.$file_name));
						} else {
							echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
						}
					}
				}
			} else {
				echo '{"file_name": null, "url": null}';
			}
		} else {
			echo json_encode(array('error'=>JText::_('JERROR_ALERTNOAUTHOR')));
		}
		if(!$xhr) echo '</textarea>';
		jexit();
	}

	function save_survey(){
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$title = JRequest::getVar('title');
		if($title != '') {
			$survey = $model->create_edit_survey();
			if(!empty($survey) || !empty($survey->error)){
				$survey_id = $survey->id;
				$uniq_id = JRequest::getVar('uniq_id');
				$model->update_all_process($survey_id, $uniq_id);
				$model->update_answers($survey_id, $uniq_id);
				
			}
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=survey&task=survey.get_all_surveys', false), JText::_('Updated Successful'));
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=formzs&task=formzs.do_first&package_id='.JRequest::getVar('package_id').'&uniq_id='.JRequest::getVar('uniq_id'), false), JText::_('Required fields are missing. Please check the form and submit again.'));
		}

	}

	function fetch_questions() {

		$user = JFactory::getUser();

		if($user->guest) {

			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {

			$app = JFactory::getApplication();
			$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
			$survey_id = $app->input->getInt('id', 0);
			$pid = $app->input->getInt('pid', 0);

			if(!$survey_id || !$pid) {
					
				echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
			}else {

				if(!$this->authorize_survey($survey_id)) {

					echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
				}else{

					$questions = $model->get_questions($survey_id, $pid);
					$error = $model->getError();

					if($questions) {

						echo json_encode(array('questions'=>$questions));
					}else if(!empty($error)) {

						echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
					}else {

						echo json_encode(array('questions'=>array()));
					}
				}
			}
		}

		jexit();
	}

	function save_question() {
	
		$user = JFactory::getUser();
		

		if($user->guest) {

			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {

			$id = JFactory::getApplication()->input->getInt('id');

			if(!$this->authorize_survey($id)) {

				CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
			}else{

				$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );

				if($qid = $model->save_question()) {

					$question = $model->get_question($qid);

					if($question){
							
						echo json_encode(array('question'=>$question));
					} else {

						echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? '<br><br>Error:<br>'.$model->getError() : '')));
					}
				}else {

					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? '<br><br>Error:<br>'.$model->getError() : '')));
				}
			}
		}

		jexit();
	}

	public function new_page(){
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$app = JFactory::getApplication();
		$uniq_id = JRequest::getVar('uniq_id');
		$surveyId = (JRequest::getVar('id') != '' || (int)JRequest::getVar('id') > 0 ? (int)JRequest::getVar('id') : 0);
		$pg = $model->create_page($surveyId, $uniq_id);
		$this->config_page();
	}

	public function remove_page(){
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$uniq_id =  JRequest::getVar('uniq_id');
		$package_id = JRequest::getVar('package_id');
		$pid = JRequest::getVar('pageId');
		$model->remove_page_2($pid, $uniq_id);
		$this->do_first();
	}

	function rename_page() {

		$user = JFactory::getUser();

		if($user->guest) {

			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {

			$app = JFactory::getApplication();
			$survey_id = $app->input->getInt('id', 0);

			if(!$this->authorize_survey($survey_id)) {

				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{

				$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
				$pid = $app->input->getInt('pid', 0);
				$title = $app->input->getString('title', '');

				if($survey_id && $pid && !empty($title) && $model->rename_page($survey_id, $pid, $title)) {

					echo json_encode(array('data'=>1));
				}else {

					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').
					(S_DEBUG_ENABLED ? '<br><br>Error:<br>id: '.$survey_id.'<br>qid: '.$qid.'<br>pid: '.$pid.'<br>'.$model->getError() : '')));
				}
			}
		}

		jexit();
	}

	public function reorder_pages(){
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$order = JRequest::getVar('order');
		$ordering = array('order'=>$order);
		if(!empty($ordering['order']) && $model->reorder_pages_2($ordering['order'])) {
			echo json_encode(array('data'=>1));
		}
	}

	function move_question() {

		$user = JFactory::getUser();

		if($user->guest) {

			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {

			$app = JFactory::getApplication();
			$survey_id = $app->input->getInt('id', 0);

			if(!$this->authorize_survey($survey_id)) {

				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{

				$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
				$qid = $app->input->getInt('qid', 0);
				$pid = $app->input->getInt('pid', 0);

				if($survey_id && $qid && $pid && $model->move_question($survey_id, $qid, $pid)) {

					echo json_encode(array('data'=>1));
				}else {

					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? '<br><br>Error:<br>id: '.$survey_id.'<br>qid: '.$qid.'<br>pid: '.$pid.'<br>'.$model->getError() : '')));
				}
			}
		}

		jexit();
	}

	function delete_question(){

		$user = JFactory::getUser();

		if($user->guest) {

			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {

			$app = JFactory::getApplication();
			$survey_id = $app->input->getInt('id', 0);

			if(!$this->authorize_survey($survey_id)) {

				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{

				$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
				$qid = $app->input->getInt('qid', 0);
				$pid = $app->input->getInt('pid', 0);

				if($survey_id && $pid && $qid && $model->delete_question($survey_id, $pid, $qid)) {

					echo json_encode(array('data'=>1));
				}else {

					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? '<br><br>Error:<br>id: '.$survey_id.'<br>qid: '.$qid.'<br>pid: '.$pid.'<br>'.$model->getError() : '')));
				}
			}
		}

		jexit();
	}

	function finalize_survey(){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$id = JRequest::getInt('id', 0);
		$itemid = CJFunctions::get_active_menu_id();

		$survey = $model->get_survey_details($id, true);
			
		if($user->authorise('core.create', S_APP_NAME.'.category.'.$survey->catid) || $user->authorise('core.manage', S_APP_NAME)){

			if($survey->published == 3){

				$status = $user->authorise('core.autoapprove', S_APP_NAME.'.category.'.$survey->catid) ? 1 : 2;

				if(!$model->finalize_survey($id, $status)){

					$error = S_DEBUG_ENABLED ? $model->getError() : '';
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=list'.$itemid, false), JText::_('MSG_ERROR_PROCESSING').$error);
				}else{

					$config = JComponentHelper::getParams(S_APP_NAME);

					if(!$user->authorise('core.autoapprove', S_APP_NAME.'.category.'.$survey->catid)){

						if($config->get('admin_new_survey_notification', 1) == 1){

							$from = $app->getCfg('mailfrom' );
							$fromname = $app->getCfg('fromname' );
							$params = JComponentHelper::getParams(S_APP_NAME);
							$admin_emails = $model->get_admin_emails($params->get('admin_user_groups', 8));

							if(!empty($admin_emails)){

								CJFunctions::send_email($from, $fromname, $admin_emails, JText::_('MSG_MAIL_PENDING_REVIEW_SUBJECT'), JText::_('MSG_MAIL_PENDING_REVIEW_BODY'), 1);
							}
						}

						$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=list'.$itemid, false), JText::_('MSG_SENT_FOR_REVIEW'));
					}else{

						if($survey->private_survey == 0){

							$link = JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=take_survey&id='.$survey->id.":".$survey->alias.$itemid);

							CJFunctions::stream_activity(
							$config->get('activity_stream_type', 'none'),
							$user->id,
							array(
										'command' => A_APP_NAME.'.new_survey',
										'component' => S_APP_NAME,
										'title' => JText::sprintf('TXT_CREATED_SURVEY', $link, $survey->title),
										'href' => $link,
										'description' => $survey->introtext,
										'length' => $config->get('stream_character_limit', 256),
										'icon' => 'components/'.S_APP_NAME.'/assets/images/icon-16-survey.png',
										'group' => 'Surveys'
										));
						}

						$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=list'.$itemid, false), JText::_('MSG_SUCCESSFULLY_SAVED'));
					}
				}
			} else {

				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=list'.$itemid, false), JText::_('MSG_SUCCESSFULLY_SAVED'));
			}
		} else {

			$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=survey&task=survey.get_latest_surveys'.$survey_itemid, false), JText::_('MSG_UNAUTHORIZED'));
		}
	}

	function save_conditional_rule(){

		$user = JFactory::getUser();

		if($user->guest) {

			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {

			$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
			$app = JFactory::getApplication();

			$survey_id = $app->input->getInt('id', 0);
			$rule_data = $app->input->getArray(array('rule'=>'array'));
			$rule_data = $rule_data['rule'];

			if($survey_id && !empty($rule_data) && !empty($rule_data['qid']) && $rule_data['type'] >= 1 && $rule_data['type'] <= 4 && $this->authorize_survey($survey_id)){

				$rule = new stdClass();
				$rule_names = array(1=>'answered', 2=>'unanswered', 3=>'selected', 4=>'unselected');

				$rule->name = $rule_names[$rule_data['type']];
				$rule->answer_id = !empty($rule_data['answer']) ? $rule_data['answer'] : 0;
				$rule->column_id = !empty($rule_data['column']) ? $rule_data['column'] : 0;
				$rule->finalize = $rule_data['outcome'] == 1 ? 0 : 1;
				$rule->page =  $rule_data['outcome'] == 1 ?  $rule_data['page'] : 0;
				$question_id = $rule_data['qid'];

				if(!$rule->name || (!$rule->page && !$rule->answer_id && !$rule->finalize)){

					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').' <br>Error: 100108'));
				}else{

					$rule_id = $model->save_conditional_rule($survey_id, $question_id, json_encode($rule));

					if($rule_id > 0){
							
						$rules = $model->get_conditional_rules($survey_id, null, $question_id);
						echo json_encode(array('rules'=>$rules));
					}else{
							
						echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError() : '') ));
					}
				}
			} else {

				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}

		jexit();
	}

	function remove_conditional_rule(){

		$user = JFactory::getUser();

		if($user->guest) {

			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {

			$app = JFactory::getApplication();
			$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );

			$survey_id = $app->input->getInt('id', 0);
			$question_id = $app->input->getInt('qid', 0);
			$rule_id = $app->input->getInt('rid', 0);

			if($survey_id > 0 && $question_id > 0 && $rule_id > 0 && $this->authorize_survey($survey_id)){

				if($model->remove_conditional_rule($survey_id, $question_id, $rule_id)){

					echo json_encode(array('data'=>1));
				}else{

					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError() : '') ));
				}
			} else {

				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}
		}

		jexit();
	}

	private function authorize_survey($survey_id){

		$user = JFactory::getUser();

		if(!$user->authorise('core.manage', S_APP_NAME)){

			$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
			$survey = $model->get_survey_details($survey_id);

			if(!$user->authorise('core.create', S_APP_NAME.'.category.'.$survey->catid)){

				return false;
			}
		}

		return true;
	}

	function copy_survey(){

		$app = JFactory::getApplication();
		$id = $app->input->getInt('id', 0);
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );

		if(!$id){

			$this->setRedirect('index.php?option='.S_APP_NAME.'&view=surveys', JText::_('MSG_ERROR_PROCESSING'));
		} if(!JFactory::getUser()->authorise('core.create', S_APP_NAME) || !$model->authorize_survey($id)){

			return CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 403);
		} else{

			$user_itemid = CJFunctions::get_active_menu_id(true, 'index.php?option='.S_APP_NAME.'&view=user');

			if(!$model->copy_survey($id)){

				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=userzs&task=userzs.get_my_surveys'.$user_itemid), JText::_('MSG_ERROR_PROCESSING'));
			} else {

				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=userzs&task=userzs.get_my_surveys'.$user_itemid), JText::_('MSG_COPY_SUCCESS'));
			}
		}
	}

public function have_session() {
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$user	= JFactory::getUser();	
		
		$post = $_SESSION['surveys'];
		$survey = new stdClass();
		$survey->title = $post['title'];
		$survey->introtext = $post['introtext'];
		$survey->endtext = $post['endtext'];
		$survey->custom_header = $post['custom_header'];
		$survey->alias = $post['alias'];
		$survey->redirect_url = $post['redirect-url'];
		$survey->restriction = $post['restriction'];
		$survey->id = (!empty($post['id'])? $post['id'] : 0); 
		$survey->created_by = $survey->duration = $survey->skip_intro =
		$survey->anonymous = $survey->public_permissions = $survey->published = 0;
		$survey->max_responses = $post['max-responses'];
		$survey->show_answers = $survey->display_template = $survey->multiple_responses =
		$survey->private_survey = $survey->display_notice = $survey->display_progress = $survey->notification = 1;
		$survey->backward_navigation = $post['backward-navigation'];
		$survey->publish_up = $post['publish-up'];
		$survey->publish_down = $post['publish-down'];
		$survey->created_by = $user->id;
		$survey->username = $user->username;
		$survey->tags = array();
		$survey->catid = $post['catid'];
		$survey->package_id = $post['package_id'];
		$survey->questionSelectedId = $post['questionSelectedId'];
		$survey->uniq_id = $post['uniq_id'];
		$survey->surveyPage = (int)$post['surveyPages'];
		$data = $model->get_survey_question_giftcode2($survey->package_id,
			'0', $survey->surveyPage, $survey->uniq_id);
		$arrQuestion = array();
		$i=0;
		foreach ($data as $d) {
			$arrQuestion[$i] = $d->question_id;
			$i++;
		}
		$survey->questionSelectedId = $arrQuestion[0];
		$survey->questionId = $data;
		$survey->surveyPages = $survey->surveyPages = $model->get_pages($survey->package_id, $survey->uniq_id);
		$s = (count($survey->surveyPages) > 0 ? $survey->surveyPages[(int)$post['surveyPages']] : null);					
		$survey->pageTitle = ($s != null ? $s->title : '');
		$survey->pageId = ($s != null ? $s->id : '');
		$view = $this->getView('formzs', 'html');
		$view->survey = $survey;		
		if (isset($_SESSION['surveys'])) {
			unset($_SESSION['surveys']);
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=formzs&task=formzs.do_first&package_id='.JRequest::getVar('package_id').'&uniq_id='.JRequest::getVar('uniq_id'), false));
		}			
		$view->display('doFirst');
	}
	
	public function config_page() {
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$user	= JFactory::getUser();
		$survey = new stdClass();
		$survey->title = $_POST['title'];
		$survey->introtext = $_POST['introtext'];
		$survey->endtext = $_POST['endtext'];
		$survey->custom_header = $_POST['custom_header'];
		$survey->alias = $_POST['alias'];
		$survey->redirect_url = $_POST['redirect-url'];
		$survey->restriction = $_POST['restriction'];
		$survey->id = (!empty($_POST['id']) ? $_POST['id'] : 0 );
		$survey->created_by = $survey->duration = $survey->skip_intro = $survey->anonymous =
		$survey->public_permissions = $survey->published = 0;
		$survey->max_responses = $_POST['max-responses'];
		$survey->show_answers = $survey->display_template = $survey->multiple_responses =
		$survey->private_survey = $survey->display_notice = $survey->display_progress = $survey->notification = 1;
		$survey->backward_navigation = $_POST['backward-navigation'];
		$survey->publish_up = $_POST['publish-up'];
		$survey->publish_down = $_POST['publish-down'];
		$survey->created_by = $user->id;
		$survey->username = $user->username;
		$survey->tags = array();
		$survey->catid = $_POST['catid'];
		$survey->package_id = $_POST['package_id'];
		$survey->uniq_id = $_POST['uniq_id'];
		$survey->questionSelectedId = $_POST['questionSelectedId'];
		$survey->giftcodeQuantityComplete = array(0=>'');
		$survey->costPerResponseComplete = array(0=>'');
		$survey->giftcodeQuantityIncomplete = array(0=>'');
		$survey->costPerResponseIncomplete = array(0=>'');
		$survey->categorySurveyComplete = array('New');
		$survey->categorySurveyIncomplete = array('New');
		$survey->surveyPage = ($_GET['page'] == 'xxx' ? $model->get_max_id_pages($survey->uniq_id) : ((int)$_GET['page']));
		$s = $model->get_survey_question_giftcode($survey->package_id,
			'0', $survey->surveyPage, $survey->questionSelectedId, $survey->uniq_id);
		if(empty($s)) {
			$model->insert_survey_question_giftcode($survey->package_id,
			'0', $survey->surveyPage, $survey->questionSelectedId, $survey->uniq_id);	
		}
		$data = $model->get_survey_question_giftcode2($survey->package_id,
				'0', $survey->surveyPage, $survey->uniq_id);
		$arrQuestion = array();
		$i=0;
		foreach ($data as $d) {
			$arrQuestion[$i] = $d->question_id;
			$i++;
		}
		$survey->questionSelectedId = $arrQuestion[0];
		$survey->questionId = $data;
		$survey->surveyPages = $model->get_pages($survey->package_id, $survey->uniq_id);
		foreach ($survey->surveyPages as $pages) {
			if($pages->id == (int)$_GET['page']) {
				$survey->pageTitle = $pages->title;
				$survey->pageId = $pages->id;
			}
		}
		$view = $this->getView('formzs', 'html');
		$view->survey = $survey;
		$view->display('doFirst');
	}

public function save_giftcode() {
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$uniq_id =  JRequest::getVar('uniq_id');
		$package_id = JRequest::getVar('package_id');
		$page = JRequest::getVar('surveyPages');

		$questions = JRequest::getVar('questionId');

		$categorySurveyCompletes = JRequest::getVar('categorySurveyComplete');
		$giftcodeQuantityCompletes = JRequest::getVar('giftcodeQuantityComplete');
		$costPerResponseCompletes = JRequest::getVar('costPerResponseComplete');

		$categorySurveyIncompletes = JRequest::getVar('categorySurveyIncomplete');
		$giftcodeQuantityIncompletes = JRequest::getVar('giftcodeQuantityIncomplete');
		$costPerResponseIncompletes = JRequest::getVar('costPerResponseIncomplete');
		
		for($i = 0; $i < count($questions); $i++) {
			$question = $questions[$i];
			$categorySurveyComplete = $categorySurveyCompletes[$i];
			$giftcodeQuantityComplete = $giftcodeQuantityCompletes[$i];
			$costPerResponseComplete = $costPerResponseCompletes[$i];

			$categorySurveyIncomplete = $categorySurveyIncompletes[$i];
			$giftcodeQuantityIncomplete = $giftcodeQuantityIncompletes[$i];
			$costPerResponseIncomplete = $costPerResponseIncompletes[$i];
			$model->update_question_giftcode($package_id, $page, $question, $uniq_id,
			$categorySurveyComplete, $giftcodeQuantityComplete, $costPerResponseComplete,
			$categorySurveyIncomplete, $giftcodeQuantityIncomplete, $costPerResponseIncomplete);
		}
	}	
	public function delete_giftcode() {
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$uniq_id =  JRequest::getVar('uniq_id');
		$package_id = JRequest::getVar('package_id');
		$page = JRequest::getVar('surveyPages');
		$question = JRequest::getVar('questionToDelete');
		if($model->delete_question_giftcode($package_id, $page, $question, $uniq_id)) {
			echo 'SUCCESS';
		} else {
			echo 'ERROR';
		}
		die();
	}

	public function update_page_title(){
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$uniq_id =  JRequest::getVar('uniq_id');
		$package_id = JRequest::getVar('package_id');
		$pid = JRequest::getVar('pageId');
		$title = JRequest::getVar('pageTitle');
		$model->rename_page2($pid, $uniq_id, $title);
	}

	public function question() {
		if (!isset($_SESSION['surveys']) || empty($_SESSION['surveys'])) {
			session_start();
			$_SESSION['surveys'] = $_POST;
		}
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=question', false), JText::_('MSG_SURVEY_UPDATED'));
	}
	
	
	
	public function preview(){
		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		if($_GET['page'] == '') {
			if (!isset($_SESSION['survey']) || empty($_SESSION['survey'])) {
				session_start();
				$_SESSION['survey'] = $_POST;
			}
		} else {
			$post = $_SESSION['survey'];
		}

		$survey = null;
		$survey = new stdClass();
		$survey ->package_id = ($_GET['page'] == '' ? $_POST['package_id'] : $post['package_id']);
		$survey ->uniq_id = ($_GET['page'] == '' ? $_POST['uniq_id'] : $post['uniq_id']);
		
		$survey->surveyPages = $model->get_pages($survey->package_id, $survey->uniq_id);
		foreach ($survey->surveyPages as $pages) {
			$survey->surveyPage = $pages->id;
		}
		$survey->surveyPage = ($_GET['page'] == '' ? $survey->surveyPage : $_GET['page']);
		$data = $model->get_survey_question_giftcode2($survey->package_id,
		($_GET['page'] == '' ? JRequest::getVar('id') : $post['id']),
		$survey->surveyPage, $survey->uniq_id);
		$newData = array();
		$i = 0;
		foreach ($data as $d) {
			$questions = $model->get_question_details($survey->uniq_id,
			$d->question_id, $survey->surveyPage,
			($_GET['page'] == '' ? JRequest::getVar('id') : $post['id']));
			$d->questions = $questions;
			$newData[$i] = $d;
			$i++;
		}

		$survey->questionId = $newData;
		$view = $this->getView('formzs', 'html');
		$view->assign('action', 'preview');
		$view->survey = $survey;
		$view->display('preview');
	}
}
?>