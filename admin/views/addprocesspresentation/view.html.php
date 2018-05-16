<?php 
//redirect
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
class awardpackageViewaddprocesspresentation extends JViewLegacy {
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();		
		$model = & JModelLegacy::getInstance( 'addprocesspresentation', 'AwardpackageModel' );
		$package_id = JRequest::getVar("package_id");	
		switch ($this->action){
			case 'list':
			    $result = $model->get_processpresentation($package_id);
				$fundprizes = array();
				if(!empty($result['fundprizes'])) {
					$fundprizes = $result['fundprizes'];
				}
				$this->assignRef('fundprizes', $fundprizes);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				JToolBarHelper::title(JText::_('Fund Prize Plan List'), 'logo.png');
				JToolBarHelper::addNew('addprocesspresentation.create_update');			
				JToolBarHelper::deleteList('', 'addprocesspresentation.delete_addprocesspresentation');
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id'));
				break;
			case 'create':		
			$id = '0';
				if(JRequest::getVar('process_id') != null) {
					$id = JRequest::getVar('process_id');
				}
				$result = $model->get_processpresentation_byid($id);
				$this->assignRef('fundprizes', $result);
				
				JToolBarHelper::title(JText::_('Create Process Presentation'), 'logo.png');				
				JToolBarHelper::custom('addprocesspresentation.save_create', 'copy', 'copy', 'Save & Close', false);
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar('package_id'));
				break;				
		}
		parent::display($tpl);
	}	
}