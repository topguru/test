<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
jimport( 'joomla.application.application' );

class AwardPackageControllerAward extends AwardPackageController
{
	function display() {
		
		JRequest::setVar('view', JRequest::getCmd('view', 'Award'));
		
		parent::display();
	}
 	
	function save(){
		if(strlen(JRequest::getVar('name'))<=3 || strlen(JRequest::getVar('email'))<=3 || strlen(JRequest::getVar('username'))<=3 || strlen(JRequest::getVar('passkey'))<=3) {			
			$url = JRoute::_( 'index.php?option=com_symbol');
			JFactory::getApplication()->redirect($url, "You didn't fill correctly", 'error' );
            return false;
			}
		else {
		$data = array(
			'name' => JRequest::getVar('name'),
			'email' => JRequest::getVar('email'),
			'address' => JRequest::getVar('address'),
			'city' => JRequest::getVar('city'),
			'state' => JRequest::getVar('state'),
			'country' => JRequest::getVar('country'),
			'phone' => JRequest::getVar('phone'),
			'username' => JRequest::getVar('username'),
			'passkey' => md5(JRequest::getVar('passkey'))			
		);			 
		$model =& JModelLegacy::getInstance('Symbol','SymbolModel');
		$i = $model->save($data);
		$j = $model->queue($i);
		
		$url = JRoute::_( 'index.php?option=com_awardpackage&controller=award&task=s');
		JFactory::getApplication()->redirect($url, "User Registered", 'info' );
		return false;
		
		//$this->setRedirect('index.php?option=com_symbol','User Registered');	
		}
	}
		
	function s() {
		echo "Thank You For Registering";
	}
}

?>
