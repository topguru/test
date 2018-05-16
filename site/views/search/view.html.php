<?php
/**
 * @version		$Id: view.html.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Answers
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
class AwardpackageViewSearch extends JViewLegacy {
	
	protected $params;
	protected $print;
	protected $state;
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );;
		$document = JFactory::getDocument();
		$pathway = $app->getPathway();
		
		$active = $app->getMenu()->getActive();
		$page_heading = JText::_('LBL_ADVANCED_SEARCH');
		$this->print = $app->input->getBool('print');
		
		/********************************** PARAMS *****************************/
		$appparams = JComponentHelper::getParams(Q_APP_NAME);
		$menuParams = new JRegistry;
		
		if ($active) {
		
			$menuParams->loadString($active->params);
		}
		
		$this->params = clone $menuParams;
		$this->params->merge($appparams);
		/********************************** PARAMS *****************************/
		
		$itemid = CJFunctions::get_active_menu_id();
		$categories = $model->get_category_tree();
		
		$this->assignRef('categories', $categories);
		$this->assign('brand', JText::_('LBL_HOME'));
		$this->assign('brand_url', JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$itemid));

		$pathway->addItem($page_heading);
		
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
		$this->assign('page_id', 4);
		parent::display($tpl);
	}
}