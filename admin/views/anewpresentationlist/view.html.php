<?php
/**
 * @version		$Id: view.html.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quizzes
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );
jimport('joomla.html.pagination');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageViewAnewpresentationlist extends JViewLegacy {
	
	function display($tpl = null) {	
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();		
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$total = 10;//$model->getDonationHistoryTotal($userId, $packageId);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
				
		JToolBarHelper::title(JText::_('Create Presentation'), 'logo.png');
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );		
		switch ($this->action){
			case 'initiate' :
				$symbols = $model->getSymbols(JRequest::getVar('package_id'));
				$prizes = $model->getPrizes(JRequest::getVar('package_id'));
				$symbolPrizes = $model->getSymbolSymbolPrize(JRequest::getVar('package_id'),$limit, $limitstart);
				$prizeSelected = $model->getPrizeById(JRequest::getVar('idPrizeId'), JRequest::getVar('package_id'));
				$symbolSelected = $model->getSymbolById(JRequest::getVar('idSymbolId'), JRequest::getVar('package_id'));
				$this->assignRef('symbols', $symbols);
				$this->assignRef('prizes', $prizes);
				$this->assignRef('symbolPrizes', $symbolPrizes);
				$this->assignRef('prizeSelected', $prizeSelected);
				$this->assignRef('symbolSelected', $symbolSelected);
				break;
		}		
		JToolBarHelper::custom('anewpresentationlist.create', 'copy', 'copy', 'Save & Close', false);		
		JToolBarHelper::deleteList('Are you sure ?', 'anewpresentationlist.delete', 'Delete');
		JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));
		parent::display($tpl);
	}		
}