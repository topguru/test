<?php
/**
 * @version		$Id: view.html.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );

class AwardpackageViewTags extends JViewLegacy {
	
	protected $params;
	protected $print;
	protected $state;
	
	function display($tpl = null) {
		
		$app = JFactory::getApplication();
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );;
		$document = JFactory::getDocument();
		
		$pathway = $app->getPathway();
		$active = $app->getMenu()->getActive();
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

		$limit = $this->params->get('list_length', $app->getCfg('list_limit', 50));
		$limitstart = $app->input->getInt('start', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$keywords = $app->input->getString('search', null);
		
		$itemid = CJFunctions::get_active_menu_id();
		$user = JFactory::getUser();
		
		$return = $model->get_tags($limitstart, $limit, $this->params, $keywords);
		
		$this->assignRef('items', $return['tags']);
		$this->assignRef('pagination', $return['pagination']);
		$this->assign('task', null);
		$this->assign('brand', JText::_('LBL_TAGS'));
		$this->assign('brand_url', JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz&task=tags'.$itemid));
		$this->assign('page_url', 'index.php?option='.Q_APP_NAME.'&view=quiz&task=tags'.$itemid);

		$page_heading = JText::_('LBL_TAGS');
		$pathway->addItem($page_heading);
		
		// set browser title
		$this->params->set('page_heading', $this->params->get('page_heading', $page_heading));
		
		if ($app->getCfg('sitename_pagetitles', 0) == 1) {
		
			$document->setTitle(JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $page_heading));
		} elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
		
			$document->setTitle(JText::sprintf('JPAGETITLE', $page_heading, $app->getCfg('sitename')));
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
		
		parent::display($tpl);
	}
}