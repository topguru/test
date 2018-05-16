<?php

defined('_JEXEC') or die();
jimport('joomla.application.component.model');
class AwardpackageModelApresentationlist extends JModelLegacy {
	function __construct() {
		parent::__construct();
	}
	
	function InsertNewRow($packageId){
	$dateCreated = date('Y-m-d H:i:s');
	$query = "INSERT INTO `#__usergroup_presentation` ( `date_created`, `package_id`) VALUES ('".$dateCreated."' ,'".$packageId."')";
	$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getProcessId(){
		$query = "select * from #__process_presentation ORDER BY id DESC LIMIT 1";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getDetailPresentationProcess($id,$package_id){
		$query = "select * from #__process_presentation where id = '".$id."' AND package_id = '".$package_id."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getDetailPresentationProcess_List($id,$package_id){
		$query = "select * from #__process_presentation_list where presentation = '".$id."' AND package_id = '".$package_id."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getDetailPresentationProcess_1($package_id){
		$query = "select a.*, b.name as symbol_queue_group from #__process_presentation a
		 LEFT JOIN #__symbol_queue_group b ON b.id = a.symbol_queue
		where a.package_id = '".$package_id."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getProcessPresentationDetail($id,$package_id){
		$query = "select a.*, b.name as fund_receiver_name , d.title, c.name as symbol_name
		 from #__process_presentation a
		 LEFT JOIN #__fund_prize_plan b ON b.id = a.fund_prize_plan
		 LEFT JOIN #__symbol_queue_group c ON c.id = a.symbol_queue
		 LEFT JOIN #__fund_receiver_list d ON d.criteria_id = a.fund_receiver	
		 where a.id = '".$id."' AND a.package_id = '".$package_id."' 
		  ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getProcessPresentationDetailValueFrom($id,$package_id){
		$query = "select a.name, a.fund_amount, a.limit_receiver, b.name as fund_receiver_name , d.title, c.name as symbol_name, e.prize_value as selected_prize
		 from #__process_presentation a
		 LEFT JOIN #__fund_prize_plan b ON b.id = a.fund_prize_plan
		 LEFT JOIN #__symbol_queue_group c ON c.id = a.symbol_queue
		 LEFT JOIN #__fund_receiver_list d ON d.criteria_id = a.fund_receiver	
 		 LEFT JOIN #__process_presentation_list e ON e.presentation = a.id	
		 where a.id = '".$id."' AND a.package_id = '".$package_id."' 
		 ORDER BY selected_prize ASC LIMIT 1 ";
		$this->_db->setQuery ( $query );
			$valueFrom = $this->_db->loadObjectList();
		return 	$valueFrom;
	}
	
	public function getProcessPresentationDetailValueTo($id,$package_id){
		$query = "select a.name, a.fund_amount, a.limit_receiver, b.name as fund_receiver_name , d.title, c.name as symbol_name, e.prize_value as selected_prize
		 from #__process_presentation a
		 LEFT JOIN #__fund_prize_plan b ON b.id = a.fund_prize_plan
		 LEFT JOIN #__symbol_queue_group c ON c.id = a.symbol_queue
		 LEFT JOIN #__fund_receiver_list d ON d.criteria_id = a.fund_receiver	
 		 LEFT JOIN #__process_presentation_list e ON e.presentation = a.id	
		 where a.id = '".$id."' AND a.package_id = '".$package_id."' 
		 ORDER BY selected_prize DESC LIMIT 1 ";
		$this->_db->setQuery ( $query );
		$valueTo = $this->_db->loadObjectList();
		return $valueTo;
	}
	
	public function getProcessPresentationDetailPrize($id,$package_id){
		$query = "select a.name, a.fund_amount, a.limit_receiver, b.name as fund_receiver_name , d.title, c.name as symbol_name, e.prize_value as selected_prize
		 from #__process_presentation a
		 LEFT JOIN #__fund_prize_plan b ON b.id = a.fund_prize_plan
		 LEFT JOIN #__symbol_queue_group c ON c.id = a.symbol_queue
		 LEFT JOIN #__fund_receiver_list d ON d.criteria_id = a.fund_receiver	
 		 INNER JOIN #__process_presentation_list e ON e.presentation = a.id	
		 where a.id = '".$id."' AND a.package_id = '".$package_id."' 
		  ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getSymbolQueueCount($usergroupname){
		$query = "select a.* FROM #__ap_usergroup a 
		WHERE a.group_name = '".$usergroupname."'  ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getSymbolQueueCount2($presentationId){
		$query = "select a.* FROM #__ap_usergroup a 
		WHERE a.is_presentation = '".$presentationId."'  ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	
	public function getUserGroupName($presentationId){
		$query = "select a.* FROM #__usergroup_presentation a 
		WHERE a.presentation_id = '".$presentationId."'  ";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		if(!empty($results)) {
			return $results[0];
		}
		return null;
	}
	
	public function getProcessPresentationDetailList($id,$package_id,$limitstart,$limit){
		$query = "select a.*, c.prize_image, d.symbol_image, d.pieces, e.fund_prize_plan, e.funding_value_from, e.funding_value_to, e.fund_receiver
		 from #__process_presentation_list a
 		 INNER JOIN #__symbol_symbol_prize b ON b.presentation_id = a.selected_presentation
		 INNER JOIN #__symbol_prize c ON c.id = b.id	 
		 INNER JOIN #__symbol_symbol d ON d.symbol_id = b.symbol_id	 
		 INNER JOIN #__process_presentation e ON e.id = a.presentation	
		 where a.presentation = '".$id."' AND 
		 a.package_id = '".$package_id."' AND 
		 a.prize_value >= e.funding_value_from AND
		 a.prize_value <= e.funding_value_to
		 	ORDER BY a.id
				LIMIT ".$limitstart.", ".$limit." ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getFundReceiverListQueue($id,$package_id,$cbfilter,$limitstart,$limit){
	
	if(!empty($cbfilter)){
	$filter = "AND i.status = '".$cbfilter."' ";
		}else{
	$filter = "";		
		}
		$query = "select a.*, c.prize_image, d.symbol_image, d.pieces, e.fund_prize_plan, e.funding_value_from, e.funding_value_to, f.title,
		          h.useraccount_id, h.firstname, h.lastname, i.status, g.amount
		 from #__process_presentation_list a
 		 INNER JOIN #__symbol_symbol_prize b ON b.presentation_id = a.selected_presentation
		 INNER JOIN #__symbol_prize c ON c.id = b.id	 
		 INNER JOIN #__symbol_symbol d ON d.symbol_id = b.symbol_id	 
		 INNER JOIN #__process_presentation e ON e.id = a.presentation	
		 INNER JOIN #__fund_receiver_list f ON f.criteria_id = e.fund_receiver	
		 INNER JOIN #__award_fund_plan g ON g.id = e.fund_prize_plan
    	 INNER JOIN #__ap_usergroup h on h.group_name = g.usergroup
		 INNER JOIN #__symbol_presentation i on i.presentation_id = b.symbol_prize_id
		 INNER JOIN #__ap_symbol_process j on j.presentation_id = e.selected_presentation
		 where a.presentation = '".$id."' AND 
		 a.package_id = '".$package_id."' AND 
		 a.prize_value >= e.funding_value_from AND
		 a.prize_value <= e.funding_value_to
		 ".$filter."
			ORDER BY a.id
				LIMIT ".$limitstart.", ".$limit." ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getPresentation($package_id){
		$query = "select * from #__symbol_presentation where package_id = '".$package_id."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	public function getPresentationWithStatusActive($package_id, $status){
		$query = "select * from #__symbol_presentation where 1=1
			" .($status == '1' ? " and status = '1' " : "" ). "  
			and package_id = '".$package_id."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	public function getSymbolFilled(){
		$query = "select *, b.firstname from #__symbol_queue a 
						left join #__ap_useraccounts b on b.id = a.user_id	
		Where user_id <> '' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getSymbolGroupQueue($package_id){
		$query = "select * from #__symbol_queue_group a where package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getSymbolQueue($package_id){
		$query = "select * from #__symbol_queue a where shufle <> 0 ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getSymbols($package_id){
		$query = "select * from #__symbol_symbol where package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	public function getPrizes($package_id){
		$query = "select * from #__symbol_prize where package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	public function getFundPrizes($package_id){
		$query = "select * from #__fund_prize_plan where package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getStartFundPrizes($selected, $package_id){
		$query = "select * from #__start_fund_prize_plan where package_id = '".$package_id."' AND selected_presentation = '".$selected."'";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getFundPrizesId($id,$package_id){
		$query = "select * from #__fund_prize_plan where package_id = '".$package_id."' AND id = '".$id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getAwardFundPlan($package_id){
		$query = "select a.* ,b.firstname,b.lastname
		from #__award_fund_plan a
		left join #__ap_usergroup b on b.group_name = a.usergroup
		where a.package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getUserFundReceiver($processId){
		$query = "select * from #__process_presentation 
		WHERE id = '".$processId."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getFundReceiver($package_id){
		$query = "select * from #__fund_receiver where package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
		public function getFundReceiverList($package_id){
		$query = "select * from #__fund_receiver_list where package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getFundReceiverProcess($id, $package_id){
		$query = "select a. * from #__fund_receiver_list a
		inner join #__process_presentation c ON c.fund_receiver = a.criteria_id
		where a.package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getPrizeById($id,$package_id){
		$query = "select * from #__symbol_prize where id = '".$id."' and package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	public function getPrizeByStatus($prize_name, $status,$package_id){
		$query = "select * from #__symbol_prize where prize_name = '".$prize_name."' and status = 1 and package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
		public function getPrizeByUnlock($prize_name, $status,$package_id){
		$query = "select * from #__symbol_prize where prize_name = '".$prize_name."' and status = 1 and package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getSymbolById($id,$package_id){
		$query = "select * from #__symbol_symbol where symbol_id = '".$id."' and package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	public function insertNewSymbolPrize(){
		$user = JFactory::getUser();
		$dateCreated = date('Y-m-d H:i:s');
		$db	= &JFactory::getDbo();
		$query = "insert into #__symbol_prize (date_created,prize_name,created_by,package_id,status,unlocked_status)
				  values ('".$dateCreated."','New','".$user->username."','".JRequest::getVar('package_id')."','0','0')";
		$db->setQuery($query);
		$db->query();
		return $db->insertId();
	}
	public function insertNewSymbol(){
		$dateCreated = date('Y-m-d H:i:s');
		$db	= &JFactory::getDbo();
		$query = "insert into #__symbol_symbol (date_created,symbol_name,package_id,status)
				  values ('".$dateCreated."','New','".JRequest::getVar('package_id')."','0')";
		$db->setQuery($query);
		$db->query();
		return $db->insertId();
	}
	public function createPresentation($prize,$symbol,$package_id){
		$db = &JFactory::getDBO ();
		$createdate = date('Y-m-d H:i:s');
		$query = "insert into #__symbol_presentation (presentation_create,presentation_modify,presentation_publish,package_id,status) values
			('".$createdate."', '".$createdate."', '0', '".$package_id."', '0') ";
		$db->setQuery($query);
		if($db->query()) {
			$presentationId = $db->insertId();
			$query = "insert into #__symbol_symbol_prize (id, symbol_id, presentation_id)
					values ('".$prize."', '".$symbol."', '".$presentationId."' ) ";			
			$db->setQuery($query);
			if($db->query()){
				return true;
			} else {
				return false;
			}
		}
	}
	
	public function UpdatePresentationByProcess_1($process, $value, $presentation_id, $package_id,$id){
		$db = &JFactory::getDBO ();
		$createdate = date('Y-m-d H:i:s');		
		$result1 = $this->getPrizeName($presentation_id);
		foreach ( $result1 as $row1 ){
		 $prizename = $row1->prize_name;
		 $prizevalue = $row1->prize_value;
		}

			$query = "update #__process_presentation set name = '".$process."' ,fund_amount = '".$value."', 
			selected_presentation ='".$presentation_id."', 	
			prize_name ='".$prizename."', 	
			prize_value ='".$prizevalue."', 	
			date_created ='".$createdate."' 
			WHERE id ='".$id."' "
			;
				$db->setQuery($query);
				$db->query();
				return ;
	}
	
	public function InputPresentationByProcess_1($process, $value, $presentation_id, $package_id,$id){
		$db = &JFactory::getDBO ();
		$createdate = date('Y-m-d H:i:s');		
		$result1 = $this->getPrizeName($presentation_id);
		foreach ( $result1 as $row1 ){
		 $prizename = $row1->prize_name;
		 $prizevalue = $row1->prize_value;
		}

			/*$query = "INSERT INTO #__process_presentation () values ()
			set name = '".$process."' ,fund_amount = '".$value."', 
			selected_presentation ='".$presentation_id."', 	
			prize_name ='".$prizename."', 	
			prize_value ='".$prizevalue."', 	
			date_created ='".$createdate."' 
			WHERE id ='".$id."' "
			;*/
			$query = 'insert into #__process_presentation_list (name,fund_amount,selected_presentation,prize_name,prize_value,date_created,presentation,package_id, fund_prize) 
					values (\'' . $process . '\',\''.$value.'\',\''.$presentation_id.'\',\''.$prizename.'\',\''.$prizevalue.'\',\''.$createdate.'\',\''.$id.'\',\''.$package_id.'\',\''.$presentation_id.'\')';
				$db->setQuery($query);
				$db->query();
				return ;
	}
	
	public function UpdatePresentationByProcess_2($process, $value, $idFundPrizePlan, $package_id, $id){
		$db = &JFactory::getDBO ();
		$createdate = date('Y-m-d H:i:s');		
		$result2 = $this->getFundPrizePlan($idFundPrizePlan);
		foreach ( $result2 as $row2 ){
		 $valuefrom = $row2->value_from;
		 $valueto = $row2->value_to;
		}
			$query = "update #__process_presentation set name = '".$process."' ,fund_amount = '".$value."', 
			fund_prize_plan = '".$idFundPrizePlan."',
			funding_value_from = '".$valuefrom."',								
			funding_value_to = '".$valueto."',								
			date_created ='".$createdate."' WHERE id ='".$id."' "
			;
				$db->setQuery($query);
				$db->query();
				return ;
	}
	
	public function save_fund_prize_plan($package_id, $sname,$selected,$value_from,$value_to,$id){
		$this->_db = &JFactory::getDBO ();
		$createdate = JFactory::getDate()->toSql();
		if($id != '') {
			$query = 'update #__start_fund_prize_plan set name = \''.$sname.'\' , selected_presentation = \''.$selected.'\' ,value_from = \''.$value_from.'\' ,value_to = \''.$value_to.'\'   where id = \''.$id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		} else {
			$query = 'insert into #__start_fund_prize_plan (name,selected_presentation,value_from,value_to, published, date_created, package_id)
					values (\'' . $sname . '\',\''.$selected.'\',\''.$value_from.'\',\''.$value_to.'\',' . 0 . ', \''.$createdate.'\', \''.$package_id.'\')';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		}
	}
	
	public function UpdatePresentationByProcess_3($process, $value, $AwardFundPlan, $package_id,$id){
		$db = &JFactory::getDBO ();
		$createdate = date('Y-m-d H:i:s');		
		$result2 = $this->getAwardFundPlan_3($AwardFundPlan);
		foreach ( $result2 as $row2 ){
		 $rate = $row2->rate;
		 $amount = $row2->amount;
		}
			$query = "update #__process_presentation set name = '".$process."' ,fund_amount = '".$value."', 
			award_fund_plan = '".$AwardFundPlan."',
			award_fund_rate = '".$rate."',								
			award_fund_amount = '".$amount."',								
			date_created ='".$createdate."' WHERE id ='".$id."' "
			;
				$db->setQuery($query);
				$db->query();
				return ;
	}
	
	public function UpdateSelectedBy($id,$AwardFundPlan){
		$db = &JFactory::getDBO ();
		
			$query = "update #__award_fund_plan set published = '".$id."' WHERE id ='".$AwardFundPlan."' "
			;
				$db->setQuery($query);
				$db->query();
				return ;
	}
	
	public function UpdateUserAssigned($userid,$idSymbolGroupID,$queueId,$shufle,$idPresentationID) {
		$db = &JFactory::getDBO ();
		$dateUpdated = date('Y-m-d H:i:s');
			$query = "update #__symbol_queue set 
			user_id = '".$userid."', 
			shufle =  '".$shufle."', 
			selected_presentation = '".$idPresentationID."' ,
			date_created = '".$dateUpdated."' 
			WHERE groupId ='".$idSymbolGroupID."' AND queue_id ='".$queueId."' ";
				$db->setQuery($query);
				$db->query();
				return ;
	}
	
	public function getUserGroupId($idUserGroupsId){
		//$db = &JFactory::getDBO ();
		$query = "SELECT * FROM  #__ap_usergroup WHERE group_name ='".$idUserGroupsId."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getSymbolqueues($idSymbolGroupID){
		//$db = &JFactory::getDBO ();
		$query = "SELECT * FROM  #__symbol_queue  WHERE groupId ='".$idSymbolGroupID."' ORDER BY queue_id DESC";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getUserGroup($id,$title,$usergroup_id){
		$db = &JFactory::getDBO ();		
			$query = "update #__usergroup_presentation set usergroup = '".$title."' , usergroup_id = '".$usergroup_id."'   WHERE id ='".$id."' ";
				$db->setQuery($query);
				$db->query();
				return ;
	}
	
	  public function getGroupId($title) {
				   $query = " select id from #__ap_groups WHERE name ='".$title."' ";
                   $this->_db->setQuery($query);
                   $this->_db->query();					    
					return $this->_db->loadObject();
		 }
	
	public function UpdatePresentationByProcess_4($process, $value, $idReceiverID, $package_id,$id){
		$db = &JFactory::getDBO ();
		$createdate = date('Y-m-d H:i:s');		
		$result2 = $this->getFundReceiver_4($idReceiverID);
		foreach ( $result2 as $row2 ){
		 $filter = $row2->filter;
		}
			$query = "update #__process_presentation set name = '".$process."' ,fund_amount = '".$value."', 
			fund_receiver = '".$idReceiverID."',
			limit_receiver = '".$filter."',								
			date_created ='".$createdate."' WHERE id ='".$id."' "
			;
				$db->setQuery($query);
				$db->query();
				return ;
	}
	
	public function UpdatePresentationByProcess_5($process, $value, $idSymbolFilledID, $package_id,$id){
		$db = &JFactory::getDBO ();
		$createdate = date('Y-m-d H:i:s');		
		$result2 = $this->getSymbolQueue_5($idSymbolFilledID);
		foreach ( $result2 as $row2 ){
		 $userid = $row2->user_id;
		}
			$query = "update #__process_presentation set name = '".$process."' ,fund_amount = '".$value."', 
			symbol_queue = '".$idSymbolFilledID."',
			symbol_assign = '".$userid."',								
			date_created ='".$createdate."' WHERE id ='".$id."' "
			;
				$db->setQuery($query);
				$db->query();
				return ;
	}
	
	public function getSymbolQueue_5($idSymbolFilledID){
		$db = &JFactory::getDBO ();
			$query = "	SELECT *
					FROM #__symbol_queue  
					WHERE queue_id = '".$idSymbolFilledID."' ";
				$db->setQuery($query);
				$db->query();
				$results = $this->_db->loadObjectList();
				return $results;
	}
	
	public function getFundReceiver_4($idReceiverID){
		$db = &JFactory::getDBO ();
			$query = "	SELECT *
					FROM #__fund_receiver_list  
					WHERE criteria_id = '".$idReceiverID."' ";
				$db->setQuery($query);
				$db->query();
				$results = $this->_db->loadObjectList();
				return $results;
	}
	
	public function getAwardFundPlan_3($AwardFundPlan){
		$db = &JFactory::getDBO ();
			$query = "	SELECT *
					FROM #__award_fund_plan  
					WHERE id = '".$AwardFundPlan."' ";
				$db->setQuery($query);
				$db->query();
				$results = $this->_db->loadObjectList();
				return $results;
	}
	
	public function getFundPrizePlan($idFundPrizePlan){
		$db = &JFactory::getDBO ();
			$query = "	SELECT *
					FROM #__fund_prize_plan  
					WHERE id = '".$idFundPrizePlan."' ";
				$db->setQuery($query);
				$db->query();
				$results = $this->_db->loadObjectList();
				return $results;
	}
	
		public function getPrizeName($presentation_id){
		$db = &JFactory::getDBO ();
			$query = "	SELECT a.*, c.prize_name, c.prize_image, c.prize_value, b.symbol_name, b.symbol_image, b.pieces, b.cols, b.rows, d.presentation_id, d.status
					FROM #__symbol_symbol_prize a 
					LEFT JOIN #__symbol_symbol b ON b.symbol_id = a.symbol_id 
					LEFT JOIN #__symbol_prize c ON c.id = a.id
					INNER JOIN #__symbol_presentation d ON d.presentation_id = a.presentation_id 
					WHERE a.presentation_id  = '".$presentation_id."'";
				$db->setQuery($query);
				$db->query();
				$results = $this->_db->loadObjectList();
				return $results;
	}
	
	public function createPresentationByProcessPrize($selected,$value, $package_id){
		$db = &JFactory::getDBO ();
		$createdate = date('Y-m-d H:i:s');
			$query = "update #__process_presentation set name = '".$process."' ,value_from = '".$value."', date_created ='".$createdate."' ";
				$db->setQuery($query);
				$db->query();
				return ;
	}
	
	public function createPresentationByPrize($prize,$package_id){
		$db = &JFactory::getDBO ();
		$createdate = date('Y-m-d H:i:s');
		$presentation_id = JRequest::getVar('presentation_id');
		if($presentation_id != ''){
			$query = "update #__symbol_symbol_prize set id = '".$prize."' where presentation_id = '".$presentation_id."' ";
			$db->setQuery($query);
			$db->query();
			return 0;
		} else {
			$query = "insert into #__symbol_presentation (presentation_create,presentation_modify,presentation_publish,package_id,status) values
					('".$createdate."', '".$createdate."', '0', '".$package_id."', '0') ";
			$db->setQuery($query);
			if($db->query()) {
				$presentationId = $db->insertId();
				$query = " insert into #__symbol_symbol_prize (id, symbol_id, presentation_id)
						   values ('".$prize."', '0', '".$presentationId."' ) ";			
				$db->setQuery($query);
				$db->query();
				return $presentationId;
			}
		}
	}
	public function createPresentationBySymbol($symbol,$package_id){
		$db = &JFactory::getDBO ();
		$createdate = date('Y-m-d H:i:s');
		$presentation_id = JRequest::getVar('presentation_id');
		if($presentation_id != ''){
			$query = "update #__symbol_symbol_prize set symbol_id = '".$symbol."' where presentation_id = '".$presentation_id."' ";
			$db->setQuery($query);
			$db->query();
			return 0;
		} else {
			$query = "insert into #__symbol_presentation (presentation_create,presentation_modify,presentation_publish,package_id,status) values
					('".$createdate."', '".$createdate."', '0', '".$package_id."', '0') ";
			$db->setQuery($query);
			if($db->query()) {

				$presentationId = $db->insertId();
				$query = " insert into #__symbol_symbol_prize (id, symbol_id, presentation_id)
						   values ('0', '".$symbol."', '".$presentationId."' ) ";			
				$db->setQuery($query);
				$db->query();
				return $presentationId;
			}
		}
	}
	public function deletePresentation($presentation_id){
		$db = &JFactory::getDBO ();
		$query = "select * from #__symbol_symbol_prize where presentation_id = '".$presentation_id."' ";
		$this->_db->setQuery ($query);
		$results = $this->_db->loadObjectList();
		if(!empty($results)){
			$result = $results[0];
			$query = "delete from #__symbol_symbol_prize where presentation_id = '".$presentation_id."' ";
			$db->setQuery($query);
			$db->query();
			$query = "delete from #__symbol_presentation where presentation_id = '".$presentation_id."' ";
			$db->setQuery($query);
			$db->query();
		}
	}
	public function getSymbolSymbolPrize($package_id,$limit, $limitstart){
		$query = "	SELECT a.symbol_prize_id, c.prize_name, c.prize_image, c.prize_value, b.symbol_name, b.symbol_image, b.pieces, b.cols, b.rows, d.presentation_id, d.status
					FROM #__symbol_symbol_prize a 
					LEFT JOIN #__symbol_symbol b ON b.symbol_id = a.symbol_id 
					LEFT JOIN #__symbol_prize c ON c.id = a.id
					INNER JOIN #__symbol_presentation d ON d.presentation_id = a.presentation_id AND d.package_id = '".$package_id."' ORDER by a.symbol_prize_id DESC LIMIT ".$limitstart.", ".$limit." ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	
	public function getsymbolQueueGroup($package_id){
		$query = "	SELECT *
					FROM #__symbol_queue_group
					WHERE package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
		public function getSymbolPrize($userid){
		
			$query = "SELECT COUNT(*) AS count
					FROM #__gc_recieve_user a 
				WHERE a.`user_id` = '" .$userid. "'  ";
				
		
		$this->_db->setQuery ($query);
		$rows = $this->_db->loadObjectList ();
		return $rows[0]->count;
	}
	
	public function getSymbolPrizeUser($userid){
		
			$query = "SELECT COUNT(*) AS count
					FROM #__gc_recieve_user a 
				WHERE a.`user_id` = '" .$userid. "' AND a.status ='1' ";
				
		
		$this->_db->setQuery ($query);
		$rows = $this->_db->loadObjectList ();
		return $rows[0]->count;
	}
	
	public function getSymbolSymbolPrize_2($package_id){
		$query = " SELECT presentations FROM #__selected_presentation WHERE package_id = '".$package_id."' AND is_delete = '0' ";
		$this->_db->setQuery ($query);
		$rSelectedPresentations = $this->_db->loadObjectList();
		$r = "";
		foreach ($rSelectedPresentations as $rSelectedPresentation) {
			$r .= $rSelectedPresentation->presentations . ',';
		}
		$query = "	SELECT a.symbol_prize_id, c.prize_name, c.prize_image, c.prize_value, b.symbol_name, b.symbol_image, b.pieces, b.cols, b.rows, d.presentation_id, d.status
					FROM #__symbol_symbol_prize a 
					LEFT JOIN #__symbol_symbol b ON b.symbol_id = a.symbol_id 
					LEFT JOIN #__symbol_prize c ON c.id = a.id
					INNER JOIN #__symbol_presentation d ON d.presentation_id = a.presentation_id AND d.package_id = '".$package_id."'									 
				 ";
		$this->_db->setQuery ($query);
		$results = $this->_db->loadObjectList();
		$ss = array();
		foreach ($results as $result) {
			if(strpos($r, $result->presentation_id) === false || $r == ""){
				$ss[] = $result;
			}
		}
		return $results;
	}
	public function updateSymbolPresentation($presentation_id){
		$db	= &JFactory::getDbo();
		$query = "update #__symbol_presentation set status = '1' where presentation_id in (".$presentation_id.")";
		$db->setQuery($query);
		if (! $db->query()) {
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
			return false;
		} else {
			return true;
		}
	}
	public function addPresentationUserGroup($package_id){
		$db	= &JFactory::getDbo();
		$query = "insert into #__ap_usergroup (package_id,status)
				  values ('".$package_id."','0') ";
		$db->setQuery($query);
		if (! $db->query()) {
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
			return false;
		} else {
			return true;
		}
	}
	public function getUserGroups($package_id){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT dat.`criteria_id`, dat.`group_name`, dat.`field`, SUM(dat.`users`) AS users FROM (
				SELECT a.`criteria_id`, a.`group_name`, a.`field`, COALESCE(b.`ap_account_id`,0,1) AS users 
				FROM #__ap_usergroup a 
				LEFT JOIN #__ap_useraccounts b ON b.`id` = a.`criteria_id` WHERE a.`package_id` = '".$package_id."'
				GROUP BY a.`criteria_id`, a.`group_name`, a.`field` ORDER BY a.`group_name`
				) dat 
				GROUP BY dat.`criteria_id`, dat.`group_name`, dat.`field`
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function getPresentationUserGroups($package_id){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT a.`id`, a.`process_presentation`, dat.* FROM (
				SELECT dat.`criteria_id`, dat.`group_name`, dat.`field`, SUM(dat.`users`) AS users FROM (
				SELECT a.`criteria_id`, a.`group_name`, a.`field`, COALESCE(b.`ap_account_id`,0,1) AS users 
				FROM #__ap_usergroup a 
				LEFT JOIN #__ap_useraccounts b ON b.`id` = a.`criteria_id` WHERE a.`package_id` = '".$package_id."'
				GROUP BY a.`criteria_id`, a.`group_name`, a.`field` ORDER BY a.`group_name`
				) dat 
				GROUP BY dat.`criteria_id`, dat.`group_name`, dat.`field`
				) dat
				INNER JOIN #__usergroup_presentation a ON a.`usergroup` = dat.`criteria_id`	
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	
	public function get_user_from_usergroups($criteria, $filter1, $filter2, $package_id) {
		$query = 'select 0 AS cnt';
		switch ($criteria){
			case 'email' :
				$query = 'select count(*) as cnt from #__ap_useraccounts where package_id = \''.$package_id.'\'';
				break;
			case 'age' :
				$query = 'select count(*) as cnt from #__ap_useraccounts where package_id = \''.$package_id.'\' and
				      	  timestampdiff(year,birthday,now()) between '.$filter1.' and '.$filter2.' ';
				break;
			case 'gender' :
				$query = 'select count(*) as cnt from #__ap_useraccounts where package_id = \''.$package_id.'\' and
				     gender = \''.$filter1.'\' ';
				break;
			case 'name' :
				$query = 'select count(*) as cnt from #__ap_useraccounts where package_id = \''.$package_id.'\' and
				     lower(firstname) like \'%'.strtolower($filter1).'%\' or lower(lastname) like \'%'.strtolower($filter2).'%\' ';
				break;
		}
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();
		return !empty($data) ? $data[0]->cnt : '0';
	}
	public function get_user_from_usergroups_by_location($street, $city, $state, $post_code, $country, $package_id) {
		$query = $query = 'select count(*) as cnt from #__ap_useraccounts where package_id = \''.$package_id.'\' and
				     lower(street) like \'%'.strtolower($street).'%\' or lower(city) like \'%'.strtolower($city).'%\'
				     or lower(state) like \'%'.strtolower($state).'%\' or lower(country) like \'%'.$country.'%\'
				     or post_code = \''.$post_code.'\' ';
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();
		return !empty($data) ? $data[0]->cnt : '0';
	}

	public function getDetailPresentation3($package_id, $status){
		$query = $this->_db->getQuery(true);
		$query = "
				select presentation_id, package_id, status from #__symbol_presentation where
				package_id = '".$package_id."' and status = '".$status."' 
			";
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		$presentations = '';
		foreach ($results as $result){
			$presentations .= $result->presentation_id . ',';
		}
		$presentations = substr($presentations, 0, strlen($presentations)-1);
		return $this->getDetailPresentation($package_id, $presentations, $status);
	}

	public function getDetailPresentation($package_id, $presentations, $status){
		/*
		 $processPresentation = JRequest::getVar('processPresentation');
		 $query = $this->_db->getQuery(true);
		 $query = "
		 SELECT pp.`presentations` FROM #__selected_presentation pp
		 WHERE  pp.`package_id` = '".$package_id."'
		 ".(empty($processPresentation) ? "" : " AND pp.`process_presentation` = '".JRequest::getVar('processPresentation')."'") . "
			";
			$this->_db->setQuery($query);
			$rs = $this->_db->loadObjectList();

			$r = '-88,';
			foreach ($rs as $s){
			if(!empty($s->presentations)){
			$r .= $s->presentations . ',';
			}
			}
			$r = substr($r, 0, strlen($r)-1);
			*/
		$arr_data = array();
		//if($r != '-88') {

		if($presentations != ''){
			$query = "
				SELECT COUNT(a.`presentation_id`) AS presentations, 
				CONCAT('$', MIN(CAST(c.`prize_value` AS UNSIGNED)), ' - ', '$', MAX(CAST(c.`prize_value` AS UNSIGNED))) AS prize_value_range,
				'' AS presentation_user_group, '' AS presentation_users,
				'' AS total_of_each,
				c.prize_name, a.presentation_create 
				FROM #__symbol_presentation a			 
				INNER JOIN #__symbol_symbol_prize b ON b.`presentation_id` = a.`presentation_id` 
				INNER JOIN #__symbol_prize c ON c.`id` = b.`id` 
				WHERE a.`package_id` = '".$package_id."'
				AND a.`presentation_id` IN (".$presentations.") 
				".($status == 1 ? " AND a.`status` = '".$status."' " : "") . "   
				ORDER BY a.`presentation_id` ASC		
			";
			$this->_db->setQuery ($query);
			$results = $this->_db->loadObjectList();
			$groups = $this->getAllUserGroups($package_id, JRequest::getVar('idUserGroupsId'));

			if(!empty($groups)){
				foreach ($groups as $group) {
					foreach ($results as $result) {
						$data = new stdClass();
						$data->presentations = $result->presentations;
						$data->prize_value_range = $result->prize_value_range;
						$data->prize_name = $result->prize_name;
					$data->presentation_create = $result->presentation_create;									
						$data->presentation_user_group = $group->group_name;
						$data->presentation_users = $group->accounts;
						$data->total_of_each = $result->total_of_each;
						$fundings = $this->getFundingPresentation($package_id, $presentations);
						foreach ($fundings as $funding){
							$fundPrize = $fundPrize + (int) $funding->pct_funded;
						}
						$data->funding = $fundPrize;
						$arr_data[] = $data;
					}
				}
			} else {
				foreach ($results as $result) {
					$data = new stdClass();
					$data->presentations = $result->presentations;
					$data->prize_value_range = $result->prize_value_range;
					$data->prize_name = $result->prize_name;		
					$data->presentation_create = $result->presentation_create;									
					$data->presentation_user_group = '';
					$data->presentation_users = '';
					$data->total_of_each = $result->total_of_each;
					$data->total_of_each = $result->total_of_each;
					$fundings = $this->getFundingPresentation($package_id, $presentations);
					foreach ($fundings as $funding){
						$fundPrize = $fundPrize + (int) $funding->pct_funded;
					}
					$data->funding = $fundPrize;
					$arr_data[] = $data;
				}
			}
		} else {
			$groups = $this->getAllUserGroups($package_id, JRequest::getVar('idUserGroupsId'));
			if(!empty($groups)){
				foreach ($groups as $group) {
					$data = new stdClass();
					$data->presentations = '';
					$data->prize_value_range = '';
					$data->prize_name = $result->prize_name;	
					$data->presentation_create = $result->presentation_create;									
					$data->presentation_user_group = $group->group_name;
					$data->presentation_users = $group->accounts;
					$data->total_of_each = '';
					$data->funding = '';
					$arr_data[] = $data;
				}
			}
		}
		return $arr_data;
	}
	
	public function getDetailPresentation_2($package_id, $presentations, $status){
		$query = "
			SELECT COUNT(a.`presentation_id`) AS presentations, 
				CONCAT('$', MIN(CAST(c.`prize_value` AS UNSIGNED)), ' - ', '$', MAX(CAST(c.`prize_value` AS UNSIGNED))) AS prize_value_range,
				'' AS presentation_user_group, '' AS presentation_users,
				'' AS total_of_each
				FROM #__symbol_presentation a			 
				INNER JOIN #__symbol_symbol_prize b ON b.`presentation_id` = a.`presentation_id` 
				INNER JOIN #__symbol_prize c ON c.`id` = b.`id` 
				WHERE a.`package_id` = '".$package_id."'
				".($presentations != null ? "AND a.`presentation_id` IN (".$presentations.")" : "")." 
				".($status == 1 ? " AND a.`status` = '".$status."' " : "") . "   
				ORDER BY a.`presentation_id` ASC		
		";		

		$this->_db->setQuery ($query);
		$results = $this->_db->loadObjectList();
		foreach ($results as $result) {
			$groups = $this->getAllUserGroups($package_id);
			$result->presentation_user_group = count($groups);
			$account = 0;
			foreach ($groups as $group) {
				$account = $account + (int) $group->accounts;
			}
			$result->presentation_users = $account;
		}
		return $results;
	}
	
	public function getSelectedPresentations($package_id, $processPresentation = 0){
		$selectedPresentation = array();
		$query = "select selected_presentation from #__process_presentation where id = '".$processPresentation."' and package_id = '".$package_id."' ";
		$this->_db->setQuery ($query);
		$results = $this->_db->loadObjectList();
		$result = null;
		if(!empty($results)){
			$result = $results[0];
			$data = $result->selected_presentation;
			$selectedPresentation = explode(",", $data);
		}

		if(!empty($selectedPresentation)) {
			$retBack = array();
			$query = "
				SELECT a.`id`, 
					   a.`presentations`, 
					   a.`package_id`, 
					   a.`date_created` 
					FROM #__selected_presentation a
				WHERE a.`package_id` = '".$package_id."' AND a.`process_presentation` = '" . $processPresentation . "' AND a.`is_delete` = '0' ORDER BY a.`id` ASC		
			";

			$this->_db->setQuery ($query);
			$results = $this->_db->loadObjectList();
			//foreach ($results as $result) {
			//	$id = $result->id;
			//	if(in_array($id, $selectedPresentation)) {
			//		$retBack[] = $result;
			//	}
			//}
			//return $retBack;
			return $results;
		} else {
			return null;
		}
	}

	public function updateProcessPresentation($id, $package_id, $selectedPresentation){
		$db	= &JFactory::getDbo();
		$query = "update #__process_presentation set selected_presentation = '" .$selectedPresentation. "' where id = '".$id."' and package_id = '".$package_id."' ";
		$db->setQuery($query);
		$db->query();
	}

	public function insertNewProcessPresentation($package_id){
		$db	= &JFactory::getDbo();
		$query = "INSERT INTO `#__process_presentation` (selected_presentation, package_id) VALUES (NULL, '" .$package_id. "') ";
		$db->setQuery($query);
		$db->query();
		if ($db->insertid() > 0) {
			$id = $db->insertid();
		} else {
			$id = 0;
		}
		return $id;
	}
	
	public function insertNewProcessPresentationTitle($idProses,$idProsesValue,$package_id){
		$db	= &JFactory::getDbo();
		$query = "INSERT INTO `#__process_presentation` (name, fund_amount, package_id) VALUES ('" .$idProses. "','" .$idProsesValue. "', '" .$package_id. "') ";
		$db->setQuery($query);
		$db->query();
		if ($db->insertid() > 0) {
			$id = $db->insertid();
		} else {
			$id = 0;
		}
		return $id;
	}

	public function getSelectedPresentationsById($id){
		$query = "
			SELECT a.`id`, a.`presentations`, a.`package_id`, a.`date_created` FROM #__selected_presentation a
			WHERE a.`id` = '".$id."' AND a.`is_delete` = '0' ORDER BY a.`id` ASC		
		";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function delete_startfundprizeplan($id){
		$queries = array();
		$id = implode(',', $id);			
		$queries[] = 'delete from #__start_fund_prize_plan where id in ('.$id.')';			
		foreach ($queries as $query){
			$this->_db->setQuery($query);
			if(!$this->_db->query()){					
				return false;
			}
		}			
		return true;
	}
	
	public function deleteSelectedPresentations($id){
		$query = "
			 SELECT `presentations` FROM #__selected_presentation WHERE `id` = '".$id."'
			";
		$this->_db->setQuery($query);
		$presentations = $this->_db->loadObjectList();
		$presentation = !empty($presentations) ? $presentations[0] : null;
		$presentations = explode(',', $presentation->presentations);
		foreach ($presentations as $pr) {
			$processSymbol =  $this->getProcessSymbolByPresentationId($pr, $id);
			if($processSymbol != null) {
				$process_symbol_id = $processSymbol->id;
				$prizes	= $this->check_prize($pr);
				foreach($prizes as $prize){
					//DELETE EXTRACTEDS PROCESS
					$extracteds = $this->getExtract_2($process_symbol_id, $prize->prize_id);
					if(!empty($extracteds)) {
						$extracted = $extracteds[0];
						//do delete for extract detail
						$this->delete_extract_detail($extracted->id);
						//do delete from extract
						$this->deleteExtract($extracted->id);
						//update all is_locked status to 0
						$this->activeStatusAll($prize->symbol_id);
						//update status
						$this->saveUpdateExtractData('0', $prize->process_symbol);
					}


					//DELETE CLONE PROCESS
					$clonedPrizes = $this->get_clone_prize($prize->prize_id, $process_symbol_id, $prize->symbol_id);
					if(!empty($clonedPrizes)) {
						$clonedPrize = $clonedPrizes[0];
						//delete clone pieces
						$this->delete_clone_pieces($prize->symbol_id, $clonedPrize->id);
						//delete detail clone pieces
						$this->delete_clone_detail($clonedPrize->id);
						//delete master clone
						$this->delete_clone_prize($prize->prize_id, $process_symbol_id, $prize->symbol_id);
						//update status
						$this->saveUpdateClonData('0', $prize->process_symbol);
					}
					//DELETE FUNDING
					$this->deleteFunding($pr, $prize->prize_id);
				}
			}
			//DELETE SYMBOL PRICING
			$symbol_pricing_id = $this->saveSymbolPricing($pr);
			$detail = $this->getPricingDetailsByPricingId($symbol_pricing_id);
			$detail_id = $detail->details_id;
			$this->deleteSymbolPricingDetails($detail_id);
			$this->deletePricingBreakDownDetails($detail_id);
			$this->deleteSymbolPricing($pr);
			//DELETE QUEUE
			$user = JFactory::getUser();
			if(!empty($user)){
				$awardProgressModel	=  JModelLegacy::getInstance('awardprogress', 'AwardpackageModel');
				$awardProgressModel->delete_quee($user->id);
				$awardProgressModel->deleteQueueDetailByPresentation($pr);
			}
		}
		$db	= &JFactory::getDbo();
		$query = "DELETE FROM #__selected_presentation WHERE id = '".$id."' ";
		$db->setQuery($query);
		$db->query();

		//$dateUpdated = date('Y-m-d H:i:s');
		//$db	= &JFactory::getDbo();
		//$query = "update #__selected_presentation set is_delete = '1', date_update = '".$dateUpdated."' where id = '".$id."' ";
		//$db->setQuery($query);
		//$db->query();
	}
	
	public function addNewSelectedPresentation($package_id){
		$dateCreated = date('Y-m-d H:i:s');
		$db	= &JFactory::getDbo();
		$query = "insert into #__selected_presentation (`presentations`, `package_id`, `date_created`, `process_presentation`) values
			(null, '".$package_id."', '".$dateCreated."', '" .JRequest::getVar('processPresentation'). "')";
		$db->setQuery($query);
		$db->query();
	}
	public function updateSelectedPresentationsSetPresentations($id, $selectedPresentations){
		$db	= &JFactory::getDbo();
		$query = "update #__selected_presentation set `presentations` = '".$selectedPresentations."' where `id` = '".$id."' ";
		$db->setQuery($query);
		$db->query();
	}
	
	public function getSymbolName($prizename){
		$query = "	select a.`presentation_id`, c.`prize_name`, c.`prize_value`, a.`status`,c.`id`,c.`prize_image`,d.`symbol_image`,d.`pieces`, d.`symbol_id`, e.`id` AS process_symbol , e.`extra_from` , e.`extra_to`, e.`clone_from`, e.`clone_to`, e.`shuffle_from`
					from #__symbol_presentation a
					inner join #__symbol_symbol_prize b on b.`presentation_id` = a.`presentation_id`
					inner join #__symbol_prize c on c.`id` = b.`id`
					inner join #__symbol_symbol d on b.`symbol_id`=d.`symbol_id`
					LEFT JOIN #__ap_symbol_process e ON e.`presentation_id` = a.`presentation_id` 
					WHERE b.symbol_prize_id = '".$selectedPresentation."'
				";

		//$query .= !empty($id) || $id != "" ? "a.`presentation_id` in (".$id.") " : "1!=1";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
		/*	query = " SELECT a.* ,c.`prize_name`, c.`prize_value`, a.`status`,c.`id`,c.`prize_image`,d.`symbol_image`,d.`pieces`, d.`symbol_id`
		FROM  #__process_presentation_list
		inner join #__symbol_symbol_prize b ON b.presentation_id = a.selected_presentation
		inner join #__symbol_prize c on c.`id` = b.`id`
		inner join #__symbol_symbol d on b.`symbol_id`= d.`symbol_id`
		WHERE a.selected_presentation = '".$selectedId."'
	
	"; */
	public function getSymbolPresentation($prize_name){
		$query = "SELECT c.*, d.symbol_image, d.pieces, e.fund_prize_plan, e.funding_value_from, e.funding_value_to
 		 from #__symbol_symbol_prize b 
		 LEFT JOIN #__symbol_prize c ON c.id = b.id	 
		 LEFT JOIN #__symbol_symbol d ON d.symbol_id = b.symbol_id	 
		 LEFT JOIN #__process_presentation e ON e.id = b.presentation_id	 
					WHERE c.`prize_name` = '".$prize_name."'
				";

		//$query .= !empty($id) || $id != "" ? "a.`presentation_id` in (".$id.") " : "1!=1";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getSymbolPresentationList22($PresentationId,$selectedPresentation){
		$query = "	select a.`presentation_id`, c.`prize_name`, c.`prize_value`, a.`status`,c.`id`,c.`prize_image`,d.`symbol_image`,d.`pieces`, d.`symbol_id`, e.`id` AS process_symbol , e.`extra_from` , e.`extra_to`, e.`clone_from`, e.`clone_to`, e.`shuffle_from`, e.prize_value_from ,e.prize_value_to, f.`selected_presentation`, f.`presentation`
					from #__symbol_presentation a
					inner join #__symbol_symbol_prize b on b.`presentation_id` = a.`presentation_id`
					inner join #__symbol_prize c on c.`id` = b.`id`
					inner join #__symbol_symbol d on b.`symbol_id`=d.`symbol_id`
					inner join #__process_presentation_list f on f.`selected_presentation`= a.`presentation_id` 
					inner JOIN #__ap_symbol_process e ON e.`presentation_id` = a.`presentation_id` 
					WHERE f.`selected_presentation` = '".$selectedPresentation."' AND f.`presentation` = '".$PresentationId."'
				";

		//$query .= !empty($id) || $id != "" ? "a.`presentation_id` in (".$id.") " : "1!=1";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	
	public function getSymbolPresentationList($PresentationId,$selectedPresentation){
		$query = "	select a.*, d.`prize_name`, d.`prize_value`, d.`id`,d.`prize_image`, f.`symbol_image`,f.`pieces`, f.`symbol_id`,
		g.`extra_from` , g.`extra_to`, g.`clone_from`, g.`clone_to`, g.`shuffle_from`, g.prize_value_from ,g.prize_value_to
					from #__process_presentation_list a
					inner join #__process_presentation b on b.id = a.`presentation`
					inner join #__symbol_symbol_prize c on c.`presentation_id` = a.`selected_presentation`
					inner join #__symbol_prize d on d.`id` = c.`id`
					inner join #__symbol_symbol f on f.`symbol_id`= c.`symbol_id`	
					LEFT JOIN #__ap_symbol_process g ON g.`selected_presentation` = a.`presentation` AND g.`presentation_id` = a.`selected_presentation` 													
					WHERE a.`selected_presentation` = '".$selectedPresentation."' AND a.`presentation` = '".$PresentationId."' 
				";


		//$query .= !empty($id) || $id != "" ? "a.`presentation_id` in (".$id.") " : "1!=1";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function updatePresentationStatus($presentationId) {
		$db	= &JFactory::getDbo();
		$query = "update #__symbol_presentation set selected = '0'";
		$db->setQuery($query);
		if (! $db->query()) {
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
			return false;
		} else {
			$query = "update #__symbol_presentation set selected = '1' where presentation_id = '".$presentationId."' ";
			$db->setQuery($query);
			if (! $db->query()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
			return true;
		}
	}
	public function check_prize($id) {
		$db		= &JFactory::getDbo();
		$query = "
			select a.*,b.*,c.id AS prize_id, a.id AS process_symbol
			from
			#__ap_symbol_process AS a
			inner join #__symbol_symbol_prize AS b ON a.presentation_id=b.presentation_id
			inner join #__symbol_prize AS c ON c.`id`=b.`id`
			where c.unlocked_status = '0' ";
		$query .= (!empty($id) || $id != "" ? " and b.presentation_id in (".$id.") " : "1!=1");
		$db->setQuery($query);
		$db->query();
		$rows = $db->loadObjectList();
		return $rows;
	}
	public function check_prize_2($id, $process_symbol) {
		$db		= &JFactory::getDbo();
		$query = "
			select a.*,b.*,c.id AS prize_id, a.id AS process_symbol
			from
			#__ap_symbol_process AS a
			inner join #__symbol_symbol_prize AS b ON a.presentation_id=b.presentation_id
			inner join #__symbol_prize AS c ON c.`id`=b.`id`
			where c.unlocked_status = '0' ";		
		$query .= (!empty($id) || $id != "" ? " and b.presentation_id in (".$id.") " : "1!=1");
		$query .= " and a.`id` = '".$process_symbol."' ";
		$db->setQuery($query);
		$db->query();
		$rows = $db->loadObjectList();
		return $rows;
	}
	public function get_clone_prize($prize_id,$process_id,$symbol_id) {
		$db		= &JFactory::getDbo();
		$query  = "
			select *
			from #__ap_symbol_process_process_clone AS a
			where process_id = '".$process_id."' and prize_id = '".$prize_id."' and symbol_id = '".$symbol_id."'
		";	
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
	}
	public function delete_clone_pieces($symbol_id, $clone_id) {
		$db		= &JFactory::getDbo();
		$query  = "
			delete from #__symbol_symbol_pieces where symbol_pieces_id NOT IN 
			(
				SELECT pieces_id FROM #__ap_symbol_process_clone WHERE clone_id = '".$clone_id."'
			) AND symbol_id = '".$symbol_id."' AND is_lock = 0
		";	
		$db->setQuery($query);
		$db->query();
	}
	public function delete_clone_detail($clone_id){
		$db	= &JFactory::getDbo();
		$query = "
			DELETE FROM #__ap_symbol_process_clone
			WHERE clone_id = '".$clone_id."'
		";
		$db->setQuery($query);
		$db->query();
	}
	public function delete_clone_prize($prize_id,$process_id,$symbol_id) {
		$db	= &JFactory::getDbo();
		$query = "
			DELETE FROM #__ap_symbol_process_process_clone
			WHERE process_id = '".$process_id."' AND prize_id = '".$prize_id."' AND symbol_id = '".$symbol_id."'
		";
		$db->setQuery($query);
		$db->query();
	}
	public function onSelectedPricingRPC($prize_id,$process_id,$symbol_id, $rpc_selected_for_pricing){
		$db	= &JFactory::getDbo();
		$query = "
			UPDATE #__ap_symbol_process_process_clone SET percent_pricing = '".$rpc_selected_for_pricing."'
			WHERE process_id = '".$process_id."' AND prize_id = '".$prize_id."' AND symbol_id = '".$symbol_id."'
		";
		$db->setQuery($query);
		$db->query();
	}
	public function onInsertOfPricedRPC($prize_id,$process_id,$symbol_id,$insert_of_priced_rpc){
		$db	= &JFactory::getDbo();
		$query = "
			UPDATE #__ap_symbol_process_process_clone SET percent_of_priced_rpc = '".$insert_of_priced_rpc."'
			WHERE process_id = '".$process_id."' AND prize_id = '".$prize_id."' AND symbol_id = '".$symbol_id."'
		";
		$db->setQuery($query);
		$db->query();
	}
	public function onInsertOfFreePRC($prize_id,$process_id,$symbol_id,$insert_of_free_rpc){
		$db	= &JFactory::getDbo();
		$query = "
			UPDATE #__ap_symbol_process_process_clone SET percent_of_free_rpc = '".$insert_of_free_rpc."'
			WHERE process_id = '".$process_id."' AND prize_id = '".$prize_id."' AND symbol_id = '".$symbol_id."'
		";
		$db->setQuery($query);
		$db->query();
	}
	public function save_clone_prize($prize_id,$process_id,$symbol_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_process_clone");
		$query->set("process_id='".$process_id."'");
		$query->set("prize_id='".$prize_id."'");
		$query->set("symbol_id='".$symbol_id."'");
		$db->setQuery($query);
		$db->query();
		return $db->insertId();
	}
	public function getPieces($symbol_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_symbol_pieces AS a");
		$query->order("RAND()");
		$query->where("a.symbol_id='".$symbol_id."'");
		$query->where("a.is_lock='0'");
		$db->setQuery($query);
		$rows	= $db->loadObjectList();
		return $rows;
	}
	public function save_clone_pieces($symbol_id,$symbol_pieces_image){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__symbol_symbol_pieces");
		$query->set("symbol_id='".$symbol_id."'");
		$query->set("symbol_pieces_image='".$symbol_pieces_image."'");
		$db->setQuery($query);
		$db->query();
	}
	public function save_clone_detail($clone_id,$pieces_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_clone");
		$query->set("pieces_id='".$pieces_id."'");
		$query->set("clone_id='".$clone_id."'");
		$db->setQuery($query);
		$db->query();
	}
	
	public function get_clone_detail($prize_id, $process_id, $symbol_id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query  = "
			SELECT b.* FROM #__ap_symbol_process_process_clone a
			INNER JOIN #__ap_symbol_process_clone b ON b.`clone_id` = a.`id`
			WHERE a.`prize_id` = '".$prize_id."' AND a.`process_id` = '".$process_id."' AND a.`symbol_id` = '".$symbol_id."'
		";
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
	}
	public function updateCloneDetailForLock($prize_id, $process_id, $symbol_id, $count, $lock){
		$clones = $this->get_clone_detail($prize_id, $process_id, $symbol_id);
		for($i = 0; $i < $count; $i++){
			$clone = $clones[$i];
			$db	= &JFactory::getDbo();
			$query	= $db->getQuery(TRUE);
			$query = "
				UPDATE #__ap_symbol_process_clone SET is_lock = '".$lock."' WHERE id = '".$clone->id."'
			";
			$db->setQuery($query);
			$db->query();
		}
	}
	public function updateInsertOfPricedRPC($id,$status){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query = "
				UPDATE #__ap_symbol_process_clone SET is_lock_priced_rpc = '".$status."' WHERE id = '".$id."'
			";
		$db->setQuery($query);
		$db->query();
	}
	public function updateInsertOfFreeRPC($id, $status){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query = "
				UPDATE #__ap_symbol_process_clone SET is_lock_free_rpc = '".$status."' WHERE id = '".$id."'
			";
		$db->setQuery($query);
		$db->query();
	}
	public function updateCloneDetailForSelectedPrice($prize_id, $process_id, $price, $symbol_id){
		$clones = $this->get_clone_detail($prize_id, $process_id, $symbol_id);
		foreach ($clones as $clone) {
			$db	= &JFactory::getDbo();
			$query	= $db->getQuery(TRUE);
			$query = "
				UPDATE #__ap_symbol_process_clone SET price = '".$price."' WHERE id = '".$clone->id."' AND is_lock = '1'
			";
			$db->setQuery($query);
			$db->query();
		}
	}
	public function getExtract_2($process_id, $prize_id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_symbol_process_prize_extracted");
		$query->where("prize_id='".$prize_id."' and process_id='".$process_id."'");
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
	}
	public function delete_extract_detail($extract_id) {
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->delete("#__ap_symbol_process_extract");
		$query->where("extract_id='".$extract_id."'");
		$db->setQuery($query);
		$db->query();
	}
	public function deleteExtract($id) {
		$db	= &JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->delete("#__ap_symbol_process_prize_extracted");
		$query->where("id='".$id."' ");
		$db->setQuery($query);
		$db->query();
	}
	public function activeStatusAll($symbol_id) {
		$db	= &JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->update("#__symbol_symbol_pieces");
		$query->set("is_lock='0'");
		$query->where("symbol_id='".$symbol_id."'");
		$db->setQuery($query);
		$db->query();
	}
	public function saveExtract($process_id,$prize_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_prize_extracted");
		$query->set("prize_id='".$prize_id."'");
		$query->set("process_id='".$process_id."'");
		$db->setQuery($query);
		$db->query();
		return $db->insertId();
	}
	public function getPiecesAll($symbol_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_symbol_pieces AS a");
		$query->order("RAND()");
		$query->where("a.symbol_id='".$symbol_id."'");
		$query->where("a.is_lock='0'");
		$query->order("a.symbol_pieces_id");
		$db->setQuery($query);
		$rows	= $db->loadObjectList();
		return $rows;
	}
	public function save_extract_detail($extract_id,$pieces_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_extract");
		$query->set("pieces_id='".$pieces_id."'");
		$query->set("extract_id='".$extract_id."'");
		$db->setQuery($query);
		$db->query();
	}
	public function updateStatus($pieces_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__symbol_symbol_pieces");
		$query->set("is_lock='1'");
		$query->where("symbol_pieces_id='".$pieces_id."'");
		$db->setQuery($query);
		$db->query();
	}
	public function saveUpdateExtractData($extra_from, $id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__ap_symbol_process");
		$query->set("extra_from = '".$extra_from."'");
		$query->where("id = '".$id."'");
		$db->setQuery($query);
		return $db->query();
	}
	public function saveUpdateClonData($clon_from, $id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__ap_symbol_process");
		$query->set("clone_from = '".$clon_from."'");
		$query->where("id = '".$id."'");
		$db->setQuery($query);
		return $db->query();
	}
	public function saveUpdateShuffleData($shuffle_from, $id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__ap_symbol_process");
		$query->set("shuffle_from = '".$shuffle_from."'");
		$query->where("id = '".$id."'");
		$db->setQuery($query);
		return $db->query();
	}
	public function saveUpdatePricePiecesData($price_pieces, $id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__ap_symbol_process");
		$query->set("prize_value_from = '".$price_pieces."'");
		$query->where("id = '".$id."'");
		$db->setQuery($query);
		return $db->query();
	}
	public function saveUpdatePriceValuesData($price_pieces, $id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__ap_symbol_process");
		$query->set("prize_value_to = '".$price_pieces."'");
		$query->where("id = '".$id."'");
		$db->setQuery($query);
		return $db->query();
	}
	function getDataDetail($gcid){
		$db			= &JFactory::getDbo();
		$query		= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_symbol_prize AS a");
		$query->innerJoin("#__symbol_prize AS b ON a.id=b.id");
		$query->innerJoin("#__symbol_symbol AS c ON a.symbol_id=c.symbol_id");
		$query->where("a.symbol_prize_id = '" . $gcid . "'");
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	public function get_extracted_pieces($id, $symbol_id, $processId=-1) {
		$query = "
			SELECT d.`symbol_pieces_id`, d.`symbol_id`, d.`symbol_pieces_image`, d.`is_lock` FROM 
			#__ap_symbol_process_prize_extracted a 
			INNER JOIN #__ap_symbol_process_extract b ON b.`extract_id` = a.`id` 
			INNER JOIN #__symbol_symbol_prize c ON c.`id` = a.`prize_id` ".
		(!empty($id) || $id != "" ? " AND c.`presentation_id` in (".$id.") " : "") .
		    " INNER JOIN #__symbol_symbol_pieces d ON d.`symbol_pieces_id` = b.`pieces_id` AND d.`symbol_id` IN (".$symbol_id.")
		    AND a.`process_id` = '".$processId."' ";
		$query .= " GROUP BY d.`symbol_pieces_id`, d.`symbol_id`, d.`symbol_pieces_image`, d.`is_lock` ";

		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();
		return $data;
	}
	public function insertNewProcessSymbol($presentation_id, $selectedPresentation) {
		$db	= &JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process");
		$query->set("presentation_id = '".$presentation_id."'");
		$query->set("selected_presentation = '" .$selectedPresentation. "' ");
		$db->setQuery($query);
		$db->query();
		return $db->insertId();
	}
	
	public function getProcessSymbolByPresentationId($id, $selectedPresentation){
		$query = "select * from #__ap_symbol_process where selected_presentation = '".$selectedPresentation."' ";
		$query .= !empty($id) || $id != "" ? " and presentation_id in (".$id.") " : "1!=1";
		$this->_db->setQuery($query);
		$symbols = $this->_db->loadObjectList();
		if(!empty($symbols)){
			return $symbols[0];
		}
		return null;
	}
	//utk prize
	public function getExtractPrize_2($process_id, $prize_id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_symbol_process_prize_extracted");
		$query->where("prize_id='".$prize_id."' and process_id='".$process_id."'");
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
	}
	public function delete_extractPrize_detail($extract_id) {
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->delete("#__ap_symbol_process_extract");
		$query->where("extract_id='".$extract_id."'");
		$db->setQuery($query);
		$db->query();
	}
	public function deleteExtractPrize($id) {
		$db	= &JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->delete("#__ap_symbol_process_prize_extracted");
		$query->where("id='".$id."' ");
		$db->setQuery($query);
		$db->query();
	}
	public function activeStatusAllPrize($symbol_id) {
		$db	= &JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->update("#__symbol_symbol_pieces");
		$query->set("is_lock='0'");
		$query->where("symbol_id='".$symbol_id."'");
		$db->setQuery($query);
		$db->query();
	}
	public function saveExtractPrize($process_id,$prize_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_prize_extracted");
		$query->set("prize_id='".$prize_id."'");
		$query->set("process_id='".$process_id."'");
		$db->setQuery($query);
		$db->query();
		return $db->insertId();
	}
	public function getPiecesAllPrize($symbol_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_symbol_pieces AS a");
		$query->order("RAND()");
		$query->where("a.symbol_id='".$symbol_id."'");
		$query->where("a.is_lock='0'");
		$query->order("a.symbol_pieces_id");
		$db->setQuery($query);
		$rows	= $db->loadObjectList();
		return $rows;
	}
	public function save_extract_detailPrize($extract_id,$pieces_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_extract");
		$query->set("pieces_id='".$pieces_id."'");
		$query->set("extract_id='".$extract_id."'");
		$db->setQuery($query);
		$db->query();
	}
	public function updateStatusPrize($pieces_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__symbol_symbol_pieces");
		$query->set("is_lock='1'");
		$query->where("symbol_pieces_id='".$pieces_id."'");
		$db->setQuery($query);
		$db->query();
	}
	
	public function saveUpdateExtractDataPrize($extra_from, $id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__ap_symbol_process");
		$query->set("prize_value_from = '".$extra_from."'");
		$query->where("id = '".$id."'");
		$db->setQuery($query);
		return $db->query();
	}
	
	public function getUpdateSymbol($id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_symbol_process");
		$query->where("id='".$id."'");
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
	}
	
	public function saveUpdateSymbol($extra_from, $value_pieces, $clone_vpc ,$clone_fpc ,$id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__ap_symbol_process");
		$query->set("extra_from = '".$extra_from."'");
		$query->set("extra_to = '".$value_pieces."'");
		$query->set("clone_from = '".$clone_vpc."'");
		$query->set("clone_to = '".$clone_fpc."'");
		$query->where("id = '".$id."'");
		$db->setQuery($query);
		return $db->query();
	}
	
	public function deleteSymbolPricing($presentation_id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->delete("#__symbol_pricing");
		$query->where("presentation_id = '".$presentation_id."'");
		$db->setQuery($query);
		$db->query();
	}
	public function saveSymbolPricing($presentation_id, $selectedPresentation){
		$query = $this->_db->getQuery(true);
		$query = "select * from #__symbol_pricing where
			presentation_id = '".$presentation_id."' and selected_presentation = '".$selectedPresentation."' ";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		if(!empty($rows)) {
			$row = $rows[0];
			return $row->symbol_pricing_id;
		} else {
			$is_all_user = '0';
			$db		= &JFactory::getDbo();
			$query	= $db->getQuery(TRUE);
			$query->insert("#__symbol_pricing");
			$query->set("is_all_user='".$is_all_user."'");
			$query->set("presentation_id='".$presentation_id."'");
			$query->set("selected_presentation = '".$selectedPresentation."' ");
			$query->set("is_publish='0'");
			$db->setQuery($query);
			$db->query();
			return $db->insertId();
		}
	}
	public function getPricingDetails($presentation_id) {
		$query = $this->_db->getQuery(true);
		$query = "select * from #__symbol_symbol_prize a
       			inner join #__symbol_prize b ON a.id=b.id 
       			left join #__symbol_symbol c ON a.symbol_id=c.symbol_id WHERE a.presentation_id='$presentation_id'";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	
	public function addPricingDetail($price_to, $details_id, $pricing_id, $presentation_id, $process_symbol_id){
		$db = JFactory::getDbo();
		for ($i = 0; $i < $price_to; $i++) {
			$total++;
		}
		$_data = $total / 2;
		$i = 0;
		$virtual_price = $_data + 0;
		if ($details_id == null) {
			$details = $this->getPricingDetails($presentation_id);
			foreach ($details as $price_detail) {
				$pricing_details_id = $this->PricingDetails($pricing_id, $price_detail->id, $price_detail->symbol_id, $price_to, $virtual_price);
				$detail_pieces = $this->getPieces_3($price_detail->symbol_id, $process_symbol_id);
				foreach ($detail_pieces as $pieces_detail) {
					$q_breakdown = "insert into #__symbol_pricing_breakdown (detailsid,price_from,price_to,virtual_price_breakdown,symbol_pieces_id,status) VALUES ('" .$pricing_details_id . "','0','".$price_to."','" . $virtual_price . "','" . $pieces_detail->symbol_pieces_id . "','0')";
					$db->setQuery($q_breakdown);
					$db->query();
				}
			}
		} else {
			$query = "update #__symbol_pricing_details SET price_to='".$price_to."', virtual_price='" . $virtual_price . "' where details_id='" . $details_id . "'";
			$db->setQuery($query);
			$db->query();
			$breakdowns = $this->pricingBreakdownDetails($details_id);
			foreach ($breakdowns as $breakdown) {
				$q_update = "update #__symbol_pricing_breakdown SET price_to='".$price_to."',virtual_price_breakdown='" . $virtual_price . "' WHERE breakdownid='" . $breakdown->breakdownid . "'";
				$db->setQuery($q_update);
				$db->query();
			}
		}
	}
	
	public function addPricingDetail2($price_to, $details_id, $pricing_id, $presentation_id, $process_symbol_id){
		$db = JFactory::getDbo();
		for ($i = 0; $i < $price_to; $i++) {
			$total++;
		}
		$_data = $total / 2;
		$i = 0;
		$virtual_price = $_data + 0;
		if ($details_id == null) {
			$details = $this->getPricingDetails($presentation_id);
			foreach ($details as $price_detail) {
				$pricing_details_id = $this->PricingDetails($pricing_id, $price_detail->id, $price_detail->symbol_id, $price_to, $virtual_price);
				$detail_pieces = $this->getPieces_3($price_detail->symbol_id, $process_symbol_id);
				foreach ($detail_pieces as $pieces_detail) {
					$q_breakdown = "insert into #__symbol_pricing_breakdown (detailsid,price_from,price_to,virtual_price_breakdown,symbol_pieces_id,status) VALUES ('" .$pricing_details_id . "','".$price_to."','0','". $virtual_price . "','" . $pieces_detail->symbol_pieces_id . "','0')";
					$db->setQuery($q_breakdown);
					$db->query();
				}
			}
		} else {
			$query = "update #__symbol_pricing_details SET price_from='".$price_to."', virtual_price='" . $virtual_price . "' where details_id='" . $details_id . "'";
			$db->setQuery($query);
			$db->query();
			$breakdowns = $this->pricingBreakdownDetails($details_id);
			foreach ($breakdowns as $breakdown) {
				$q_update = "update #__symbol_pricing_breakdown SET price_from='".$price_to."',virtual_price_breakdown='" . $virtual_price . "' WHERE breakdownid='" . $breakdown->breakdownid . "'";
				$db->setQuery($q_update);
				$db->query();
			}
		}
	}
	
	public function PricingDetails($pricing_id, $prize_id, $symbol_id, $price_to, $virtual_price) {
		$query = $this->_db->getQuery(true);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__symbol_pricing_details'));
		$query->where($this->_db->QuoteName('symbol_pricing_id')."='$pricing_id'");
		$query->where($this->_db->QuoteName('prize_id')."='".$prize_id."'");
		$query->where($this->_db->QuoteName('symbol_id')."='".$symbol_id."'");
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObject();
		if (count($rows) < 1) {
			$q = $this->_db->getQuery(true);
			$q->insert($this->_db->QuoteName('#__symbol_pricing_details'));
			$q->set("symbol_pricing_id='".$pricing_id."'");
			$q->set("prize_id='".$prize_id."'");
			$q->set("symbol_id='".$symbol_id."'");
			$q->set("price_from='0'");
			$q->set("price_to='".$price_to."'");
			$q->set("virtual_price='".$virtual_price."'");
			$this->_db->setQuery($q);
			$this->_db->query();
			return $this->_db->insertId();
		}else {
			return $rows;
		}
	}
	public function getPieces_2($symbol_id, $extracts) {
		$query = $this->_db->getQuery(TRUE);
		$query->select('*');
		$query->from($this->_db->QuoteName('#__symbol_symbol_pieces'));
		$query->where($this->_db->QuoteName('symbol_id')."='".$symbol_id."'");
		$query->where($this->_db->QuoteName('is_lock')."='0'");
		$query->where($this->_db->QuoteName('symbol_pieces_id') . 'not in ('.$extracts.')');
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function getPieces_3($symbol_id, $process_symbol_id) {
		$query = $this->_db->getQuery(TRUE);
		$query = "
				select * from #__symbol_symbol_pieces  
				where symbol_id = '".$symbol_id."' and
				symbol_pieces_id in (
					select pieces_id from #__ap_symbol_process_extract a
					inner join #__ap_symbol_process_prize_extracted b on b.id = a.extract_id
					and b.process_id = '".$process_symbol_id."'
				)
			";		
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function pricingBreakdownDetails($detailsid) {
		$query = $this->_db->getQuery(true);
		$query = "select * from #__symbol_pricing_breakdown where detailsid = '".$detailsid."' ";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function deleteSymbolPricingDetails($detailsid) {
		$db	= &JFactory::getDbo();
		$query = "
			DELETE FROM #__symbol_pricing_details WHERE details_id = '" .$detailsid. "' 
		";
		$db->setQuery($query);
		$db->query();
	}
	public function deletePricingBreakDownDetails($detailsid) {
		$db	= &JFactory::getDbo();
		$query = "
			DELETE FROM #__symbol_pricing_breakdown WHERE detailsid = '" .$detailsid. "' 
		";
		$db->setQuery($query);
		$db->query();
	}
	public function getPriceOfExtractedPieces($presentation_id, $selectedPresentation){

			$query = "
					SELECT a.* FROM #__symbol_pricing_details a
					INNER JOIN #__symbol_pricing c ON c.`symbol_pricing_id` = a.`symbol_pricing_id`
					AND c.`presentation_id` = '".$presentation_id."' AND c.`selected_presentation` = '".$selectedPresentation."'
				";
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			return $rows;
	}
	
	public function getPricingDetailsByPricingId($symbol_pricing_id){
		$query = $this->_db->getQuery(true);
		$query = "select * from #__symbol_pricing_details where symbol_pricing_id = '".$symbol_pricing_id."' ";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		if(!empty($rows)){
			return $rows[0];
		} else {
			return null;
		}
	}
	public function deleteFunding($presentation_id, $prize_id) {
		$db	= &JFactory::getDbo();
		$query = "
			DELETE FROM #__funding WHERE presentation_id = '" .$presentation_id. "'
		";
		$db->setQuery($query);
		$db->query();

		$query = "
			DELETE FROM #__funding_presentations WHERE prize_id = '" .$prize_id. "'
		";
		$db->setQuery($query);
		$db->query();
	}
	public function insertUpdateFunding($presentation_id, $package_id, $prize_id, $fundPrizes, $fundingQueue, $selectedPresentation){
		$user = JFactory::getUser();
		$funding_id = '';
		$dateCreated = date('Y-m-d H:i:s');
		$query = $this->_db->getQuery(true);
		$query = "select * from #__funding where package_id = '".$package_id."' and presentation_id = '".$presentation_id."' and selected_presentation = '".$selectedPresentation."' ";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		if(empty($rows)){
			$db	= &JFactory::getDbo();
			$query = "insert into #__funding (funding_session, funding_desc, funding_published, funding_created, package_id, presentation_id, selected_presentation)
					  values ('Funding_session','Funding_session','0','".$dateCreated."','".$package_id."','".$presentation_id."', '".$selectedPresentation."')";
			$db->setQuery($query);
			$db->query();
			$funding_id = $db->insertId();
		} else {
			$row = $rows[0];
			$funding_id = $row->funding_id;
		}
		if($funding_id != '') {
			$query = $this->_db->getQuery(true);
			$query = "select * from #__funding_presentations where prize_id = '".$prize_id."' and prize_funding_session_id = '".$funding_id."' ";
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			if(empty($rows)) {
				$db	= &JFactory::getDbo();
				$query = " insert into #__funding_presentations (prize_funding_session_id, prize_id, value, funding, shortfall, pct_funded,
						   status, unlocked_date, created, created_by, modified, modified_by, ordering, published, donation_id, revenue_id, queue) values 
						   ('".$funding_id."','".$prize_id."', '0', '0', '0', '" . (null == $fundPrizes ? '0' : $fundPrizes) . "', '0', null, '".$dateCreated."', 
						   '".$user->username."', null, null, '0', '0', '0', '0', '" . (null == $fundingQueue ? '' : $fundingQueue) .  "' ) ";
				$db->setQuery($query);
				$db->query();
			} else {
				$db	= &JFactory::getDbo();
				$query = "	update #__funding_presentations set pct_funded = '".$fundPrizes."'
							".(null != $fundingQueue ? ", queue = '".$fundingQueue."' " : "")."
							where prize_id = '".$prize_id."'
						   	and prize_funding_session_id = '".$funding_id."' ";
				$db->setQuery($query);
				$db->query();
			}
		}
	}
	public function getFundingPresentation($package_id, $id, $selectedPresentation='-1'){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT a.* FROM  #__funding_presentations a 
				INNER JOIN #__funding b ON b.`funding_id` = a.`prize_funding_session_id` AND b.`package_id` = '".$package_id."'
			";		
//							".($selectedPresentation != '-1' ? " AND b.`selected_presentation` in (".$selectedPresentation.") " : "")."								
	//$query .= !empty($id) || $id != "" ? " AND b.`presentation_id` in (".$id.") " : " AND 1!=1";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function getAllSymbolPiecesCollected($prize_id, $presentations){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT b.*, c.`symbol_pieces_image` FROM #__ap_symbol_process_process_clone a
				INNER JOIN #__ap_symbol_process_clone b ON b.`clone_id` = a.`id` AND b.`is_lock_priced_rpc` = '1' OR b.`is_lock_free_rpc` = '1'
				INNER JOIN #__ap_symbol_process d ON d.`id` = a.`process_id`
				INNER JOIN #__symbol_symbol_pieces c ON c.`symbol_pieces_id` = b.`pieces_id`
				WHERE a.`prize_id` = '".$prize_id."'
				" .(empty($presentations) ? " AND 1!=1 " : " AND d.`presentation_id` IN (".$presentations.")" ). "
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function createPresentationByUserGroup($usergroup, $package_id, $presentationid){
		$db = &JFactory::getDBO ();
		$createdate = date('Y-m-d H:i:s');
		$var_id = JRequest::getVar('var_id');
		if($var_id != ''){
			$query = "update #__usergroup_presentation set usergroup = '".$usergroup."' where id = '".$var_id."' ";
			$db->setQuery($query);
			$db->query();
			return 0;
		} else {
			$query = "insert into #__usergroup_presentation (usergroup, presentation_id, process_presentation, date_created, package_id) values
					('".$usergroup."', '".$presentationid."', null, '".$createdate."', '".$package_id."') ";
			$db->setQuery($query);
			if($db->query()) {
				$var_id = $db->insertId();
				return $var_id;
			}
		}
	}
	public function createPresentationByProcessSymbol($processPresentation,$prizename,$symbolqueue,$funding, $package_id){
		$db = &JFactory::getDBO ();
		$createdate = date('Y-m-d H:i:s');
		$var_id = JRequest::getVar('var_id');
		if($var_id != ''){
			$query = "update #__usergroup_presentation set process_presentation = '".$processPresentation."', funds = '".$funding."', symbol = '".$symbolqueue."', name = '".$prizename."', presentation_id = '".$symbolqueue."' where id = '".$var_id."' ";
			$db->setQuery($query);
			$db->query();
			return 0;
		} else {
			$query = "insert into #__usergroup_presentation (usergroup, presentation_id, process_presentation, name, funds, symbol, date_created, package_id) values
					(null, '".$symbolqueue."', '".$processPresentation."', '".$name."','".$funding."','".$symbolqueue."','".$createdate."', '".$package_id."') ";
			$db->setQuery($query);
			if($db->query()) {
				$var_id = $db->insertId();
				return $var_id;
			}
		}
	}
	public function getUserGroupByName($package_id, $groupId){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT DISTINCT dat.group_name, dat.field, COUNT(dat.account_id) AS accounts FROM (
					SELECT a.`criteria_id`, a.`group_name` AS group_name, UPPER(a.`field`) AS FIELD, b.`ap_account_id` account_id  FROM #__ap_usergroup a
					LEFT JOIN #__ap_useraccounts b ON (b.`firstname` LIKE CONCAT('%', a.`firstname`, '%')
					OR b.`lastname` LIKE CONCAT(a.`lastname`)) AND b.`package_id` = '".$package_id."'
					WHERE a.`field` = 'name' AND a.`package_id` = '".$package_id."'
					
					AND a.is_presentation = '1'
				) dat
				GROUP BY dat.criteria_id, dat.group_name, dat.field
			";	
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function getUserGroupByEmail($package_id, $groupId){
		$query = $this->_db->getQuery(true);
		$query = "
					SELECT DISTINCT dat.group_name, dat.field, COUNT(dat.account_id) AS accounts FROM (
					SELECT a.`criteria_id`, a.`group_name` AS group_name, UPPER(a.`field`) AS FIELD, b.`ap_account_id` account_id  FROM #__ap_usergroup a
					LEFT JOIN #__ap_useraccounts b ON LOWER(b.`email`) LIKE CONCAT('%', LOWER(a.`email`), '%') AND b.`package_id` = '".$package_id."'
					WHERE a.`field` = 'email' AND a.`package_id` = '".$package_id."'
					".($groupId == '0' ? " AND 1!=1 " : " AND a.`criteria_id` = '".$groupId."' ")."
					AND a.`is_presentation` = '1'
				) dat
				GROUP BY dat.criteria_id, dat.group_name, dat.field
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function getUserGroupByAge($package_id, $groupId){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT DISTINCT dat.group_name, dat.field, COUNT(dat.account_id) AS accounts FROM (
				SELECT a.`criteria_id`, a.`group_name` AS group_name, UPPER(a.`field`) AS FIELD, b.`ap_account_id` account_id  FROM #__ap_usergroup a
				LEFT JOIN #__ap_useraccounts b ON YEAR( NOW( ) ) - YEAR( b.`birthday` ) BETWEEN a.`from_age` AND a.`to_age` AND b.`package_id` = '".$package_id."'
				WHERE a.`field` = 'age' AND a.`package_id` = '".$package_id."'
				".($groupId == '0' ? " AND 1!=1 " : " AND a.`criteria_id` = '".$groupId."' ")."
				AND a.`is_presentation` = '1'				
				) dat
				GROUP BY dat.criteria_id, dat.group_name, dat.field
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function getUserGroupByGender($package_id, $groupId){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT DISTINCT dat.group_name, dat.field, COUNT(dat.account_id) AS accounts FROM (
				SELECT a.`criteria_id`, a.`group_name` AS group_name, UPPER(a.`field`) AS FIELD, b.`ap_account_id` account_id  FROM #__ap_usergroup a
				LEFT JOIN #__ap_useraccounts b ON b.`gender` = a.`gender` AND b.`package_id` = '".$package_id."'
				WHERE a.`field` = 'gender' AND a.`package_id` = '".$package_id."'
				".($groupId == '0' ? " AND 1!=1 " : " AND a.`criteria_id` = '".$groupId."' ")."
				AND a.`is_presentation` = '1'
				) dat
				GROUP BY dat.criteria_id, dat.group_name, dat.field
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function getUserGroupByLocation($package_id, $groupId){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT DISTINCT dat.group_name, dat.field, COUNT(dat.account_id) AS accounts FROM (
				SELECT a.`criteria_id`, a.`group_name` AS group_name, UPPER(a.`field`) AS FIELD, b.`ap_account_id` account_id  FROM #__ap_usergroup a
				LEFT JOIN #__ap_useraccounts b ON 
				(LOWER(b.`street`) LIKE CONCAT('%', LOWER(a.`street`), '%') OR 
				LOWER(b.`city`) LIKE CONCAT('%', LOWER(a.`city`), '%') OR 
				LOWER(b.`state`) LIKE CONCAT('%', LOWER(a.`state`), '%') OR 
				LOWER(b.`post_code`) LIKE CONCAT('%', LOWER(a.`post_code`), '%') OR 
				LOWER(b.`country`) LIKE CONCAT('%', LOWER(a.`country`), '%')) AND b.package_id = '".$package_id."' 
				WHERE a.`field` = 'location' AND a.`package_id` = '".$package_id."'
				".($groupId == '0' ? " AND 1!=1 " : " AND a.`criteria_id` = '".$groupId."' ")."
				AND a.`is_presentation` = '1'
				) dat
				GROUP BY dat.criteria_id, dat.group_name, dat.field
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function getAllUserGroups($package_id, $groupId='0'){
		$userGroups = array();
		$groupName = $this->getUserGroupByName($package_id, $groupId);
		$groupEmail = $this->getUserGroupByEmail($package_id, $groupId);
		$groupAge = $this->getUserGroupByAge($package_id, $groupId);
		$groupGender = $this->getUserGroupByGender($package_id, $groupId);
		$groupLocation = $this->getUserGroupByLocation($package_id, $groupId);
		foreach ($groupName as $group){
			$userGroups[] = $group;
		}
		foreach ($groupEmail as $group){
			$userGroups[] = $group;
		}
		foreach ($groupAge as $group){
			$userGroups[] = $group;
		}
		foreach ($groupGender as $group){
			$userGroups[] = $group;
		}
		foreach ($groupLocation as $group){
			$userGroups[] = $group;
		}
		return $userGroups;
	}

	public function getDonationByPackageId($package_id){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT dd.`transaction_id`,
				dd.`category_id`,dd.`donation_amount`,dd.`quantity`,
				dt.`user_id`,dt.`transaction`,dt.`credit`,dt.`debit`,dt.`payment_gateway`,dt.`status`
				FROM #__ap_donation_details dd
				INNER JOIN #__ap_donation_transactions dt ON dt.transaction_id = dd.transaction_id
				AND dt.package_id = '".$package_id."'			
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	public function getFundingByPackageId($package_id){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT fp.* FROM #__funding_presentations fp
				INNER JOIN #__funding f ON f.`funding_id` = fp.`prize_funding_session_id` AND f.`package_id` = '".$package_id."'	
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

public function getSymbolCount($groupId){
	$query = 'select
        			count(*)
        		from
        			#__symbol_queue
					where groupId ='.$groupId.'        		
        		';
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();
		return $total;
	
	}
	
	public function SelectUserGroupPresentations($package_id,$limitstart,$limit){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT up.*,pp.name, pp.prize_name, pp.prize_value, af.rate, sq.name as symbolqueue, sq.amount
				FROM #__usergroup_presentation as up	
				LEFT JOIN #__process_presentation pp ON pp.id = up.presentation_id	
				LEFT JOIN #__award_fund_plan af ON af.id = up.funds		
				LEFT JOIN #__symbol_queue_group sq ON sq.id = up.symbol			
				WHERE up.`package_id` = '".$package_id."'
				ORDER BY up.id DESC
				LIMIT ".$limitstart.", ".$limit."
			";
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		return $results;
		}

public function SelectUserGroupPresentationsID($groupId, $package_id,$limitstart,$limit){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT up.*,pp.name, pp.prize_name, pp.prize_value, af.rate, sq.name as symbolqueue, sq.amount
				FROM #__usergroup_presentation as up	
				LEFT JOIN #__process_presentation pp ON pp.id = up.presentation_id	
				LEFT JOIN #__award_fund_plan af ON af.id = up.funds		
				LEFT JOIN #__symbol_queue_group sq ON sq.id = up.symbol			
				WHERE up.`usergroup` = '".$groupId."'
				AND up.`package_id` = '".$package_id."'
				ORDER BY up.id
				LIMIT ".$limitstart.", ".$limit."
			";
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
return $results;
}

	public function getUserGroupPresentations($package_id) {
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT up.* FROM #__usergroup_presentation as up
				WHERE up.`package_id` = '".$package_id."'
			";
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		$arr_data = array();
		foreach ($results as $result) {
			$groupName = "";
			$groupAccounts = "";
			$groups = $this->getAllUserGroups($package_id, $result->usergroup);
			if(!empty($groups)){
				$group = $groups[0];
				$groupName = $group->group_name;
				$groupAccounts = $group->accounts;
			}
			$data = new stdClass();
			$data->id = $result->id;
			$data->usergroup = $result->usergroup;
			$data->presentation_id = $result->presentation_id;
			$data->presentation_user_group = $groupName;
			$data->presentation_users = $groupAccounts;
			if(!empty($result->process_presentation)){
				$data->selected_presentation = $result->selected_presentation;
				$processPresentations = $this->getSelectedPresentations($package_id, $result->process_presentation);
				$data->process_presentation = $result->process_presentation;
				$data->name = $result->name;
				$data->funds = $result->funds;
				$data->symbol = $result->symbol;
				$data->total_of_each = '0';
				$fundPrize = 0;
				//foreach ($processPresentations as $pr){
				$fundings = $this->getFundingPresentation($package_id, $pr->presentations, $data->selected_presentation);
				foreach ($fundings as $funding){
					$fundPrize = $fundPrize + (int) $funding->pct_funded;
				}
				//}
				$data->funding = (!empty($result->process_presentation) && $result->process_presentation != null ? $fundPrize : '');
			} else {
				$data->process_presentation = '';
				$data->total_of_each = '';
				$data->funding = '';
			}				
			$arr_data[] = $data;
		}
		return $arr_data;
	}

	public function deleteUserGroupPresentation($id){
		$db = &JFactory::getDBO ();
		$query = $this->_db->getQuery(true);
		$query = "
				select * from #__usergroup_presentation where id = '".$id."'	
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		foreach ($rows as $row){
			$query = "delete from #__selected_presentation where process_presentation = '".$row->process_presentation."' ";
			$db->setQuery($query);
			$db->query();
		}
		$query = "delete from #__usergroup_presentation where id = '".$id."' ";
		$db->setQuery($query);
		$db->query();
	}

	public function getProcessPresentation($package_id){
		$query = $this->_db->getQuery(true);
		$query = "
				select * from #__process_presentation where package_id = '".$package_id."'	
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}

	public function getPresentionsFromProcessPresentations($package_id, $processPresentation){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT pp.`id` as selected_presentation, pp.`presentations` FROM #__selected_presentation pp WHERE pp.`process_presentation` = '".$processPresentation."'
				AND pp.`package_id` = '".$package_id."'
			";		 
		$this->_db->setQuery($query);
		$rs = $this->_db->loadObjectList();
		return $rs;
	}

public function SaveUserGroupPresentation($cid,$idAwardFundID,$idSymbolGroupID,$idSymbolQueue,$idAssignSymbolQueue,$idUserGroupsId,$idPresentationID,$idSymbolQueueID,$idPrizeValueID) {
		$db = &JFactory::getDBO ();
		$createdate = date('Y-m-d H:i:s');		
$query = "update #__usergroup_presentation set 
            usergroup = '".$idUserGroupsId."' ,
			presentation_id = '".$idPresentationID."', 
			name ='".$idSymbolQueue."', 	
			funds ='".$idAwardFundID."', 	
			prize ='".$idSymbolQueueID."', 	
			prize_value ='".$idPrizeValueID."', 				
			symbol ='".$idSymbolGroupID."', 	
			date_created ='".$createdate."' 
			WHERE id ='".$cid."' "
			;
				$db->setQuery($query);
				$db->query();
				return ;
}

public function UpdateUserGroupSymbol($cid,$idPresentationID,$idSymbolGroupID){
		$db = &JFactory::getDBO ();
$createdate = date('Y-m-d H:i:s');		
$query = "update #__process_presentation set 
            symbol_queue = '".$idSymbolGroupID."' 
			WHERE id ='".$idPresentationID."' ";
				$db->setQuery($query);
				$db->query();

$query2 = "update #__symbol_queue_group set 
            selected = '".$idPresentationID."' 
			WHERE id = '".$idSymbolGroupID."' ";
				$db->setQuery($query2);
				$db->query();
				
				return ;
}

public function UpdateAwardFundPlan($cid,$idAwardFundID,$idUserGroupsId){
		$db = &JFactory::getDBO ();
$createdate = date('Y-m-d H:i:s');		
$query = "update #__award_fund_plan set 
            usergroup = '".$idUserGroupsId."' 
			WHERE id ='".$idAwardFundID."' ";
				$db->setQuery($query);
				$db->query();
				return ;
}

	public function getDistributePrizeQueue($package_id, $processPresentation, $selectedPresentation, $limit = 20, $limitstart = 0){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT b.*, a.usergroup 
				FROM #__usergroup_presentation a
				INNER JOIN `#__process_presentation` b ON b.`id` = a.`process_presentation` AND b.`package_id` = '".$package_id."'
				WHERE a.`process_presentation` = '".$processPresentation."'
			";
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		$result = null;
		$usergroup = null;
		if(!empty($results)){
			$result = $results[0];
		}
		if(null != $result){
			$selected_presentations = explode(',', $result->selected_presentation);
			$usergroup = $result->usergroup;
		}

		if(in_array('1', $selected_presentations)) {
			$status = JRequest::getVar('prizeStatus');
			$query = "
					SELECT * FROM (
						SELECT c.*, d.`symbol_pieces_image`,
						CASE
							WHEN f.`unlocked_date` IS NULL THEN
							'locked'
							ELSE
							'unlocked'
						END
						prize_status
						FROM 
						#__ap_symbol_process a
						INNER JOIN #__ap_symbol_process_prize_extracted b ON b.`process_id` = a.`id`
						INNER JOIN #__ap_symbol_process_extract c ON c.`extract_id` = b.`id`
						INNER JOIN #__symbol_symbol_pieces d ON d.`symbol_pieces_id` = c.`pieces_id`
						INNER JOIN #__funding e ON e.`selected_presentation` = a.`selected_presentation`
						INNER JOIN #__funding_presentations f ON f.`prize_funding_session_id` = e.`funding_id`
						WHERE a.selected_presentation = '".$selectedPresentation."'
						ORDER BY d.`symbol_pieces_id`
					) dat
					WHERE 1=1
					".(!empty($status) ? " AND dat.prize_status = '".$status."' " : "")."
				";
			$this->_db->setQuery($query, $limitstart, $limit);
			$results = $this->_db->loadObjectList();

			$arrdata = array();
			$i = 0;
			foreach ($results as $result){
				$i++;
				$usergroups = $this->getAllUserGroups($package_id, $usergroup);
				$ug = null;
				if(!empty($usergroups)){
					$ug = $usergroups[0];
				}
				$data = new stdClass();
				$data->number = $i;
				$data->history = '0';
				$data->users = $ug->accounts;
				$data->status = $result->prize_status;
				$data->einserted = $result->symbol_pieces_image;
				$data->symbolq = $i;
				$arrdata[] = $data;
			}

			$return['data']  = $arrdata;

			$query = "
					SELECT count(*)
					FROM 
					#__ap_symbol_process a
					INNER JOIN #__ap_symbol_process_prize_extracted b ON b.`process_id` = a.`id`
					INNER JOIN #__ap_symbol_process_extract c ON c.`extract_id` = b.`id`
					INNER JOIN #__symbol_symbol_pieces d ON d.`symbol_pieces_id` = c.`pieces_id`
					INNER JOIN #__funding e ON e.`selected_presentation` = a.`selected_presentation`
					INNER JOIN #__funding_presentations f ON f.`prize_funding_session_id` = e.`funding_id`
					WHERE a.selected_presentation = '".$selectedPresentation."'
					ORDER BY d.`symbol_pieces_id`
				";
			jimport('joomla.html.pagination');
			$this->_db->setQuery($query);
			$total = $this->_db->loadResult();

			$return['pagination'] = new JPagination( $total, $limitstart, $limit );
			$return['lists'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit    			
			);
		}
		return $return;
	}

	public function getFundPrizeHistory($package_id, $limit = 20, $limitstart = 0){
		$query = $this->_db->getQuery(true);
		$query = "
					SELECT c.`prize_value`, b.`pct_funded`, b.`value`,
					CASE
						WHEN b.`unlocked_date` IS NULL
						THEN 'Locked'
						ELSE 'Unlocked'
					END AS status
					FROM #__funding a
					INNER JOIN #__funding_presentations b ON b.`prize_funding_session_id` = a.`funding_id`
					INNER JOIN #__symbol_prize c ON c.`id`= b.`prize_id`
					WHERE a.`package_id` = '".$package_id."'					
				";
		$this->_db->setQuery($query, $limitstart, $limit);
		$results = $this->_db->loadObjectList();

		$arrdata = array();
		foreach ($results as $result){
			$data = new stdClass();
			$data->prize_value = $result->prize_value;
			$data->each_fund_prize = $result->pct_funded;
			$data->value_funded = $result->value;
			$data->shortfall = (int) $result->prize_value - (int) $result->value;
			$data->percent_funded = "0";
			$data->status = $result->status;
			$arrdata[] = $data;
		}

		$return['data']  = $arrdata;

		$query = "
					SELECT COUNT(*)					
					FROM #__funding a
					INNER JOIN #__funding_presentations b ON b.`prize_funding_session_id` = a.`funding_id`
					INNER JOIN #__symbol_prize c ON c.`id`= b.`prize_id`
					WHERE a.`package_id` = '".$package_id."'
				";
		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
		$return['lists'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit    			
		);

		return $return;
	}
	public function getUserGroupByName1($package_id, $groupId){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT dat.criteria_id, dat.group_name, dat.field, dat.account_id FROM (
					SELECT a.`criteria_id`, a.`group_name` AS group_name, UPPER(a.`field`) AS FIELD, b.`ap_account_id` account_id  FROM #__ap_usergroup a
					LEFT JOIN #__ap_useraccounts b ON (b.`firstname` LIKE CONCAT('%', a.`firstname`, '%')
					OR b.`lastname` LIKE CONCAT(a.`lastname`)) AND b.`package_id` = '".$package_id."'
					WHERE a.`field` = 'name' AND a.`package_id` = '".$package_id."'
					".($groupId == '0' ? " AND 1!=1 " : " AND a.`criteria_id` = '".$groupId."' ")."
					AND a.is_presentation = '1'
				) dat				
			";	
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function getUserGroupByEmail1($package_id, $groupId){
		$query = $this->_db->getQuery(true);
		$query = "
					SELECT dat.criteria_id, dat.group_name, dat.field, dat.account_id FROM (
					SELECT a.`criteria_id`, a.`group_name` AS group_name, UPPER(a.`field`) AS FIELD, b.`ap_account_id` account_id  FROM #__ap_usergroup a
					LEFT JOIN #__ap_useraccounts b ON LOWER(b.`email`) LIKE CONCAT('%', LOWER(a.`email`), '%') AND b.`package_id` = '".$package_id."'
					WHERE a.`field` = 'email' AND a.`package_id` = '".$package_id."'
					".($groupId == '0' ? " AND 1!=1 " : " AND a.`criteria_id` = '".$groupId."' ")."
					AND a.`is_presentation` = '1'
				) dat				
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function getUserGroupByAge1($package_id, $groupId){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT dat.criteria_id, dat.group_name, dat.field, dat.account_id FROM (
				SELECT a.`criteria_id`, a.`group_name` AS group_name, UPPER(a.`field`) AS FIELD, b.`ap_account_id` account_id  FROM #__ap_usergroup a
				LEFT JOIN #__ap_useraccounts b ON YEAR( NOW( ) ) - YEAR( b.`birthday` ) BETWEEN a.`from_age` AND a.`to_age` AND b.`package_id` = '".$package_id."'
				WHERE a.`field` = 'age' AND a.`package_id` = '".$package_id."'
				".($groupId == '0' ? " AND 1!=1 " : " AND a.`criteria_id` = '".$groupId."' ")."
				AND a.`is_presentation` = '1'				
				) dat				
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function getUserGroupByGender1($package_id, $groupId){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT dat.criteria_id, dat.group_name, dat.field, dat.account_id AS accounts FROM (
				SELECT a.`criteria_id`, a.`group_name` AS group_name, UPPER(a.`field`) AS FIELD, b.`ap_account_id` account_id  FROM #__ap_usergroup a
				LEFT JOIN #__ap_useraccounts b ON b.`gender` = a.`gender` AND b.`package_id` = '".$package_id."'
				WHERE a.`field` = 'gender' AND a.`package_id` = '".$package_id."'
				".($groupId == '0' ? " AND 1!=1 " : " AND a.`criteria_id` = '".$groupId."' ")."
				AND a.`is_presentation` = '1'
				) dat				
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	public function getUserGroupByLocation1($package_id, $groupId){
		$query = $this->_db->getQuery(true);
		$query = "
				SELECT dat.criteria_id, dat.group_name, dat.field, dat.account_id FROM (
				SELECT a.`criteria_id`, a.`group_name` AS group_name, UPPER(a.`field`) AS FIELD, b.`ap_account_id` account_id  FROM #__ap_usergroup a
				LEFT JOIN #__ap_useraccounts b ON 
				(LOWER(b.`street`) LIKE CONCAT('%', LOWER(a.`street`), '%') OR 
				LOWER(b.`city`) LIKE CONCAT('%', LOWER(a.`city`), '%') OR 
				LOWER(b.`state`) LIKE CONCAT('%', LOWER(a.`state`), '%') OR 
				LOWER(b.`post_code`) LIKE CONCAT('%', LOWER(a.`post_code`), '%') OR 
				LOWER(b.`country`) LIKE CONCAT('%', LOWER(a.`country`), '%')) AND b.package_id = '".$package_id."' 
				WHERE a.`field` = 'location' AND a.`package_id` = '".$package_id."'
				".($groupId == '0' ? " AND 1!=1 " : " AND a.`criteria_id` = '".$groupId."' ")."
				AND a.`is_presentation` = '1'
				) dat				
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	
	public function getAllUserGroups1($package_id, $groupId='0'){
		$userGroups = array();
		$groupName = $this->getUserGroupByName1($package_id, $groupId);
		$groupEmail = $this->getUserGroupByEmail1($package_id, $groupId);
		$groupAge = $this->getUserGroupByAge1($package_id, $groupId);
		$groupGender = $this->getUserGroupByGender1($package_id, $groupId);
		$groupLocation = $this->getUserGroupByLocation1($package_id, $groupId);
		foreach ($groupName as $group){
			$userGroups[] = $group;
		}
		foreach ($groupEmail as $group){
			$userGroups[] = $group;
		}
		foreach ($groupAge as $group){
			$userGroups[] = $group;
		}
		foreach ($groupGender as $group){
			$userGroups[] = $group;
		}
		foreach ($groupLocation as $group){
			$userGroups[] = $group;
		}
		return $userGroups;
	}
	public function getFundingByPresentation($package_id, $selectedPresentation='-1', $processPresentation='-1'){
		$groups = $this->getAllUserGroups1($package_id, JRequest::getVar('idUserGroupsId'));
//		if(!empty($groups)){
//			$results = array();
//			foreach ($groups as $group){
				$accountId = $group->accounts;
				$query = $this->_db->getQuery(true);
				$query = "
					SELECT a.`debit`, c.* FROM #__funding_history a
					INNER JOIN #__funding_user b ON b.`funding_id` = a.`funding_id`
					INNER JOIN #__ap_useraccounts c ON c.`id` = b.`user_id` AND c.`package_id` = '".$package_id."' 
					AND a.transaction_type='DONATION'
				";	
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();
				foreach ($rows as $row){
					$results[] = $row;
				}
			//}
			return $results;
		//}
		return null;
	}
	public function getPrizeClaimed($package_id, $selectedPresentation='-1', $processPresentation='-1'){
		$groups = $this->getAllUserGroups1($package_id, JRequest::getVar('idUserGroupsId'));
		if(!empty($groups)){
			$results = array();
			foreach ($groups as $group){
				$accountId = $group->accounts;
				$query = $this->_db->getQuery(true);
				$query = "
					SELECT c.* FROM #__ap_winners a
					INNER JOIN #__ap_winners_user b ON b.`ap_winner_id` = a.`id` AND b.`user_id` = '".$accountId."' 
					INNER JOIN #__ap_prize_claim c ON c.`winner_id` = a.`id`
					WHERE a.`package_id` = '".$package_id."'					
				";	
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();
				foreach ($rows as $row){
					$results[] = $row;
				}
			}
			return $results;
		}
		return null;
	}
	
	public function delete_categories($id){
		$queries = array();
		$id = implode(',', $id);			
		$queries[] = 'delete from #__process_presentation where id in ('.$id.')';			
		foreach ($queries as $query){
			$this->_db->setQuery($query);
			if(!$this->_db->query()){					
				return false;
			}
		}			
		return true;
	}
	
	public function getPrizeWon($package_id, $prize_id){
		$groups = $this->getAllUserGroups1($package_id, JRequest::getVar('idUserGroupsId'));
		if(!empty($groups)){
			$results = array();
			foreach ($groups as $group){
				$accountId = $group->accounts;
				$query = $this->_db->getQuery(true);
				$query = "
					SELECT b.`id`, b.`ap_winner_id`, b.`awarded_date`, c.`prize_image`, c.`prize_name`, c.`prize_value` FROM #__ap_winners a
					INNER JOIN #__ap_winners_user b ON b.`ap_winner_id` = a.`id` AND b.`user_id` = '".$accountId."' AND b.`prize_id` = '".$prize_id."'
					INNER JOIN #__symbol_prize c ON c.`id` = b.`prize_id`
					WHERE a.`package_id` = '".$package_id."' 					 					
				";
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();
				foreach ($rows as $row){
					$results[] = $row;
				}
			}
			return $results;
		}
		return null;
	}
	
	
}
