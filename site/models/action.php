<?php
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );
 

class AwardpackageModelAction extends JModelLegacy
{

	function __construct(){
	}

 	public function info($id){
		$db = JFactory::getDBO();
		$query = "SELECT * FROM `#__ap_categories` WHERE category_id = '$id'";
		$db->setQuery($query);
		$row = $db->loadObject();
		return $row;
	}
	
 	public function transaction_info($id){
		$db = JFactory::getDBO();
		$query = "SELECT * FROM `#__ap_donation_transactions` WHERE transaction_id = '$id'";
		$db->setQuery($query);
		$row = $db->loadObject();
		return $row;
	}
	
	function delete($post_id){
		$db =& JFactory::getDBO();	
		$query = "DELETE FROM `#__appl_posts` WHERE post_id = '".$post_id."'";
		//echo $query;
		$db->setQuery($query);
		$result = $db->query();		
	}
	
	function save_transaction($data){
	$db =& JFactory::getDBO();	
	$fields = $this->show_fields('ap_donation_transactions');  
		foreach($data as $k => $v){
			if(in_array($k,$fields)){
				$input[$k] = $db->escape($v);
			}
		}
		if($input['transaction_id']){
			foreach($input as $k => $v){
				$up[] = "$k = '$v'";
			}
			$query = "UPDATE `#__ap_donation_transactions` SET ".implode(", ",$up)." WHERE transaction_id = '".$input['transaction_id']."'";
			//echo $query;
			$db->setQuery($query);
			$result = $db->query();	
			$id = 0;
		}else{
			$query = "INSERT INTO `#__ap_donation_transactions` (".implode(',',array_keys($input)).") VALUES ('".implode('\',\'',array_values($input))."')";
			//echo $query;
			$db->setQuery($query);
			$db->query();	
			if($db->insertid()>0){
				$id = $db->insertid();
				$this->update_title($id);
				$this->save_details($id);
			}else{
				$id = 0;
			}
		}
		return $id;
		
		//$app = &JFactory::getApplication();
		//$app->redirect('index.php?option=com_awardpackage&controller=donation');	
	}	
	
	function update_title($id){
		$db = JFactory::getDBO();				
		$query2 = "UPDATE `#__ap_donation_transactions` SET transaction = '".'Donation ID '.$id."' WHERE transaction_id = '".$id."'";
		//echo $query;
		$db->setQuery($query2);
		$db->query();	
	}
	
	function save_details($transaction_id){
		$db =& JFactory::getDBO();
		foreach($_POST['category_id'] as $k => $v){
			//$subtotal = $subtotal+($_POST['donation_amount'][$k]*$_POST['quantity'][$k]);
			$query = "INSERT INTO `#__ap_donation_details` (transaction_id,category_id,donation_amount,quantity)
					  VALUES('$transaction_id','".$_POST[category_id][$k]."','".$_POST[donation_amount][$k]."','".$_POST[quantity][$k]."')";
			//echo $query;
			$db->setQuery($query);
			$db->query();					  
		}	
	}
	
	function view($id){
        $db =& JFactory::getDBO();
		$query = "SELECT * FROM `#__ap_donation_details` WHERE transaction_id = '". $id."' "  ;
			$db->setQuery($query);
			//echo $query;
			$rs = $db->loadObjectList();
			if(count($rs)>0){
				return $rs;
			}
	}
	
	function show_fields($table){
		$db = JFactory::getDBO();
		$db->setQuery("SHOW FIELDS FROM #__".$table);
		$fields = $db->loadResultArray();
		return $fields;
	}  
}