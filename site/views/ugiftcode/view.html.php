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

class AwardpackageViewUgiftcode extends JViewLegacy {

	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$users = AwardPackageHelper::getUserData();
						$userId = $users->ap_account_id;
					$packageId = $users->package_id;
					$paypalacc = $users->paypal_account;
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
		$model = & JModelLegacy::getInstance( 'ugiftcode', 'AwardpackageUsersModel' );
		$symbolPrizes = $model->getSymbolSymbolPrize_2($packageId); 
		$this->assignRef('symbolPrizes', $symbolPrizes);

		switch($this->action){
			case 'main_page' :
				$this->assign('page_id', 6);
				// breadcrumbs
				$pathway->addItem('Giftcode', JRoute::_($this->page_url.'&view=ugiftcode&task=ugiftcode.getMainPage'));
				// add to pathway
				$pathway->addItem($page_heading);

				$user_id = $users->id;
				if(isset($_POST['button_check'])){
					$giftcode =  $_POST['giftcode'];
					$button_check = $_POST['button_check'];
					$giftcodeId =  $_POST['giftcodeId'];
					$model->UpdateUserGiftcodes($giftcode, $giftcodeId,$user_id);
				}

				$categories = $model->getCategories($packageId);
				$this->assignRef('categories', $categories);
				$gcid = $this->categories->setting_id;
				$this->assignRef('gcid', $gcid);
				$this->assignRef('tot_symbol', $tot_symbol);
				
				$collection = $model->getAllGiftcodes_2();
				$this->assignRef('collects', $collection);
				$usercollection = $model->getAllUserGiftcodes($gcid,$user_id);			
				$this->assignRef('usercollection', $usercollection);				
				
				$collect_user = $model->getAllGiftcodesUser ($gcid,$user_id);
				$this->assignRef('collect_user', $collect_user);		
				
				$usersymbol = $model->getAllUserSymbol($symbol);				
				$this->assignRef('usersymbol', $usersymbol);
				break;
			case 'getHistoryGiftcode':
				$user = JFactory::getUser();
				$user_id = $users->id;
				$package_id = JRequest::getVar('package_id');
				$total = 10;//$model->getDonationHistoryTotal($userId, $packageId);
		$app = JFactory::getApplication();		
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
						$giftcodes = $model->getAllUserGiftcodesHistory($package_id,$user_id,$limit, $limitstart);
				//$giftcodes = !empty($result['giftcodes']) ? $result['giftcodes'] : array();
				$this->assignRef('giftcodes', $giftcodes);
				
								
				break;			
				case 'select_category':
				$this->assign('page_id', 6);
				$gcid = $_POST['category'];
				$tot_symbol = $_POST['categoryid'];
				$this->assignRef('gcid', $gcid);
				$this->assignRef('tot_symbol', $tot_symbol);
				
				// breadcrumbs
				$pathway->addItem('Giftcode', JRoute::_($this->page_url.'&view=ugiftcode&task=ugiftcode.getMainPage'));
				// add to pathway
				$pathway->addItem($page_heading);
				
				$users = AwardPackageHelper::getUserData();
				$userId = $users->ap_account_id;
				$packageId = $users->package_id;
				$user_id = $users->id;
				if(isset($_POST['button_check'])){
					$giftcode =  $_POST['giftcode'];
					$button_check = $_POST['button_check'];
					$giftcodeId =  $_POST['giftcodeId'];
					$model->UpdateUserGiftcodes($giftcode, $giftcodeId,$user_id);
				}
				
				$categories = $model->getCategories($packageId);
				$this->assignRef('categories', $categories);
				
				
				
				$categories = $model->getCategories($packageId);
				$this->assignRef('categories', $categories);
				
				$collection = $model->getAllGiftcodes($gcid);
				$this->assignRef('collects', $collection);
				$usercollection = $model->getAllUserGiftcodes($gcid,$user_id);			
				$this->assignRef('usercollection', $usercollection);				
				
				$collect_user = $model->getAllGiftcodesUser ($gcid,$user_id);
				$this->assignRef('collect_user', $collect_user);		
				
				$usersymbol = $model->getAllUserSymbol($symbol);				
				$this->assignRef('usersymbol', $usersymbol);
				break;
		}

		parent::display($tpl);
	}

}
