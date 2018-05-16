<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pagination' );

class AwardpackageViewFundingadd extends JViewLegacy
{
	/**
	 * HelloWorlds view display method
	 * @return void
	 */
	function display() 
	{
		// Load the submenu.
		AwardpackagesHelper::addSubmenuFunding(JRequest::getCmd('view', 'referral'));
		
		$items 		= $this->get('Items');
		
		$pagination 	= $this->get('Pagination');
		
		$this->model	= & JModelLegacy::getInstance('funding','AwardpackageModel');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->items = $items;
		
		$this->pagination = $pagination;
		
		$this->state = $this->get('State');
		
		// Display the template
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		JToolBarHelper::title('[Add] Funding Session', 'article-add.png');
		JToolBarHelper::addNew('funding.fundingadd_add');
		JToolBarHelper::editList('funding.fundingadd_edit');
		JToolBarHelper::deleteList('Are You Sure?', 'funding.fundingadd_delete', 'Delete');
		JToolBarHelper::apply('funding.fundingadd_apply','Save');
		JToolBarHelper::save('funding.fundingadd_save','Save & Close');
		JToolBarHelper::cancel('funding.cancel');
	}
}
