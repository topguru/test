<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * DonationList Model
 */
class AwardpackageModelElements extends JModelLegacy
{
	function update_category($data,$task){
		if($task=='publish'){
			$p = 1;
		}else{
			$p = 0;
		}
		if(count($data)>0){
			$db = JFactory::getDBO();				
			$query = "UPDATE `#__ap_categories` SET published = '$p' WHERE setting_id in (".implode(",",$data).")";
			echo $query;
			$db->setQuery($query);
			$db->query();			
		}
	}
	
}
