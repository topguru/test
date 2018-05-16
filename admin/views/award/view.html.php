<?php 
//redirect access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

symbolimport('symbol.render.renderadmin');

class AwardViewAward extends JViewLegacy
{

	function display($tpl = null)
	{
		$document= &JFactory::getDocument();		
		
		$document->addStyleSheet(JURI::base(true).'/components/com_award/asset/style.css');
		
		parent::display($tpl);

	}

}