<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
 
class AwardpackageControllerAwardcollection extends AwardPackageController
{
	function display() {
		JRequest::setVar('view', JRequest::getCmd('view', 'Awardcollection'));
		
		parent::display();
	}
 
    
}

?>