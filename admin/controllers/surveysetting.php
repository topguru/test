<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.controlleradmin');
class AwardPackageControllerSurveysetting extends JControllerLegacy {
	function __construct() {
		parent::__construct();
	}
	public function do_edit() {
		$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
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
		
		$view = $this->getView('surveysetting', 'html');
		$view->survey = $survey;
		$view->display('doFirst');	
	}	
	public function do_first() {		
		$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
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
		
		$view = $this->getView('surveysetting', 'html');
		$view->survey = $survey;
		$view->display('doFirst');
	} 
	public function config_page() {
		$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
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
		$view = $this->getView('surveysetting', 'html');
		$view->survey = $survey;
		$view->display('doFirst');
	}	
	public function have_session() {
		$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
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
		$view = $this->getView('surveysetting', 'html');
		$view->survey = $survey;		
		if (isset($_SESSION['surveys'])) {
			unset($_SESSION['surveys']);
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=surveysetting&task=surveysetting.do_first&package_id='.JRequest::getVar('package_id').'&uniq_id='.JRequest::getVar('uniq_id'), false));
		}			
		$view->display('doFirst');
	}
	public function save_giftcode() {
		$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
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
		$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
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
		$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
		$uniq_id =  JRequest::getVar('uniq_id');
		$package_id = JRequest::getVar('package_id');
		$pid = JRequest::getVar('pageId');
		$title = JRequest::getVar('pageTitle');
		$model->rename_page2($pid, $uniq_id, $title);
	}
	public function reorder_pages(){
		$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
		$order = JRequest::getVar('order');
		$ordering = array('order'=>$order);
		if(!empty($ordering['order']) && $model->reorder_pages_2($ordering['order'])) {
			echo json_encode(array('data'=>1));
		}
	}
	public function new_page(){
		$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
		$app = JFactory::getApplication();
		$uniq_id = JRequest::getVar('uniq_id');
		$surveyId = (JRequest::getVar('id') != '' || (int)JRequest::getVar('id') > 0 ? (int)JRequest::getVar('id') : 0);
		$pg = $model->create_page($surveyId, $uniq_id);
		$this->config_page();
	}
	public function remove_page(){
		$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
		$uniq_id =  JRequest::getVar('uniq_id');
		$package_id = JRequest::getVar('package_id');
		$pid = JRequest::getVar('pageId');
		$model->remove_page_2($pid, $uniq_id);
		$this->do_first();
	}
	public function preview(){
		$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
		if($_GET['page'] == '') {
			if (!isset($_SESSION['surveys']) || empty($_SESSION['surveys'])) {
				session_start();
				$_SESSION['surveys'] = $_POST;
			}	
		} else {
			$post = $_SESSION['surveys'];
		}
		$survey->package_id = ($_GET['page'] == '' ? $_POST['package_id'] : $post['package_id']); 
		$survey->uniq_id = ($_GET['page'] == '' ? $_POST['uniq_id'] : $post['uniq_id']);
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
		$view = $this->getView('surveysetting', 'html');
		$view->survey = $survey;
		$view->display('preview');
	}
	public function question() {
		if (!isset($_SESSION['surveys']) || empty($_SESSION['surveys'])) {			
			session_start();
			$_SESSION['surveys'] = $_POST;		
		}
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=question', false), JText::_('MSG_SURVEY_UPDATED'));
	}
	public function save_survey(){
		$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
		$title = JRequest::getVar('title');
		if($title != '') {
			$survey = $model->create_edit_survey();
			if(!empty($survey) || !empty($survey->error)){
				$survey_id = $survey->id;
				$uniq_id = JRequest::getVar('uniq_id');
				$model->update_all_process($survey_id, $uniq_id);
				$model->update_answers($survey_id, $uniq_id);
			}
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=surveys&task=survey.get_survey_list&package_id='.JRequest::getVar('package_id'), false), JText::_('Updated Successful'));	
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=surveysetting&task=surveysetting.do_first&package_id='.JRequest::getVar('package_id').'&uniq_id='.JRequest::getVar('uniq_id'), false), JText::_('Required fields are missing. Please check the form and submit again.'));
		}	
		
	}
	
	public function cancel(){
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=surveys&package_id='.JRequest::getVar('package_id'), false), JText::_('MSG_CANCELLED'));
	}
	
}
?>