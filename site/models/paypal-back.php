<?php
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );
 

class AwardpackageModelPaypal extends JModelLegacy
{
	
	function details_info($id){
		$info = $this->info($id);
		$info->business = $this->invar('business','seller_1315922650_biz@gmail.com');
		$info->amount = $info->credit;
		return $info;
	}
	
	function info($id){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM `#__ap_donation_transactions` WHERE transaction_id = '$id'");
		$row = $db->loadObject();
		return $row;
	}
	
	function update($data){
		$db =& JFactory::getDBO();
		$log = '';
		foreach($data as $k => $v){
			$log .= 'POST '.$k.' => '.$v."\n";
		}
		$query = "UPDATE `#__ap_donation_transactions` SET status = '$data[payment_status]', log = '$log', transaction = '".'Transaction ID '.$data['item_number'].' by '.$data['payer_email']."' WHERE transaction_id = '$data[item_number]' AND user_id = '$data[custom]' LIMIT 1";
		$db->setQuery($query);
		$result = $db->query();			
	}
	
	function currency_symbol($code){
		if($this->invar('currency_unit',0)==1){
			$symbol = '&cent;';
			}else{
			switch($code){
				case 'AUD':
				case 'USD' :
					$symbol = '$';
				break;
				case 'EUR':
					$symbol = '&euro;';
				break;
			}	
		}
			return $symbol;
	}
	
	function invar($name,$value){
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM ".$db->nameQuote('#__ap_donation_variables')." WHERE name = '$name' LIMIT 1";
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
	
	function save(){
		$db =& JFactory::getDBO();
		$currency_unit = JRequest::getVar('currency_unit');
		$poll_donation_amount = JRequest::getVar('poll_donation_amount');
		$survey_donation_amount = JRequest::getVar('survey_donation_amount');
		
		if($currency_unit){
			$_POST['poll_donation_amount'] = $poll_donation_amount/100;
			$_POST['survey_donation_amount'] = $survey_donation_amount/100;			
		}else{
			$_POST['currency_unit'] = '0';
		}
		//$donation_amount = JRequest::getVar('poll_donation_amount');
		foreach($_POST as $key => $value){
		  if($key == 'poll_donation_amount' || $key == 'survey_donation_amount'){
			if(is_numeric($value) && $value > 0){
				}else{
					JError::raiseWarning(500, JText::_('Invalid amount, the amount should be greater than zero'));
					$key = '';
			}
		  }
		  if($key == 'business'){
			if(preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i",$value)){
				}else{
					JError::raiseWarning(500, JText::_('Invalid email address : '.$value));
					$key = '';
			}
		  }
		if($key){  
		$query = "REPLACE INTO ".$db->nameQuote('#__ap_donation_variables')." (name,value) VALUES('$key','$value')";
		$db->setQuery($query);
		$db->query();
		}
		}
		$message = JText::_('Your setting has been saved...');
		$this->setRedirect('index.php?option=com_communitypolls&view=paypal', $message);
	}	
}