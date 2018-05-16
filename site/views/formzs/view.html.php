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
class AwardpackageViewFormzs extends JViewLegacy {
	
	protected $params;
	protected $print;
	protected $state;
		
	function display($tpl = null) {
			$document = & JFactory::getDocument();
        $document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js');
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();
		
		$this->params = JComponentHelper::getParams(S_APP_NAME);
		$page_heading = JText::_('TXT_CREATE_EDIT_SURVEY');
		$this->print = $app->input->getBool('print');
		/********************************** PARAMS *****************************/
		$this->params = JComponentHelper::getParams(S_APP_NAME);
		/********************************** PARAMS *****************************/
		$id = $app->input->getInt('id', 0);
		$package_id = $app->input->getInt('package_id', 0);
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

		$model = & JModelLegacy::getInstance( 'survey', 'AwardpackageUsersModel' );
		$users = AwardPackageHelper::getUserData();	
		$categories_model = & JModelLegacy::getInstance( 'categorieszs', 'AwardpackageUsersModel' );
		$scategories = $categories_model->get_categories($users->package_id);		
		$this->assignRef("scategories", $scategories);
		$poll_model =& JModelLegacy::getInstance('poll','AwardpackageModel');
		$categories = $poll_model->get_categories($users->package_id);	
		$package_id = $users->package_id;	
		$this->assignRef("categories", $categories);
		$this->assignRef("package_id", $package_id);		
		$this->model = $poll_model;

		if($this->action == 'formzs') {
			///JToolBarHelper::custom( 'form.save_quiz', 'save', 'save', 'Save & Close', false, false );
			//JToolBarHelper::cancel('form.cancel_form');
		}
		if($this->action == 'preview') {
			//JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=form&task=form.have_session&package_id='.JRequest::getVar('package_id').'&uniq_id='.JRequest::getVar('uniq_id'));
		}
		
		if ($this->action == 'list_question') {
			$id = JRequest::getVar('id');
			$answers = $model->get_question_list($id);

			$this->assignRef('answers', $answers);
			//JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=form&task=form.have_session&package_id='.JRequest::getVar('package_id').'&uniq_id='.JRequest::getVar('uniq_id'));
		}
		
		if ($tpl != 'doFirst') {
			parent::display($tpl);	
		} else {
			parent::display(null);
		}
	}
}