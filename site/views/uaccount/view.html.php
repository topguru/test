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
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/awardpackages.php';

class AwardpackageViewUaccount extends JViewLegacy {

	function __construct($config = array()) {
		$editor 			= JFactory::getEditor();
		$this->editor		= $editor;
		parent::__construct($config);
	}

	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();			
 $document = JFactory::getDocument();

        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.min.js');
        $document->addStyleSheet(JURI::base() . 'components/com_awardpackage/assets/css/jquery.ui.all.css');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.core.js');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.widget.js');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.tabs.js');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/tabs.js');		
		$document->addScript('http://services.iperfect.net/js/IP_generalLib.js');
		// set browser title
		$model = JModelLegacy::getInstance( 'ufunding', 'AwardpackageUsersModel' );
		$PackagesHelper = new AwardpackagesHelper;

		$user = AwardPackageHelper::getUserData();
		$session =& JFactory::getSession();
		$PackageId = $user->package_id;
		$packagedate = AwardPackageHelper::getPackageId($PackageId);			
		foreach ($packagedate as $row ){
		$enddate = date("Y-m-d", strtotime($row->end_date));
		}
		$today = date("Y-m-d"); 
					if ($today >= $enddate) {
					$expired = 1;
					}
// $CountryList = AwardpackagesHelper::Countries_list(); use on php 5.5//				
$countries = $PackagesHelper->Countries_list();				
$this->assignRef('countries', $countries);
				$this->assignRef('user', $user);
		        $this->assignRef('expired', $expired);	
				$this->assignRef('unlock', $unlock);
$from = JRequest::getVar('from');
$to = JRequest::getVar('to');
	 $filter_start = (!empty($from) ? $from : date ("1990-01-01") ) ;
	 $filter_end = (!empty($to) ? $to : date('Y-m-d', strtotime("+1 days")) ) ;
	 //$unlock =	( !empty(JRequest::getVar('unlock')) ? JRequest::getVar('unlock') : '0') ;
	/* Above code changed to below code by Sushil on 30-11-2015 */
	$unlock = JRequest::getVar('unlock');
	$unlock = ( !empty($unlock) ? $unlock : '0') ;

	 switch($this->action){
	 case 'getProfile' :
				$user = AwardPackageHelper::getUserData();
		// $CountryList = AwardpackagesHelper::Countries_list(); use on php 5.5//				
$CountryList = new AwardpackagesHelper;
$countries = $CountryList->Countries_list();
				$this->assignRef('countries', $countries);
				$this->assignRef('user', $user);
				$this->assignRef('unlock', $unlock);
				break;
		case 'NextProfile' :
				$user = AwardPackageHelper::getUserData();
		// $CountryList = AwardpackagesHelper::Countries_list(); use on php 5.5//				
		$CountryList = new AwardpackagesHelper;
		$countries = $CountryList->Countries_list();
				$this->assignRef('countries', $countries);
				$this->assignRef('user', $user);
				$this->assignRef('unlock', $unlock);
				break;				
		case 'getDonation' :
				$user = AwardPackageHelper::getUserData();
				$userId = $user->id;
				$userName = $user->username;				
				$packageId = $user->package_id;
//				$total = $model->getDonationHistoryTotal($userId, $packageId);
				$total = $model->getAllHistoryTotal($userId, $packageId, 'DONATION');
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
				$pending_histories = $model->getAllFundHistory($userId, $packageId, 'DONATION',$limit, $limitstart, $filter_start, $filter_end,'PENDING');
				$completed_histories = $model->getAllFundHistory($userId, $packageId, 'DONATION',$limit, $limitstart, $filter_start, $filter_end,'COMPLETED');
				$this->assignRef('pending_histories', $pending_histories);
				$this->assignRef('completed_histories', $completed_histories);
				break;	
       case 'getAwardSymbol' :
				$user = AwardPackageHelper::getUserData();
				$userId = $user->id;
				$userName = $user->username;				
				$packageId = $user->package_id;
				$total = count($model->getTotalSymbol($userId,1,100,0, $filter_start, $filter_end));
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
				
				$histories = $model->getTotalSymbol($userId,1,$limit, $limitstart, $filter_start, $filter_end);
				$symbolPrizes = $model->getSymbolSymbolPrize();
				$this->assignRef('symbolPrizes', $symbolPrizes);		
				foreach ($this->symbolPrizes as $rows){
				$amount = number_format($rows->prize_value/$rows->pieces ,0);
				}
				$this->assignRef('total_symbol', $total_symbol);
				$this->assignRef('histories', $histories);
				$this->assignRef('username', $userName);
								$this->assignRef('amount', $amount);

				
				break;				
		case 'getFunds' :
				$user = AwardPackageHelper::getUserData();
				$userId = $user->id;
				$userName = $user->username;				
				$packageId = $user->package_id;
				$total = $model->getAllHistoryTotal($userId, $packageId, 'FUNDING');
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
				$pending_histories = $model->getAllFundHistory($userId, $packageId, 'FUNDING',$limit, $limitstart, $filter_start, $filter_end,'PENDING');
				$completed_histories = $model->getAllFundHistory($userId, $packageId, 'FUNDING',$limit, $limitstart, $filter_start, $filter_end,'COMPLETED');
				$this->assignRef('pending_histories', $pending_histories);
				$this->assignRef('completed_histories', $completed_histories);
				break;	
		case 'getPrize' :
				$user = AwardPackageHelper::getUserData();
				$userId = $user->id;
				$userName = $user->username;				
				$packageId = $user->package_id;
				$total = $model->getAllHistoryTotal($userId, $packageId, 'PRIZE');
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
$pending_histories = $model->getAllFundHistory($userId, $packageId, 'PRIZE',$limit, $limitstart, $filter_start, $filter_end,'PENDING');
				$completed_histories = $model->getAllFundHistory($userId, $packageId, 'PRIZE',$limit, $limitstart, $filter_start, $filter_end,'COMPLETED');
				$this->assignRef('pending_histories', $pending_histories);
				$this->assignRef('completed_histories', $completed_histories);
				break;				
		case 'getShoppingCreditBusiness' :
				$user = AwardPackageHelper::getUserData();
				$userId = $user->id;
				$userName = $user->username;				
				$packageId = $user->package_id;
//				$total = $model->getDonationHistoryTotal($userId, $packageId);
				$total = $model->getAllHistoryTotal($userId, $packageId, 'REFUND');
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
$pending_histories = $model->getAllFundHistory($userId, $packageId, 'REFUND',$limit, $limitstart, $filter_start, $filter_end,'PENDING');
				$completed_histories = $model->getAllFundHistory($userId, $packageId, 'REFUND',$limit, $limitstart, $filter_start, $filter_end,'COMPLETED');
				$this->assignRef('pending_histories', $pending_histories);
				$this->assignRef('completed_histories', $completed_histories);
				$this->assignRef('fee', $fee);
				$this->assignRef('refund', $refund);				
				$this->assignRef('username', $userName);
				
				break;	
case 'getShoppingCredit' :
				$user = AwardPackageHelper::getUserData();
				$userId = $user->id;
				$userName = $user->username;				
				$packageId = $user->package_id;
//				$total = $model->getDonationHistoryTotal($userId, $packageId);
				$total = $model->getAllHistoryTotal($userId, $packageId, 'REFUND');
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
				$pending_histories = $model->getAllFundHistory($userId, $packageId, 'REFUND',$limit, $limitstart, $filter_start, $filter_end,'PENDING');
				$completed_histories = $model->getAllFundHistory($userId, $packageId, 'REFUND',$limit, $limitstart, $filter_start, $filter_end,'COMPLETED');
				$this->assignRef('pending_histories', $pending_histories);
				$this->assignRef('completed_histories', $completed_histories);
				$this->assignRef('fee', $fee);
				$this->assignRef('refund', $refund);				
				$this->assignRef('username', $userName);				
				break;					
				
		}
		parent::display($tpl);
	}

}
