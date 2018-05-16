<?php 
//redirect
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardpackageViewPaypals extends JViewLegacy {
	/*
		package 	: com_awardpackage
	*/
	function display($tpl = null) {
		//set variable
		$model = & JModelLegacy::getInstance( 'paypals', 'AwardpackageModel' );
		switch ($this->action){
			case 'list':
				$result = $model->getPaypalConfiguration();
				$payments = array();
				if(!empty($result['paypals'])) {
					$payments = $result['paypals'];
				}
				$this->assignRef('paypals', $payments);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				
				JToolBarHelper::title('Paypals Configuration');
				JToolBarHelper::addNew('paypals.createUpdate');
				JToolBarHelper::deleteList('are you sure ?', 'paypals.delete', 'Delete');
				JToolbarHelper::back('Close', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));
				break;
			case 'create':
				$id = '0';
				if(JRequest::getVar('id') != null) {
					$id = JRequest::getVar('id');
				}
				$result = $model->getPaypalConfigurationById($id);
				$this->assignRef('paypals', $result);				
				JToolBarHelper::title('Paypal Configuration');
				JToolBarHelper::custom('paypals.saveCreate', 'copy', 'copy', 'Save & Close', false);
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=paypals&task=paypals.getPaypalConfigurationList&package_id='.JRequest::getVar('package_id'));
				break;
		}
		parent::display($tpl);
	}
	
	function addToolBar(){
		JToolBarHelper::title('Paypal Configuration');
		JToolBarHelper::addNew('payments.add');
		JToolBarHelper::editList('payments.edit');
		JToolBarHelper::deleteList('are you sure ?', 'payments.delete', 'Delete');
	}
}

?>