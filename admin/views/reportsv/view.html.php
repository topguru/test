<?php
/**
 * @version		$Id: view.html.php 01 2013-01-13 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
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
class AwardpackageViewReportsv extends JViewLegacy {
	
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
		$this->params = JComponentHelper::getParams(S_APP_NAME);
		/********************************** PARAMS *****************************/

		CJFunctions::load_jquery(array('libs'=>array('fontawesome')));
		$document->addScript(JURI::base(true).'/components/com_awardpackage/assets/js/cj.surveys.min.js');
		$document->addStyleDeclaration('#element-box .m {background-color: #fff;}');
		
		//JToolBarHelper::custom( 'surveys', 'back', 'back', JText::_('COM_COMMUNITYSURVEYS_SURVEYS'), false, false );
		JToolbarHelper::back('Surveys', 'index.php?option=com_awardpackage&view=surveys&package_id='.JRequest::getVar('package_id'));
		
		switch($this->action){

			case 'survey_reports':
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);

				$survey = $model->get_survey_details($id, false, true);
				if(empty($survey)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);

				$survey->stats = $model->get_survey_statistics($survey->id);
				$this->assignRef('item', $survey);
			
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($survey->title));
			
				break;

			case 'consolidated_report':
				
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($this->item->title));
				JToolBarHelper::custom( 'reportsv.get_survey_reports', 'picture', 'picture', JText::_('COM_COMMUNITYSURVEYS_REPORTS'), false, false );
				break;

			case 'survey_responses':
				
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
				
				$survey = $model->get_survey_details($id, false, true);
				if(empty($survey)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
				
				$result = $model->get_responses($survey->id, 0, 'limitstart');
				$responses = empty($result->rows) ? array() : $result->rows;
				
				$this->assignRef('item', $survey);
				$this->assignRef('responses', $responses);
				$this->assignRef('pagination', $result->pagination);
				$this->assignRef('lists', $result->lists);
				
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($survey->title));
				JToolBarHelper::custom( 'reportsv.get_survey_reports', 'picture', 'picture', JText::_('COM_COMMUNITYSURVEYS_REPORTS'), false, false );
				JToolBarHelper::custom( 'reportsv.download_csv_report', 'download', 'download', 'CSV', false, false );
				JToolBarHelper::custom( 'reportsv.download_pdf_report', 'download', 'download', 'PDF', true, false );
				
				JToolBarHelper::divider();
				JToolBarHelper::deleteList('', 'reportsv.remove_responses', 'JTOOLBAR_DELETE');
				
				break;

			case 'location_report':
			
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$survey = $model->get_survey_details($id, false, true);
				if(empty($survey)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$result = $model->get_location_report($survey->id, 'limitstart');
				$responses = empty($result->rows) ? array() : $result->rows;

				$this->assignRef('item', $survey);
				$this->assignRef('locations', $responses);
				$this->assignRef('pagination', $result->pagination);
				$this->assignRef('lists', $result->lists);
			
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($survey->title));
				JToolBarHelper::custom( 'reportsv.get_survey_reports', 'picture', 'picture', JText::_('COM_COMMUNITYSURVEYS_REPORTS'), false, false );
				
				break;

			case 'device_report':
					
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
					
				$survey = $model->get_survey_details($id, false, true);
				if(empty($survey)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
					
				$result = $model->get_device_report($survey->id, 'limitstart');
				$responses = empty($result->rows) ? array() : $result->rows;
				
				$this->assignRef('item', $survey);
				$this->assignRef('devices', $responses);
				$this->assignRef('pagination', $result->pagination);
				$this->assignRef('lists', $result->lists);
					
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($survey->title));
				JToolBarHelper::custom( 'reportsv.get_survey_reports', 'picture', 'picture', JText::_('COM_COMMUNITYSURVEYS_REPORTS'), false, false );
					
				break;

			case 'os_report':
					
				$id = $app->input->getInt('id', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
					
				$survey = $model->get_survey_details($id, false, true);
				if(empty($survey)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
					
				$result = $model->get_os_report($survey->id, 'limitstart');
				$responses = empty($result->rows) ? array() : $result->rows;
				
				$this->assignRef('item', $survey);
				$this->assignRef('oses', $responses);
				$this->assignRef('pagination', $result->pagination);
				$this->assignRef('lists', $result->lists);
					
				$page_heading = JText::sprintf('TXT_REPORTS', $this->escape($survey->title));
				JToolBarHelper::custom( 'reports', 'picture', 'picture', JText::_('COM_COMMUNITYSURVEYS_REPORTS'), false, false );
					
				break;
				
			case 'view_response':

				$id = $app->input->getInt('id', 0);
				$rid = $app->input->getInt('rid', 0);
				if(!$id) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$survey = $model->get_survey_details($id, false, true);
				if(empty($survey)) CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTHOR'), 401);
			
				$survey->questions = $model->get_questions($survey->id);
				$responses = $model->get_response_details($rid, $survey->id, 0, false);
					
				foreach ($survey->questions as &$question){

					$question->responses = array();
						
					foreach ($responses as $response){
			
						if($question->id == $response->question_id){
								
							$question->responses[] = $response;
						}
					}
				}
			
				$page_heading = JText::sprintf('TXT_RESULTS', $this->escape($survey->title));
				JToolBarHelper::custom( 'reports', 'picture', 'picture', JText::_('COM_COMMUNITYSURVEYS_REPORTS'), false, false );
				JToolBarHelper::custom( 'responses', 'picture', 'picture', JText::_('COM_COMMUNITYSURVEYS_RESPONSES'), false, false );

				$this->assignRef('item', $survey);
				$this->assign('response_id', $rid);
			
				break;
		}
		
		$this->assign('brand', JText::_('LBL_HOME'));
		$this->assign('brand_url', 'index.php?option='.S_APP_NAME.'&view=survey'.$itemid);
		
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
		
		// set meta description
		if ($this->params->get('menu-meta_description')){
				
			$document->setDescription($this->params->get('menu-meta_description'));
		}
		
		// set meta keywords
		if ($this->params->get('menu-meta_keywords')){
				
			$document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		
		// set robots
		if ($this->params->get('robots')){
				
			$document->setMetadata('robots', $this->params->get('robots'));
		}
		
		// set nofollow if it is print
		if ($this->print){
				
			$document->setMetaData('robots', 'noindex, nofollow');
		}
		
		JToolBarHelper::title(JText::_('COM_COMMUNITYSURVEYS').': <small><small>[ ' . JText::_('COM_COMMUNITYSURVEYS_REPORTS') .' ]</small></small>', 'logo.png');
		
		parent::display($tpl);
	}
	
	function is_hide_template($config_value, $user_value){
		
		if($config_value == '1'){ // force hide
			
			return true;
		} else if($config_value == '2'){ // force show
			
			return false;
		} else if($user_value == 0){ //user selectible
			
			return true; // hide template as survey display_template value is not set
		} else {
			
			return false;
		}
	}
}