<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class AwardPackageModelgiftcodecode extends JModelLegacy {

	var $_get_created_date;
	var $_getLastCollectionSetting;
	var $_counter_giftcode;
	var $_get_color;
	var $_get_color_per_category;
	var $_getLastCollection;
	var $_dataGiftCollection;
	var $_data_gift_collection_setting;
	var $_colorID;
	var $_countGiftcode;
	var $_data;
	var $_user;
	var $_owner;
	var $_giftcodes;
	var $_renew_schedule;
	var $_checking_schedule_created;
	var $_giftcode_collections;
	var $_giftcode_collection;
	var $_renew_status;
	var $_exist_renewed;
	var $_schedule_day;
	var $_unrenewed_giftcode;
	var $_unrenewed_giftcode_by_id;
	var $_schedule_created;

	function get_color($giftcode_collection_id) {
		if (empty($this->_get_color)) {
			$this->_get_color = $this->_getList("SELECT giftcode_category_id
                FROM #__giftcode_giftcode 
                WHERE giftcode_collection_id = '".$giftcode_collection_id."'
                ORDER BY id 
                DESC LIMIT 1");                
		}
		return $this->_get_color;
	}

	function get_color_per_category($color_id) {
		if (empty($this->_get_color_per_category)) {
			$this->_get_color_per_category = $this->_getList("SELECT *
                FROM #__giftcode_collection 
                WHERE color_id = '".$color_id."'");                
		}
		return $this->_get_color_per_category;
	}

	function get_created_date($giftcode_collection_id) {
		if (empty($this->_get_created_date)) {
			$this->_get_created_date = $this->_getList("SELECT created_date_time
                FROM #__giftcode_collection 
                WHERE id = '".$giftcode_collection_id."'
                ORDER BY id 
                DESC LIMIT 1");                
		}
		return $this->_get_created_date;
	}


	function counter_giftcode($giftcode_collection_id) {
		if (empty($this->_counter_giftcode)) {
			$this->_counter_giftcode = $this->_getList("SELECT * FROM #__giftcode_giftcode WHERE giftcode_collection_id = '".$giftcode_collection_id."'");
		}
		return $this->_counter_giftcode;
	}

	function getLastCollectionSetting() {
		if (empty($this->_getLastCollectionSetting)) {
			$this->_getLastCollectionSetting = $this->_getList("SELECT id FROM #__giftcode_collection_setting ORDER BY id DESC LIMIT 1");
		}		
		return $this->_getLastCollectionSetting;
	}

	function getLastCollection() {
		if (empty($this->_getLastCollection)) {
			$this->_getLastCollection = $this->_getList("SELECT id FROM #__giftcode_collection ORDER BY id DESC LIMIT 1");
		}
		return $this->_getLastCollection;
	}

	function getDataGiftCollection() {
		if (empty($dataGiftCollection)) {
			$this->_dataGiftCollection = $this->_getList("SELECT * FROM #__giftcode_collection ORDER BY created_date_time");
		}		
		return $this->_dataGiftCollection;
	}

	function get_data_gift_collection_setting() {
		if (empty($data_gift_collection_setting)) {
			$this->_data_gift_collection_setting = $this->_getList("SELECT * FROM #__giftcode_collection_setting ORDER BY id");
		}
		return $this->_data_gift_collection_setting;
	}

	function getColorID($giftcode_collection_id) {		
		if (empty($colorID)) {
			$this->_colorID = $this->_getList("
            SELECT giftcode_category_id FROM #__giftcode_giftcode 
            WHERE giftcode_collection_id = '".$giftcode_collection_id."' 
            ORDER BY id DESC LIMIT 1");           
		}		
		return $this->_colorID;
	}

	function countGiftcode($giftcode_collection_setting_id) {
		if (empty($countGiftcode)) {
			$this->_countGiftcode = $this->_getList("
            SELECT max_number_of_code FROM #__giftcode_collection_setting 
            WHERE id = '".$giftcode_collection_setting_id."' 
            ORDER BY id");           
		}
		return $this->_countGiftcode;
	}
	 
	function getDataDetail($gcid)
	{
		if(empty($this->_data))
		{
			$this->_data=$this->_getList("SELECT * FROM #__giftcode_collection_setting WHERE giftcode_collection_id = '".$gcid."'");
		}
		return $this->_data;
	}

	function getGiftcode($gcid) {
		if (empty($this->_data)) {
			$this->_data = $this->_getList("SELECT * FROM #__giftcode_giftcode WHERE giftcode_collection_id = '".$gcid."'");
		}
		return $this->_data;
	}

	function get_category() {
		if(empty($this->_data)) {
			$this->_data= $this->_getList( "SELECT * FROM #__giftcode_category ORDER BY id" );
		}
		return $this->_data;
	}
	 
	function save($giftcodes, $giftcode_collection, $edit) {

		$category = $this->get_category();

		$color_collection = array();

		foreach ($category as $cat) {
			$color_collection[] = $cat->name;
		}

		$row =& $this->getTable('Giftcode_collection');
		$created_date = JRequest::getVar("created_date");
		$row->created_date_time = !empty($created_date) ? $created_date : date('Y-m-d G:i:s');
		$row->modified_date_time = date('Y-m-d G:i:s');
		$row->published = 0;
		$row->color_id = $giftcode_collection['color'];
		$row->total_giftcodes = $giftcode_collection['max_number_of_code'];
		$row->package_id = JRequest::getVar('package_id');

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		$row =& $this->getTable('Giftcode_collection_setting');

		foreach ($this->getLastCollection() as $idCollection) {
			$row->giftcode_collection_id = $idCollection->id;
		}
		 
		if (!$row->bind($giftcode_collection)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		 
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		 
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}
		 
		if ($edit == true) {
			$cid = JRequest::getVar("cid");
			$this->_getList( "DELETE FROM #__giftcode_giftcode WHERE giftcode_collection_id = '$cid'" );
			$this->_getList( "DELETE FROM #__giftcode_collection WHERE id = '$cid'" );
			$this->_getList( "DELETE FROM #__giftcode_collection_setting WHERE giftcode_collection_id = '$cid'" );
		}
		 
		foreach($giftcodes as $giftcode){
			$row =& $this->getTable('Giftcode_giftcode');
			$row->id = '';
			$row->giftcode = $giftcode;
			$row->giftcode_category_id = $giftcode_collection['color'];

			foreach ($this->getLastCollectionSetting() as $idCollectionSetting) {
				$row->giftcode_setting_id = $idCollectionSetting->id;
			}

			$row->created_date_time = date('Y-m-d G:i:s');
			$row->published = 0;
			$row->giftcode_queue_id = '';
			$row->redeemed = '';

			foreach ($this->getLastCollection() as $idCollection) {
				$row->giftcode_collection_id = $idCollection->id;
			}
			 
			if (!$row->check()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			 
			if (!$row->store()) {
				$this->setError( $row1->getErrorMsg() );
				return false;
			}
		}
		 
		return $setid;
	}
	 
	function remove($code_setting_id) {
		$this->_getList( "DELETE FROM #__giftcode_collection WHERE id = '".$code_setting_id."'" );
		$this->_getList( "DELETE FROM #__giftcode_collection_setting WHERE giftcode_collection_id = '".$code_setting_id."'" );
		$this->_getList( "DELETE FROM #__giftcode_giftcode WHERE giftcode_collection_id = '".$code_setting_id."'" );
	}

	function getlast(){
		$id = $this->_db->insertid();
		return $id;
	}

	function published($id) {
		$db = JFactory::getDBO();
		$query = "UPDATE #__giftcode_collection SET published='1' WHERE id=" . $id;
		$db->setQuery($query);
		if (!$db->query()) {
			echo "<script>alert('" . $db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		}
	}

	function published_giftcode($id) {
		$db = JFactory::getDBO();
		$query = "UPDATE #__giftcode_giftcode SET published='1' WHERE giftcode_collection_id=" . $id;
		$db->setQuery($query);
		if (!$db->query()) {
			echo "<script>alert('" . $db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		}
	}

	function unpublished($id) {
		$db = JFactory::getDBO();
		$query = "UPDATE #__giftcode_collection SET published='0' WHERE id=" . $id;
		$db->setQuery($query);
		if (!$db->query()) {
			echo "<script>alert('" . $db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		}
	}

	function unpublished_giftcode($id) {
		$db = JFactory::getDBO();
		$query = "UPDATE #__giftcode_giftcode SET published='0' WHERE giftcode_collection_id=" . $id;
		$db->setQuery($query);
		if (!$db->query()) {
			echo "<script>alert('" . $db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		}
	}

	function getGiftcodeOwner($gcid) {
		if (empty($this->_owner)) {
			$this->_owner = $this->_getList("SELECT * FROM #__giftcode_collection WHERE id = '".$gcid."'");
		}
		return $this->_owner;
	}

	function getUser($id) {
		if (empty($this->_user)) {
			$this->_user = $this->_getList("SELECT * FROM #__users WHERE id = '".$id."'");
		}
		return $this->_user;
	}

	function get_giftcodes($giftcode_category_id) {
		if (empty($this->_giftcodes)) {
			$this->_giftcodes = $this->_getList("SELECT * FROM #__giftcode_giftcode WHERE giftcode_category_id = ".$giftcode_category_id);
		}
		return $this->_giftcodes;
	}

	function save_renew($cids, $color_id) {
		$row =& $this->getTable('Giftcode_renew_schedule');
		$row->giftcode_color_id = $color_id;
		$row->created = date('Y-m-d G:i:s');
		$row->modified = date('Y-m-d G:i:s');

		foreach ($cids as $cid) {
			if ($cid == 1) { $row->monday = 1; }
			if ($cid == 2) { $row->tuesday = 1; }
			if ($cid == 3) { $row->wednesday = 1; }
			if ($cid == 4) { $row->thursday = 1; }
			if ($cid == 5) { $row->friday = 1; }
			if ($cid == 6) { $row->saturday = 1; }
			if ($cid == 7) { $row->sunday = 1; }
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}
	}

	function get_renew_schedule($color_id) {
		$db = JFactory::getDBO();
		$query = "
      SELECT * FROM ".$db->quoteName('#__giftcode_renew_schedule')."
        WHERE ".$db->quoteName('giftcode_color_id')." = ".$db->quote($color_id).";
      ";
		$db->setQuery($query);
		$renew_schedule = $db->loadObject();
		return $renew_schedule;
	}

	function getSchedule($color_id){
		$db = JFactory::getDBO();
		$query = "
      SELECT * FROM ".$db->quoteName('#__giftcode_renew_schedule')."
        WHERE ".$db->quoteName('giftcode_color_id')." = ".$db->quote($color_id).";
      ";
		$db->setQuery($query);
		$renew_schedule = $db->loadObjectList();
		return $renew_schedule;
	}

	function update_renew($cids, $color_id) {
		$created = JRequest::getVar("created");
		$this->_getList( "DELETE FROM #__giftcode_renew_schedule WHERE giftcode_color_id = '".$color_id."'" );
		$row =& $this->getTable('Giftcode_renew_schedule');
		$row->giftcode_color_id = $color_id;
		$row->created = $created == "" ? date('Y-m-d G:i:s') : $created;
		$row->modified = date('Y-m-d G:i:s');

		foreach ($cids as $cid) {
			if ($cid == 1) { $row->monday = 1; }
			if ($cid == 2) { $row->tuesday = 1; }
			if ($cid == 3) { $row->wednesday = 1; }
			if ($cid == 4) { $row->thursday = 1; }
			if ($cid == 5) { $row->friday = 1; }
			if ($cid == 6) { $row->saturday = 1; }
			if ($cid == 7) { $row->sunday = 1; }
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}
	}

	# Checking whether today is the schedule for renew or not
	function checking_renew_schedule($color_id) {
		$db = JFactory::getDBO();
		if (empty($this->_renew_schedule)) {
			$this->_renew_schedule = $this->_getList("
      SELECT * FROM ".$db->quoteName("#__giftcode_renew_schedule")." 
      WHERE ".$db->quoteName("giftcode_color_id")." = $color_id");
		}
		return $this->_renew_schedule;
	}
	 
	function checking_schedule_created($color_id) {
		$db = JFactory::getDBO();
		if (empty($this->_checking_schedule_created)) {
			$this->_checking_schedule_created = $this->_getList("
      SELECT * FROM ".$db->quoteName("#__giftcode_schedule_created")." 
      WHERE ".$db->quoteName("color_id")." = $color_id
      AND ".$db->quoteName("created_date")." = ".date('Y-m-d'));
		}
		return $this->_checking_schedule_created;
	}

	function get_giftcode_collections($color_id) {
		if (empty($this->_giftcode_collections)) {
			$this->_giftcode_collections = $this->_getList("SELECT * FROM #__giftcode_collection WHERE color_id = $color_id");
		}
		return $this->_giftcode_collections;
	}

	function get_giftcode_collection($id) {
		if (empty($this->_giftcode_collection)) {
			$this->_giftcode_collection = $this->_getList("
      SELECT * FROM #__giftcode_collection WHERE id = $id"
			);
		}
		return $this->_giftcode_collection;
	}

	function checking_renew_status($color_id) {
		if (empty($this->_renew_status)) {
			$this->_renew_status = $this->_getList("
      SELECT * FROM #__giftcode_collection 
      WHERE renew_status IS NOT TRUE 
      AND color_id = $color_id"
			);
		}
		return $this->_renew_status;
	}

	function checking_exist_renewed($color_id, $day) {
		if (empty($this->_exist_renewed)) {
			$this->_exist_renewed = $this->_getList("
      SELECT * FROM #__giftcode_schedule_created
      WHERE created_date = '$day'
      AND color_id = $color_id"
			);
		}
		return $this->_exist_renewed;
	}

	function checking_schedule_day($color_id, $day) {
		$db = JFactory::getDBO();
		if (empty($this->_schedule_day)) {
			$this->_schedule_day = $this->_getList("
      SELECT * FROM #__giftcode_renew_schedule
      WHERE giftcode_color_id = '$color_id'
      AND ".$db->quoteName("$day")." IS TRUE"
      );
		}
		return $this->_schedule_day;
	}

	function updating_renew_status($id) {
		$db = JFactory::getDBO();
		$giftcode_collection =& $this->getTable('Giftcode_collection');
		$giftcode_collection->id = $id;
		$giftcode_collection->renew_status = 1;
		$giftcode_collection->published = 0;
		$db->updateObject("#__giftcode_collection", $giftcode_collection, 'id', false);
		$query = "UPDATE #__giftcode_giftcode SET renew_status='1' WHERE giftcode_collection_id=" . $id;
		$db->setQuery($query);
		if (!$db->query()) {
			echo "<script>alert('" . $db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		}
	}

	function updating_schedule_created($giftcode_collection_id, $color_id, $day) {
		$db = JFactory::getDBO();
		$giftcode_collection =& $this->getTable('Giftcode_schedule_created');
		$giftcode_collection->created_date = $day;
		$giftcode_collection->color_id = $color_id;
		$giftcode_collection->giftcode_collection_id = $giftcode_collection_id;

		if (!$giftcode_collection->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$giftcode_collection->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}
	}

	function checking_unrenewed_giftcode($color_id) {
		$db = JFactory::getDBO();
		if (empty($this->_unrenewed_giftcode)) {
			$this->_unrenewed_giftcode = $this->_getList("
      SELECT * FROM #__giftcode_collection
      WHERE color_id = '$color_id'
      AND renew_status IS NOT TRUE"
			);
		}		
		return $this->_unrenewed_giftcode;
	}

	function find_unrenewed_giftcode_by_id($id) {
		$db = JFactory::getDBO();
		if (empty($this->_unrenewed_giftcode_by_id)) {
			$this->_unrenewed_giftcode_by_id = $this->_getList("
      SELECT * FROM #__giftcode_collection
      WHERE id = '$id'
      AND renew_status IS NOT TRUE"
			);
		}
		return $this->_unrenewed_giftcode_by_id;
	}

	function get_schedule_created_data($color_id, $giftcode_collection_id) {
		$db = JFactory::getDBO();
		if (empty($this->_schedule_created)) {
			$this->_schedule_created = $this->_getList("
      SELECT * FROM #__giftcode_schedule_created
      WHERE color_id = '$color_id'
      AND giftcode_collection_id = '$giftcode_collection_id'"
			);
		}
		return $this->_schedule_created;
	}

	function getGiftCodeCat($category_id){

		$db = JFactory::getDBO();

		$query = "SELECT * FROM #__giftcode_category WHERE ".$db->QuoteName('id')."='".$category_id."'";

		$db->setQuery($query);

		$rows = $db->loadObjectList();

		foreach($rows as $row){

			return $row;

		}
	}

	public function getSymbolPices($pieces){

		$db = JFactory::getDBO();

		$query = "SELECT * FROM #__symbol_symbol WHERE pieces='$pieces'";

		$db->setQuery($query);

		$rows = $db->loadObjectList();

		foreach($rows as $row){
			 
			return $row;

		}
	}

	public function getGiftCodePackage($package_id){

		$db	= JFactory::getDBO();

		$query = "SELECT * FROM #__giftcode_category WHERE ".$db->QuoteName('package_id')."='".$package_id."' LIMIT 0,1";

		$db->setQuery($query);

		$rows = $db->loadObjectList();

		return $rows;
	}

	function get_name($user_id) {
		$db = JFactory::getDBO();
		$query = "
      SELECT ".$db->quoteName('name')."
	FROM ".$db->quoteName('#__users')."
	WHERE ".$db->quoteName('id')." = ".$db->quote($user_id).";
      ";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

	public function getGiftCodeCollection($gcid){

		$db 	= & JFactory::getDBO();

		$query 	= "SELECT * FROM #__giftcode_collection WHERE ".$db->QuoteName('id')."='".$gcid."'";

		$db->setQuery($query);

		$rows 	= $db->loadObjectList();

		foreach($rows as $row){

			return $row;

		}

	}

	public function cloneGiftCode($data){

		$db		= & JFactory::getDBO();

		$query 	= "INSERT INTO #__giftcode_collection (".$db->QuoteName('color_id').",".$db->QuoteName('total_giftcodes').
    ",".$db->QuoteName('created_date_time').",".$db->QuoteName('modified_date_time').",".
		$db->QuoteName('published').",".$db->QuoteName('user_id').",".$db->QuoteName('renew_status').",".$db->QuoteName('package_id').
    ") VALUES ('".$data->color_id."','".$data->total_giftcodes."','".$data->created_date_time."','".$data->modified_date_time."','".
		$data->published."','".$data->user_id."','".$data->renew_status."','".$data->package_id."')";

		$db->setQuery($query);

		if($db->query()){
			return true;
		}
	}

}