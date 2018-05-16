<?php
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );
 

class awardpackageModelHistory extends JModelLegacy
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
	
	function delete($id){
		$db =& JFactory::getDBO();	
		$query = "DELETE FROM `#__ap_donation_transactions` WHERE transaction_id = '".$id."'";
		$db->setQuery($query);
		$result = $db->query();		
	}
	
	function save_categories(){
	$db =& JFactory::getDBO();		
		$model = JModel::getInstance('settings','AwardpackageModel');			
		for($i=0;$i<=count(JRequest::getVar('category_id'))-1;$i++){
			if($_POST['donation_amount'][$i] > 0 ){ 		
				if($model->invar('currency_unit',0)==1){
					$amount[$i] = $_POST['donation_amount'][$i]/100;
				}else{
					$amount[$i] = $_POST['donation_amount'][$i];
				}
				$query = "UPDATE `#__ap_categories` SET 
				colour_code = '".$_POST['colour_code'][$i]."',
				category_name = '".$_POST['category_name'][$i]."',
				donation_amount = '".$amount[$i]."'
				WHERE category_id = '".$_POST['category_id'][$i]."'";
				$db->setQuery($query);
				$db->query();	
			}else{
				JFactory::getApplication()->enqueueMessage( JText::_($_POST['donation_amount'][$i]." is not valid entry"), 'error' );
			}
		}
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
	function update_category($data,$task){
		if($task=='publish'){
			$p = 1;
		}else{
			$p = 0;
		}
		if(count($data)>0){
			$db = JFactory::getDBO();				
			$query = "UPDATE `#__ap_categories` SET published = '$p' WHERE category_id in (".implode(",",$data).")";
			$db->setQuery($query);
			$db->query();			
		}
	}
	
	function update_title($id){
		$db = JFactory::getDBO();				
		$query = "UPDATE `#__ap_donation_transactions` SET transaction = '".'Donation ID '.$id."' WHERE transaction_id = '".$id."'";
		$db->setQuery($query);
		$db->query();	
	}
	
	function save_status($id,$status){
		$db = JFactory::getDBO();				
		$query = "UPDATE `#__ap_donation_transactions` SET status = '$status' WHERE transaction_id = '".$id."'";
		$db->setQuery($query);
		$db->query();	
	}
	
	function save_details($transaction_id){
		$db =& JFactory::getDBO();
		foreach($_POST['category_id'] as $k => $v){
			$query = "INSERT INTO `#__ap_donation_details` (transaction_id,category_id,donation_amount,quantity)
					  VALUES('$transaction_id','".$_POST[category_id][$k]."','".$_POST[donation_amount][$k]."','".$_POST[quantity][$k]."')";
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
	
	function iscent($value,$digit=0){
		if($value>0){
			if($this->invar('currency_unit','0')){
				return number_format(($value*100),$digit).' cents ('.$this->invar('currency_code','').')';
			}else{
				return number_format($value,2).' dollars ('.$this->invar('currency_code','').')';
			}
		}else{
			return '';
		}
	}	
	
	function invar($name,$value){
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM `#__ap_donation_variables` WHERE name = '$name' LIMIT 1";
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
}