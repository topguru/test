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
class AwardpackageViewUtransfer extends JViewLegacy {

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
		$model = & JModelLegacy::getInstance( 'utransfer', 'AwardpackageUsersModel' );
		$paypal = $model->getPaypall($packageId);
	    $this->assignRef('paypals', $paypal);
        $this->assignRef('paycheck', $paypalacc);
		switch($this->action){
			case 'main_page':
				$this->assign('page_id', 2);

				if($user->guest) {
					$this->setRedirect(JRoute::_('index.php?option=com_awardpackage'));
				} else {
					$users = AwardPackageHelper::getUserData();
					$userId = $users->id;
					$packageId = $users->package_id;					
					
				}

				break;
			case 'add_transfer':
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
				
			case 'add_transfer_confirm':
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
			
			case 'add_transfer_complete':
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
			
			case 'add_convert':
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
				
			case 'add_convert_confirm':
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
			
			case 'add_convert_complete':
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
		}
		parent::display($tpl);
	}

}