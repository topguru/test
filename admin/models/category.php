<?php
//resdirect
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class AwardpackageModelCategory extends JModelLegacy
{
	function save_settings($post){
		$db =& JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		$query->update("#__ap_categories");
		$query->set("unlocked='".$post['islocked']."'");
		$query->where("setting_id='".$post['id']."'");
		$db->setQuery($query);
		$db->query();
	}

	public function getCategory($package_id){

		$db			= &JFactory::getDBO();
			
		$query		= $db->getQuery(TRUE);

		$query->select("*");

		$query->from("#__ap_categories");

		$query->where("package_id='".$package_id."'");

		$query->order("category_id ASC ");

		$db->setQuery($query);

		$rows = $db->loadObjectList();

		return $rows;
	}

	function save_categories($data,$id){
		$db 		=	& JFactory::getDBO();
		$query		=	$db->getQuery(TRUE);
		$query->update("#__ap_categories");
		$query->set("colour_code='".$data['colour_code']."'");
		$query->set("category_name='".$data['category_name']."'");
		$query->set("giftcode_quantity = '".$data['giftcode_quantity']."' ");
		$query->set("unlocked='0'");
		$query->where("setting_id=".$id." ");

		$db->setQuery($query);
		return $db->query();
	}

	function save_giftcode($data){
		$db		= &JFactory::getDBO();
		$query	= $db->getQuery(TRUE);
		$query->update("#__giftcode_category");
		$query->set("color_code='".$data['colour_code']."'");
		$query->set("name='".$data['category_name']."'");
		$db->setQuery($query);
		return $db->query();
	}

	function update_giftcode($data,$id) {
		$db		= &JFactory::getDBO();
		$query	= $db->getQuery(TRUE);
		$query->update("#__giftcode_category");
		$query->set("color_code='".$data['colour_code']."'");
		$query->set("name='".$data['category_name']."'");
		$query->where("id='".$id."'");
		$db->setQuery($query);
		return $db->query();
	}

	function invar($name,$value){
		$db			= &JFactory::getDBO();
		$query		= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_donation_variables");
		$query->where("name='".$name."'");
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

	function save_price($price,$id,$price_type){
		$db =& JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		$query->update($db->QuoteName('#__ap_categories'));
		if($price_type=='poll'){
			$query->set("poll_price='".$price*0.01."'");
		}else{
			$query->set("survey_price='".$price*0.01."'");
		}
		$query->where("setting_id='".$id."'");
		$db->setQuery($query);
		return $db->query();
	}

	function save_quiz($price,$id){
		$db =& JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		$query->update($db->QuoteName('#__ap_categories'));
		$query->set("quiz_price='".$price*0.01."'");
		$query->where("setting_id='".$id."'");
		$db->setQuery($query);
		return $db->query();
	}

	function save_user_quiz($price,$id){
		$db =& JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		$query->update($db->QuoteName('#__ap_categories'));
		$query->set("user_quiz_price='".$price*0.01."'");
		$query->where("setting_id='".$id."'");
		$db->setQuery($query);
		return $db->query();
	}

	function save_user_survey($price,$id){
		$db =& JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		$query->update($db->QuoteName('#__ap_categories'));
		$query->set("user_survey_price='".$price*0.01."'");
		$query->where("setting_id='".$id."'");
		$db->setQuery($query);
		return $db->query();
	}

	function published($id) {
		$query = $this->_db->getQuery(TRUE);
		$query->update($this->_db->QuoteName('#__giftcode_category'));
		$query->set("published='1'");
		$query->where("id='$id'");

		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			echo "<script>alert('" . $this->_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		} else {
			$QUpdate = $this->_db->getQuery(TRUE);
			$QUpdate->update($this->_db->QuoteName('#__ap_categories'));
			$QUpdate->set("published='1'");
			$QUpdate->where("setting_id='".$id."'");

			$this->_db->setQuery($QUpdate);
			$this->_db->query();
		}
	}

	function unpublished($id) {
		$query = $this->_db->getQuery(TRUE);
		$query->update($this->_db->QuoteName('#__giftcode_category'));
		$query->set("published='0'");
		$query->where("id='".$id."'");
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			echo "<script>alert('" . $this->_db->getErrorMsg() . "'); window.history.go(-1);</script>\n";
			exit();
		} else {
			$QUpdate = $this->_db->getQuery(TRUE);
			$QUpdate->update($this->_db->QuoteName('#__ap_categories'));
			$QUpdate->set("published='0'");
			$QUpdate->where("setting_id='$id'");
			$this->_db->setQuery($QUpdate);
			$this->_db->query();
		}
	}

}
