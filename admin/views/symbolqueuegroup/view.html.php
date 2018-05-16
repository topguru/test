<?php 
//redirect
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class awardpackageViewSymbolqueuegroup extends JViewLegacy {
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/assets/css/jquery-ui.css');
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/assets/css/jquery.ui.all.css');
		$document->addScript(JURI::base(true).'/components/com_awardpackage/assets/js/jquery-ui.js');
		$app = JFactory::getApplication();		
		$model = & JModelLegacy::getInstance( 'symbolqueuegroup', 'AwardpackageModel' );
		$package_id = JRequest::getVar("package_id");	
		switch ($this->action){
			case 'list':
			    $result = $model->get_symbol_queue_group($package_id);
				$userlist = $model->getAllUserlist($package_id);
				$symbolqueue = array();
				if(!empty($result['symbolqueue'])) {
					$symbolqueue = $result['symbolqueue'];
				}
				$this->assignRef('symbolqueue', $symbolqueue);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				$this->assignRef('userlist', $userlist);
				
				
				JToolBarHelper::title(JText::_('Symbol Queue Group List'), 'logo.png');
				JToolbarHelper::back('Close', 'index.php?option=com_awardpackage&package_id=' . JRequest::getVar('package_id'));

				break;
			case 'create':
			$id = '0';
				if(JRequest::getVar('id') != null) {
					$id = JRequest::getVar('id');
				}
				$resultA = $model->get_symbol_queue_group_byid($id);
				$userlist = $model->getAllUserlist($package_id);
				$this->assignRef('symbolqueue', $resultA);
				$this->assignRef('userlist', $userlist);
				$result = $model->get_symbol_queue_group($package_id);
	
				JToolBarHelper::title(JText::_('New Symbol Queue Group '), 'logo.png');				
				JToolBarHelper::custom('symbolqueuegroup.save_create', 'copy', 'copy', 'Save & Close', false);
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=symbolqueuegroup&task=symbolqueuegroup.get_symbolqueuegroup&package_id='.JRequest::getVar('package_id'));
				break;				
		}
		$this->model = $model;
		parent::display($tpl);
	}	
}