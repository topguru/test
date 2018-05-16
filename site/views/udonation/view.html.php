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
class AwardpackageViewUdonation extends JViewLegacy {
	
	function display($tpl = null) {		
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();		
		
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
		$model = & JModelLegacy::getInstance( 'udonation', 'AwardpackageUsersModel' );
		$paypal = $model->getPaypall($packageId);
	    $this->assignRef('paypals', $paypal);
        $this->assignRef('paycheck', $paypalacc);
		$this->assignRef('userId', $userId);
		$this->assignRef('package_id', $packageId);
		switch($this->action){
			case 'main_page':

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
				$remain =  $credit - $debit ;
				$this->assignRef('remain', $remain);					
				$this->assign('page_id', 3);
				// breadcrumbs
				$pathway->addItem('Donation', JRoute::_($this->page_url.'&view=udonation&task=udonation.getMainPage'));		
				// add to pathway
				$pathway->addItem($page_heading);				
				// set browser title
				
				$poll_model =& JModelLegacy::getInstance('Poll','AwardpackageModel');
				$categories = $poll_model->get_categories('5');
				$this->assignRef('categories', $categories);
				break;	
			case 'do_calculate':

				$users = AwardPackageHelper::getUserData();
				$userId = $users->id;
				$this->assign('page_id', 3);
				// breadcrumbs
				$pathway->addItem('Donation', JRoute::_($this->page_url.'&view=udonation&task=udonation.getMainPage'));		
				$pathway->addItem('Calculate Total Donation', JRoute::_($this->page_url.'&view=udonation&task=udonation.doCalculate'));
				// add to pathway
				$pathway->addItem($page_heading);				
				// set browser title		
			$model_donation = & JModelLegacy::getInstance( 'udonation', 'AwardpackageUsersModel' );
			$categories = JRequest::getVar('category');
			$setting = JRequest::getVar('setting');
			$colors = JRequest::getVar('colors');
			$times = JRequest::getVar('times');
			
			$i = 0;
			$kali =0;
			foreach ($categories as $i=>$category) {			
				if (!empty($times[$i])){
				$category_id = $category;
				$setting_id = $setting[$i];
				$kali = $times[$i];
					
			
			$giftcode_user = $model_donation->CekGiftcode($setting_id,$user->id);
			$jml_gc = count($giftcode_user);
			$tot_gc = $jml_gc + $kali;
		
			//$giftcode = $this->getGiftcode($setting_id, $jml_gc, $tot_gc);	

				$giftcode = $model_donation->getGiftcode($setting_id,$jml_gc, $tot_gc);
						$tmp=0;

						foreach ($giftcode as $row)
						if ($tmp++ < $kali)
						{	
						  $model_donation->savegiftcode($users->id, $setting_id, $row->id);
						}
				}			
						$i++;
				}		
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
			case 'select_payment':
				$users = AwardPackageHelper::getUserData();
				$userId = $users->id;
				$this->assign('page_id', 3);
				// breadcrumbs
				$pathway->addItem('Donation', JRoute::_($this->page_url.'&view=udonation&task=udonation.getMainPage'));		
				$pathway->addItem('Select Payment', JRoute::_($this->page_url.'&view=udonation&task=udonation.selectPayment'));
				// add to pathway
				$pathway->addItem($page_heading);
				// set browser title
				$amount = JRequest::getVar('amount');
				$this->assignRef('amount', $amount);
				$model = & JModelLegacy::getInstance( 'ufunding', 'AwardpackageUsersModel' );				
				$paymentMethods = $model->getPaymentMethod();
				$this->assignRef('methods', $paymentMethods);
			

				break;				
		}			
		parent::display($tpl);

	}	
	
}