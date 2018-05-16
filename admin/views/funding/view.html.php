<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pagination' );

class AwardPackageViewFunding extends JViewLegacy
{
	/**
	 * Funding 
	 * @return void
	 */
	function display() 
	{
		// Load the submenu.
		AwardpackagesHelper::addSubmenuFunding(JRequest::getCmd('view', 'referral'));
		
		$items = $this->get('Items');
		
		$pagination = $this->get('Pagination');

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
		JToolBarHelper::title('Funding Session List', 'article-add.png');
		
		JToolBarHelper::addNew('funding.add');
		
		JToolBarHelper::deleteList('Are You Sure?', 'funding.delete', 'Delete');
		
		JToolBarHelper::publish('funding.publish', 'Publish', true);
		
		JToolBarHelper::unpublish('funding.unpublish', 'Unpublish', true);
	}
}
