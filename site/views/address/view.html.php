<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Donation View
 */
class AwardpackageViewAddress extends JViewLegacy
{
	/**
	 * Donation view display method
	 * @return void
	 */
	 
	function display($tpl = null) 
	{
		//set variable
		$this->form			= $this->get('Form');
		$this->item			= $this->get('item');
		$this->user			= &JFactory::getUser();
		//$country_list 		= JHTMLAdsmanagerGeneral::countrieslist();
		//$this->country_list	= $country_list;
		$model 				=& JModelLegacy::getInstance('address','AwardpackageModel');
		$this->info			= $model->info($this->user->id);
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		//get function 
		$this->addStyleSheet();
		
		parent::display($tpl);
	}
	
	function addStyleSheet(){
		$document    = & JFactory::getDocument();
		$document->addStyleSheet('administrator/templates/system/css/system.css');
		$document->addCustomTag(
			'<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />'."\n\n".
			'<!--[if IE 7]>'."\n".
			'<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />'."\n".
			'<![endif]-->'."\n".
			'<!--[if gte IE 8]>'."\n\n".
			'<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />'."\n".
			'<![endif]-->'."\n".
			'<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />'."\n"
			);		
	}
	
}