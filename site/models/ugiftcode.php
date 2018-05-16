<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.modelitem');
class AwardpackageUsersModelUgiftcode extends JModelLegacy {

	function __construct() {
		parent::__construct ();
	}
	
	function getAllGiftcodes ($gcid) {
		$query = "select * from #__giftcode_giftcode where giftcode_category_id ='" .$gcid. "'  ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function getAllGiftcodes_2() {
		$query = "select * from #__giftcode_giftcode  ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function getAllGiftcodesUser ($gcid,$userid) {
		$query = "SELECT * FROM #__giftcode_giftcode WHERE giftcode_setting_id = '" .$gcid. "' AND giftcode_queue_id = '" .$userid. "'";		
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function getAllUserGiftcodesHistory($package_id,$user_id,$limit, $limitstart){
  		$query = "select COUNT(b.category_name) as total, SUM(a.status) as jml, a.*, b.colour_code, b.category_name, c.giftcode 
		 from  #__gc_recieve_user a
		 LEFT JOIN #__ap_categories b ON b.setting_id = a.category_id
		 LEFT JOIN #__giftcode_giftcode c ON c.id = a.gcid 
		 WHERE a.user_id = '" .$user_id. "'
		 Group by b.category_name 
		 LIMIT ".$limitstart.", ".$limit."
 		  ";
		 //".$limitstart.", ".$limit."' ";
		 $this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	  }
	  
	function getAllUserGiftcodes($gcid, $userid) {
		$query = "select * from #__gc_recieve_user where category_id = '" .$gcid. "'  AND user_id = '" .$userid. "' AND status = 0 ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function getAllUserGiftcodes_2( $userid) {
		$query = "select * from #__gc_recieve_user where  user_id = '" .$userid. "' AND status = 0 ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function UpdateUserGiftcodes($giftcode, $giftcodeId,$userid) {
		$query4 = "UPDATE #__gc_recieve_user
			  SET status = '1'
			  WHERE	category_id = '" .$giftcode. "' AND gcid = '" .$giftcodeId. "'  AND user_id = '" .$userid. "' " ;
		$this->_db->setQuery($query4);
		$this->_db->query();
	
		$query5 = "UPDATE #__giftcode_giftcode
			  SET giftcode_queue_id = '" .$userid. "'
			  WHERE	id = '" .$giftcodeId. "' ";
		$this->_db->setQuery($query5);
		$this->_db->query();
		
		
		
	}
	
	function update_symbol_pieces($id,$userid){
		//$queries = array();
		$id = implode(',', $id);		
		$query = "UPDATE #__symbol_symbol_pieces
			  SET is_lock = '" .$userid. "' 
			  WHERE symbol_pieces_id in (".$id.")";	

		$this->_db->setQuery($query);
		$this->_db->query();
	
		return true;
	}
	
	function InsertSymbolQueue($id, $giftcode, $giftcodeId,$user_id, $prize_id,$symbol_id, $status ){
		//$queries = array();
		$id = implode(',', $id);		
		$date = JFactory::getDate();
		$now = $date;
		$query = "INSERT #__symbol_queue_detail ( symbol_pieces_id, status, symbol_prize_id, userid, gcid, category_id ,queue_id, date_created, date_end ) 
		VALUE 
		('".$id."', '".$status."' , '".$prize_id."','" .$user_id. "','" . $giftcodeId. "','" .$giftcode. "' ,'" .$symbol_id. "', '".$now."', '".$now."' )";
//			  SET is_lock = '" .$userid. "' 
	//		  WHERE symbol_pieces_id in (".$id.")";	

		$this->_db->setQuery($query);
		$this->_db->query();
	
		return true;
	}
	
	function getAllUserSymbol($symbol) {
		$query = "select a.*, b.id as prize_id, c.prize_name, c.prize_value  
		from #__symbol_symbol_pieces a
		INNER JOIN #__symbol_symbol_prize b ON b.symbol_id = a.symbol_id
		INNER JOIN #__symbol_prize c ON c.id = a.symbol_id
		where is_lock = 0 order by symbol_pieces_id asc";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}


					
	function getCategories($package_id){
		$query = "select setting_id, category_id, colour_code, category_name from #__ap_categories
				where package_id = '" .$package_id. "' order by category_id asc ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function getUserGiftCode($package_id,$userId,$category){
		if ($category =='All'){
		$query = "select *, b.category_name, c.description 
		 from  #__gc_recieve_user a
		 LEFT JOIN #__ap_categories b ON b.setting_id = a.status 
		 LEFT JOIN #__funding_history c ON c.funding_history_id = a.category_id 
		 WHERE a.user_id = '" .$userId. "' ";
		 }else {
		 $query = "select *, b.category_name, c.description 
		 from  #__gc_recieve_user a
		 LEFT JOIN #__ap_categories b ON b.setting_id = a.status 
		 LEFT JOIN #__funding_history c ON c.funding_history_id = a.category_id 
		 WHERE a.user_id = '" .$userId. "' AND a.status='" .$category. "'";
		 }
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
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
					INNER JOIN #__symbol_presentation d ON d.presentation_id = a.presentation_id AND d.package_id = '".$package_id."'									 LIMIT 1
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
	
	function getPresentationCategory($category, $package_id, $prize_id = '0'){
		$query = "
				SELECT pac.`category_id`, pac.`colour_code`, pac.`category_name`, ppc.`presentation_id`,
				pss.`symbol_image`, pss.`pieces`, pss.`rows`, pss.`cols`, ps.`id` as prize_image_id, ps.`prize_image`, ps.`prize_name`
				FROM #__presentation_category ppc
				INNER JOIN #__ap_categories pac ON pac.`category_id` = ppc.`category_id`
				INNER JOIN #__symbol_presentation psp ON psp.`presentation_id` = ppc.`presentation_id`
				INNER JOIN #__symbol_symbol_prize pssp ON pssp.`presentation_id` = psp.`presentation_id`
				INNER JOIN #__symbol_symbol pss ON pss.`symbol_id` = pssp.`symbol_id`
				INNER JOIN #__symbol_prize ps ON ps.`id` = pssp.`id` " .($prize_id == '0' ? "" : " AND ps.`id` = '" .$prize_id. "' "). "
				WHERE ppc.`category_id` = '" .$category. "' AND ppc.`package_id` = '" .$package_id. "'
			";
		$this->_db->setQuery ( $query );
		$rows = $this->_db->loadObjectList();
		if(!empty($rows)) {
			$row = $rows[0];
			$result = new stdClass();
			$result->category_id = $row->category_id;
			$result->colour_code = $row->colour_code;
			$result->category_name = $row->category_name;

			$prs = array();
			foreach ($rows as $row) {
				$pr = new stdClass();
				$pr->presentation_id = $row->presentation_id;
				$pr->symbol_image = $row->symbol_image;
				$pr->pieces = $row->pieces;
				$pr->rows = $row->rows;
				$pr->cols = $row->cols;
				$pr->prize_image_id = $row->prize_image_id;
				$pr->prize_image = $row->prize_image;
				$pr->prize_name = $row->prize_name;
				$prs[] = $pr;			
			}
			$result->presentation = $prs;
			return $result;
		}
		return null;
	}
}