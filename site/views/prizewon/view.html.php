<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Prizes won 
 */
class AwardpackageViewPrizeWon extends JViewLegacy
{
	/**
	 * prize won display method
	 * @return void
	 */
         public function __construct($config = array()) {
		 	 $this->model =& JModelLegacy::getInstance('prizewon','AwardPackageModel');
             parent::__construct($config);
         }  
         
         public function display($tpl=null){
		 
		 	 $this->pagination = $this->get('pagination');
			 
			 $this->items 	   = $this->get('items');
			 
			 $document= &JFactory::getDocument();
			  
			 $document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/style.css');
			  
			 $document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/layout.css');
			  
			 $document->addScript(JURI::root().'/media/system/js/modal.js');
			  
			 $document->addStyleSheet(JURI::root().'media/system/css/modal.css');
			 
			$document->addCustomTag(
					'<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />' . "\n\n" .
					'<!--[if IE 7]>' . "\n" .
					'<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />' . "\n" .
					'<![endif]-->' . "\n" .
					'<!--[if gte IE 8]>' . "\n\n" .
					'<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />' . "\n" .
					'<![endif]-->' . "\n" .
					'<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />' . "\n"
			);
		  
             parent::display($tpl);
         }
}