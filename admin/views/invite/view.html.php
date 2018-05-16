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
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageViewInvite extends JViewLegacy {
	
	protected $params;
	protected $print;
	protected $state;
		
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();
		
		
		$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$usermodel = $this->getModel('usersv');
		
		$active = $app->getMenu()->getActive();
		$this->print = $app->input->getBool('print');
		
		/********************************** PARAMS *****************************/
		$this->params = JComponentHelper::getParams(S_APP_NAME);
		/********************************** PARAMS *****************************/
		
		$page_heading = JText::_('LBL_INVITE_USERS');
		$itemid = CJFunctions::get_active_menu_id();
		$id = $app->input->getInt('id', 0);
		
		$survey = $model->get_survey_details($id);
		$contact_groups = $model->get_contact_groups($user->id);
		$credits = $model->check_user_credits();
		$contacts = $model->get_contacts($user->id, 3, 0, true);
		$jsgroups = $usermodel->get_jomsocial_user_groups($survey->created_by, true);
		$unique_urls = $model->get_survey_keys($id);
		
		$this->assignRef('item', $survey);
		$this->assignRef('contact_groups', $contact_groups);
		$this->assignRef('contacts', $contacts);
		$this->assignRef('credits', $credits);
		$this->assignRef('jsgroups', $jsgroups);
		$this->assignRef('unique_urls', $unique_urls);
		
		JToolBarHelper::preferences(S_APP_NAME);
		//JToolBarHelper::cancel();
		JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=surveys&package_id='.JRequest::getVar('package_id'));
		
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
		
		JToolBarHelper::title(JText::_('COM_COMMUNITYSURVEYS').': <small><small>[ ' . JText::_('COM_COMMUNITYSURVEYS_INVITE') .' ]</small></small>', 'logo.png');
		
		parent::display($tpl);
	}
}