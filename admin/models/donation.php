<?php

jimport('joomla.application.component.model');
/**
 * DonationList Model
 */
class AwardpackageModelDonation extends JModelLegacy
{

	function publish($data){
		
		$db = JFactory::getDBO();
		
		$query = "UPDATE `#__ap_categories` SET published = 1 WHERE package_id IN (".implode(',',$data).")";
		
		$db->setQuery($query);
		
		if($db->query()){
			
			$QueryUpdate = "UPDATE `#__giftcode_category` SET published = 1 WHERE package_id IN (".implode(',',$data).")";
			
			$db->setQuery($QueryUpdate);
			
			if($db->query()){
				
				return true;
			
			}
		}
	}
	
	function unpublish($data){
		
		$db = JFactory::getDBO();
		
		$query= "UPDATE `#__ap_categories` SET published = 0 WHERE package_id IN (".implode(',',$data).")";
		
		$db->setQuery($query);
		
		if($db->query()){
			
			$QueryUpdate = "UPDATE `#__giftcode_category` SET published = 0 WHERE package_id IN (".implode(',',$data).")";
			
			$db->setQuery($QueryUpdate);
			
			if($db->query()){
				
				return true;
			
			}
		}
	}
	
	function update_category($data,$task){
		
		if($task=='publish'){
			$p = 1;
		}else{
			$p = 0;
		}
		if(count($data)>0){
			
			$db = JFactory::getDBO();				
			
			$query = "UPDATE `#__ap_categories` SET published = '$p' WHERE setting_id in (".implode(",",$data).")";
			
			$db->setQuery($query);
			
			if($db->query()){
				
				$query2 = "UPDATE `#__giftcode_category` SET published = '$p' WHERE id in (".implode(",",$data).")";
				
				$db->setQuery($query2);
				
				$db->query();
			
			}
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
	
	function unlock($cid){
		
		$db		= & JFactory::getDBO();
		
		$query 		= "UPDATE #__ap_categories SET ".$db->QuoteName('unlocked')."='1' WHERE ".$db->QuoteName('setting_id')."='".$cid."'";
		
		$db->setQuery($query);
		
		if($db->query()){
			
			return true;
		
		}else{
			
			return false;
		
		}
	}

	function save_categories($cid,$data){
		
		$db 		=& JFactory::getDBO();
		
		$QueryCheck 	= "SELECT * FROM ".$db->QuoteName('#__ap_categories')." WHERE ".$db->QuoteName('setting_id')."='".$cid."' AND unlocked='1'";
		
		$db->setQuery($QueryCheck);
		
		if($db->query()){
			
			$numRows	= $db->getNumRows();
			
		}
		
		if($numRows>0){
		
			$query		= "UPDATE ".$db->QuoteName('#__ap_categories')." SET ".$db->QuoteName('donation_amount')."='".$data['amount']."',".$db->QuoteName('unlocked')."='0' WHERE ".
					  
					  $db->QuoteName('setting_id')."='".$cid."'";
					   
			$db->setQuery($query);
			
			
			if($db->query()){
				
				return true;
			
			}else{
				
				return false;
			
			}
		}
		
	}	
	
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
	
	function registered_donation($package_id){
		$db 	= JFactory::getDBO();
		
		$query 	= $db->getQuery(TRUE);
		
		$query->select("*");
		
		$query->from("#__ap_donation_transactions");
		
		$query->where("package_id='".$package_id."'");
		
		$db->setQuery($query);
		
		return count($db->loadObjectList());
	}
}
