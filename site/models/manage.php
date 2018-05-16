<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class AwardPackageModelManage extends JModelList{
	
	public function getListQuery(){
		
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true);
		
		$user 	= &JFactory::getUser();
		
		$query->select('*');
		
		$query->from('#__gc_recieve_user a')->innerjoin('#__giftcode_category b ON a.category_id=b.id')->innerjoin('#__giftcode_giftcode c ON a.gcid=c.id')->leftjoin('#__giftcode_collection d ON d.color_id=a.category_id')->where('a.user_id='.$user->id);
		
		return $query;
	}
	
	public function getGiftCode($color_id){
		
		$db	= &JFactory::getDBO();
		
		$query 	= "SELECT * FROM #__giftcode_giftcode WHERE giftcode_category_id ='".$color_id."'";
		
		$db->setQuery($query);
		
		$rows 	= $db->loadObjectList();
		
		foreach($rows as $row){
			
			return $row;
		
		}
	}
	
	function info($id){
    
		$db = JFactory::getDBO();
	       
		$db->setQuery("SELECT * FROM #__ap_useraccounts WHERE id = '$id'");
	       
		$row = $db->loadObjectList();
	       
		foreach($row as $r){
			
			return $r;
		}
	}
}