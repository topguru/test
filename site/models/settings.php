<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.model');
/**
 * DonationList Model
 */
class AwardpackageModelSettings extends JModelLegacy
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	function invar($name,$value){
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM `#__ap_donation_variables` WHERE name = '$name' LIMIT 1";
		$db->setQuery($query);
		$rs=$db->loadObject();
		if($rs->name){
			if($rs->value){
				$result = $rs->value;
			}else{
				$result = $value; 
			}
			return $result;
		}else{
			return $value;
		}
	}
	
	function iscent($value,$digit=0){
		if($value>0){
			if($this->invar('currency_unit','0')){
				return number_format(($value*100),$digit).' cents ('.$this->invar('currency_code','').')';
			}else{
				return number_format($value,2).' dollars ('.$this->invar('currency_code','').')';
			}
		}else{
			return '';
		}
	}
	
	function iscent_raw($value,$digit=0){
		if($value>0){
			if($this->invar('currency_unit','0')){
				return ($value*100);
			}else{
				return $value;
			}
		}else{
			return 0;
		}
	}	
	
	function save_settings($post){
		$db =& JFactory::getDBO();	
		foreach($post as $key => $value){
			echo $key .' => '.$value.'<br>';
			$query = "REPLACE INTO `#__ap_donation_variables` (name,value) VALUES ('$key','$value')";
			$db->setQuery($query);
			$db->query();
		}
		$message = JText::_('Your setting has been saved...');	
	}
}
