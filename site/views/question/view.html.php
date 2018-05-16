<?php

/**
 * @version		$Id: view.html.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
 
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class AwardpackageViewQuestion extends JViewLegacy {

	protected $params;
	protected $print;
	protected $state;
	protected $canDo;

	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();
		
		$id = $_GET['id'];
		$qid = $_GET['qid'];
		$page_id = $_GET['pageid'];
		$uniq_id = $_GET['uniqkey'];
		$questionSelectedId = $post['questionSelectedId'];

		if (empty($id) && empty($qid)){
			if(!empty($_SESSION['surveys'])){
				$post = $_SESSION['surveys'];
				$id = $post['id'];//$app->input->getInt('id', 0);
				$page_id = $post['surveyPages'];//$app->input->getInt('pid', 0);
				$uniq_id = $post['uniq_id'];
				$questionSelectedId = $post['questionSelectedId'];
				}
			}
			

		$model = JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$document = JFactory::getDocument();
		$user	= JFactory::getUser();

		$page_heading = JText::_('TXT_CREATE_EDIT_SURVEY');
		$this->print = $app->input->getBool('print');

		/********************************** PARAMS *****************************/
		$this->params = JComponentHelper::getParams(S_APP_NAME);
		/********************************** PARAMS *****************************/


		//$this->addToolBar($post);

		$itemid = CJFunctions::get_active_menu_id();

		$this->addToolBar($post);

		$survey = $model->get_survey_details($id);
		$survey->pages = $model->get_pages_list($id);

		//if(!$page_id && !empty($survey->pages)) $page_id = $survey->pages[0];
		/*
		$survey->questions = $model->get_question_details(JRequest::getVar('uniq_id'), 
				JRequest::getVar('questionSelectedId'),JRequest::getVar('surveyPages'));

		$survey->questions = $model->get_question_details($post['uniq_id'], 
				$post['questionSelectedId'],$post['surveyPages']);*/
		$id = JRequest::getVar("id");	//$_GET['id'];
		$qid = JRequest::getVar("qid");	//$_GET['qid'];
		$page_id = JRequest::getVar("pageid");	//$_GET['pageid'];
		$uniq_id = JRequest::getVar("uniqkey");	// $_GET['uniqkey'];
		$questionSelectedId = $post['questionSelectedId'];
		$survey->questions = $model->get_question_details($uniq_id, $qid, $page_id, $id);	
//		get_question_details($uniq_key, $question_id, $page_id, $survey_id)
		//$survey = $model->get_survey_details($id, $page_id, false, true, false);
		$this->assignRef('item', $survey);
		$this->assignRef('pid', $page_id);
		$this->assignRef('uniq_id', $uniq_id);
		$this->assignRef('questionSelectedId', $qid);
		$this->assign('brand', JText::_('LBL_HOME'));
		$this->assign('brand_url', JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid));

		if (count($user->getAuthorisedCategories(S_APP_NAME, 'core.create')) > 0) {

			//JToolBarHelper::custom( 'finalize', 'save', 'save', 'Save & Close', false, false );
		}

		$tpl = 'questions';

		$document->addScript(JURI::base(true).'/components/com_awardpackage/assets/js/cj.surveys.min.js');

		// set browser title
		$this->params->set('page_heading', $this->params->get('page_heading', $page_heading));
		$title = $this->params->get('page_title', $app->getCfg('sitename'));

		if ($app->getCfg('sitename_pagetitles', 0) == 1) {

			$document->setTitle(JText::sprintf('COM_COMMUNITYSURVEYS_JPAGETITLE', $title, $page_heading));
		} elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {

			$document->setTitle(JText::sprintf('COM_COMMUNITYSURVEYS_JPAGETITLE', $page_heading, $title));
		} else {

			$document->setTitle($page_heading);
		}

		parent::display();
	}	
	protected function addToolBar($post){
		$user	= JFactory::getUser();
		$this->state	= $this->get('State');
		//JToolBarHelper::title(JText::_('COM_COMMUNITYSURVEYS').': <small><small>[ ' . JText::_('COM_COMMUNITYSURVEYS_FORM') .' ]</small></small>', 'logo.png');
		//JToolBarHelper::preferences(S_APP_NAME);
		//JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=surveysetting&task=surveysetting.have_session&package_id='.$post['package_id'].'&uniq_id='.$post['uniq_id']);
		//JToolBarHelper::divider();		
	}	
}