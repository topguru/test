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

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');
jimport( 'joomla.filesystem.file' );
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_COMPONENT . '/helpers/awardpackage.php';

class AwardPackageControllerForm extends JControllerLegacy {

	function __construct() {

		parent::__construct();

		$this->registerDefaultTask('create_edit_quiz');

		/* create quiz */
		$this->registerTask('form', 'create_edit_quiz');
		$this->registerTask('edit', 'edit_questions');
		$this->registerTask('save', 'save_quiz');
		$this->registerTask('get_tags', 'get_tags');
		$this->registerTask('save_qn','save_question');
		$this->registerTask('delete_qn', 'delete_question');
		$this->registerTask('move_qn','move_question');
		$this->registerTask('new_page','new_page');
		$this->registerTask('delete_page','remove_page');
		$this->registerTask('finalize', 'finalize_quiz');
		$this->registerTask('update_order', 'update_ordering');
		$this->registerTask('upload_answer_image', 'upload_answer_image');
	}

	function add_new_quiz(){
		$uniq_id = md5(uniqid(rand(), true));
		$users = AwardPackageHelper::getUserData();
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=form&task=form.create_edit_quiz&package_id='.$users->package_id.'&uniq_id='.$uniq_id, null);
	}


	function create_edit_quiz(){
		$user = JFactory::getUser();
		if($user->guest) {
			$itemid = CJFunctions::get_active_menu_id();
			$redirect_url = base64_encode(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid));
			$this->setRedirect(CJFunctions::get_login_url($redirect_url, $itemid), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
			$app = JFactory::getApplication();
			$id = $app->input->getInt('id', 0);

			$package_id = JRequest::getVar("package_id");
			$uniq_key = JRequest::getVar("uniq_id");
			$view = $this->getView('form', 'html');
			$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );

			$pages =  $model->get_pages_list(JRequest::getVar("id"));
			if(!empty($pages)) {
				$page = $pages[0];
				$uniq_key = $page->uniq_key;
			}

			$quiz = null;
			$quiz = new stdClass();
			$quiz->package_id = $package_id;
			$quiz->uniq_id = $uniq_key;
			$quiz->id = $quiz->created_by = $quiz->catid = $quiz->duration = $quiz->skip_intro = $quiz->published = $quiz->cutoff = 0;
			$quiz->show_answers = $quiz->show_template = $quiz->multiple_responses = 1;
			$quiz->title = $quiz->description = $quiz->alias = '';
			$quiz->quizPages = $model->get_pages($quiz->package_id, $quiz->uniq_id);
			$s = (count($quiz->quizPages) > 0 ? $quiz->quizPages[count($quiz->quizPages)-1] : null);
			$quiz->pageTitle = ($s != null ? $s->title : '');
			$quiz->pageId = ($s != null ? $s->id : '1');
			$quiz->quizPage = $quiz->pageId;
			$s = $model->get_quiz_question_giftcode($quiz->package_id,
						'0', $quiz->quizPage, '1', $quiz->uniq_id);
			if(empty($s)) {
				$pageId = $model->create_page(0, $quiz->uniq_id);
				$model->insert_quiz_question_giftcode($quiz->package_id,
					'0', $pageId, '1', $quiz->uniq_id);

				$quiz->quizPages = $model->get_pages($quiz->package_id, $quiz->uniq_id);
				$s = (count($quiz->quizPages) > 0 ? $quiz->quizPages[count($quiz->quizPages)-1] : null);
				$quiz->pageTitle = ($s != null ? $s->title : '');
				$quiz->pageId = ($s != null ? $s->id : '');
				$quiz->quizPage = $quiz->pageId;
			}

			$data = $model->get_quiz_question_giftcode2($quiz->package_id,
					'0', $quiz->quizPage, $quiz->uniq_id);
			$arrQuestion = array();
			$i=0;
			foreach ($data as $d) {
				$arrQuestion[$i] = $d->question_id;
				$i++;
			}

			$quiz->questionSelectedId = $arrQuestion[0];
			$quiz->questionId = $data;
			$quiz->tags = array();
			$categories = $model->get_category_tree();
			$view->quiz = $quiz;
			$view->categories__ = $categories;
			$view->setModel($model, true);
			$view->assign('action', 'form');
			$view->display();
		}
	}

	public function save_giftcode(){
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$uniq_id =  JRequest::getVar('uniq_id');
		$package_id = JRequest::getVar('package_id');
		$page = JRequest::getVar('quizPages');
		$questions = JRequest::getVar('questionId');
		$categoryQuizCompletes = JRequest::getVar('categoryQuizComplete');
		$giftcodeQuantityCompletes = JRequest::getVar('giftcodeQuantityComplete');
		$costPerResponseCompletes = JRequest::getVar('costPerResponseComplete');
		$categoryQuizIncompletes = JRequest::getVar('categoryQuizIncomplete');
		$giftcodeQuantityIncompletes = JRequest::getVar('giftcodeQuantityIncomplete');
		$costPerResponseIncompletes = JRequest::getVar('costPerResponseIncomplete');
		for($i = 0; $i < count($questions); $i++) {
			$question = $questions[$i];
			$categoryQuizComplete = $categoryQuizCompletes[$i];
			$giftcodeQuantityComplete = $giftcodeQuantityCompletes[$i];
			$costPerResponseComplete = $costPerResponseCompletes[$i];
			$categoryQuizIncomplete = $categoryQuizIncompletes[$i];
			$giftcodeQuantityIncomplete = $giftcodeQuantityIncompletes[$i];
			$costPerResponseIncomplete = $costPerResponseIncompletes[$i];
			$model->update_question_giftcode($package_id, $page, $question, $uniq_id,
			$categoryQuizComplete, $giftcodeQuantityComplete, $costPerResponseComplete,
			$categoryQuizIncomplete, $giftcodeQuantityIncomplete, $costPerResponseIncomplete);
		}
	}

    public function have_session(){
		$user = JFactory::getUser();

		if($user->guest) {
			$itemid = CJFunctions::get_active_menu_id();
			$redirect_url = base64_encode(JRoute::_('index.php?option='.Q_APP_NAME.'&view=form'.$itemid));
			$this->setRedirect(CJFunctions::get_login_url($redirect_url, $itemid), JText::_('MSG_NOT_LOGGED_IN'));
		}else{
			$app = JFactory::getApplication();
					
			$id = $app->input->getInt('id', 0);
			/*if(!$user->authorise('quiz.create', Q_APP_NAME) && !$user->authorise('quiz.manage', Q_APP_NAME)){
				CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
			}else if($id && !$user->authorise('quiz.edit', Q_APP_NAME)){
				CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
			}else{*/
				$view = $this->getView('form', 'html');
			$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
				$quiz = null;
				$quiz = new stdClass();
				if(isset($_SESSION['quiz'])){
					$post = $_SESSION['quiz'];
					unset($_SESSION['quiz']);
				}
				$quiz->package_id = $post['package_id'];
				$quiz->uniq_id = $post['uniq_id'];
				$quiz->questionSelectedId = $post['questionSelectedId'];
				$quiz->published = $post['published'];
				$quiz->id = $post['id'];
				$quiz->catid = $post['catid'];
				$quiz->title = $post['title'];
				$quiz->alias = $post['alias'];
				$quiz->description = $post['description'];
				$quiz->duration = $post['duration'];
				$quiz->multiple_responses = $post['multiple-responses'];
				$quiz->skip_intro = $post['skip_intro'];
				$quiz->show_answers = $post['show-result'];
				$quiz->show_template = $post['show-template'];
				$quiz->cutoff = $post['cutoff'];
				$quiz->giftcodeQuantityComplete = array(0=>'');
				$quiz->costPerResponseComplete = array(0=>'');
				$quiz->giftcodeQuantityIncomplete = array(0=>'');
				$quiz->costPerResponseIncomplete = array(0=>'');
				$quiz->categoryQuizComplete = array('New');
				$quiz->categoryQuizIncomplete = array('New');
				$quiz->quizPage = ($_GET['page'] == 'xxx' ? $model->get_max_id_pages($quiz->uniq_id) : ((int)$_GET['page']));
				$s = $model->get_quiz_question_giftcode($quiz->package_id,
						'0', $quiz->quizPage, $quiz->questionSelectedId, $quiz->uniq_id);
				if(empty($s)) {
					$model->insert_quiz_question_giftcode($quiz->package_id,
					'0', $quiz->quizPage, $quiz->questionSelectedId, $quiz->uniq_id);
					$quiz->quizPages = $model->get_pages($quiz->package_id, $quiz->uniq_id);
						
					$s = (count($quiz->quizPages) > 0 ? $quiz->quizPages[count($quiz->quizPages)-1] : null);
					$quiz->pageTitle = ($s != null ? $s->title : '');
					$quiz->pageId = ($s != null ? $s->id : '');
					$quiz->quizPage = $quiz->pageId;
				//}
				$data = $model->get_quiz_question_giftcode2($quiz->package_id,
						'0', $quiz->quizPage, $quiz->uniq_id);
				$arrQuestion = array();
				$i=0;
				foreach ($data as $d) {
					$arrQuestion[$i] = $d->question_id;
					$i++;
				}
				$quiz->questionSelectedId = $arrQuestion[0];
				$quiz->questionId = $data;
				$quiz->quizPages = $model->get_pages($quiz->package_id, $quiz->uniq_id);

				foreach ($quiz->quizPages as $pages) {
					if($pages->id == (int)$_GET['page']) {
						$quiz->pageTitle = $pages->title;
						$quiz->pageId = $pages->id;
					}
				}
				$quiz->tags = array();
				$categories = $model->get_category_tree();
				$view->quiz = $quiz;
				$view->categories__ = $categories;
				$view->setModel($model, true);
				$view->assign('action', 'form');
				$view->display();
			}
		}
	}
	
	public function config_page(){
		$user = JFactory::getUser();
		if($user->guest) {
			$itemid = CJFunctions::get_active_menu_id();
			$redirect_url = base64_encode(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes'.$itemid));
			$this->setRedirect(CJFunctions::get_login_url($redirect_url, $itemid), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
			$app = JFactory::getApplication();
			$id = $app->input->getInt('id', 0);
			$view = $this->getView('form', 'html');
			$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
			$quiz = null;
			$quiz = new stdClass();
			$quiz->package_id = $_POST['package_id'];
			$quiz->uniq_id = $_POST['uniq_id'];
			$quiz->questionSelectedId = $_POST['questionSelectedId'];
			$quiz->published = $_POST['published'];
			$quiz->id = $_POST['id'];
			$quiz->catid = $_POST['catid'];
			$quiz->title = $_POST['title'];
			$quiz->alias = $_POST['alias'];
			$quiz->description = $_POST['description'];
			$quiz->duration = $_POST['duration'];
			$quiz->multiple_responses = $_POST['multiple-responses'];
			$quiz->skip_intro = $_POST['skip_intro'];
			$quiz->show_answers = $_POST['show-result'];
			$quiz->show_template = $_POST['show-template'];
			$quiz->cutoff = $_POST['cutoff'];
			$quiz->giftcodeQuantityComplete = array(0=>'');
			$quiz->costPerResponseComplete = array(0=>'');
			$quiz->giftcodeQuantityIncomplete = array(0=>'');
			$quiz->costPerResponseIncomplete = array(0=>'');
			$quiz->categoryQuizComplete = array('New');
			$quiz->categoryQuizIncomplete = array('New');
			$quiz->quizPage = ($_GET['page'] == 'xxx' ? $model->get_max_id_pages($quiz->uniq_id) : ((int)$_GET['page']));
			$s = $model->get_quiz_question_giftcode($quiz->package_id,
						'0', $quiz->quizPage, $quiz->questionSelectedId, $quiz->uniq_id);
			if(empty($s)) {
				$model->insert_quiz_question_giftcode($quiz->package_id,
					'0', $quiz->quizPage, $quiz->questionSelectedId, $quiz->uniq_id);
				$quiz->quizPages = $model->get_pages($quiz->package_id, $quiz->uniq_id);
				$s = (count($quiz->quizPages) > 0 ? $quiz->quizPages[count($quiz->quizPages)-1] : null);
				$quiz->pageTitle = ($s != null ? $s->title : '');
				$quiz->pageId = ($s != null ? $s->id : '');
				$quiz->quizPage = $quiz->pageId;
			}
			$data = $model->get_quiz_question_giftcode2($quiz->package_id,
						'0', $quiz->quizPage, $quiz->uniq_id);
			$arrQuestion = array();
			$i=0;
			foreach ($data as $d) {
				$arrQuestion[$i] = $d->question_id;
				$i++;
			}
			$quiz->questionSelectedId = $arrQuestion[0];
			$quiz->questionId = $data;
			$quiz->quizPages = $model->get_pages($quiz->package_id, $quiz->uniq_id);
			foreach ($quiz->quizPages as $pages) {
				if($pages->id == (int)$_GET['page']) {
					$quiz->pageTitle = $pages->title;
					$quiz->pageId = $pages->id;
				}
			}
			$quiz->tags = array();
			$categories = $model->get_category_tree();
			$view->quiz = $quiz;
			$view->categories__ = $categories;
			$view->setModel($model, true);
			$view->assign('action', 'form');
			$view->display();
		}
	}

	public function delete_giftcode() {
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$uniq_id =  JRequest::getVar('uniq_id');
		$package_id = JRequest::getVar('package_id');
		$page = JRequest::getVar('quizPages');
		$question = JRequest::getVar('questionToDelete');
		if($model->delete_question_giftcode($package_id, $page, $question, $uniq_id)) {
			echo 'SUCCESS';
		} else {
			echo 'ERROR';
		}
		die();

	}

	function edit_questions(){
		$user = JFactory::getUser();
		if($user->guest) {
			$itemid = CJFunctions::get_active_menu_id();
			$redirect_url = base64_encode(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid));
			$this->setRedirect(CJFunctions::get_login_url($redirect_url, $itemid), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
			$app = JFactory::getApplication();
			$id = $app->input->getInt('id', 0);
			if(!$id || (!$user->authorise('quiz.create', Q_APP_NAME) && !$user->authorise('quiz.manage', Q_APP_NAME))){
				CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
			}else{
				$view = $this->getView('form', 'html');
				$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
				$view->setModel($model, true);
				$view->assign('action', 'questions');
				$view->display();
			}
		}
	}

	function update_ordering(){

		$user = JFactory::getUser();

		if($user->guest) {

			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {

			if(!$user->authorise('quiz.create', Q_APP_NAME) && !$user->authorise('quiz.manage', Q_APP_NAME)){

				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{

				$app = JFactory::getApplication();

				$quiz_id = $app->input->getInt('id', 0);
				$pid = $app->input->getInt('pid', 0);
				$ordering = $app->input->getArray(array('ordering'=>'array'));

				if(!$quiz_id || !$pid || empty($ordering['ordering'])) {

					echo json_encode( array( 'error'=>JText::_('MSG_ERROR_PROCESSING').' Error Code: 105101') );
				}else {

					$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );

					if($model->update_ordering($quiz_id, $pid, $ordering['ordering'])) {

						echo json_encode(array('return'=>'1'));
					}else {

						echo json_encode( array( 'error'=>JText::_('MSG_ERROR_PROCESSING').' Error Code: 105102'.(Q_DEBUG_ENABLED ? $model->getError() : '') ) );
					}
				}
			}
		}

		jexit();
	}

	function upload_answer_image(){
		$user = JFactory::getUser();
		$xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		if(!$xhr) echo '<textarea>';
		if($user->authorise('quiz.create', Q_APP_NAME) || $user->authorise('quiz.manage', Q_APP_NAME)){
			$params = JComponentHelper::getParams(Q_APP_NAME);
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

	function save_quiz(){
		$user = JFactory::getUser();
		$title = JRequest::getVar('title');
		if($title != '') {
			$model = JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
			$quiz = $model->create_edit_quiz();
			if(empty($quiz)){
				JFactory::getApplication()->enqueueMessage(JText::_('MSG_ERROR_PROCESSING'));
				$view = $this->getView('form', 'html');
				$view->setModel($model, true);
				$view->assignRef('quiz', $quiz);
				$view->assign('action', 'form');
				$view->display();
			}else{
				//$itemid = CJFunctions::get_active_menu_id();
				$quiz_id = $quiz->id;
				$uniq_id = JRequest::getVar('uniq_id');
				$model->update_all_process($quiz_id, $uniq_id);
				$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_latest_quizzes', false), JText::_('MSG_QUIZ_UPDATED'));				
			}
		} else {
			$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.create_edit_quiz&package_id='.JRequest::getVar('package_id').'&uniq_id='.JRequest::getVar('uniq_id'), false), JText::_('Required fields are missing. Please check the form and submit again'));
		}
		
	}

	function fetch_questions() {

		$user = JFactory::getUser();

		if($user->guest) {

			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {

			if(!$user->authorise('quiz.create', Q_APP_NAME) && !$user->authorise('quiz.manage', Q_APP_NAME)){

				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{

				$app = JFactory::getApplication();

				$quiz_id = $app->input->getInt('id', 0);
				$pid = $app->input->getInt('pid', 0);

				if(!$quiz_id || !$pid) {

					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
				}else {

					$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
					$questions = $model->get_questions($quiz_id, $pid);
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

	function new_page() {

		$user = JFactory::getUser();
		$itemid = CJFunctions::get_active_menu_id();

		if($user->guest) {

			$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid, false), JText::_('MSG_NOT_LOGGED_IN'));
		}else {

			if(!$user->authorise('quiz.create', Q_APP_NAME) && !$user->authorise('quiz.manage', Q_APP_NAME)){

				$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid, false), JText::_('MSG_UNAUTHORIZED'));
			}else{

				$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
				$app = JFactory::getApplication();

				$quiz_id = $app->input->getInt('id', 0);

				if($quiz_id && ($pid = $model->create_page($quiz_id))) {

					$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.edit_questions&id='.$quiz_id.'&pid='.$pid.$itemid, false), JText::_('MSG_PAGE_CREATED'));
				}else {

					$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.edit_questions&id='.$quiz_id.$itemid, false), JText::_('MSG_ERROR_PROCESSING'));
				}
			}
		}
	}

	function remove_page() {

		$user = JFactory::getUser();

		if($user->guest) {

			$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid, false), JText::_('MSG_NOT_LOGGED_IN'));
		}else {

			if(!$user->authorise('quiz.create', Q_APP_NAME) && !$user->authorise('quiz.manage', Q_APP_NAME)){

				$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid, false), JText::_('MSG_UNAUTHORIZED'));
			}else{

				$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
				$app = JFactory::getApplication();

				$quiz_id = $app->input->getInt('id', 0);
				$pid = $app->input->getInt('pid', 0);

				if($pid && $model->remove_page($quiz_id, $pid)) {

					$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=form.edit_questions&id='.$quiz_id.'&pid=0'.$itemid, false), JText::_('MSG_PAGE_REMOVED'));
				}else {

					$msg = JText::_('MSG_ERROR_PROCESSING').(Q_DEBUG_ENABLED ? $model->getError() : '');
					$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=form&task=edit_questions&id='.$quiz_id.$itemid, false), $msg);
				}
			}
		}
	}

	function move_question() {

		$user = JFactory::getUser();

		if($user->guest) {

			$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid, false), JText::_('MSG_NOT_LOGGED_IN'));
		}else {

			if(!$user->authorise('quiz.create', Q_APP_NAME) && !$user->authorise('quiz.manage', Q_APP_NAME)){

				$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid, false), JText::_('MSG_UNAUTHORIZED'));
			}else{

				$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
				$app = JFactory::getApplication();

				$quiz_id = $app->input->getInt('id', 0);
				$qid = $app->input->getInt('qid', 0);
				$pid = $app->input->getInt('pid', 0);

				if($quiz_id && $qid && $pid && $model->move_question($quiz_id, $qid, $pid)) {

					echo json_encode(array('data'=>1));
				}else {

					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(Q_DEBUG_ENABLED ? '<br><br>Error:<br>id: '.$quiz_id.'<br>qid: '.$qid.'<br>pid: '.$pid.'<br>'.$model->getError() : '')));
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

			/*if(!$user->authorise('quiz.create', Q_APP_NAME) || !$user->authorise('quiz.manage', Q_APP_NAME)){

				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{*/

				$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
				$app = JFactory::getApplication();
				$quiz_id = $app->input->getInt('quiz_id', 0);
				$qid = $app->input->getInt('qid', 0);
				$pid = $app->input->getInt('page_number', 0);
				$uniq_id = JRequest::getVar('uniq_id');
				//$pid = JRequest::getVar('pageId');

				if($qid && $model->delete_question($quiz_id, $pid, $qid)) {
					echo json_encode(array('data'=>1));
				}else {
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(Q_DEBUG_ENABLED ? '<br><br>Error:<br>quiz_id: '.$quiz_id.'<br>qid: '.$qid.'<br>pid: '.$pid.'<br>'.$model->getError() : '')));
				}
			//}
		}

		jexit();
	}

	function move_question_up(){

		$user = JFactory::getUser();

		if($user->guest) {

			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {

			if(!$user->authorise('quiz.create', Q_APP_NAME) && !$user->authorise('quiz.manage', Q_APP_NAME)){

				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{

				$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
				$quiz_id = JRequest::getVar('id', 0, 'post', 'int');
				$qid = JRequest::getVar('qid', 0, 'post', 'int');

				if($qid && $quiz_id){

					$order = $model->reorder_question($quiz_id,$qid,1);

					if($order !== false) {

						echo json_encode(array('data'=>$order));
					}else {

						echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').$model->getError()));
					}
				}
			}
		}

		jexit();
	}

	function move_question_down(){

		$user = JFactory::getUser();

		if($user->guest) {

			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {

			if(!$user->authorise('quiz.create', Q_APP_NAME) && !$user->authorise('quiz.manage', Q_APP_NAME)){

				echo json_encode(array('error'=>JText::_('MSG_UNAUTHORIZED')));
			}else{

				$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
				$quiz_id = JRequest::getVar('id', 0, 'post', 'int');
				$qid = JRequest::getVar('qid', 0, 'post', 'int');

				if($qid && $quiz_id){

					$order = $model->reorder_question($quiz_id, $qid, 0);

					if($order !== false) {

						echo json_encode(array('data'=>$order));
					}else {

						echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(Q_DEBUG_ENABLED ? $model->getError() : '')));
					}
				}
			}
		}

		jexit();
	}

	function finalize_quiz(){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		if(!$user->authorise('quiz.create', Q_APP_NAME)){

			CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
		}else{

			$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
			$id = JRequest::getInt('id', 0);
			$quiz = $model->get_quiz_details($id, true);
			$itemid = CJFunctions::get_active_menu_id();

			if($quiz->published == 3){

				if(!$model->finalize_quiz($id)){

					$error = Q_DEBUG_ENABLED ? $model->getError() : '';
					$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=list'.$itemid, false), JText::_('MSG_ERROR_PROCESSING').$error);
				}else{

					$params = JComponentHelper::getParams(Q_APP_NAME);

					if(!$user->authorise('quiz.autoapprove', Q_APP_NAME)){

						if($params->get('admin_new_quiz_notification', 1) == 1){

							$from = $app->getCfg('mailfrom' );
							$fromname = $app->getCfg('fromname' );
							$admin_emails = $model->get_admin_emails($params->get('admin_user_groups', 8));

							if(!empty($admin_emails)){

								CJFunctions::send_email($from, $fromname, $admin_emails, JText::_('MSG_MAIL_PENDING_REVIEW_SUBJECT'), JText::_('MSG_MAIL_PENDING_REVIEW_BODY'), 1);
							}
						}

						$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=list'.$itemid, false), JText::_('MSG_SENT_FOR_REVIEW'));
					}else{

						$info = JHtml::link(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=respond&id='.$quiz->id.':'.$quiz->alias.$itemid), $quiz->title);

						QuizHelper::award_points($params, $quiz->created_by, 1, $quiz->id, $info);
						$link = JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=respond&id='.$quiz->id.":".$quiz->alias.$itemid);

						CJFunctions::stream_activity(
						$params->get('activity_stream_type', 'none'),
						$user->id,
						array(
										'command' => CQ_JSP_NEW_QUIZ,
										'component' => Q_APP_NAME,
										'title' => JText::sprintf('TXT_CREATED_QUIZ', $link, $quiz->title),
										'href' => $link,
										'description' => $quiz->description,
										'length' => $params->get('stream_character_limit', 256),
										'icon' => 'components/'.Q_APP_NAME.'/assets/images/icon-16-quiz.png',
										'group' => 'Quiz'
										));

										$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=list'.$itemid, false), JText::_('MSG_SUCCESSFULLY_SAVED'));
					}
				}
			} else {

				$this->setRedirect(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=list'.$itemid, false), JText::_('MSG_SUCCESSFULLY_SAVED'));
			}
		}
	}

	public function get_tags(){

		$app = JFactory::getApplication();
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$search = $app->input->getString('search');

		if(!empty($search)){

			$tags = $model->search_tags_by_name($search);
			echo json_encode(array('tags'=>$tags));
		} else {

			echo json_encode(array('tags'=>array()));
		}

		jexit();
	}

	public function new_page_2(){
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$app = JFactory::getApplication();
		$uniq_id = JRequest::getVar('uniq_id');
		$quizId = (JRequest::getVar('id') != '' || (int)JRequest::getVar('id') > 0 ? (int)JRequest::getVar('id') : 0);
		$pg = $model->create_page($quizId, $uniq_id);
		$this->config_page();
	}

	public function remove_page_2(){
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$uniq_id =  JRequest::getVar('uniq_id');
		$package_id = JRequest::getVar('package_id');
		$pid = JRequest::getVar('pageId');
		$model->remove_page_2($pid, $uniq_id);
		$this->config_page();
	}

	public function update_page_title(){
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$uniq_id =  JRequest::getVar('uniq_id');
		$package_id = JRequest::getVar('package_id');
		$pid = JRequest::getVar('pageId');
		$title = JRequest::getVar('pageTitle');
		$model->rename_page2($pid, $uniq_id, $title);
	}

	public function reorder_pages(){
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$order = JRequest::getVar('order');
		$ordering = array('order'=>$order);
		if(!empty($ordering['order']) && $model->reorder_pages_2($ordering['order'])) {
			echo json_encode(array('data'=>1));
		}
	}
	public function do_edit() {
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$user	= JFactory::getUser();
		$quiz = $model->get_quiz_details(JRequest::getVar("id"));
		$pages =  $model->get_pages_list(JRequest::getVar("id"));
		if(!empty($pages)) {
			$page = $pages[0];
			$uniq_key = $page->uniq_key;
		}
		$users = AwardPackageHelper::getUserData();	
		$quiz->package_id = $users->package_id;
		$quiz->uniq_id = $uniq_key;
		$quiz->giftcodeQuantityComplete = array(0=>'');
		$quiz->costPerResponseComplete = array(0=>'');
		$quiz->giftcodeQuantityIncomplete = array(0=>'');
		$quiz->costPerResponseIncomplete = array(0=>'');
		$quiz->categoryQuizComplete = array('New');
		$quiz->categoryQuizIncomplete = array('New');
		$quiz->quizPages = $model->get_pages($quiz->package_id, $quiz->uniq_id);
		$s = (count($quiz->quizPages) > 0 ? $quiz->quizPages[count($quiz->quizPages)-1] : null);
		$quiz->pageTitle = ($s != null ? $s->title : '');
		$quiz->pageId = ($s != null ? $s->id : '1');
		$quiz->quizPage = $quiz->pageId;
		$s = $model->get_quiz_question_giftcode($quiz->package_id,
		JRequest::getVar("id"), $quiz->quizPage, '1', $quiz->uniq_id);
		if(empty($s)) {
			$pageId = $model->create_page(0, $quiz->uniq_id);
			$model->insert_quiz_question_giftcode($quiz->package_id,
			'0', $pageId, '1', $quiz->uniq_id);

			$quiz->quizPages = $model->get_pages($quiz->package_id, $quiz->uniq_id);
			$s = (count($quiz->quizPages) > 0 ? $quiz->quizPages[count($quiz->quizPages)-1] : null);
			$quiz->pageTitle = ($s != null ? $s->title : '');
			$quiz->pageId = ($s != null ? $s->id : '');
			$quiz->quizPage = $quiz->pageId;
		}
		$data = $model->get_quiz_question_giftcode2($quiz->package_id,
			'0', $quiz->quizPage, $quiz->uniq_id);
		$arrQuestion = array();
		$i=0;
		foreach ($data as $d) {
			$arrQuestion[$i] = $d->question_id;
			$i++;
		}
		$quiz->questionSelectedId = $arrQuestion[0];
		$quiz->questionId = $data;

		$view = $this->getView('form', 'html');
		$quiz->tags = array();
		$categories = $model->get_category_tree();
		$view->quiz = $quiz;
		$view->categories__ = $categories;
		$view->setModel($model, true);
		$view->assign('action', 'form');
		$view->display();
	}

	public function question(){
		$uniq_id = JRequest::getVar('uniq_id');
		$package_id = JRequest::getVar('package_id');
		$question_id =JRequest::getVar('questionSelectedId');
		$pid = JRequest::getVar('pageId');
		$user = JFactory::getUser();
		if($user->guest) {
			$itemid = CJFunctions::get_active_menu_id();
			$redirect_url = base64_encode(JRoute::_('index.php?option='.Q_APP_NAME.'&view=quizzes'.$itemid));
			$this->setRedirect(CJFunctions::get_login_url($redirect_url, $itemid), JText::_('MSG_NOT_LOGGED_IN'));
		}else {
			$view = $this->getView('form', 'html');
			$model = JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
			$questions = $model->get_questions_2($uniq_id, $question_id, $pid);
			$quiz = null;
			$quiz = new stdClass();
			$quiz->questions = $questions;
			$quiz->pages = $model->get_pages($package_id, $uniq_id);
			$quiz->pid = $pid;

			if(!isset($_SESSION['quiz']) || empty($_SESSION['quiz'])) {
				session_start();
				$_SESSION['quiz'] = $_POST;
			}
			$view->setModel($model, true);
			$view->assign('questions', $quiz);
			$view->assign('action', 'questions');
			$view->display('questions');
		}
	}

	function save_question() {
		$user = JFactory::getUser();
		if($user->guest) {
			echo json_encode(array('error'=>JText::_('MSG_NOT_LOGGED_IN')));
		}else {
			$model = JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
			echo $qid;
			if($qid = $model->save_question()) {
				$question = $model->get_question($qid);
				if($question){
					echo json_encode(array('question'=>$question));
				} else {
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(Q_DEBUG_ENABLED ? '<br><br>Error:<br>'.$model->getError() : '')));
				}
			}else {
				echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING').(Q_DEBUG_ENABLED ? '<br><br>Error:<br>'.$model->getError() : '')));
			}
		}
		jexit();
	}

	function getQuestion(){
		$user = JFactory::getUser();
		if(!$user->guest) {
			$view = $this->getView('form', 'html');
			$view->assign('action', 'list_question');
			$view->display('list_question');
		}
	}
	public function preview(){
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		if($_GET['page'] == '') {
			if (!isset($_SESSION['quiz']) || empty($_SESSION['quiz'])) {
				session_start();
				$_SESSION['quiz'] = $_POST;
			}
		} else {
			$post = $_SESSION['quiz'];
		}
		$quiz = null;
		$quiz = new stdClass();
		$quiz ->package_id = ($_GET['page'] == '' ? $_POST['package_id'] : $post['package_id']);
		$quiz ->uniq_id = ($_GET['page'] == '' ? $_POST['uniq_id'] : $post['uniq_id']);
		$quiz->quizPages = $model->get_pages($quiz->package_id, $quiz->uniq_id);
		foreach ($quiz->quizPages as $pages) {
			$quiz->quizPage = $pages->id;
		}
		$quiz->quizPage = ($_GET['page'] == '' ? $quiz->quizPage : $_GET['page']);
		$data = $model->get_quiz_question_giftcode2($quiz->package_id,
		($_GET['page'] == '' ? JRequest::getVar('id') : $post['id']),
		$quiz->quizPage, $quiz->uniq_id);
		$newData = array();
		$i = 0;
		foreach ($data as $d) {
			$questions = $model->get_question_details($quiz->uniq_id,
			$d->question_id, $quiz->quizPage,
			($_GET['page'] == '' ? JRequest::getVar('id') : $post['id']));
			$d->questions = $questions;
			$newData[$i] = $d;
			$i++;
		}

		$quiz->questionId = $newData;
		$view = $this->getView('form', 'html');
		$view->assign('action', 'preview');
		$view->quiz = $quiz;
		$view->display('preview');
	}
}
?>
