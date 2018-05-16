<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';

class AwardpackageControllerQuestion extends JControllerLegacy {

	function __construct() {
		parent::__construct();
		$this->initiate();
	}

	function get_question() {
		$view = $this->getView('question', 'html');
		$model = $this->getModel('surveys');
		$view->setModel($model, true);
		$view->display();
	}

	function save_question() {
		$user = JFactory::getUser();
		if($user->guest) {
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
			if(!$user->authorise('core.create', S_APP_NAME) && !$user->authorise('core.manage', S_APP_NAME)){
				CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
			}else{
				$model = JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
				if($qid = $model->save_question()) {
					$question = $model->get_question($qid);
					if($question){
						echo json_encode(array('question'=>$question));
					} else {
						echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? '<br><br>Error:<br>'.$model->getError() : '')));
					}
				} else {
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? '<br><br>Error:<br>'.$model->getError() : '')));
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
			if(!$user->authorise('core.create', S_APP_NAME) && !$user->authorise('core.manage', S_APP_NAME)){
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{
				$model = JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
				$app = JFactory::getApplication();
				$survey_id = $app->input->getInt('id', 0);			
				$qid = $app->input->getInt('qid', 0);
				$pid = $app->input->getInt('pid', 0);
				if($qid && $model->delete_question($survey_id, $pid, $qid)) {
					echo json_encode(array('data'=>1));
				}else {
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? '<br><br>Error:<br>id: '.$survey_id.'<br>qid: '.$qid.'<br>pid: '.$pid.'<br>'.$model->getError() : '')));
				}
			}
		}
		jexit();
	}

	function update_ordering(){
		$user = JFactory::getUser();
		if($user->guest) {
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
			if(!$user->authorise('core.create', S_APP_NAME) && !$user->authorise('core.manage', S_APP_NAME)){
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{
				$app = JFactory::getApplication();
				$survey_id = $app->input->getInt('id', 0);
				$pid = $app->input->getInt('pid', 0);
				$ordering = $app->input->getArray(array('ordering'=>'array'));
				if(!$survey_id || empty($ordering['ordering'])) {
					echo json_encode( array( 'error'=>JText::_('MSG_ERROR_PROCESSING').' Error Code: 105101') );
				}else {
					$model = JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
					if($model->update_ordering($survey_id, $pid, $ordering['ordering'])) {
						echo json_encode(array('return'=>'1'));
					}else {
						echo json_encode( array( 'error'=>JText::_('MSG_ERROR_PROCESSING').' Error Code: 105102'.(S_DEBUG_ENABLED ? $model->getError() : '') ) );
					}				}
			}
		}

		jexit();
	}

	function move_question() {
		$user = JFactory::getUser();
		if($user->guest) {
			$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys'.$itemid, false), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
			if(!$user->authorise('core.create', S_APP_NAME) && !$user->authorise('core.manage', S_APP_NAME)){
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=question'.$itemid, false), JText::_('MSG_UNAUTHORIZED'));
			}else{
				$model = JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
				$app = JFactory::getApplication();
				$survey_id = $app->input->getInt('id', 0);
				$qid = $app->input->getInt('qid', 0);
				$pid = $app->input->getInt('pid', 0);
				if($survey_id && $qid && $model->move_question($survey_id, $qid, $pid)) {
					echo json_encode(array('data'=>1));
				}else {
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? '<br><br>Error:<br>id: '.$survey_id.'<br>qid: '.$qid.'<br>pid: '.$pid.'<br>'.$model->getError() : '')));
				}
			}
		}

		jexit();
	}

	function save_conditional_rule(){
		$user = JFactory::getUser();
		if($user->guest) {
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
			$model = JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
			$app = JFactory::getApplication();
			$survey_id = $app->input->getInt('id', 0);
			$rule_data = $app->input->getArray(array('rule'=>'array'));
			$rule_data = $rule_data['rule'];
			if($survey_id 
				//&& !empty($rule_data) && !empty($rule_data['qid']) && $rule_data['type'] >= 1 && $rule_data['type'] <= 4 && $model->authorize_survey($survey_id)
			){
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
			$model = JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
			$survey_id = $app->input->getInt('id', 0);
			$question_id = $app->input->getInt('qid', 0);
			$rule_id = $app->input->getInt('rid', 0);
			if($survey_id > 0 && $question_id > 0 && $rule_id > 0 && $model->authorize_survey($survey_id)){
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

	function rename_page() {
		$user = JFactory::getUser();
		if($user->guest) {
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
			$app = JFactory::getApplication();
			$survey_id = $app->input->getInt('id', 0);
			if(!$user->authorise('core.create', S_APP_NAME) && !$user->authorise('core.manage', S_APP_NAME)){
				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{
				$model = JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
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

	function reorder_pages() {
		$user = JFactory::getUser();
		if(!$user->authorise('core.create', S_APP_NAME) && !$user->authorise('core.manage', S_APP_NAME)){
			echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
		}else {
			$app = JFactory::getApplication();
			$survey_id = $app->input->getInt('id', 0);
			$model = JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
			$ordering = $app->input->post->getArray(array('order'=>'array'));
			if(!empty($ordering['order']) && $model->reorder_pages($survey_id, $ordering['order'])) {
				echo json_encode(array('data'=>1));
			}else {
				echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').
				(S_DEBUG_ENABLED ? '<br><br>Error:<br>id: '.$survey_id.'<br>Order: '.print_r($ordering['order'], true).'<br>'.$model->getError() : '')));
			}
		}
		jexit();
	}

	function finalize_survey(){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		if(!$user->authorise('core.create', S_APP_NAME)){
			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{
			$model = JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
			$id = JRequest::getInt('id', 0);
			$survey = $model->get_survey_details($id, true);
			$itemid = CJFunctions::get_active_menu_id();
			if($survey->published == 3){
				$status = $user->authorise('core.autoapprove', S_APP_NAME) ? 1 : 2;
				if(!$model->finalize_survey($id, $status)){
					$error = S_DEBUG_ENABLED ? $model->getError() : '';
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=list'.$itemid, false), JText::_('MSG_ERROR_PROCESSING').$error);
				}else{
					$config = JComponentHelper::getParams(S_APP_NAME);
					if(!$user->authorise('core.autoapprove', S_APP_NAME)){
						if($config->get('admin_new_survey_notification', 1) == 1){
							$from = $app->getCfg('mailfrom' );
							$fromname = $app->getCfg('fromname' );
							$admin_emails = $model->get_admin_emails($params->get('admin_user_groups', 8));
							if(!empty($admin_emails)){
								CJFunctions::send_email($from, $fromname, $admin_emails, JText::_('MSG_MAIL_PENDING_REVIEW_SUBJECT'), JText::_('MSG_MAIL_PENDING_REVIEW_BODY'), 1);
							}
						}
						$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys&task=list'.$itemid, false), JText::_('MSG_SENT_FOR_REVIEW'));
					}else{
						$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys&task=list'.$itemid, false), JText::_('MSG_SUCCESSFULLY_SAVED'));
					}
				}
			} else {
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys&task=list'.$itemid, false), JText::_('MSG_SUCCESSFULLY_SAVED'));
			}
		}
	}

	function new_page() {
		$user = JFactory::getUser();
		$itemid = CJFunctions::get_active_menu_id();
		if($user->guest) {
			$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys'.$itemid, false), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
			if(!$user->authorise('core.create', S_APP_NAME) && !$user->authorise('core.manage', S_APP_NAME)){
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys'.$itemid, false), JText::_('MSG_UNAUTHORIZED'));
			}else{
				$model = JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
				$app = JFactory::getApplication();
				$survey_id = $app->input->getInt('id', 0);
				if($survey_id && ($pid = $model->create_page($survey_id))) {
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=edit_questions&id='.$survey_id.'&pid='.$pid.$itemid, false), JText::_('MSG_PAGE_CREATED'));
				}else {
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=edit_questions&id='.$survey_id.$itemid, false), JText::_('MSG_ERROR_PROCESSING'));
				}
			}
		}
	}

	function remove_page() {
		$user = JFactory::getUser();
		if($user->guest) {
			$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys'.$itemid, false), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
			if(!$user->authorise('core.create', S_APP_NAME) && !$user->authorise('core.manage', S_APP_NAME)){
				$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=surveys'.$itemid, false), JText::_('MSG_UNAUTHORIZED'));
			}else{
				$model = JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
				$app = JFactory::getApplication();
				$survey_id = $app->input->getInt('id', 0);
				$pid = $app->input->getInt('pid', 0);
				if($pid && $model->remove_page($survey_id, $pid)) {
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=edit_questions&id='.$survey_id.'&pid=0'.$itemid, false), JText::_('MSG_PAGE_REMOVED'));
				}else {
					$msg = JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $model->getError() : '');
					$this->setRedirect(JRoute::_('index.php?option='.S_APP_NAME.'&view=question&task=edit_questions&id='.$survey_id.$itemid, false), $msg);
				}
			}
		}
	}
	
	function upload_answer_image(){	
		$user = JFactory::getUser();
		$xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		if(!$xhr) echo '<textarea>';
	
		if($user->authorise('core.create', S_APP_NAME) || $user->authorise('core.manage', S_APP_NAME)){
	
			$params = JComponentHelper::getParams(S_APP_NAME);
			$allowed_extensions = $params->get('allowed_image_types', 'jpg,png,gif');
			$allowed_size = ((int)$params->get('max_attachment_size', 256))*1024;
			$input = JFactory::getApplication()->input;
	
			if(!empty($allowed_extensions)){
	
				$tmp_file = $input->files->get('input-attachment');
	
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
						if(JFile::upload($temp_file_path, 'components/com_awardpackage/assets/images/'.$file_name)){	
							echo json_encode(array('file_name'=>$file_name, 'url'=>'components/com_awardpackage/assets/images/'.$file_name));
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

	function initiate()
	{
		$cjlib = JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cjlib'.DIRECTORY_SEPARATOR.'framework.php';
		if(file_exists($cjlib)){
			require_once $cjlib;
		}else{
			die('CJLib (CoreJoomla API Library) component not found. Please <a href="http://www.corejoomla.com/downloads/required-extensions.html" target="_blank">download it here</a> and install it to continue.');
		}
		CJLib::import('corejoomla.framework.core');
		CJLib::import('corejoomla.ui.bootstrap');

		$document = JFactory::getDocument();
		CJFunctions::load_jquery(array('libs'=>array('fontawesome')));
		$document->addStyleSheet(CJLIB_URI.'/framework/assets/cj.framework.css');
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/assets/css/cj.surveys.admin.min.css');
		$document->addScript(JURI::base(true).'/components/com_awardpackage/assets/js/cj.surveys.admin.min.js');
	}

}
?>