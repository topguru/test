<?php 
//redirect
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
class awardpackageViewfundprizeplan extends JViewLegacy {
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();		
		$model = & JModelLegacy::getInstance( 'fundprizeplan', 'AwardpackageModel' );
		$package_id = JRequest::getVar("package_id");	
		switch ($this->action){
			case 'list':
			    $result = $model->get_fund_prize_plan($package_id);
				$fundprizes = array();
				if(!empty($result['fundprizes'])) {
					$fundprizes = $result['fundprizes'];
				}
				$this->assignRef('fundprizes', $fundprizes);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				JToolBarHelper::title(JText::_('Fund Prize Plan List'), 'logo.png');
								JToolbarHelper::back('Close', 'index.php?option=com_awardpackage&package_id=' . JRequest::getVar('package_id'));

				break;
			case 'create':
			    $id = '';
				if(JRequest::getVar('id') != null) {
					$id = JRequest::getVar('id');
				}
				$result = $model->get_fund_prize_plan_byid($id);
				$this->assignRef('fundprizes', $result);
				
				JToolBarHelper::title(JText::_('New Fund Prize Plan'), 'logo.png');				
				JToolBarHelper::custom('fundprizeplan.save_create', 'copy', 'copy', 'Save & Close', false);
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=fundprizeplan&task=fundprizeplan.get_fundprizeplan&package_id='.JRequest::getVar('package_id'));
				break;				
		}
		parent::display($tpl);
	}	
}