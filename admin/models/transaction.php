<?php 
jimport('joomla.application.component.modellist');

class AwardpackageModelTransaction extends JModelList
{
	protected function getListQuery()
	{
		$user =& JFactory::getUser();		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__ap_donation_transactions AS a');
		//$query->innerJoin("#__refund_package_list AS b ON a.package_id=b.package_id");
		$query->where("a.package_id = '".JRequest::getVar('package_id')."'");
		$query->order('a.dated ASC');
		return $query;
	}
	
	public function getTransaction($user_id){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__ap_donation_transactions AS a');
		$query->where("a.package_id = '".JRequest::getVar('package_id')."'");
		$query->where("a.user_id='".$user_id."'");
		$query->order('a.dated ASC');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		//create array 
		
	}
	
	function getInfoUser(){
		$this->user_id = JRequest::getVar('id');
		$db	= JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select('*');
		$query->from('#__users');
		$query->where("id='".$this->user_id."'");
		$db->setQuery($query);
		$row = $db->loadObject();
		return $row;
	}
	
	function getRefund(){
		$db	= JFactory::getDbo();
		$package_id  = JRequest::getVar('package_id');
		$query=$db->getQuery(TRUE);
		$query->select("*");
        $query->from($db->QuoteName('#__refund_record') . 'AS a');
        $query->leftJoin('#__refund_package b ON a.refund_package_list_id = b.refund_package_id');
        $query->innerJoin('#__refund_package_list c ON c.refund_package_list_id=b.refund_package_list_id');
        //$query->innerJoin('#__shopping_credit_config d ON d.shopping_credit_id=a.shopping_credit_package_list_id');
        $query->where("c.package_id='$package_id'");
		$db->setQuery($query);
		$row = $db->loadObjectList();
		return $row;
	}
}
?>
