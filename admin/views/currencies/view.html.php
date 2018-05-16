<?php 
//redirect
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardpackageViewCurrencies extends JViewLegacy {
	/*
		package 	: com_awardpackage
	*/
	function display($tpl = null) {
		//set variable
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		//add tool bar
		$this->addToolBar();
		
		parent::display($tpl);
	}
	
	function addToolBar(){
		JToolBarHelper::title('Currencies');
		JToolBarHelper::addNew('currency.add');
		JToolBarHelper::editList('currency.edit');		
		JToolBarHelper::deleteList('are you sure ?', 'currencies.delete', 'Delete');
		JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));
	}
}

?>