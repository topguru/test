<?php 
//-- No direct access
defined('_JEXEC') or die('=;)');

jimport('joomla.application.component.model');

class AwardPackageModelPaypal extends JModelLegacy {
	
	function __construct() {
		parent::__construct();
	}
	
	function getItem(){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from('#__ap_paypal');
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	function save_deposit($data){
		$date	= &JFactory::getDate();
		$user	= &JFactory::getUser();
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__jvotesystem_deposit");
		$query->set("deposit_amount='".$data['deposit_amount']."'");
		$query->set("deposit_number='".$data['deposit_number']."'");
		$query->set("top_amount='".$data['top_amount']."'"); 
		$query->set("top_every='".$data['top_every']."'");
		$query->set("currency_code='".$data['currency_code']."'");
		$query->set("user_id='".$user->id."'");
		$query->set("payment_gateway='".$data['payment_gateway']."'");
		$query->set("dated='".$data['dated']."'");
		$db->setQuery($query);
		$db->query(); 
	}
	
	function get_deposit(){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__jvotesystem_deposit");
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}
?>