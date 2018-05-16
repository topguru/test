<?php 
//redirect
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class awardpackageViewPayout extends JViewLegacy {
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();		
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$total = 10;//$model->getDonationHistoryTotal($userId, $packageId);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
		$model = & JModelLegacy::getInstance( 'payout', 'AwardpackageModel' );
		$package_id = JRequest::getVar("package_id");	
		switch ($this->action){
			case 'list':
				//$result = $model->get_payout($package_id);
				$symbolPrizes = $model->getSymbolSymbolPrize($limit,$limitstart);
				$this->assignRef('symbolPrizes', $symbolPrizes);

				$payouts = array();
				if(!empty($result['payouts'])) {
					$payouts = $result['payouts'];
				}
				$this->assignRef('payouts', $payouts);
				//$this->assignRef('pagination', $result['pagination']);
				//$this->assignRef('lists', $result['lists']);
				JToolBarHelper::title(JText::_('Prize Payout History'), 'logo.png');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));
				break;
			case 'paymass':
				JToolBarHelper::title(JText::_('Prize Payout History'), 'logo.png');
				//JToolBarHelper::addNew('payout.create_update');			
				JToolBarHelper::deleteList('', 'payout.delete_payout');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));
				break;	
			case 'create':
					JToolBarHelper::title(JText::_('New Prize Payout'), 'logo.png');
				$id = '0';
				if(JRequest::getVar('id') != null) {
					$id = JRequest::getVar('id');
				}
				//$result = $model->get_shopping_credit_category_byid($id);
				$this->assignRef('shopping', $result);
				JToolBarHelper::custom('payout.save_create', 'copy', 'copy', 'Save & Close', false);
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));
				break;				
		}
		parent::display($tpl);
	}	
}