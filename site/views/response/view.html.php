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
class AwardpackageViewResponse extends JViewLegacy {

	protected $params;
	protected $print;
	protected $state;

	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();
		$model = JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );;
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
						
		$pathway = $app->getPathway();
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

		switch($this->task){

			case 'quiz_intro':
				$page_heading = JText::sprintf('TXT_INTRODUCTION', $this->escape($this->item->title));
				break;

			case 'response_form':

				$page_heading = JText::sprintf('TXT_QUIZ_RESPONSE', $this->escape($this->item->title));
				break;
		}
			
		if ($this->is_hide_template($this->params->get('hide_template', 0), $this->item->show_template)) {

			$app->input->set('tmpl', 'component');
			$app->input->set('format', 'raw');
				
			$this->assign('hide_template', 1);
		} else {

			$this->assign('hide_template', 0);
		}

		$this->assign('brand', JText::_('LBL_HOME'));
		$this->assign('brand_url', 'index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid);

		// set browser title
		$this->params->set('page_heading', $this->params->get('page_heading', $page_heading));
		$pathway->addItem($page_heading);

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
		$this->assign('page_id', 4);
		parent::display($tpl);
	}

	function is_hide_template($config_value, $user_value){

		if($config_value == '1'){ // force hide
				
			return true;
		} else if($config_value == '2'){ // force show
				
			return false;
		} else if($user_value == 0){ //user selectible
				
			return true; // hide template as quiz show_template value is not set
		} else {
				
			return false;
		}
	}
}