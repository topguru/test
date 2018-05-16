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

class AwardpackageViewUserlist extends JViewLegacy {
	
	function display($tpl = null) {		
		CommunitySurveysHelper::initiate();

		JToolBarHelper::title(JText::_('User List'), 'logo.png');	
		$app = JFactory::getApplication();	
		$package_id = JRequest::getVar('package_id');	
		$model = & JModelLegacy::getInstance( 'userlist', 'AwardpackageModel' );		
		$model_poll = & JModelLegacy::getInstance( 'poll', 'AwardpackageModel' );
		$total = 10;//$model->getDonationHistoryTotal($userId, $packageId);
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		     			$this->pager = new JPagination($total, $limitstart, $limit);
     			$this->pagination = $this->pager;

		switch ($this->action){
			case 'list':
				$data = array();
				//user filter
				$data['user_name'] = JRequest::getVar('user_name');
				$data['user_dob'] = JRequest::getVar('user_dob');
				$data['user_age_from'] = JRequest::getVar('user_age_from');
				$data['user_age_to'] = JRequest::getVar('user_age_to');
				$data['user_gender'] = JRequest::getVar('user_gender');
				$data['user_city'] = JRequest::getVar('user_city');
				$data['user_country'] = JRequest::getVar('user_country');

				//fund filter
				$data['funds_from'] = JRequest::getVar('funds_from');
				$data['funds_to'] = JRequest::getVar('funds_to');
				$data['funds_amount_from'] = JRequest::getVar('funds_amount_from');
				$data['funds_amount_to'] = JRequest::getVar('funds_amount_to');
				$data['funds_withdraw_from'] = JRequest::getVar('funds_withdraw_from');
				$data['funds_withdraw_to'] = JRequest::getVar('funds_withdraw_to');
				$data['funds_current_from'] = JRequest::getVar('funds_current_from');
				$data['funds_current_to'] = JRequest::getVar('funds_current_to');
				
				$result = $model->get_user_list($data, $package_id, null, 20, 0);
				$users = array();
				if(!empty($result['users'])) {
					$users = $result['users'];
				}
				$this->assignRef('users', $users);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				$country_list = AwardpackagesHelper::Countries_list();
				$categories = $model_poll->get_categories(JRequest::getVar('package_id'));
				$this->assignRef('countries', $country_list);
				$this->assignRef('categories', $categories);
				JToolbarHelper::title('User List');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.$package_id);
				break;
			case 'all_transactions':
			    $data = array();				
				$data['accountId'] = JRequest::getVar('accountId');
				$transactions = $model->getFundingHistory($data, JRequest::getVar('package_id'));
				$this->assignRef('transactions', $transactions);
				JToolbarHelper::title('All Transactions');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=userlist&task=userlist.user_list&package_id='.$package_id);
				break;
			case 'all_funds':
				$transactions = $model->getFundingView($data, JRequest::getVar('package_id'));
				$this->assignRef('transactions', $transactions);
				JToolbarHelper::title('Funding');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=userlist&task=userlist.user_list&package_id='.$package_id);
				break;
			case 'all_donation':
				$transactions = $model->getDonationView($data, JRequest::getVar('package_id'));
				$this->assignRef('transactions', $transactions);
				JToolbarHelper::title('Donation');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=userlist&task=userlist.user_list&package_id='.$package_id);
				break;
			case 'symbol_queue_of':		
				//$accountId = JRequest::getVar('accountId');		
				$package_id = JRequest::getVar('package_id');
				//$user = $model->getUserFromId($accountId);
				$data['accountId'] = JRequest::getVar('accountId');
				$user = $model->getFundingHistory($data, JRequest::getVar('package_id'));
				
				
				$userGroups = $model->getUserGroup($package_id, $user->firstname, $user->lastname, $user->email,
					$user->birthday, $user->gender, $user->street, $user->city, $user->state, $user->post_code, $user->country);
				$userGroup = $userGroups[0];		
				
				$results = $model->getSymbolQueue($package_id, $userGroup->criteria_id);
				
				$this->assignRef('users', $user);
				$this->assignRef('results', $results);
				JToolbarHelper::title('Symbol Queue Of ' . (null != $user ? $user->name : '' ));
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=userlist&task=userlist.user_list&package_id='.$package_id);
				break;
			case 'symbol_queue_detail':
			//$accountId = JRequest::getVar('accountId');		
				$package_id = JRequest::getVar('package_id');
				//$user = $model->getUserFromId($accountId);
				$data['accountId'] = JRequest::getVar('accountId');
				$user = $model->getFundingHistory($data, JRequest::getVar('package_id'));
				
				
				$userGroups = $model->getUserGroup($package_id, $user->firstname, $user->lastname, $user->email,
					$user->birthday, $user->gender, $user->street, $user->city, $user->state, $user->post_code, $user->country);
				$userGroup = $userGroups[0];		
				
				$results = $model->getSymbolQueue($package_id, $userGroup->criteria_id);
				$symbolPrizes = $model->getSymbolSymbolPrize();
				$this->assignRef('symbolPrizes', $symbolPrizes);
				$this->assignRef('users', $user);
				$this->assignRef('results', $results);
				foreach ($users as $user){
				$name = $user->firstname.' '.$user->lastname;
				}
				$this->assignRef('users', $user);
				JToolbarHelper::title('Symbol Queue Of ' . (null != $name ? $name: '' ));
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=userlist&task=userlist.get_symbol_queue&accountId='.$accountId.'&package_id='.$package_id);
				break;
			case 'presentation_of':
				$package_id = JRequest::getVar('package_id');
				//$user = $model->getUserFromId($accountId);
				$data['accountId'] = JRequest::getVar('accountId');
				$users = $model->getFundingHistory($data, JRequest::getVar('package_id'));
				foreach ($users as $user){
				$name = $user->firstname.' '.$user->lastname;
				$userid = $user->user_id;
				}
				
				$userGroups = $model->getUserGroup($package_id, $user->firstname, $user->lastname, $user->email,
					$user->birthday, $user->gender, $user->street, $user->city, $user->state, $user->post_code, $user->country);
				$userGroup = $userGroups[0];		
				
				$results = $model->get_symbol_presentation($userid, $presentation);
				$model_presentation = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
				$presentations = $model_presentation->getDetailPresentation3(JRequest::getVar('package_id'), '1');
				$symbolprize = $model_presentation->getSymbolSymbolPrize($package_id);
				$countprize = $model_presentation->getSymbolPrize($userid);
				/*$this->model = $model;
				$this->present_model = & JModelLegacy::getInstance('apresentationlist', 'AwardpackageModel');*/
				$this->assignRef('presentations', $presentations);
				$this->assignRef('countprize', $countprize);
				$this->assignRef('symbolprize', $symbolprize);
								
				$this->assignRef('users', $user);
				JToolbarHelper::title('User Presentation ' . (null != $name ? $name: '' ));
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=userlist&task=userlist.user_list&package_id='.$package_id);
				break;
			case 'symbol_piece_of':
				break;
			case 'prize_status':
				$accountId = JRequest::getVar('accountId');
				$package_id = JRequest::getVar('package_id');
				JToolbarHelper::title('Prize status');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=userlist&task=userlist.get_presentation&accountId='.$accountId.'&package_id='.$package_id);
				break;
			case 'distribute_prize':
				$accountId = JRequest::getVar('accountId');
				$package_id = JRequest::getVar('package_id');		
				$user = $model->getUserFromId($accountId);
				$this->assignRef('users', $user);
				JToolbarHelper::title('Distribute prize queue history - ' . (null != $user ? $user->firstname . ' ' . $user->lastname : '' ));
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=userlist&task=userlist.get_presentation&accountId='.$accountId.'&package_id='.$package_id);
				break;
			case 'shopping_credit':
				$accountId = JRequest::getVar('id');	
 				$package_id = JRequest::getVar('package_id');			
				$user = $model->getUserFromId($accountId);
				$items = $model->get_shopping_credit_view($package_id);
                $description = $model->get_donation_history($user_id);
			//	var_dump($description);
				foreach ($description as $desc){
					$user_description = $desc->description;
					$amount = $desc->debit;
					$date_donation = $desc->created_date;
				}
				$this->assignRef('items', $items);
				$this->assignRef('users', $user);
				$this->assignRef('date_donation', $date_donation);	
				JToolbarHelper::title('Shopping credit of ' . (null != $user ? $user->firstname . ' ' . $user->lastname : '' ));
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=userlist&task=userlist.user_list&package_id='.$package_id);
				break;
			case 'get_quizzes':
				$user_id = JRequest::getVar('accountId');
				$package_id = JRequest::getVar('package_id');
				
				$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageModel' );
				
				$result = $model->get_quizzes_by_responses($user_id);
				$quizzes = !empty($result['quizzes']) ? $result['quizzes'] : array();
				
				$this->assignRef('quizzes', $quizzes);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
								
				JToolbarHelper::title('Quizzes');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=userlist&task=userlist.user_list&package_id='.$package_id);
				break;
			case 'get_surveys':
				$user_id = JRequest::getVar('accountId');
				$package_id = JRequest::getVar('package_id');
				
				$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
				
				$result = $model->get_surveys_response($user_id);
				$surveys = !empty($result['surveys']) ? $result['surveys'] : array();
				
				$this->assignRef('surveys', $surveys);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				
				JToolbarHelper::title('Surveys');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=userlist&task=userlist.user_list&package_id='.$package_id);
				break;
			case 'get_giftcode':
				/*$package_id = JRequest::getVar('package_id');
				
				$model =& JModelLegacy::getInstance('giftcode','AwardpackageModel');
				$this->model = $model;
				
				$this->giftcodes = $model->getAllUserGiftcodes($package_id,JRequest::getVar('accountId'));//getGiftCode(JRequest::getVar('package_id'));*/
				
				$user_id = JRequest::getVar('accountId');
				$package_id = JRequest::getVar('package_id');
				
				$model =& JModelLegacy::getInstance('giftcode','AwardpackageModel');
				//$transactions = $model->getDonationView($data, JRequest::getVar('package_id'));
				//$this->assignRef('transactions', $transactions);
				$giftcodes = $model->getAllUserGiftcodes($package_id,$user_id);
				//$giftcodes = !empty($result['giftcodes']) ? $result['giftcodes'] : array();
				$this->assignRef('giftcodes', $giftcodes);
				JToolbarHelper::title('Giftcode');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=userlist&task=userlist.user_list&package_id='.$package_id);				
				break;			
		}
		$this->addStyleSheet();				
		parent::display($tpl);
	}	
	
	protected function addStyleSheet() {    	
        $doc = JFactory::getDocument();                
        $url = JURI::base() . 'components/com_awardpackage/assets/';        
        $doc->addStyleSheet($url . 'css/jquery-ui.min.css');
        $doc->addScript($url . 'js/jquery-1.8.3.js');
        $doc->addScript($url . 'js/jquery.ui.core.min.js');
        $doc->addScript($url . 'js/jquery.ui.widget.min.js');
        $doc->addScript($url . 'js/jquery.ui.tabs.min.js');
		$doc->addScript($url . 'js/jquery_003.js');
		$doc->addScript($url . 'js/jquery_002.js');
		$doc->addScript($url . 'js/highlight.js');
		$doc->addStyleSheet($url . 'css/style.css');
		$doc->addStyleSheet($url . 'css/collapsible.css');
    }
}