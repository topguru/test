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
class AwardpackageViewUfunding extends JViewLegacy {

	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		
		$users = AwardPackageHelper::getUserData();
					$userId = $users->id;
					$packageId = $users->package_id;
					$paypalacc = $users->emailRegistered;
		$packagedate = AwardPackageHelper::getPackageId($packageId);
		foreach ($packagedate as $row ){
		$enddate = date("Y-m-d", strtotime($row->end_date));
		}
		$today = date("Y-m-d"); 
					if ($today > $enddate) {
					$expired = 1;
					}
		$this->assignRef('expired', $expired);
							
		$pathway = $app->getPathway();
		$model = & JModelLegacy::getInstance( 'ufunding', 'AwardpackageUsersModel' );
		$paypal = $model->getPaypall($packageId);
	    $this->assignRef('paypals', $paypal);
        $this->assignRef('paycheck', $paypalacc);
		switch($this->action){
			case 'main_page':
				$this->assign('page_id', 2);
				// breadcrumbs
				$pathway->addItem('Funds', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.getMainPage'));
				// add to pathway
				$pathway->addItem($page_heading);
				// set browser title
				if($user->guest) {
					$this->setRedirect(JRoute::_('index.php?option=com_awardpackage'));
				} else {
					$users = AwardPackageHelper::getUserData();
					$userId = $users->id;
					$packageId = $users->package_id;
						
					$histories = $model->getAllHistory($userId, $packageId,'All');

					$debit = 0;
					$credit = 0;
					/*
					foreach ($histories as $history) {
						$debit += !empty($history->debit) && $history->debit != null ? (int) $history->debit : 0;
						$credit += !empty($history->credit) && $history->credit != null ? (int) $history->credit : 0;
					}
					*/
					/* Above code modified to below code by Sushil 30-11-2015 */
					foreach ($histories as $history) 
					{
						if($history->transaction_type == 'FUNDING')
						{
							$debit += !empty($history->debit) && $history->debit != null ? (int) $history->debit : 0;
							$credit += !empty($history->credit) && $history->credit != null ? (int) $history->credit : 0;
						}
					}
					$remain = $credit - $debit;
					$this->assignRef('remain', $remain);
				}

				break;
			case 'add_funds':
				$this->assign('page_id', 2);
				// breadcrumbs
				$pathway->addItem('Funds', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.getMainPage'));
				$pathway->addItem('Add Funds', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.addFunds'));
				// add to pathway
				$pathway->addItem($page_heading);
				// set browser title
				break;
				
			case 'shopping_credit':
				$this->assign('page_id', 2);
				$users = AwardPackageHelper::getUserData();
					$userId = $users->id;
					$packageId = $users->package_id;
					
				$result = $model->getShoppingCreditUser($userId);
				foreach ($result as $row){
					$totalsc = $row->totalsc;
				}
			    $amount = JRequest::getVar('amount');
				$this->assignRef('amount', $amount);
				$this->assignRef('totalsc', $totalsc);
			break;
			
			case 'shopping_credit_confirm':
				$this->assign('page_id', 2);
				$users = AwardPackageHelper::getUserData();
					$userId = $users->id;
					$packageId = $users->package_id;
					
				$result = $model->getShoppingCreditUser($userId);
				foreach ($result as $row){
					$totalsc = $row->totalsc;
				}
			    $amount = JRequest::getVar('amount');
				$difference = JRequest::getVar('difference');
				$this->assignRef('amount', $amount);
				$this->assignRef('difference', $difference);
				$this->assignRef('totalsc', $totalsc);
			break;
				
			case 'withdraw_funds':
				$this->assign('page_id', 2);
				// breadcrumbs
				$pathway->addItem('Funds', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.getMainPage'));
				$pathway->addItem('Witdraw Funds', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.withdrawFunds'));
				// add to pathway
				$pathway->addItem($page_heading);
				// set browser title
				if($user->guest) {
					$this->setRedirect(JRoute::_('index.php?option=com_awardpackage'));
				} else {
					$users = AwardPackageHelper::getUserData();
					$userId = $users->id;
					$packageId = $users->package_id;
						
					$histories = $model->getAllHistory($userId, $packageId,'All');
					$debit = 0;
					$credit = 0;
					foreach ($histories as $history) {
						$debit += !empty($history->debit) && $history->debit != null ? (int) $history->debit : 0;
						$credit += !empty($history->credit) && $history->credit != null ? (int) $history->credit : 0;
					}
					$remain = $credit - $debit;
					$this->assignRef('remain', $remain);
				}
				break;
			case 'add_funds_confirm':
				$this->assign('page_id', 2);
				// breadcrumbs
				$pathway->addItem('Funds', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.getMainPage'));
				$pathway->addItem('Add Funds', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.addFunds'));
				$pathway->addItem('Add Funds Confirmation', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.addFundsConfirm'));
				// add to pathway
				$pathway->addItem($page_heading);
				// set browser title
				$amount = JRequest::getVar('amount');
				$this->assignRef('amount', $amount);

				$paymentMethods = $model->getPaymentMethod();
				$this->assignRef('methods', $paymentMethods);
				break;
			case 'giftcode_list':
				$this->assign('page_id', 2);
				// breadcrumbs
				$pathway->addItem('Funds', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.getMainPage'));
				$pathway->addItem('Add Funds', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.addFunds'));
				$pathway->addItem('Add Funds Confirmation', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.addFundsConfirm'));
				// add to pathway
				$pathway->addItem($page_heading);
				// set browser title
				if($user->guest) {
					$this->setRedirect(JRoute::_('index.php?option=com_awardpackage'));
				} else {
					$users = AwardPackageHelper::getUserData();
					$userId = $users->id;
					$packageId = $users->package_id;
					$histories = $model->getAllHistory($userId, $packageId,'All');
					$debit = 0;
					$credit = 0;
					foreach ($histories as $history) {
						$debit += !empty($history->debit) && $history->debit != null ? (int) $history->debit : 0;
						$credit += !empty($history->credit) && $history->credit != null ? (int) $history->credit : 0;
					}
					$remain = $credit - $debit;
					$this->assignRef('remain', $remain);
				}

				$poll_model =& JModelLegacy::getInstance('Poll','AwardpackageModel');				
				$categories = $poll_model->get_categories($packageId);				
				$this->assignRef('categories', $categories);
				break;			
			case 'withdraw_funds_confirm':				
				$this->assign('page_id', 2);
				// breadcrumbs
				$pathway->addItem('Funds', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.getMainPage'));
				$pathway->addItem('Witdraw Funds', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.withdrawFunds'));
				$pathway->addItem('Witdraw Funds Confirmation', JRoute::_($this->page_url.'&view=ufunding&task=ufunding.withdrawFundsConfirm'));
				// add to pathway
				$pathway->addItem($page_heading);
				// set browser title
				if($user->guest) {
					$this->setRedirect(JRoute::_('index.php?option=com_awardpackage'));
				} else {
					$users = AwardPackageHelper::getUserData();
					$userId = $users->id;
					$packageId = $users->package_id;
					$histories = $model->getAllHistory($userId, $packageId,'All');
					
					
					$debit = 0;
					$credit = 0;
					foreach ($histories as $history) {
						$debit += !empty($history->debit) && $history->debit != null ? (int) $history->debit : 0;
						$credit += !empty($history->credit) && $history->credit != null ? (int) $history->credit : 0;
					}
					$remain = $credit - $debit;
					$this->assignRef('remain', $remain);
				}

				$poll_model =& JModelLegacy::getInstance('Poll','AwardpackageModel');				
				$categories = $poll_model->get_categories($packageId);				
				$this->assignRef('categories', $categories);
				break;
		}
		parent::display($tpl);
	}

}