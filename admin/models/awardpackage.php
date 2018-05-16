<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class AwardpackageModelAwardpackage extends JModelList
{
	protected function getListQuery()
	{

		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__ap_awardpackages')->order('created DESC');
		return $query;
	}

	function registered_users($package_id){
		$db 	= JFactory::getDBO();

		$query 	= $db->getQuery(TRUE);

		$query->select("*");

		$query->from("#__ap_usergroup");

		$query->where("package_id='".$package_id."'");

		$db->setQuery($query);

		$accounts = $db->loadObjectList();
		$results = array();
		foreach ($accounts as $account){
			if(	$this->is_registered_group_by_name($package_id, $account->firstname, $account->lastname) ||
				$this->is_registered_group_by_email($package_id, $account->email) ||
				$this->is_registered_group_by_age($package_id, $account->birthday) ||
				$this->is_registered_group_by_gender($package_id, $account->gender) ||
				$this->is_registered_group_by_location($package_id, $account->street, $account->city,
							$account->state, $account->post_code, $account->country)){
				$results[]= $account;
			}
		}
		return count($results);
	}

	function is_registered_group_by_name($package_id, $firstname, $lastname){
		$query = "
				select * from #__ap_usergroup where 
				(
					lower(firstname) like '%".strtolower($firstname)."%' or 
					lower(lastname) like '%".strtolower($lastname)."%'
				) and package_id = '".$package_id."'
			";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return true;
		}
		return false;
	}

	function is_registered_group_by_email($package_id, $email){
		$query = "
				select * from #__ap_usergroup where
				lower(email) like '%".$email."%' 
				and package_id = '".$package_id."'
			";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return true;
		}
		return false;
	}

	function is_registered_group_by_age($package_id, $birthday){
		$query = "	
				SELECT * FROM (
				SELECT FLOOR((DATEDIFF('".$birthday."', NOW()) / 360 )) AS age 
				FROM #__ap_useraccounts WHERE package_id = '".$package_id."'
				) dat
				INNER JOIN #__ap_usergroup au ON dat.age >= au.`from_age` AND dat.age <= au.`to_age`
				AND au.`package_id` = '".$package_id."'
			";	
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return true;
		}
		return false;
	}

	function is_registered_group_by_gender($package_id, $gender){
		$query = "
				select * from #__ap_usergroup where
				lower(gender) like '%".$gender."%' 
				and package_id = '".$package_id."'
			";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return true;
		}
		return false;
	}

	function is_registered_group_by_location($package_id, $street, $city="", $state="", $post_code="", $country="") {
		$query = "
				select * from #__ap_usergroup where 
				(lower(street) like '%".strtolower($street)."%'
				 or lower(city) like '%".strtolower($city)."%'
				 or lower(state) like '%".strtolower($state)."%'					
				 or lower(post_code) like '%".strtolower($post_code)."%'
				 or lower(country) like '%".strtolower($country)."%')
				and package_id = '".$package_id."'
			";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return true;
		}
		return false;
	}

	public function getAwardPackage($package_id){
		$db			= 	JFactory::getDBO();
		$query		= 	$db->getQuery(TRUE);
		$query->select("*");
		$query->from('#__ap_awardpackages');
		$query->where("package_id='".$package_id."'");
		$db->setQuery($query);
		$row		= 	$db->loadObject();
		return $row;
	}
}

?>
