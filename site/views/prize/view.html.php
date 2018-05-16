<?php
/**
 * @version		$Id: view.html.php 01 2013-01-13 11:37:09Z maverick $
 * @package		CoreJoomla.Survey
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );
jimport('joomla.html.pagination');

class AwardpackageViewPrize extends JViewLegacy {
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$users = AwardPackageHelper::getUserData();
					$packageId = $users->package_id;
		$packagedate = AwardPackageHelper::getPackageId($packageId);
		foreach ($packagedate as $row ){
		$enddate = date("Y-m-d", strtotime($row->end_date));
		}
		$today = date("Y-m-d"); 
					if ($today > $enddate) {
					$expired = 1;
					}
		$this->assignRef('expired', $expired);
		$user = JFactory::getUser();		
		$pathway = $app->getPathway();
				$total = 10;//$model->getDonationHistoryTotal($userId, $packageId);
		$app = JFactory::getApplication();		
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
		$model = & JModelLegacy::getInstance( 'prizewon', 'AwardpackageModel' );
		$paypal = $model->getPaypall();
	    $this->assignRef('paypals', $paypal);
		$user_id = $user->id;
		switch($this->action){
			case 'main_page':	
				$symbolPrizes = $model->getSymbolSymbolPrize($limit, $limitstart);
				$this->assignRef('symbolPrizes', $symbolPrizes);		
				$this->assign('page_id', 7);
				foreach ($this->symbolPrizes as $rows){
				$amount = number_format($rows->prize_value/$rows->pieces ,0);
				}
				
				
				$total_symbol = $model->getTotalSymbol($user_id,1);
				$this->assignRef('total_symbol', $total_symbol);	
				$this->assignRef('amount', $amount);	

				// breadcrumbs
				$pathway->addItem('Prize', JRoute::_($this->page_url.'&view=prize&task=prize.getMainPage'));		
				// add to pathway
				$pathway->addItem($page_heading);				
				// set browser title
				break;				
		case 'pieces':	
				$symbolPrizes = $model->getSymbolSymbolPrize($limit, $limitstart);
				$this->assignRef('symbolPrizes', $symbolPrizes);		
				$this->assign('page_id', 7);
				foreach ($this->symbolPrizes as $rows){
				$amount = number_format($rows->prize_value/$rows->pieces ,0);
				}
				
				
				$total_symbol = $model->getTotalSymbol($user_id,1);
				$this->assignRef('total_symbol', $total_symbol);	
				$this->assignRef('amount', $amount);	

				// breadcrumbs
				$pathway->addItem('Prize', JRoute::_($this->page_url.'&view=prize&task=prize.getMainPage'));		
				// add to pathway
				$pathway->addItem($page_heading);				
				// set browser title
				break;		
			case 'quantity':	
				$symbolPrizes = $model->getSymbolSymbolPrize($limit, $limitstart);
				$this->assignRef('symbolPrizes', $symbolPrizes);		
				$this->assign('page_id', 7);
				foreach ($this->symbolPrizes as $rows){
				$amount = number_format($rows->prize_value/$rows->pieces ,0);
				}
				
				
				$total_symbol = $model->getTotalSymbol($user_id,1);
				$this->assignRef('total_symbol', $total_symbol);	
				$this->assignRef('amount', $amount);	

				// breadcrumbs
				$pathway->addItem('Prize', JRoute::_($this->page_url.'&view=prize&task=prize.getMainPage'));		
				// add to pathway
				$pathway->addItem($page_heading);				
				// set browser title
				break;					
		}			
		parent::display($tpl);
	}	
	
}