<?php 
//redirect
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardpackageViewPayments extends JViewLegacy {
	/*
		package 	: com_awardpackage
	*/
	function display($tpl = null) {
		//set variable
		$model = & JModelLegacy::getInstance( 'payments', 'AwardpackageModel' );
		switch ($this->action){
			case 'list':
				$result = $model->get_payments();
				$payments = array();
				if(!empty($result['payments'])) {
					$payments = $result['payments'];
				}
				$this->assignRef('payments', $payments);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				
				JToolBarHelper::title('Payment Gateway');
				JToolBarHelper::addNew('payments.create_update');
				JToolBarHelper::deleteList('are you sure ?', 'payments.delete', 'Delete');
				JToolbarHelper::back('Close', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));
				break;
			case 'create':
				$id = '0';
				if(JRequest::getVar('id') != null) {
					$id = JRequest::getVar('id');
				}
				$result = $model->get_payments_byid($id);
				$this->assignRef('payment', $result);
				JToolBarHelper::title('Payment Gateway');
				JToolBarHelper::custom('payments.save_create', 'copy', 'copy', 'Save & Close', false);
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=payments&task=payments.get_payment_list');
				break;
		}
		parent::display($tpl);
	}
	
	function addToolBar(){
		JToolBarHelper::title('Payment Gateway');
		JToolBarHelper::addNew('payments.add');
		JToolBarHelper::editList('payments.edit');
		JToolBarHelper::deleteList('are you sure ?', 'payments.delete', 'Delete');
	}
}

?>