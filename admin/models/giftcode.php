<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class AwardpackageModelGiftcode extends JModelLegacy {

    function __construct($config = array()) {
        parent::__construct($config);
        $this->_db = JFactory::getDBO();
    }

    function getGiftCode($package_id) {
        //$db	= JFactory::getDBO();
        $query = $this->_db->getQuery(TRUE);
        $query->select('*');
        $query->from($this->_db->QuoteName('#__giftcode_category'));
        $query->where($this->_db->QuoteName('package_id') . "='" . $package_id . "'");
		$query->order('category_id ASC');
        //$query 	= "SELECT * FROM #__giftcode_category WHERE ".$db->QuoteName('package_id')."='".$package_id."' ORDER BY category_id ASC";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        return $rows;
    }

    function edit($id) {
        //$db =& JFactory::getDBO();
        $query = $this->_db->getQuery(TRUE);
        $query->update($this->_db->QuoteName('#__giftcode_category'));
        $query->set("locked='0'");
        $query->where($this->_db->QuoteName('id') . "='" . $id . "'");
        //$query = "UPDATE #__giftcode_category SET locked='0' WHERE id=" . $id;
        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            echo "<script>alert('" . $this->_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
            exit();
        } else {
            return true;
        }
    }

    function save($id, $name, $color_code) {
        //$db = & JFactory::getDBO();
        $q_check = $this->_db->getQuery(true);
        $q_check->select('*');
        $q_check->from($this->_db->QuoteName('#__giftcode_category'));
        $q_check->where($this->_db->QuoteName('id')."='$id'");
        $q_check->where($this->_db->QuoteName('locked')."='0'");
        $q_check->order('category_id ASC');
        //$q_check = "SELECT * FROM #__giftcode_category WHERE " . $db->QuoteName('id') . "='" . $id . "' AND locked='0' ORDER BY category_id ASC";
        $this->_db->setQuery($q_check);
        $this->_db->query();
        $numRows = $this->_db->getNumRows();
        if ($numRows > 0):
            $query = $this->_db->getQuery(TRUE);
            $query->update($this->_db->QuoteName('#__giftcode_category'));
            $query->set("locked='1'");
            $query->where("id='".$id."'");
            //$query = "UPDATE #__giftcode_category SET locked='1' WHERE id=" . $id;
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                echo "<script>alert('" . $this->_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
                exit();
            }
            $query = $this->_db->getQuery(TRUE);
            $query->update($this->_db->QuoteName('#__giftcode_category'));
            $query->set("name='".$name."'");
            $query->where("id='".$id."'");
            //$query = "UPDATE #__giftcode_category SET name='" . $name . "' WHERE id=" . $id;
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                echo "<script>alert('" . $this->_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
                exit();
            }
            $query = $this->_db->getQuery(TRUE);
            $query->update($this->_db->QuoteName('#__giftcode_category'));
            $query->set("color_code='".$color_code."'");
            $query->where($this->_db->QuoteName('id')."='".$id."'");
            //$query = "UPDATE #__giftcode_category SET color_code='" . $color_code . "' WHERE id=" . $id;
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                echo "<script>alert('" . $this->_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
                exit();
            }
        endif;
    }

    function published($id) {
        $query = $this->_db->getQuery(TRUE);
        $query->update($this->_db->QuoteName('#__giftcode_category'));
        $query->set("published='1'");
        $query->where("id='$id'");
        //$db = & JFactory::getDBO();
        //$query = "UPDATE #__giftcode_category SET published='1' WHERE id=" . $id;
        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            echo "<script>alert('" . $this->_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
            exit();
        } else {
            $QUpdate = $this->_db->getQuery(TRUE);
            $QUpdate->update($this->_db->QuoteName('#__ap_categories'));
            $QUpdate->set("published='1'");
            $QUpdate->where("setting_id='".$id."'");
            //$QUpdate = "UPDATE #__ap_categories SET published='1' WHERE setting_id='$id'";
            $this->_db->setQuery($QUpdate);
            $this->_db->query();
        }
    }

    function unpublished($id) {
        //$db = & JFactory::getDBO();
        $query = $this->_db->getQuery(TRUE);
        $query->update($this->_db->QuoteName('#__giftcode_category'));
        $query->set("published='0'");
        $query->where("id='".$id."'");
        //$query = "UPDATE #__giftcode_category SET published='0' WHERE id=" . $id;
        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            echo "<script>alert('" . $this->_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
            exit();
        } else {
            $QUpdate = $this->_db->getQuery(TRUE);
            $QUpdate->update($this->_db->QuoteName('#__ap_categories'));
            $QUpdate->set("published='0'");
            $QUpdate->where("setting_id='$id'");
            //$QUpdate = "UPDATE #__ap_categories SET published='0' WHERE setting_id='$id'";
            $this->_db->setQuery($QUpdate);
            $this->_db->query();
        }
    }
	
	function checkSchedule($package_id,$category_id){
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__ap_award_schedule'));
		$query->where("category_id='".$category_id."'");
		$query->where("package_id='".$package_id."'");
		//$query->where($day."='1'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObject();
		return $rows;
	}
	
	function insertSchedule($package_id,$category_id,$day,$active){
		$query = $this->_db->getQuery(TRUE);
		$query->insert($this->_db->QuoteName('#__ap_award_schedule'));
		$query->set("category_id='".$category_id."'");
		$query->set("package_id='".$package_id."'");
		$query->set($day."='".$active."'");
		$this->_db->setQuery($query);
		if($this->_db->query()){
			return TRUE;
		}
	}
	
	function updateSchedule($package_id,$category_id,$day,$active){
		$query = $this->_db->getQuery(TRUE);
		$query->update($this->_db->QuoteName('#__ap_award_schedule'));
		$query->set($day."='".$active."'");
		$query->where("package_id='".$package_id."'");
		$query->where("category_id='".$category_id."'");
		$this->_db->setQuery($query);
		if($this->_db->query()){
			return TRUE;
		}
	}
	
	function getAwardSymbol($categoryID){
  		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('count(id) AS total')
       		->from($db->QuoteName('#__gc_recieve_user'))
     		->where("category_id='".$categoryID."'")
	    	->where("status='1'");
		$db->setQuery($query);
		$row = $db->loadObject();
		return $row;		
	  }
	  
	  function getAllUserGiftcodes($package_id,$user_id){
  		$query = "select COUNT(b.category_name) as total, SUM(a.status) as jml, a.*, b.colour_code, b.category_name, c.giftcode 
		 from  #__gc_recieve_user a
		 LEFT JOIN #__ap_categories b ON b.setting_id = a.category_id
		 LEFT JOIN #__giftcode_giftcode c ON c.id = a.gcid 
		 WHERE a.user_id = '" .$user_id. "' 
		Group by b.category_name";
		 $this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	  }
	  
	  function getTotalUserGiftcodes($package_id,$user_id){
  		$query = "select COUNT(b.category_name) as total, COUNT(a.status) as accepted, a.*, b.colour_code, b.category_name, c.giftcode 
		 from  #__gc_recieve_user a
		 LEFT JOIN #__ap_categories b ON b.setting_id = a.category_id
		 LEFT JOIN #__giftcode_giftcode c ON c.id = a.gcid 
		 WHERE a.user_id = '" .$user_id. "' 
		Group by b.category_name";
		 $this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	  }
}