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
class AwardpackageViewUshoppingcreditplan extends JViewLegacy {	
	
	function display($tpl = null) {		
	$document = & JFactory::getDocument();
        $document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js');
		
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$model =& JModelLegacy::getInstance('Shoppingcreditplan','AwardpackageUsersModel');
		$model_categories = & JModelLegacy::getInstance( 'shoppingcreditcategory', 'AwardpackageUsersModel' );	
		$model_donation = & JModelLegacy::getInstance( 'udonation', 'AwardpackageUsersModel' );
		$pathway = $app->getPathway();
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
		switch($this->action){
			case 'main_page':
			
	   
		$items = $this->get('Items');		
				$this->assign('page_id', 8);
				// breadcrumbs
				$pathway->addItem('Shopping Credits', JRoute::_($this->page_url.'&view=ushoppingcreditplan&task=ushoppingcreditplan.getMainPage'));		
				// add to pathway
				$pathway->addItem($page_heading);				

  		        $description = $model->get_donation_history($user->id);
			//	var_dump($description);
				foreach ($description as $desc){
					$user_description = $desc->description;
					$amount = $desc->debit;
					$date_donation = $desc->created_date;
				}
				
				//$result = $model->get_shopping_credit_plan($packageId);
				$sc_plan = $model->getShoppingCredit($packageId);
				$shoppings = !empty($result['shoppings']) ? $result['shoppings'] : array();

				foreach ($sc_plan as $sc){
					$fee = $sc->fee;
					$refund = $sc->refund;
					$unlock = $sc->unlock;
					$expire = $sc->expire;
					$uniq_id = $sc->uniq_key;
					$start_date = $sc->start_date;									
					$end_date = $sc->end_date;	
					$contrib_radio = $sc->contribution_range;			
					 $progress_radio = $sc->progress_check;							

				}

				//$plans = $model->get_shopping_credit_plan_detail(JRequest::getVar('id'));
				
				$this->assignRef('shoppings', $shoppings);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				$username = $users->username;		
				$this->assignRef('user_description', $user_description);	
				$this->assignRef('amount', $amount);	
				$this->assignRef('date_donation', $date_donation);	
				
				$this->assignRef('fee', $fee);	
				$this->assignRef('refund', $refund);	
				$this->assignRef('unlock', $unlock);	
				$this->assignRef('expire', $expire);	
				$this->assignRef('sc_plan', $sc_plan);	
				$this->assignRef('start_date', $start_date);	
				$this->assignRef('end_date', $end_date);	
					$this->assignRef('contrib_radio', $contrib_radio);
					$this->assignRef('progress_radio', $progress_radio);				
				
				/*$plans = $model->get_shopping_credit_plan_detail(JRequest::getVar('id'));
				if(!empty($plans)) {
					$plan = $plans[0];
					$uniq_id = $plan->uniq_key;
					$contrib_radio = $plan->contribution_range;
					$progress_radio = $plan->progress_check;
					$contribution_range_value = $plan->contribution_range_value;
					$start_date = $plan->start_date;
					$end_date = $plan->end_date;
					$progress_check_value = $plan->progress_check_value;
					$this->assignRef('plan', $plan);					
					$this->assignRef('contrib_radio', $contrib_radio);
					$this->assignRef('progress_radio', $progress_radio);
					$this->assignRef('contribution_range_value', $contribution_range_value);
					$this->assignRef('start_date', $start_date);
					$this->assignRef('end_date', $end_date);	
					$this->assignRef('progress_check_value', $progress_check_value);				
					$this->assignRef('plan_id', $plan->id);
					$this->assignRef('uniq_id', $uniq_id);	
				}	
				*/
				$categories = $model_categories->list_categories();
				$result = $model->get_list_contribution_range($uniq_id);
				$contribution_ranges = !empty($result['contribution_range']) ? $result['contribution_range'] : array();				
				$this->assignRef('contribution_ranges', $contribution_ranges);
				$this->assignRef('pagination_contribution_range', $result['pagination_contribution_range']);

				$result = $model->get_list_progress_check($uniq_id);
				$progress_checkes = !empty($result['progress_check']) ? $result['progress_check'] : array();
				$this->assignRef('progress_checkes', $progress_checkes);
				$this->assignRef('pagination_progress_check', $result['pagination_progress_check']);
				
				break;
			case 'show_plan':
				$id = JRequest::getVar('id');
				$this->assign('page_id', 8);
				// breadcrumbs
				$pathway->addItem('Shopping Credits', JRoute::_($this->page_url.'&view=ushoppingcreditplan&task=ushoppingcreditplan.getMainPage'));
				$pathway->addItem('Detail Plan', JRoute::_($this->page_url.'&view=ushoppingcreditplan&task=ushoppingcreditplan.showPlan&id=' . $id));		
				// add to pathway
				$pathway->addItem($page_heading);				
				// set browser title
				$plans = $model->get_shopping_credit_plan_detail(JRequest::getVar('id'));
				var_dump($plans);
				if(!empty($plans)) {
					$plan = $plans[0];
					$uniq_id = $plan->uniq_key;
					$contrib_radio = $plan->contribution_range;
					$progress_radio = $plan->progress_check;
					$contribution_range_value = $plan->contribution_range_value;
					$start_date = $plan->start_date;
					$end_date = $plan->end_date;
					$progress_check_value = $plan->progress_check_value;
					$this->assignRef('plan', $plan);					
					$this->assignRef('contrib_radio', $contrib_radio);
					$this->assignRef('progress_radio', $progress_radio);
					$this->assignRef('contribution_range_value', $contribution_range_value);
					$this->assignRef('start_date', $start_date);
					$this->assignRef('end_date', $end_date);	
					$this->assignRef('progress_check_value', $progress_check_value);				
					$this->assignRef('plan_id', $plan->id);
					$this->assignRef('uniq_id', $uniq_id);	
				}	
				$categories = $model_categories->list_categories();
				$result = $model->get_list_contribution_range($uniq_id);
				$contribution_ranges = !empty($result['contribution_range']) ? $result['contribution_range'] : array();				
				$this->assignRef('contribution_ranges', $contribution_ranges);
				$this->assignRef('pagination_contribution_range', $result['pagination_contribution_range']);
				
				$result = $model->get_list_progress_check($uniq_id);
				$progress_checkes = !empty($result['progress_check']) ? $result['progress_check'] : array();
				$this->assignRef('progress_checkes', $progress_checkes);
				$this->assignRef('pagination_progress_check', $result['pagination_progress_check']);
				
				$donations = $model->get_credit_from_donation($uniq_id, (!empty($plan) ? $plan->contribution_range : '0'), (!empty($plan) ? $plan->progress_check : '0'));
				$awards = $model->get_credit_from_award($uniq_id, (!empty($plan) ? $plan->contribution_range : '0'), (!empty($plan) ? $plan->progress_check : '0'));
				$giftcodes = $model->get_giftcode_category($uniq_id, (!empty($plan) ? $plan->contribution_range : '0'), (!empty($plan) ? $plan->progress_check : '0'));
				$this->assignRef('categories', $categories);				
				$this->assignRef('donations', $donations);
				$this->assignRef('awards', $awards);
				$this->assignRef('giftcodes', $giftcodes);	
				break;			
		}			
		parent::display($tpl);
	}	
	
}