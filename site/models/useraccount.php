<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.modelitem');
jimport('joomla.user.helper');

class AwardpackageUsersModelUseraccount extends JModelLegacy {

	function __construct() {
		parent::__construct ();
	}

	function save($data){
		$db 	= JFactory::getDbo();
		$user	= JFactory::getUser();
		$now = JFactory::getDate();
        $yourpass = JUserHelper::hashPassword($data['pasw']);
		$plainpass = $data['pasw'];
		
		$query1 = "select u.* from #__users u
				where username = '".$data['username']."' or u.email = '".$data['email']."' ";
		$db->setQuery ( $query1 );
		$result1 = $db->loadObjectList();

		if (!empty($result1)){
					return false;
		}
		
		$query = "select u.*, au.*, u.email as emailRegistered from #__users u
				inner join #__ap_useraccounts au on au.id = u.id 
				where username = '".$data['username']."' or u.email = '".$data['email']."' ";
		$db->setQuery ( $query );
		$result = $db->loadObjectList();

		if(empty($result)){ 
		$query1 = "INSERT INTO #__users (name, username, email, password, registerDate, lastvisitDate, activation)
                       VALUES ('" . $data['firstname'] . " " . $data['lastname'] . "', '" . $data['username'] . "', '" . $data['email'] . "', '" . $yourpass . "', '".$now."', '" . $now . "', '" .$data['activation']. "')";
					   		$db->setQuery($query1);
							$db->query();
				   			$insertedId = $db->insertId();
			$query2 = "INSERT INTO  #__user_usergroup_map (user_id, group_id)
					  VALUES ('" .$insertedId. "', 4)";
			$db->setQuery($query2);
			$db->query();
		}else {
		$query3 = "UPDATE #__users SET name = '" .$data['firstname']. "', email = '" .$data['email']. "' , password = '" .$yourpass. "' WHERE id = ".$data['id']."  ";
					   		$db->setQuery($query3);
							$db->query();
				   			$insertedId = $data['id'];

		}

		$query = "INSERT INTO #__ap_useraccounts (id, email, firstname, lastname, gender, country, paypal_account, state, is_active)
					  VALUES ('" .$insertedId. "', '" . $data['email'] . "','" . $data['firstname'] . "','" . $data['lastname'] . "','" . $data['gender'] . "','" . $data['country'] . "','" . $data['paypal_account'] . "','" . $data['pasw'] . "',0)";
			$db->setQuery($query);
			
if ($db->query()){
			return true;
			}else{
			return false;
			}
		
		
		}
		
	function insertUserInfo($data){
		$db = JFactory::getDbo();
		$user= JFactory::getUser();
		$now = JFactory::getDate();
		$query = "insert into #__ap_useraccounts (id, firstname, lastname, birthday, gender, street, city, state, post_code, country,
					phone, paypal_account, package_id, email, is_active)
				  values ('" .$data['userId']. "', '" .$data['firstname']. "', '" .$data['lastname']. "',
				    '" .$data['birthdate']. "', '" .$data['gender']. "', '" .$data['street']. "',
				    '" .$data['city']. "', '" .$data['state']. "', '" .$data['postCode']. "',
				    '" .$data['country']. "', '" .$data['phone']. "', '" .$data['paypal_account']. "',
				    null, '" .$data['email']. "', '1'  ) ";

		$db->setQuery($query);
		if ($db->query()) {
		     $query = "UPDATE #__user_usergroup_map SET group_id = '4' WHERE user_id = ".$data['userId']." AND group_id = '2' ";
			$db->setQuery($query);
			$db->query();
			return true;
		} else {
			return false;
		}
	}


	function updateInfo($data){
		$db = JFactory::getDbo();
		$user= JFactory::getUser();		
		$now = JFactory::getDate();
		//$userid = $db->insertId();

		$result = $this->getUserId();
		foreach ($result as $row){
		$userid = $row->id;
		}

		$query = "update #__ap_useraccounts set 
					birthday = '" .$data['birthdate']. "',
					street = '" .$data['street']. "', 
					phone = '" .$data['phone']. "',
					is_active = '1'
				  where id = '".$userid."'
				  ";
		$db->setQuery($query);
		if ($db->query()) {
			return true;
		} else {
			return false;
		}
	}

     function edit_save($data){
		$db 	= JFactory::getDbo();
		$user	= JFactory::getUser();
		$now = JFactory::getDate();
        $yourpass = JUserHelper::hashPassword($data['pasw']);
		$plainpass = $data['pasw'];
		
		/*$query1 = "select u.* from #__users u
				where username = '".$data['username']."' or u.email = '".$data['email']."' ";
		$db->setQuery ( $query1 );
		$result1 = $db->loadObjectList();

		if (!empty($result1)){
					return false;
		}
		
		$query = "select u.*, au.*, u.email as emailRegistered from #__users u
				inner join #__ap_useraccounts au on au.id = u.id 
				where username = '".$data['username']."' or u.email = '".$data['email']."' ";
		$db->setQuery ( $query );
		$result = $db->loadObjectList();*/

		/*if(empty($result)){ 
		$query1 = "INSERT INTO #__users (name, username, email, password, registerDate, lastvisitDate, activation)
                       VALUES ('" . $data['firstname'] . " " . $data['lastname'] . "', '" . $data['username'] . "', '" . $data['email'] . "', '" . $yourpass . "', '".$now."', '" . $now . "', '" .$data['activation']. "')";
					   		$db->setQuery($query1);
							$db->query();
				   			$insertedId = $db->insertId();
			$query2 = "INSERT INTO  #__user_usergroup_map (user_id, group_id)
					  VALUES ('" .$insertedId. "', 4)";
			$db->setQuery($query2);
			$db->query();
		}else {*/
		$query3 = "UPDATE #__users SET name = '" .$data['firstname']. "', email = '" .$data['email']. "' , password = '" .$yourpass. "' WHERE id = ".$data['userId']."  ";
					   		$db->setQuery($query3);
							$db->query();
				   			$insertedId = $data['id'];


		$query = "UPDATE #__ap_useraccounts SET 
		email = '" . $data['email'] . "', 
		firstname = '" . $data['firstname'] . "', 
		lastname = '" . $data['lastname'] . "', 
		gender = '" . $data['gender'] . "', 
		country = '" . $data['country'] . "', 
		paypal_account = '" . $data['paypal_account'] . "', 
		state = '" . $data['pasw'] . "'
		WHERE id = ".$data['userId']."  ";
		$db->setQuery($query);
		if ($db->query()){
			return true;
			}else{
			return false;
			}
		}
		
	function updateData($data){
		$db = JFactory::getDbo();
		$user= JFactory::getUser();		
		$now = JFactory::getDate();
		/*$userid = $db->insertId();

		$result = $this->getUserId();
		foreach ($result as $row){
		$userid = $row->id;
		}*/

		$query = "update #__ap_useraccounts set 
					birthday = '" .$data['birthdate']. "',
					street = '" .$data['street']. "', 
					phone = '" .$data['phone']. "',
					is_active = '1'
				  where id = '" .$data['userId']. "'
				  ";
		$db->setQuery($query);
		if ($db->query()) {
			return true;
		} else {
			return false;
		}
	}


	function login($data){
		$query = "select u.*, au.*, u.email as emailRegistered from #__users u
				left join #__ap_useraccounts au on au.id = u.id 
				where username = '".$data['username']."' and password='".base64_encode($data['password'])."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return $result[0];
		}
		return null;
	}
	
	function getUserId(){
		$query = "select * from #__users ORDER BY ID DESC LIMIT 1 ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
			return $result;
	}

	function getPackageId($package_id){
		$query = "select * from #__ap_awardpackages WHERE package_id = '".$package_id."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
			return $result;
	}
	
	function checkUserDetailInfo($username) {
		$query = "select u.*, au.*, u.email as emailRegistered from #__users u
				inner join #__ap_useraccounts au on au.id = u.id 
				where u.id = '".$username."' ";

		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();

		if(!empty($result)){
			$user = $result[0];
			if($user->package_id == '' || $user->package_id == '0'){
				return 'no_package';
			}
				
			return $user;
		}
		return null;
	}
	function checkUserDetailInfo1($username) {
		$query = "select u.*, au.*, u.email as emailRegistered from #__users u
				inner join #__ap_useraccounts au on au.id = u.id 
				where u.id = '".$username."' ";

		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			$user = $result[0];
				
			if($user->package_id == '' || $user->package_id == '0'){
				return 'no_package';
			}
				
			return $result;
		}
		return null;
	}
	function getSessionData($userId){
		$query = "select u.*, au.*, u.email as emailRegistered from #__users u
				left join #__ap_useraccounts au on au.id = u.id 
				where u.id = '".$userId."'  ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return $result[0];
		}
		return null;
	}
}