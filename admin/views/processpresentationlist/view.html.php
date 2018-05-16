<?php 
//redirect
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
class awardpackageViewprocesspresentationlist extends JViewLegacy {
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();		
			$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$package_id = JRequest::getVar("package_id");	
		switch ($this->action){
			case 'list':
			   	$presentations = $model->getDetailPresentationProcess_1(JRequest::getVar('package_id'));
				$this->assignRef('presentations', $presentations);	

				JToolBarHelper::title(JText::_('Process Presentation List'), 'logo.png');
				JToolbarHelper::back('Close', 'index.php?option=com_awardpackage&package_id=' . JRequest::getVar('package_id'));

				break;
			case 'create':
			  
												JToolbarHelper::back('Close', 'index.php?option=com_awardpackage&package_id=' . JRequest::getVar('package_id'));

				break;				
		}
		$this->model = $model;
		parent::display($tpl);
	}	
}