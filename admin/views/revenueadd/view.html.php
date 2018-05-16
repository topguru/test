<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pagination' );

class AwardPackageViewrevenueadd extends JViewLegacy
{
	/**
	 * HelloWorlds view display method
	 * @return void
	 */
	function display() 
	{
		$this->addToolbar();
		parent::display();
	}
	
	protected function addToolbar()
	{
		JToolBarHelper::title('[Add] Funding Session', 'article-add.png');
		JToolBarHelper::save('shane.save');
		JToolBarHelper::cancel('shane.cancel');
	}
}
