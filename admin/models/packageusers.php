<?php
//redirect
defined('_JEXEC') or die('Restricted accessed');
//import file
jimport('joomla.application.component.modellist');
/*
 package : awardpackage
 */
class AwardPackageModelPackageUsers extends JModelList {

	var $field;

	function __construct($config = array()) {
		$this->package_id 		= JRequest::getInt('package_id');
		$this->category_id		= JRequest::getInt('category_id');
		$this->_db 				= JFactory::getDBO();
		parent::__construct($config);
	}

	function getListQuery(){
		//set variable
		$query = $this->_db->getQuery(TRUE);

		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_user_packages'));
		$query->where($this->_db->QuoteName('package_id') . "='" . $this->package_id . "'");
		$query->where($this->_db->QuoteName('category_id') . "='" . $this->category_id . "'");
		//$query->where($this->_db->QuoteName('field') . "='" . $this->field . "'");

		return $query;
	}

	function setField($field){
		$this->field	= $field;
	}

	function checking_award_schedule($category_id, $package_id) {
		$db 		=  JFactory::getDBO();
		$query		= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_award_schedule");
		$query->where("category_id='".$category_id."'");
		$query->where("package_id='".$package_id."'");
		$db->setQuery($query);
		$row = $db->loadRow();
		return $row;
	}

	public function CheckResult($package_id, $cat_id) {
	$num='';
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_user_packages'));
		$query->where("package_id='$package_id'");
		$query->where("category_id='$cat_id'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				$num = $this->get_total($this->check_field($row->field, $row));
			}
		}
		return $num;
	}

	function criteria_info($id) {
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_user_packages'));
		$query->where($this->_db->QuoteName('id') . "='" . $id . "'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	function getUserInfo($id) {
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_useraccounts') . ' AS a');
		$query->innerJoin($this->_db->QuoteName('#__users') . ' AS b ON a.id=b.id');
		$query->where("a.id='" . $id . "'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObject();
		return $rows;
	}

	function search_result() {
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest('com_awardpackage.usergroup.filter_order', 'filter_order', 'firstname', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest('com_awardpackage.usergroup.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );

		$db = & JFactory::getDBO();

		//query
		$package_id = JRequest::getVar('package_id');
		$category_id = JRequest::getVar('category_id');

		$query  = $this->_db->getQuery(true);
		$query->select('*');
		$query->from($this->_db->quoteName('#__ap_user_packages'));
		$query->where($this->_db->quoteName('package_id')."='".$package_id."'");
		$query->where($this->_db->quoteName('category_id')."='".$category_id."'");

		$this->_db->setQuery($query);
		$rs = $this->_db->loadObjectList();
		if (count($rs) > 0) {
			foreach ($rs as $k => $v) {
				$num = ceil(($v->population / 100) * $this->get_total($this->check_field($v->field, $v)));
				$usr[] = $this->get_result($this->check_field($v->field, $v, $num));
			}
			$new_array = array();
			foreach ($usr as $array) {
				foreach ($array as $k => $v) {
					if (!array_key_exists($k, $new_array)) {
						$new_array[$k] = $v;
					}
				}
			}
			foreach ($new_array as $k => $v) {
				$this->update_user_category_id($category_id, $package_id, $k);
			}

			$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

			$query = $this->_db->getQuery(true);
			$query->select('*');
			$query->from($this->_db->quoteName('#__ap_useraccounts').' AS t');
			$query->from($this->_db->quoteName('#__users').' AS u ');
			$query->where('t.id=u.id');

			if( JRequest::getVar('command') == '1' ) {
				$query->where("is_presentation = '1' ");
			}

			$query->where("category_id='".(int) $category_id."'  ");
			$query->where("package_id='".(int) $package_id."' " . $order);


			$this->_db->setQuery($query);
			$row = $this->_db->loadAssocList();

			$query = '
            		SELECT count(*) FROM #__ap_useraccounts AS t,
					#__users  AS u
					WHERE t.`id` = u.`id`
					AND package_id = '.$package_id.'
					AND category_id = '.$category_id.'	
            	';

			$this->_db->setQuery($query);
			$total = $this->_db->loadResult();
			$return['lists'] = array(
	    			'order'=>$filter_order,
	    			'order_dir'=>$filter_order_dir,
					'data'=>$row,
					'total'=>$total
			);
			return $return;
			//return ($row);
		}
	}

	function update_user_category_id($category_id, $package_id, $id) {
		$query = $this->_db->getQuery(TRUE);
		$query->update($this->_db->QuoteName('#__ap_useraccounts'));
		$query->set("category_id = '$category_id'");
		$query->where("package_id='$package_id'");
		$query->where("id='$id'");
		$this->_db->setQuery($query);
		$result = $this->_db->query();
	}

	function get_result($qry) {
		//set query
		$query = $this->_db->getQuery(true);
		$query->select('*');
		$query->from($this->_db->quoteName('#__ap_useraccounts').' AS t');
		$query->from($this->_db->quoteName('#__users').' AS u');

		//$where = "package_id = '".JRequest::getVar('package_id')."' ";

		$where = " package_id = '".JRequest::getVar('package_id')."' ";

		if(!empty($qry) || $qry != "") {
			$where .= " AND " . $qry;
		}
		$query->where($where);

		$this->_db->setQuery($query);
		$rs = $this->_db->loadObjectList();
		if (count($rs) > 0) {
			foreach ($rs as $k => $v) {
				$user[$v->id] = array(
                    'package_id' => $v->package_id,
                    'id' => $v->id,
                    'username' => $v->username,
                    'firstname' => $v->firstname,
                    'lastname' => $v->lastname,
                    'birthday' => $v->birthday,
                    'email' => $v->email,
                    'gender' => $v->gender,
                    'street' => $v->street,
                    'city' => $v->city,
                    'state' => $v->state,
                    'post_code' => $v->post_code,
                    'country' => $v->country,
                    'phone' => $v->phone,
				);
			}
			return $user;
		}
	}

	function get_total($qry) {
		//set query
		$query = $this->_db->getQuery(true);
		$query->select('t.email');
		$query->from($this->_db->quoteName('#__ap_useraccounts').' AS t');
		$query->from($this->_db->quoteName('#__users').' AS u');
		if(!empty($qry) || $qry != "") {
			$query->where($qry);
		}
		$this->_db->setQuery($query);
		$rs = $this->_db->loadObjectList();
		return count($rs);
	}

	function check_field($field, $v, $limit = 0) {
		if ($limit > 0) {
			$rand = "ORDER BY RAND() LIMIT $limit";
		}
		switch ($field) {
			case 'name':
				$qry = "t.id = u.id AND lower(t.firstname) LIKE '%".strtolower($v->firstname)."%' AND lower(lastname) LIKE '%".strtolower($v->lastname) ."%'  $rand";
				break;
			case 'email':
				$qry = "t.id = u.id AND lower(t.email) LIKE '%".strtolower($v->email)."%'  $rand";
				break;
			case 'age':
				$qry = "t.id = u.id AND YEAR( NOW( ) ) - YEAR( t.birthday ) BETWEEN $v->from_age AND $v->to_age  $rand";
				break;
			case 'gender':
				$qry = "t.id = u.id AND t.gender = '$v->gender'  $rand";
				break;
			case 'location':
				$qry = "t.id = u.id AND lower(t.street) LIKE '%".strtolower($v->street)."%' AND lower(city) LIKE '%".strtolower($v->city) ."%' AND state LIKE '%$v->state%' AND post_code LIKE '%$v->post_code%' AND country = '$v->country' $rand";
				break;
			case 'New' :
				$qry = '1=1';
				break;
					
		}
		//echo $qry.'<br>';
		//die();
		return $qry;
	}

	public function getNonapUserPackage($package_id, $category_id) {
		$db = &JFactory::getDBO();
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_non_user_packages'));
		$query->where("package_id='".$package_id."'");
		$query->where("category_id='".$category_id."'");
		$query->order_by("id ASC");
		$this->_db->setQuery($query);
		//echo $query;
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	public function deletNonAwUser($cid) {
		$query = $this->_db->getQuery(TRUE);
		$query->delete();
		$query->from($this->_db->QuoteName('#__ap_non_user_packages'));
		$query->where("id='$cid'");
		$this->_db->setQuery($query);
		if ($this->_db->query()) {
			return true;
		}
	}

	public function updateNonAwUser($cid,$data){

		$query = $this->_db->getQuery(TRUE);
		$query->update($this->_db->QuoteName('#__ap_non_user_packages'));
		$query->set("firstname='".$data['firstname']."'");
		$query->set("lastname='".$data['lastname']."'");
		$query->set("email='".$data['email']."'");
		$query->set("subject='".$data['subject']."'");
		$query->set("message='".$data['message']."'");
		$query->set("status='1'");
		$query->where("id='".$cid."'");
		$this->_db->setQuery($query);
		if($this->_db->query()){
			return TRUE;
		}
	}
	public function update($data){

		$query = $this->_db->getQuery(TRUE);
		$query->update($this->_db->QuoteName('#__ap_non_user_packages'));
		$query->set("firstname='".$data['firstname']."'");
		$query->set("lastname='".$data['lastname']."'");
		$query->set("email='".$data['email']."'");
		$query->where("id='".$data['awuser']."'");
		echo $query;

		$this->_db->setQuery($query);
		if($this->_db->query()){
			return TRUE;
		}
	}
	public function addNewUser() {
		$query = $this->_db->getQuery(TRUE);
		$query->insert($this->_db->QuoteName('#__ap_non_user_packages'));
		$date = JFactory::getDate();
		$now = $date;
		$query->set("created_date='".$now."'");
		$query->set("package_id='".$this->package_id."'");
		$query->set("category_id='".$this->category_id."'");
		$this->_db->setQuery($query);
			
		if ($this->_db->query()) {
			return true;
		} else {
			return false;
		}
	}

	public function saveMessage($data) {
		$date = &JFactory::getDate();
		$now = $date->Format();
		$query = $this->_db->getQuery(TRUE);
		$query->update($this->_db->QuoteName('#__ap_non_user_packages'));
		$query->set("subject='".$data['subject']."'");
		$query->set("message='".$data['body']."'");
		$query->set("modified_date='".$now."'");
		$query->where("id='".$data['user_id']."'");
		$this->_db->setQuery($query);
		if ($this->_db->query()) {
			return true;
		} else {
			return false;
		}
	}

	public function getUserPackages() {
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_non_user_packages'));
		//$query = "SELECT * FROM #__ap_non_user_packages";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	public function getMessageNonAwUser($id) {		
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_non_user_packages'));
		$query->where($this->_db->QuoteName('id')."='".$id."'");		
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		foreach ($rows as $row) {
			return $row;
		}
	}

	public function CheckUser($email) {
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__users'));
		$query->where("email='$email'");
		$this->_db->setQuery($query);
		$this->_db->query();
		$numRows = $this->_db->getNumRows();
		return $numRows;
	}

	public function getEmailHistory($package_id){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_non_user_packages').' AS a');
		$query->innerJoin($this->_db->QuoteName('#__ap_categories').' AS b ON a.category_id=b.category_id');
		$query->where("a.package_id='".$package_id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	function getNonApUser($id){
		$query = $this->_db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_non_user_packages");
		$query->where("id='".$id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		$this->syncmessageApUser($rows);
	}

	function syncmessageApUser($rows){
		foreach($rows as $row){
			$query = $this->_db->getQuery(TRUE);
			$query->update("#__ap_non_user_packages");
			$query->set("message='".$row->message."'");
			$query->set("subject='".$row->subject."'");
			$query->where("user_id='0'");
			$query->where("package_id='".$row->package_id."'");
			$query->where("category_id='".$row->category_id."'");
			$this->_db->setQuery($query);
			$return = $this->_db->query();
		}
		if($return){ return true; } else { return false; }
	}

	function delete($category_id, $package_id, $id) {
		$query = "
				select * from #__ap_user_packages where id = '".$id."' and package_id = '".$package_id."' and category_id = '".$category_id."'
			";
		$this->_db->setQuery ( $query );
		$groups = $this->_db->loadObjectList();
		if(!empty($groups)){
			$group = $groups[0];
			if($group->field == 'name'){
				$this->delete_registered_account_by_name($package_id, $category_id, $group->firstname, $group->lastname);
			}
			if($group->field == 'email') {
				$this->delete_registered_account_by_email($package_id, $category_id, $group->email);
			}
			if($group->field == 'age'){
				$this->delete_registered_account_by_age($package_id, $category_id, $group->from_age, $group->to_age);
			}
			if($group->field == 'gender'){
				$this->delete_registered_account_by_gender($package_id, $category_id, $group->gender);
			}
			if($group->field == 'location'){
				$this->delete_registered_account_by_location($package_id, $category_id, $street);
			}

		}

		$query = $this->_db->getQuery(true);
		$query->delete();
		$query->from($this->_db->quoteName('#__ap_user_packages'));
		$query->where($this->_db->quoteName('category_id')."='".(int) $category_id."'");
		$query->where($this->_db->quoteName('package_id')."='".(int) $package_id."'");
		$query->where($this->_db->quoteName('id')."='".(int) $id."'");
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	function delete_registered_account_by_name($package_id, $category_id, $firstname, $lastname){
		$query = "
				select ap_account_id from #__ap_useraccounts where 
					(
						lower(firstname) like '%".strtolower($firstname)."%' or 
						lower(lastname) like '%".strtolower($lastname)."%'
					) and package_id = '".$package_id."'
					and category_id = '".$category_id."'
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		$data = array();
		$data[] = -1;
		foreach ($rows as $row) {
			$data[] = $row->ap_account_id;
		}

		$db	= &JFactory::getDbo();
		$query = '';

		$query = "
				update #__ap_useraccounts set category_id = null where ap_account_id in (
					".implode(',', $data)."
				)
			";	


		$db->setQuery($query);
		$db->query();
	}

	function delete_registered_account_by_email($package_id, $category_id, $email){
		$query = "
				select ap_account_id from #__ap_useraccounts 
				where lower(email) like '%".strtolower($email)."%' 
				and package_id = '".$package_id."'
				and category_id = '".$category_id."'
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		$data = array();
		$data[] = -1;
		foreach ($rows as $row) {
			$data[] = $row->ap_account_id;
		}

		$db	= &JFactory::getDbo();


		$query = "
				update #__ap_useraccounts set category_id = null where ap_account_id in (
					".implode(',', $data)."
				)
			";

		$db->setQuery($query);
		$db->query();
	}

	function delete_registered_account_by_age($package_id, $category_id, $from_age, $to_age) {
		$query = "
				SELECT ap_account_id FROM #__ap_useraccounts WHERE birthday >= DATE_SUB(NOW(), INTERVAL ".$from_age." YEAR) 
				AND birthday <= DATE_SUB(NOW(), INTERVAL ".$to_age." YEAR) 
				AND package_id = '".$package_id."'	
				and category_id = '".$category_id."'
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		$data = array();
		$data[] = -1;
		foreach ($rows as $row) {
			$data[] = $row->ap_account_id;
		}

		$db	= &JFactory::getDbo();

		$query = '';
		$query = "
				update #__ap_useraccounts set category_id = null where ap_account_id in (
					".implode(',', $data)."
				)
			";	

		$db->setQuery($query);
		$db->query();
	}

	function delete_registered_account_by_gender($package_id, $category_id, $gender){
		$query = "
				select ap_account_id from #__ap_useraccounts where lower(gender) like '%".strtolower($gender)."%' 
				and package_id = '".$package_id."'
				and category_id = '".$category_id."'
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		$data = array();
		$data[] = -1;
		foreach ($rows as $row) {
			$data[] = $row->ap_account_id;
		}

		$db	= &JFactory::getDbo();

		$query = '';

		$query = "
				update #__ap_useraccounts set category_id = null where ap_account_id in (
					".implode(',', $data)."
				)
			";	
		$db->setQuery($query);
		$db->query();
	}

	function delete_registered_account_by_location($package_id, $category_id, $street, $city="", $state="", $post_code="", $country=""){
		$query = "
				select ap_account_id from #__ap_useraccounts where lower(street) like '%".strtolower($street)."%' 
				and package_id = '".$package_id."'
				and category_id = '".$category_id."'
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		$data = array();
		$data[] = -1;
		foreach ($rows as $row) {
			$data[] = $row->ap_account_id;
		}

		$db	= &JFactory::getDbo();
		$query = '';

		$query = "
				update #__ap_useraccounts set category_id = null where ap_account_id in (
					".implode(',', $data)."
				)
			";	

		$db->setQuery($query);
		$db->query();
	}

	function getNameUserGroupPresentation($package_id, $field){
		$query=$this->_db->getQuery(true);
		$query->select('*');
		$query->from($this->_db->quoteName('#__ap_user_packages'));
		$query->where($this->_db->quoteName('package_id')."='".$package_id."'");
		$query->where($this->_db->quoteName('field')."='".$field."'");
		$this->_db->setQuery($query);
		$fields = $this->_db->loadObjectList();
		return $fields;
	}

	function getDataParentUserGroups($package_id, $field){
		$query=$this->_db->getQuery(true);
		$query->select('*');
		$query->from($this->_db->quoteName('#__ap_usergroup'));
		$query->where($this->_db->quoteName('package_id')."='".$package_id."'");
		$query->where($this->_db->quoteName('field')."='".$field."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	function selectCountryForUserGroup($package_id){
		$query = "SELECT DISTINCT country from #__ap_useraccounts where package_id = '".$package_id."'";
		$this->_db->setQuery($query);
		$fields = $this->_db->loadObjectList();
		$countries = array();
		foreach ($fields as $field){
			$countries[] = $field->country;
		}
		//return data
				//var_dump($countries);
		return $countries;

	}

	function selectGenderForUserGroup($package_id){
		$query = "SELECT DISTINCT gender FROM #__ap_useraccounts where package_id = '$package_id' ";
//		SELECT gender FROM `f2skf_ap_useraccounts` where `package_id`='5'
		$this->_db->setQuery($query);
		$fields = $this->_db->loadObjectList();
		$genders = array();		
		foreach ($fields as $field){
			$genders[] = $field->gender;
		}
		//return data
		$genders = $fields;
		return $genders;
	}

	function getAllUsers($package_id){
		$query = $this->_db->getQuery(true);
		$query = "SELECT * FROM #__ap_useraccounts where package_id = '".$package_id."' ";
		$this->_db->setQuery($query);
		$rs = $this->_db->loadObjectList();
		return $rs;
	}

	function updatePackageForUserAccounts($package_id, $account_id, $category_id){
		$query = "update #__ap_useraccounts set category_id = '".$category_id."' where ap_account_id in (".$account_id.") and package_id = '".$package_id."' ";
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	function getParentUserGroup($package_id, $field) {
		$query=$this->_db->getQuery(true);
		$query->select('*');
		$query->from($this->_db->quoteName('#__ap_usergroup'));
		$query->where($this->_db->quoteName('package_id')."='".$package_id."'");
		//$query->where($this->_db->quoteName('field')."='".$field."'");
		$query->where($this->_db->quoteName('is_presentation') . " IS NULL");
		$query->where($this->_db->quoteName('parent_usergroup') . " IS NULL");
		$this->_db->setQuery($query);
		$fields = $this->_db->loadObjectList();
		//return data
		return $fields;
	}
}
?>