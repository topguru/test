<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
class AwardpackageModelUfunding extends JModelLegacy {

	function __construct() {
		parent::__construct ();
	}

	function getPaypall($data){
		$query = "select business from #__paypal_config WHERE package_id = '".$data."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getTotalSymbol($userId,$status, $limit, $limitstart, $filter_start, $filter_end){
		$query = "	SELECT * 
					FROM #__gc_recieve_user  
					WHERE user_id='".$userId."' AND status='".$status."' 
				    LIMIT ".$limitstart.", ".$limit."
					";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	public function getSymbolSymbolPrize(){
		$query = "	SELECT a.symbol_prize_id, c.prize_name, c.prize_image, c.prize_value, b.symbol_name, b.symbol_image, b.pieces, b.cols, b.rows, d.presentation_id, d.status, c.unlocked_status 
					FROM #__symbol_symbol_prize a 
					LEFT JOIN #__symbol_symbol b ON b.symbol_id = a.symbol_id 
					LEFT JOIN #__symbol_prize c ON c.id = a.id
					INNER JOIN #__symbol_presentation d ON d.presentation_id = a.presentation_id";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function getUserId($userid){
		$query = "select * from #__ap_useraccounts where id='".$userid."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function getShoppingCredit($packageId){
		$query = "select * from #__shopping_credit_from_donation WHERE package_id = '".$packageId."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}


	function getPaymentMethod(){
		$query = "select * from #__ap_payment_options order by `option` asc";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}

	function updateHistory($userId, $packageId, $amount, $type, $method, $username){
		$now = JFactory::getDate();
		$db = JFactory::getDbo();
		switch ($type) {
			case 'funding' :
				$fund = $this->getFund($userId, $packageId);
				if($fund != null){
					$fundingId = $fund->funding_id;
				}
				$query = "insert into #__funding_history(funding_id, description, credit, debit, created_date, method, transaction_type, status)
					values ('".$fundingId."', '". $username . " - Funding - Funds Added '  , '".$amount."', '0', '".$now."', '".$method."', 'FUNDING', 'PENDING')";				
				$db->setQuery($query);
				$db->query();
				break;
			case 'withdraw' :
				$fund = $this->getFund($userId, $packageId);
				if($fund != null){
					$fundingId = $fund->funding_id;
				}
				$query = "insert into #__funding_history(funding_id, description, credit, debit, created_date, transaction_type, status)
					values ('".$fundingId."', '". $username . " - Withdraw - Withdraw Taken', '0', '".$amount."',  '".$now."', 'WITHDRAW', null)";
				$db->setQuery($query);
				$db->query();
				break;
			
		}

	}

/*
case 'donation' :
				$fund = $this->getFund($userId, $packageId);
				if($fund != null) {
					$fundingId = $fund->funding_id;
				}
				$query = "insert into #__funding_history(funding_id, description, credit, debit, created_date, method, transaction_type, status)
					values ('".$fundingId."', '". $username . " - Donation - Add Donation ', '0', '".$amount."', '".$now."', '".$method."' , 'DONATION', 'PENDING')";
				$db->setQuery($query);
				$db->query();
				break;
*/
	function getFund($userId, $packageId){
		$query = "
				SELECT fu.* FROM #__funding_user fu 
				WHERE fu.`user_id` = '".$userId."' AND fu.`package_id` = '".$packageId."'		
			";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();

		if(!empty($results)) {
			return $results[0];
		}
		return null;
	}

	function getWithdraw($userId, $packageId){
		$query = "
				SELECT wu.* FROM #__withdraw_user wu 
				WHERE wu.`user_id` = '".$userId."' AND wu.`package_id` = '".$packageId."'		
			";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		if(!empty($results)) {
			return $results[0];
		}
		return null;
	}

	function getDonation($userId, $packageId){
		$query = "
				SELECT dt.* FROM #__ap_donation_transactions dt 
				WHERE dt.`user_id` = '".$userId."' AND dt.`package_id` = '".$packageId."'		
			";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		if(!empty($results)) {
			return $results[0];
		}
		return null;
	}

	function getAllHistory($userId, $packageId,$trans){
		$rets = array();
		if ($trans == 'All' ){
		$query = "
				SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`user_id` = '".$userId."' AND fu.`package_id` = '".$packageId."' 
				";			
				}
				 else
				 {
		$query = "
				SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`user_id` = '".$userId."' AND fu.`package_id` = '".$packageId."' AND fh.transaction_type='".$trans."'				
				";			
				}
				
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;	
	}
	
	function getAllFundHistory($userId, $packageId,$trans, $limit, $limitstart, $filter_start, $filter_end){
		$rets = array();
		if ($trans == 'All' ){
		$query = "
				SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`user_id` = '".$userId."' AND fu.`package_id` = '".$packageId."' 
				AND fh.`created_date` BETWEEN '".$filter_start."' AND '".$filter_end."' 
				";			
				}
				 else
				 {
		$query = "
				SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`user_id` = '".$userId."' AND fu.`package_id` = '".$packageId."' AND fh.transaction_type='".$trans."'
				AND fh.`created_date` BETWEEN '".$filter_start."' AND '".$filter_end."' 
				LIMIT ".$limitstart.", ".$limit."
				";			
				}
				
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;	
		
	}

function getAllHistoryTotal($userId, $packageId,$trans){
		$rets = array();
		if ($trans == 'All' ){
		$query = "
				SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`user_id` = '".$userId."' AND fu.`package_id` = '".$packageId."' 
				";			
				}
				 else
				 {
		$query = "
				SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`user_id` = '".$userId."' AND fu.`package_id` = '".$packageId."' AND fh.transaction_type='".$trans."'
				";			
				}
				
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		$total = count($results);
		return $total;	
	}


	function getFundingHistory($userId, $packageId){
		$query = "
				SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`ap_account_id` = fu.`user_id`
				WHERE fu.`user_id` = '".$userId."' AND fu.`package_id` = '".$packageId."' AND fh.`transaction_type` ='FUNDING'
				";			
				
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;
	}

	function getWithdrawalHistory($userId, $packageId){
		$query = "
				SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`ap_account_id` = fu.`user_id`
				WHERE fu.`user_id` = '".$userId."' AND fu.`package_id` = '".$packageId."' AND fh.`transaction_type` ='WITHDRAW'
				";			
				
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;
	}

	function getDonationHistory($userId, $packageId,$limit, $limitstart, $filter_start, $filter_end){
		$query = "
				SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__donation_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`user_id` = '".$userId."' AND fu.`package_id` = '".$packageId."' 
				AND fh.`created_date` BETWEEN '".$filter_start."' AND '".$filter_end."' 
				LIMIT ".$limitstart.", ".$limit."
				";			
				
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;
	}
	
	function getDonationHistoryTotal($userId, $packageId){
		$query = "
				SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__donation_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`user_id` = '".$userId."' AND fu.`package_id` = '".$packageId."' 
				";			
				
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		$total = count($results);
		return $total;
	}

	function saveFunding($data){
		$db 	= JFactory::getDbo();
		$user	= JFactory::getUser();
		$now = JFactory::getDate();
		$fund = $this->getFund($data['user_id'], $data['package_id']);
		if($fund != null) {
			$query = "update #__funding_user set funding_last_update = '".$now."' where funding_id = '".$fund->funding_id."' ";
			$db->setQuery($query);
			if ($db->query()) {
				return true;
			} else {
				return false;
			}
		} else {
			$query = "insert into #__funding_user(funding_last_update, package_id, user_id)
			values ('".$now."', '".$data['package_id']."', '".$data['user_id']."') ";
			$db->setQuery($query);
			if ($db->query()) {
				return true;
			} else {
				return false;
			}
		}

	}

	function saveWithdraw($data){
		$db 	= JFactory::getDbo();
		$user	= JFactory::getUser();
		$now = JFactory::getDate();

		$withdraw = $this->getWithdraw($data['user_id'], $data['package_id']);
		if($withdraw != null) {
			$query = "update #__withdraw_user set withdraw_last_update = '".$now."' where withdraw_id = '".$withdraw->withdraw_id."'  ";
			$db->setQuery($query);
			if ($db->query()) {
				return true;
			} else {
				return false;
			}
		} else {
			$query = "insert into #__withdraw_user(withdraw_last_update, package_id, user_id)
			values ('".$now."', '".$data['package_id']."', '".$data['user_id']."') ";
			$db->setQuery($query);
			if ($db->query()) {
				return true;
			} else {
				return false;
			}
		}
	}
	
}