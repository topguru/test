<?php
/**
 * @version		$Id: view.html.php 01 2013-01-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageViewReports extends JViewLegacy {
	
	protected $params;
	protected $print;
	protected $state;
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		
		$active = $app->getMenu()->getActive();
		$itemid = CJFunctions::get_active_menu_id();
		
		$this->print = $app->input->getBool('print');
		$page_heading = '';
		
		/********************************** PARAMS *****************************/
		$appparams = JComponentHelper::getParams(Q_APP_NAME);
		$menuParams = new JRegistry;
		
		if ($active) {
		
			$menuParams->loadString($active->params);
		}
		
		$this->params = clone $menuParams;
		$this->params->merge($appparams);
		/********************************** PARAMS *****************************/

		switch($this->action){

			case 'quiz_results':

				$id = $app->input->getInt('id', 0);
				$rid = $app->input->getInt('rid', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$quiz = $model->get_quiz_details($id, false, true);
				if(empty($quiz)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$quiz->questions = $model->get_questions($quiz->id);
				$responses = $model->get_response_details($rid, $quiz->id, 0, false);
					
				foreach ($quiz->questions as &$question){

					$question->responses = array();
						
					foreach ($responses as $response){
			
						if($question->id == $response->question_id){
								
							$question->responses[] = $response;
						}
					}
				}
			
				$page_heading = JText::sprintf('TXT_RESULTS', $this->escape($quiz->title));
			
				$this->assignRef('item', $quiz);
				$this->assign('response_id', $rid);
			
				break;

			case 'quiz_reports':
				
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
				
				$quiz = $model->get_quiz_details_2($id, false, true);
				if(empty($quiz)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
				
				$quiz->stats = $model->get_quiz_statistics($quiz->id);
				$this->assignRef('item', $quiz);				
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($quiz->title));
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=quizzes&task=quizzes.get_quizzes_list&package_id='.JRequest::getVar('package_id'));
				
				break;

			case 'consolidated_report':
				
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($this->item->title));
				
				break;

			case 'quiz_responses':
				
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
				
				$quiz = $model->get_quiz_details($id, false, true);
				if(empty($quiz)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
				
				$result = $model->get_responses($quiz->id);
				$responses = empty($result->rows) ? array() : $result->rows;			
				$this->assignRef('item', $quiz);
				$this->assignRef('responses', $responses);
				$this->assignRef('pagination', $result->pagination);
				$this->assignRef('lists', $result->lists);				
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($quiz->title));
				//JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=reports&task=reports.get_quiz_reports&package_id='.JRequest::getVar('package_id').'&id='.JRequest::getVar('id'));
				
				break;

			case 'location_report':
			
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$quiz = $model->get_quiz_details($id, false, true);
				if(empty($quiz)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$result = $model->get_location_report($quiz->id);
				$responses = empty($result->rows) ? array() : $result->rows;
				$this->assignRef('item', $quiz);
				$this->assignRef('locations', $responses);
				$this->assignRef('pagination', $result->pagination);
				$this->assignRef('lists', $result->lists);			
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($quiz->title));
				//JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=reports&task=reports.get_quiz_reports&package_id='.JRequest::getVar('package_id').'&id='.JRequest::getVar('id'));
				break;

			case 'device_report':
					
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
					
				$quiz = $model->get_quiz_details($id, false, true);
				if(empty($quiz)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
					
				$result = $model->get_device_report($quiz->id);
				$responses = empty($result->rows) ? array() : $result->rows;
				
				$this->assignRef('item', $quiz);
				$this->assignRef('devices', $responses);
				$this->assignRef('pagination', $result->pagination);
				$this->assignRef('lists', $result->lists);					
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($quiz->title));
				//JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=reports&task=reports.get_quiz_reports&package_id='.JRequest::getVar('package_id').'&id='.JRequest::getVar('id'));
					
				break;

			case 'os_report':
					
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
					
				$quiz = $model->get_quiz_details($id, false, true);
				if(empty($quiz)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
					
				$result = $model->get_os_report($quiz->id);
				$responses = empty($result->rows) ? array() : $result->rows;
				
				$this->assignRef('item', $quiz);
				$this->assignRef('oses', $responses);
				$this->assignRef('pagination', $result->pagination);
				$this->assignRef('lists', $result->lists);
					
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($quiz->title));
				//JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=reports&task=reports.get_quiz_reports&package_id='.JRequest::getVar('package_id').'&id='.JRequest::getVar('id'));	
				break;
				
			case 'view_response':

				$id = $app->input->getInt('id', 0);
				$rid = $app->input->getInt('rid', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$quiz = $model->get_quiz_details($id, false, true);
				if(empty($quiz)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$quiz->questions = $model->get_questions($quiz->id);
				$responses = $model->get_response_details($rid, $quiz->id, 0, false);
					
				foreach ($quiz->questions as &$question){

					$question->responses = array();
						
					foreach ($responses as $response){
			
						if($question->id == $response->question_id){
								
							$question->responses[] = $response;
						}
					}
				}
			
				$page_heading = JText::sprintf('TXT_RESULTS', $this->escape($quiz->title));

				$this->assignRef('item', $quiz);
				$this->assign('response_id', $rid);
			
				break;
		}
		
		$this->assign('brand', JText::_('LBL_HOME'));
		$this->assign('brand_url', 'index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid);
		
		// set browser title
		$this->params->set('page_heading', $this->params->get('page_heading', $page_heading));
		
		$title = $this->params->get('page_title', $app->getCfg('sitename'));
		
		if ($app->getCfg('sitename_pagetitles', 0) == 1) {
				
			$document->setTitle(JText::sprintf('JPAGETITLE', $title, $page_heading));
		} elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
				
			$document->setTitle(JText::sprintf('JPAGETITLE', $page_heading, $title));
		} else {
			
			$document->setTitle($page_heading);
		}
		
		JToolBarHelper::title(JText::_('COM_COMMUNITYQUIZ_MENU').': <small><small>[ ' . $page_heading .' ]</small></small>', 'logo.png');
		
		// set meta description
		if ($this->params->get('menu-meta_description')){
				
			$document->setDescription($this->params->get('menu-meta_description'));
		}
		
		parent::display($tpl);
	}
	
}