<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pagination' );

class AwardPackageViewPrizefundingrecord extends JViewLegacy
{
	function display() 
	{
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
		parent::display();
	}
	
	protected function addToolbar()
	{
		JToolBarHelper::title('Prize Funding Manager', 'article-add.png');
	}
}
