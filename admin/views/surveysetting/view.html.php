<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';
class AwardpackageViewSurveysetting extends JViewLegacy {
	public $survey;
	
	function display($tpl = null) {
		$document = & JFactory::getDocument();
        $document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js');
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		
		$this->params = JComponentHelper::getParams(S_APP_NAME);
		$page_heading = JText::_('TXT_CREATE_EDIT_SURVEY');
		$this->print = $app->input->getBool('print');
		/********************************** PARAMS *****************************/
		$this->params = JComponentHelper::getParams(S_APP_NAME);
		/********************************** PARAMS *****************************/
		$id = $app->input->getInt('id', 0);
		$itemid = CJFunctions::get_active_menu_id();
		
		$this->assignRef('item', $this->survey);
		$this->assign('brand', JText::_('LBL_HOME'));
		$this->assign('brand_url', JRoute::_('index.php?option='.S_APP_NAME.'&view=survey'.$itemid));

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
		$package_id = JRequest::getVar("package_id");
		$smodel =& JModelLegacy::getInstance('surveys','AwardpackageModel');
		$scategories = $smodel->get_categories($package_id);		
		$this->assignRef("scategories", $scategories);
		$poll_model =& JModelLegacy::getInstance('Poll','AwardpackageModel');
		$categories = $poll_model->get_categories($package_id);		
		$this->assignRef("categories", $categories);
			
		if ($tpl != 'doFirst') {
			$this->addToolBar($tpl);
			parent::display($tpl);	
		} else {
			$this->addToolBar(null);
			parent::display(null);
		}
		
	}
	
	protected function addToolBar($tpl){
		if ($tpl == 'preview') {
			JToolBarHelper::title(JText::_('Survey Setting Preview'));
			JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=surveysetting&task=surveysetting.have_session&package_id='.JRequest::getVar('package_id').'&uniq_id='.JRequest::getVar('uniq_id'));
			JToolBarHelper::divider();	
		} else {
			JToolBarHelper::title(JText::_('Survey Setting'));
			JToolbarHelper::save('surveysetting.save_survey');
			JToolBarHelper::cancel('surveysetting.cancel');
			JToolBarHelper::divider();
		}

	}
}
?>