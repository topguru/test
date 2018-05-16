<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.modelitem');
class AwardpackageUsersModelUdonation extends JModelLegacy {
	function __construct() {
		parent::__construct ();
	}

	function saveDonation($user_id, $package_id){
		$db 	= JFactory::getDbo();
		$user	= JFactory::getUser();
		$now = JFactory::getDate();	
		if( $this->checkIsExistDonation($user_id, $package_id)){
			$query = "insert into #__ap_donation_transactions (package_id, user_id, status)
				values ('".$package_id."', '".$user_id."', 'Successful donate')";
			$db->setQuery($query);
			$db->query();			
		} 
	}

function ClaimedHistory($user_id, $package_id, $value, $name){
		$db 	= JFactory::getDbo();

		$now = JFactory::getDate();	
			$query = "insert into #__ap_prize_claim (winner_id, package_id,prize_value, prize_name, claimed_date, claimed_status, send_status)
				values ('".$user_id."', '".$package_id."', '".$value."','".$name."', '".$now."','1','1')";
			$db->setQuery($query);
			$db->query();	
			return true;		
}
					
function getPaypall($data){
		$query = "select business from #__paypal_config WHERE package_id = '".$data."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}

function getShoppingCredit($packageId,$amount){
		$query = "select a.*, b.start_date, b.end_date, c.min_amount, c.max_amount
		  from #__shopping_credit_from_donation a 
		 INNER JOIN #__shopping_credit_plan_detail b ON b.uniq_key = a.uniq_key
		 		 INNER JOIN #__contribution_range c ON c.id = a.contribution_range
		   WHERE c.min_amount <= '".$amount."' AND c.max_amount >= '".$amount."'
		  AND a.package_id = '".$packageId."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
function getUserShoppingCredit($userid){
		$query = "select a.*
		  from #__shopping_record a 
		  WHERE a.user_id = '".$userid."' ";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		if(!empty($results)) {
			return $results[0];
		}
		return null;
	}
		
function getShoppingCreditUser($userid){
		$query = "select SUM(amount) as totalsc from #__shopping_record WHERE user_id ='".$userid."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
		
function getAllHistory($userId, $packageId,$trans){
		$rets = array();
		if ($trans == 'All' ){
		$query = "
				SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__donation_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`user_id` = '".$userId."' AND fu.`package_id` = '".$packageId."' 
				";			
				}
				 else
				 {
		$query = "
				SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__donation_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`user_id` = '".$userId."' AND fu.`package_id` = '".$packageId."' AND fh.transaction_type='".$trans."'
				";			
				}
				
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;	
	}
		
		function saveFunding($data){
		$db 	= JFactory::getDbo();
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

       function updateShoppingCredit($userid, $package_id, $amount, $method)	{
  		$now = JFactory::getDate();
		$db = JFactory::getDbo();
		$usersc = $this->getUserShoppingCredit($userid);
		$total_amount = $usersc->amount + $amount;
		if($usersc != null) {
						$query = "update #__shopping_record set amount = '".$total_amount."' where user_id = '".$userid."' ";					
					} else {
					$query = " insert into #__shopping_record
					(`date_recived`, `amount`, `unlocked`, `unlocked_date`, `unlocked_status`, `user_id`, `distribution_id`, `claimed_status`, `is_blocked`) VALUES ('".$now."', '".$amount."', '0', '".$now."', '0', '".$userid."', '0', '0', '0')";		
					}
				$db->setQuery($query);
				$db->query();
				}

						
	function updateHistory($userId, $packageId, $amount, $type, $method, $username){
		$now = JFactory::getDate();
		$db = JFactory::getDbo();
		switch ($type) {
		case 'prize' :
				$fund = $this->getFund($userId, $packageId);
				if($fund != null){
					$fundingId = $fund->funding_id;
				}
				$query = "insert into #__funding_history(funding_id, description, credit, debit, total_pending, total_plus,grand_total,created_date, method, transaction_type, status)
					values ('".$fundingId."', '". $username . " - Prize - Prize Claimed '  , '".$amount."', '0','".$total_pending."','".$total_plus."','".$grand_total."', '".$now."', '".$method."', 'PRIZE', 'PENDING')";				
				$db->setQuery($query);
				$db->query();
				break;
				
			case 'funding' :
				$fund = $this->getFund($userId, $packageId);
				if($fund != null){
					$fundingId = $fund->funding_id;
				}
							$totalA = $this->getTotalPending();
				if($totalA != null){
					$total_pending = $totalA->total_pending + $amount;
				}
				$totalB = $this->getTotalPlus();				
				if($totalB != null){
					$total_plus = $totalB->total_plus;
				}
				
				$grand_total = $total_pending + $total_plus;
				$query = "insert into #__funding_history(funding_id, description, credit, debit, total_pending, total_plus,grand_total,created_date, method, transaction_type, status)
					values ('".$fundingId."', '". $username . " - Funding - Funds Added - used paypal'  , '".$amount."', '0','".$total_pending."','".$total_plus."','".$grand_total."', '".$now."', '".$method."', 'FUNDING', 'PENDING')";				
				$db->setQuery($query);
				$db->query();
				break;
				
			case 'refund' :
				$fund = $this->getFund($userId, $packageId);
				if($fund != null) {
					$fundingId = $fund->funding_id;
				}
			    $totalA = $this->getTotalPending();
				if($totalA != null){
					$total_pending = $totalA->total_pending + $amount ;
				}
				$totalB = $this->getTotalPlus();				
				if($totalB != null){
					$total_plus = $totalB->total_plus  ;
				}
				
				$grand_total = $total_pending + $total_plus;
				$query = "insert into #__funding_history(funding_id, description, credit, debit, total_pending, total_plus,grand_total,created_date, method, transaction_type, status)
					values ('".$fundingId."', '". $username . " - Shopping Credit - Donation Refund ' , '".$amount."', '0','".$total_pending."','".$total_plus."','".$grand_total."', '".$now."', '".$method."','REFUND', 'PENDING')";			
				$db->setQuery($query);
				$db->query();
				break;	
		    case 'fee' :
				$fund = $this->getFund($userId, $packageId);
				if($fund != null) {
					$fundingId = $fund->funding_id;
				}
			    $totalA = $this->getTotalPending();
				if($totalA != null){
					$total_pending = $totalA->total_pending + $amount ;
				}
				$totalB = $this->getTotalPlus();				
				if($totalB != null){
					$total_plus = $totalB->total_plus ;
				}
				
				$grand_total = $total_pending + $total_plus;
				$query = "insert into #__funding_history(funding_id, description, credit, debit, total_pending, total_plus,grand_total,created_date, method, transaction_type, status)
					values ('".$fundingId."', '". $username . " - Shopping Credit - Donation Refund fee'  , '".$amount."', '0','".$total_pending."','".$total_plus."','".$grand_total."', '".$now."', '".$method."', 'REFUND', 'PENDING')";			
				$db->setQuery($query);
				$db->query();
				break;					
			case 'withdraw' :
				$fund = $this->getFund($userId, $packageId);
				if($fund != null){
					$fundingId = $fund->funding_id;
				}
							$totalA = $this->getTotalPending();
				if($totalA != null){
					$total_pending = $totalA->total_pending + $amount;
				}
				$totalB = $this->getTotalPlus();				
				if($totalB != null){
					$total_plus = $totalB->total_plus;
				}
				
				$grand_total = $total_pending + $total_plus;
				$query = "insert into #__funding_history(funding_id, description, credit, debit, total_pending, total_plus,grand_total,created_date, method, transaction_type, status)
					values ('".$fundingId."','". $username . " - Withdraw - Withdraw Taken' , '".$amount."', '0','".$total_pending."','".$total_plus."','".$grand_total."', '".$now."', '".$method."', 'WITHDRAW', 'PENDING')";				
				$db->setQuery($query);
				$db->query();
				break;
			case 'donation' :
				$fund = $this->getFund($userId, $packageId);
				if($fund != null) {
					$fundingId = $fund->funding_id;
				}
			    $totalA = $this->getTotalPending();
				if($totalA != null){
					$total_pending = $totalA->total_pending ;
				}
				$totalB = $this->getTotalPlus();				
				if($totalB != null){
					$total_plus = $totalB->total_plus + $amount;
				}
				
				$grand_total = $total_pending + $total_plus;
 				$query = "insert into #__funding_history(funding_id, description, credit, debit, total_pending, total_plus,grand_total,created_date, method, transaction_type, status)
 					values ('".$fundingId."',  '". $username . " - Donation - Add Donation ', '".$amount."', '0', '".$total_pending."','".$total_plus."','".$grand_total."', '".$now."', '".$method."' , 'DONATION', 'PENDING')";
 				$db->setQuery($query);
 				$db->query();
				
 				$query1= "insert into #__donation_history(funding_id, description, credit, debit, created_date, method, transaction_type, status)
 					values ('".$fundingId."', '". $username . " - Donation - Add Donation ', '".$amount."', '0' , '".$now."', '".$method."' , 'DONATION', 'PENDING')";
				$db->setQuery($query1);
				$db->query();
				break;
			case 'shopping' :
				$fund = $this->getFund($userId, $packageId);
				if($fund != null) {
					$fundingId = $fund->funding_id;
				}
			    $totalA = $this->getTotalPending();
				if($totalA != null){
					$total_pending = $totalA->total_pending ;
				}
				$totalB = $this->getTotalPlus();				
				if($totalB != null){
					$total_plus = $totalB->total_plus + $amount;
				}
				
				$grand_total = $total_pending + $total_plus;
				$query = "insert into #__funding_history(funding_id, description, credit, debit, total_pending, total_plus,grand_total,created_date, method, transaction_type, status)
					values ('".$fundingId."', '". $username . " - Donation - Add Donation - used shopping credit'  , '".$amount."', '0','".$total_pending."','".$total_plus."','".$grand_total."', '".$now."', '".$method."', 'DONATION', 'PENDING')";					
				$db->setQuery($query);
				$db->query();
				
				
				break;				
		}

	}
	
	function UpdateShoppingCredit_2($userId, $packageId, $amount, $type, $method, $username){
	$now = JFactory::getDate();
		$db = JFactory::getDbo();
		$query1 = "update #__shopping_record set amount = '".$amount."' where user_id = '".$userId."' ";			
				$db->setQuery($query1);
				$db->query();
	}

		
function getTotalWithdraw(){
		$query = "
				SELECT fh.*, sum(fh.debit) as total_pending FROM #__funding_history fh 
				WHERE fh.`transaction_type` = 'WITHDRAW'  		
			";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();

		if(!empty($results)) {
			return $results[0];
		}
		return null;
	}
	
	function getTotalPending(){
		$query = "
				SELECT fh.*, sum(fh.credit) as total_pending FROM #__funding_history fh 
				WHERE fh.`transaction_type` = 'FUNDING' OR fh.`transaction_type` = 'REFUND'
			";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();

		if(!empty($results)) {
			return $results[0];
		}
		return null;
	}
	
	function getTotalPlus(){
		$query = "
				SELECT fh.*, sum(fh.credit) as total_plus FROM #__funding_history fh 
				WHERE fh.`transaction_type` = 'DONATION' OR fh.`transaction_type` = 'WITHDRAW' 		
			";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();

		if(!empty($results)) {
			return $results[0];
		}
		return null;
	}
	
	
	function savegiftcode($user_id, $category_id, $gcid){
		$db 	= JFactory::getDbo();
		$user	= JFactory::getUser();
		$now = JFactory::getDate();	
			$query = "insert into #__gc_recieve_user (category_id, user_id, gcid, date_time, status, giftcode_type)
				values ('".$category_id."', '".$user_id."', '".$gcid."','".$now."', '0', '1' )";
			$db->setQuery($query);
			$db->query();			
		} 
		
	function CekGiftcode($category_id,$userid){
		$db 	= JFactory::getDbo();
		$query = "select * FROM #__gc_recieve_user WHERE category_id = '".$category_id."' AND user_id = '".$userid."'";
			$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;			
		} 
				
	function getGiftcode($category_id, $jml_gc, $tot_gc){
		$db 	= JFactory::getDbo();
		$query = "select id, giftcode FROM #__giftcode_giftcode WHERE giftcode_category_id = '".$category_id."' ORDER BY id DESC LIMIT ".$jml_gc.",".$tot_gc." ";
			$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;			
		} 
	
	function checkIsExistDonation($user_id, $package_id){
		$db = JFactory::getDbo();
		$query = "select * from #__ap_donation_transactions where package_id = '".$package_id."' and user_id = '".$user_id."' ";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return empty($results);
	}

	function getDonationDetails($package_id, $user_id, $category_id){
		$query = "
				SELECT * FROM #__ap_donation_transactions dt
				INNER JOIN #__ap_donation_details dd ON dd.transaction_id = dt.transaction_id AND dd.category_id = '".$category_id."'
				WHERE dt.package_id = '".$package_id."' AND dt.user_id = '".$user_id."' 
			";		
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		if(!empty($results)) {
			return $results[0];
		}
		return null;
	}

	function updateDonationTransactions($package_id, $user_id, $payment_gateway){
		$db = JFactory::getDbo();
		$query = "update #__ap_donation_transactions set payment_gateway = '" .$payment_gateway. "'
				where package_id = '" .$package_id. "' and user_id = '" .$user_id. "' ";
		$db->setQuery($query);
		$db->query();
	}

}