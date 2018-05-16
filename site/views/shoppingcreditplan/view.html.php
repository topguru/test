<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardPackageViewShoppingcreditplan extends JViewLegacy {

    public function display($tpl = null) {
    	CommunitySurveysHelper::initiate();
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageUsersModel' );	
		$model_categories = & JModelLegacy::getInstance( 'shoppingcreditcategory', 'AwardpackageUsersModel' );	
		switch ($this->action){
			case 'list':
				$result = $model->get_shopping_credit_plan();
				$shoppings = !empty($result['shoppings']) ? $result['shoppings'] : array();
				$this->assignRef('shoppings', $shoppings);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);				
				break;	
			case 'show_plan':
				$plans = $model->get_shopping_credit_plan_detail(JRequest::getVar('id'));
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
			case 'contribution_range_click':				
				$contrib_radio = (!empty($post) ? $post['contrib_radio'] : JRequest::getVar('contrib_radio'));
				$progress_radio = (!empty($post) ? $post['progress_radio'] : JRequest::getVar('progress_radio'));
				$this->assignRef('contrib_radio', $contrib_radio);
				$this->assignRef('progress_radio', $progress_radio);
				$plans = $model->get_shopping_credit_plan_detail(JRequest::getVar('id'));
				if(!empty($plans)) {
					$plan = $plans[0];
					$uniq_id = $plan->uniq_key;	
					$start_date = $plan->start_date;
					$end_date = $plan->end_date;
					$this->assignRef('start_date', $start_date);
					$this->assignRef('end_date', $end_date);	
					$this->assignRef('plan', $plan);
					$this->assignRef('plan_id', $plan->id);
					$this->assignRef('uniq_id', $uniq_id);					
				}
				$categories = $model_categories->list_categories();				
				$contribs =  $model->get_contribution_range_by_id($contrib_radio);
				if(!empty($contribs)) {
					$contrib = $contribs[0];
					$this->assignRef('contribution_range_value', $contrib->contribution_range_value);
				}
				$progresses = $model->get_progress_check_by_id($progress_radio);
				if(!empty($progresses)) {
					$progress = $progresses[0];
					$this->assignRef('progress_check_value', $progress->progress_check_value);
				}
				$result = $model->get_list_contribution_range($uniq_id);
				$contribution_ranges = !empty($result['contribution_range']) ? $result['contribution_range'] : array();				
				$this->assignRef('contribution_ranges', $contribution_ranges);
				$this->assignRef('pagination_contribution_range', $result['pagination_contribution_range']);
				
				$result = $model->get_list_progress_check($uniq_id);
				$progress_checkes = !empty($result['progress_check']) ? $result['progress_check'] : array();
				$this->assignRef('progress_checkes', $progress_checkes);
				$this->assignRef('pagination_progress_check', $result['pagination_progress_check']);
				
				$donations = $model->get_credit_from_donation($uniq_id, (!empty($contrib_radio) ? $contrib_radio : '0'), (!empty($progress_radio) ? $progress_radio : '0'));
				$awards = $model->get_credit_from_award($uniq_id, (!empty($contrib_radio) ? $contrib_radio : '0'), (!empty($progress_radio) ? $progress_radio : '0'));
				$giftcodes = $model->get_giftcode_category($uniq_id, (!empty($contrib_radio) ? $contrib_radio : '0'), (!empty($progress_radio) ? $progress_radio : '0'));
				$this->assignRef('categories', $categories);				
				$this->assignRef('donations', $donations);
				$this->assignRef('awards', $awards);
				$this->assignRef('giftcodes', $giftcodes);					
				break;	
			case 'progress_plan' :
				$plans = $model->get_shopping_credit_plan_detail(JRequest::getVar('id'));
				if(!empty($plans)) {
					$plan = $plans[0];
					$uniq_id = $plan->uniq_key;
					$start_date = $plan->start_date;
					$end_date = $plan->end_date;					
					$start = strtotime($start_date);
					$end = strtotime($end_date);					
					$selisih = floor(($end - $start) / 86400);
					$contribution_range_value = $plan->contribution_range_value;
					$progress_check_every = $plan->progress_check_every;
					$progress_check_type = $plan->progress_check_type;
					$this->assignRef('start_date', $start_date);
					$this->assignRef('end_date', $end_date);	
					$this->assignRef('contribution_range_value', $contribution_range_value);
					$this->assignRef('progress_check_every', $progress_check_every);
					$this->assignRef('progress_check_type', $progress_check_type);
					$this->assignRef('selisih', $selisih);
				}		
				break;	
			case 'progress_plan_detail' :
				$plans = $model->get_shopping_credit_plan_detail(JRequest::getVar('id'));
				if(!empty($plans)) {
					$plan = $plans[0];
					$uniq_id = $plan->uniq_key;
					$start_date = $plan->start_date;
					$end_date = $plan->end_date;					
					$start = strtotime($start_date);
					$end = strtotime($end_date);					
					$selisih = floor(($end - $start) / 86400);
					$contribution_range_value = $plan->contribution_range_value;
					$progress_check_every = $plan->progress_check_every;
					$progress_check_type = $plan->progress_check_type;
					$this->assignRef('start_date', $start_date);
					$this->assignRef('end_date', $end_date);	
					$this->assignRef('contribution_range_value', $contribution_range_value);
					$this->assignRef('progress_check_every', $progress_check_every);
					$this->assignRef('progress_check_type', $progress_check_type);
					$this->assignRef('start', $start);
					$this->assignRef('selisih', $selisih);
				}		
				break;
			case 'shopping_credit_account' :
				break;
		}
		parent::display($tpl);	
    }
}

?>