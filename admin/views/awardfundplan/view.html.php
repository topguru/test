<?php 
//redirect
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class awardpackageViewawardfundplan extends JViewLegacy {
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/assets/css/jquery-ui.css');
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/assets/css/jquery.ui.all.css');
		$document->addScript(JURI::base(true).'/components/com_awardpackage/assets/js/jquery-ui.js');
		$app = JFactory::getApplication();		
		$model = & JModelLegacy::getInstance( 'awardfundplan', 'AwardpackageModel' );
		$package_id = JRequest::getVar("package_id");	
		switch ($this->action){
			case 'list':
			    $result = $model->get_award_fund_plan($package_id);
				$userlist = $model->getAllUserlist($package_id);
				$awardfunds = array();
				if(!empty($result['awardfunds'])) {
					$awardfunds = $result['awardfunds'];
				}
				$this->assignRef('awardfunds', $awardfunds);
				$totalA = 0;
				$totalP = 0;
				$totalSpent = 0;
				foreach ($awardfunds as $row) {
					$totalA = $totalA + $row->amount;				
					$totalP = $totalP + $row->rate;	
					$totalSpent = $totalSpent + $row->spent;
					$speed = $row->speed;				
				}
				$this->assignRef('totalA', $totalA);
				$this->assignRef('totalP', $totalP);
				$this->assignRef('totalSpent', $totalSpent);
				$this->assignRef('speed', $speed);
				
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				$this->assignRef('userlist', $userlist);
				
				$totaldonation = $model->total_donation($package_id);
				$totalquizcost = $model->total_quiz_cost($package_id);
				$totalsurveycost = $model->total_survey_cost($package_id);

				$awardfundtotal = $totalsurveycost + $totalquizcost + $totaldonation;
				
				//var_dump($awardfundtotal);
				$this->assignRef('awardfundtotal', $awardfundtotal);
				$spents = $model->getUserGroupPresentation($package_id);
				foreach ($spents as $rows) {
					$spent = $rows->prize_value;				
					$usergroup = $rows->usergroup;				
				}
				$this->assignRef('spent', $spent);
				$this->assignRef('usergroup', $usergroup);
				JToolBarHelper::title(JText::_('Award Funds Plan List'), 'logo.png');
				JToolbarHelper::back('Close', 'index.php?option=com_awardpackage&package_id=' . JRequest::getVar('package_id'));

				break;
			case 'create':
			$id = '0';
				if(JRequest::getVar('id') != null) {
					$id = JRequest::getVar('id');
				}
				$resultA = $model->get_award_fund_plan_byid($id);
				$userlist = $model->getAllUserlist($package_id);
				$this->assignRef('awardfund', $resultA);
				$this->assignRef('userlist', $userlist);
				$result = $model->get_award_fund_plan($package_id);
				$awardfunds = array();
				if(!empty($result['awardfunds'])) {
					$awardfunds = $result['awardfunds'];
				}
				$this->assignRef('awardfunds', $awardfunds);
				$totalA = 0;
				$totalP = 0;
				$totalSpent = 0;
				foreach ($awardfunds as $row) {
					$totalA = $totalA + $row->amount;				
					$totalP = $totalP + $row->rate;	
					$totalSpent = $totalSpent + $row->spent;	
					$speed = $row->speed;			
					$spent = $row->prize_value;		
				}
				$this->assignRef('totalA', $totalA);
				$this->assignRef('totalP', $totalP);
				$this->assignRef('totalSpent', $totalSpent);
				$this->assignRef('speed', $speed);
				$this->assignRef('spent', $spent);

				$totaldonation = $model->total_donation($package_id);
				$totalquizcost = $model->total_quiz_cost($package_id);
				$totalsurveycost = $model->total_survey_cost($package_id);

				$awardfundtotal = $totalsurveycost + $totalquizcost + $totaldonation;
				//var_dump($awardfundtotal);
				$this->assignRef('awardfundtotal', $awardfundtotal);
				
				JToolBarHelper::title(JText::_('New Award Funds Plan'), 'logo.png');				
				JToolBarHelper::custom('awardfundplan.save_create', 'copy', 'copy', 'Save & Close', false);
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id='.JRequest::getVar('package_id'));
				break;				
		}
		parent::display($tpl);
	}	
}