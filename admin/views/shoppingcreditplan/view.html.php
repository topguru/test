<?php

defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';

class AwardpackageViewShoppingCreditPlan extends JViewLegacy {
	
	protected $params;
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		JToolBarHelper::title(JText::_('Shopping Credit Plan List'), 'logo.png');
		$app = JFactory::getApplication();
		$model = & JModelLegacy::getInstance( 'shoppingcreditplan', 'AwardpackageModel' );	
		$model_categories = & JModelLegacy::getInstance( 'shoppingcreditcategory', 'AwardpackageModel' );
		//$users = AwardPackageHelper::getUserData();
		//$package_id = $users->package_id;
		$package_id = JRequest::getVar("package_id");		
		switch ($this->action){
			case 'list':
				$result = $model->get_shopping_credit_plan($package_id);
				$shoppings = !empty($result['shoppings']) ? $result['shoppings'] : array();
				$this->assignRef('shoppings', $shoppings);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				
				JToolBarHelper::addNew('shoppingcreditplan.add_shopping_credit_plan');
				JToolBarHelper::custom( 'shoppingcreditplan.close', 'copy', 'copy', 'Save & Close', false, false );
				JToolBarHelper::divider();
				JToolBarHelper::publish('shoppingcreditplan.publish_list');
				JToolBarHelper::unpublish('shoppingcreditplan.unpublish_list');				
				JToolBarHelper::deleteList('', 'shoppingcreditplan.delete_shopping_credit_plan');
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));
				break;
			case 'create_update_plan':
				$plans = $model->get_shopping_credit_plan_detail(JRequest::getVar('package_id'), JRequest::getVar('id'));					
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
				} else {
					$uniq_id = JRequest::getVar('uniq_id');
					$plan_id = '0';					
					$this->assignRef('plan_id', $plan_id);
					$this->assignRef('uniq_id', $uniq_id);						
				}			
				
				$model->insert_giftcode(JRequest::getVar('package_id'), $uniq_id);
				$categories = $model_categories->list_categories($package_id);
				
				$result = $model->get_list_contribution_range(JRequest::getVar('package_id'), $uniq_id);
				$contribution_ranges = !empty($result['contribution_range']) ? $result['contribution_range'] : array();				
				$this->assignRef('contribution_ranges', $contribution_ranges);
				$this->assignRef('pagination_contribution_range', $result['pagination_contribution_range']);
				
				$result = $model->get_list_progress_check(JRequest::getVar('package_id'), $uniq_id);
				$progress_checkes = !empty($result['progress_check']) ? $result['progress_check'] : array();
				$this->assignRef('progress_checkes', $progress_checkes);
				$this->assignRef('pagination_progress_check', $result['pagination_progress_check']);
				
				$donations = $model->get_credit_from_donation(JRequest::getVar('package_id'), $uniq_id, (!empty($plan) ? $plan->contribution_range : '0'), (!empty($plan) ? $plan->progress_check : '0'));
				$awards = $model->get_credit_from_award(JRequest::getVar('package_id'), $uniq_id, (!empty($plan) ? $plan->contribution_range : '0'), (!empty($plan) ? $plan->progress_check : '0'));
				$giftcodes = $model->get_giftcode_category(JRequest::getVar('package_id'), $uniq_id, (!empty($plan) ? $plan->contribution_range : '0'), (!empty($plan) ? $plan->progress_check : '0'));
				$this->assignRef('categories', $categories);				
				$this->assignRef('donations', $donations);
				$this->assignRef('awards', $awards);
				$this->assignRef('giftcodes', $giftcodes);
				JToolBarHelper::custom( 'shoppingcreditplan.save_and_close', 'copy', 'copy', 'Save & Close', false, false );
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.get_shopping_credit_plan_list&package_id='.JRequest::getVar('package_id'));
				break;
			case 'create_update_notes':
				break;				
			case 'contribution_range_click':
				$post = null;
				if(isset($_SESSION['plan'])) {
					$post = $_SESSION['plan'];
					unset($_SESSION['plan']);
				}
				$contrib_radio = (!empty($post) ? $post['contrib_radio'] : JRequest::getVar('contrib_radio'));
				$progress_radio = (!empty($post) ? $post['progress_radio'] : JRequest::getVar('progress_radio'));
				$this->assignRef('contrib_radio', $contrib_radio);
				$this->assignRef('progress_radio', $progress_radio);
				$plans = $model->get_shopping_credit_plan_detail(JRequest::getVar('package_id'), JRequest::getVar('id'));					
				if(!empty($plans)) {
					$plan = $plans[0];
					$uniq_id = $plan->uniq_key;	
					$this->assignRef('plan', $plan);
					$this->assignRef('plan_id', $plan->id);
					$this->assignRef('uniq_id', $uniq_id);					
				} else {
					$uniq_id = JRequest::getVar('uniq_id');
					$this->assignRef('plan_id', JRequest::getVar('plan_id'));
					$this->assignRef('uniq_id', $uniq_id);	
				}
				$categories = $model_categories->list_categories($package_id );				
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
				$start_date = (!empty($post) ? $post['from'] : JRequest::getVar('from'));
				$end_date = (!empty($post) ? $post['to'] : JRequest::getVar('to'));				
				$this->assignRef('start_date', $start_date);
				$this->assignRef('end_date', $end_date);
				$result = $model->get_list_contribution_range(JRequest::getVar('package_id'), $uniq_id);
				$contribution_ranges = !empty($result['contribution_range']) ? $result['contribution_range'] : array();								
				$this->assignRef('contribution_ranges', $contribution_ranges);
				$this->assignRef('pagination_contribution_range', $result['pagination_contribution_range']);
				
				$result = $model->get_list_progress_check(JRequest::getVar('package_id'), $uniq_id);
				$progress_checkes = !empty($result['progress_check']) ? $result['progress_check'] : array();
				$this->assignRef('progress_checkes', $progress_checkes);
				$this->assignRef('pagination_progress_check', $result['pagination_progress_check']);
				
				$donations = $model->get_credit_from_donation(JRequest::getVar('package_id'), $uniq_id, (!empty($contrib_radio) ? $contrib_radio : '0'), (!empty($progress_radio) ? $progress_radio : '0'));
				$awards = $model->get_credit_from_award(JRequest::getVar('package_id'), $uniq_id, (!empty($contrib_radio) ? $contrib_radio : '0'), (!empty($progress_radio) ? $progress_radio : '0'));
				$giftcodes = $model->get_giftcode_category(JRequest::getVar('package_id'), $uniq_id, (!empty($contrib_radio) ? $contrib_radio : '0'), (!empty($progress_radio) ? $progress_radio : '0'));
				$this->assignRef('categories', $categories);				
				$this->assignRef('donations', $donations);
				$this->assignRef('awards', $awards);
				$this->assignRef('giftcodes', $giftcodes);
				JToolBarHelper::custom( 'shoppingcreditplan.save_and_close', 'copy', 'copy', 'Save & Close', false, false );
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=shoppingcreditplan&task=shoppingcreditplan.get_shopping_credit_plan_list&package_id='.JRequest::getVar('package_id'));				
				break;
		}		
		parent::display($tpl);
	}	
}