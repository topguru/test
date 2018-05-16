<?php 
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class AwardpackageModelPrizeWon extends JModelList { 
	
	public function getListQuery(){
		$db		= &JFactory::getDbo();
		$user 	= &JFactory::getUser();
		$query	= $db->getQuery(TRUE);
		$query->select('b.*,a.id AS winner_id,a.awarded_date');
		$query->from($db->QuoteName('#__ap_winners_actual').' AS a');
		$query->innerJoin($db->QuoteName('#__symbol_prize').' AS b ON a.prize_id=b.id');
		$query->where("a.user_id='".(int) $user->id."'");
		return $query; 
	}
	
	function getPaypall(){
		$query = "select business from #__paypal_config  ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function checkClaim($winner_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select('*');
		$query->from('#__ap_prize_claim');
		$query->where("winner_id='".$winner_id."'");
		$db->setQuery($query);
		$row	= $db->loadObject();
		return $row;
	}
	
	public function save_claim($winner_id,$user_info){
		$db		= &JFactory::getDbo();
		$claimed = JRequest::getVar('claimed');
		//check first 
		$check_winner = $db->getQuery(TRUE);
		$check_winner ->select('*');
		$check_winner ->from("#__ap_prize_claim");
		$check_winner ->where("winner_id='".$winner_id."'");
		$db->setQuery($check_winner);
		$row_check = $db->loadObject();
		if(!$row_check){
			$query	= $db->getQuery(TRUE);
			$query->select('*');
			$query->from('#__ap_winners_actual AS a');
			$query->innerJoin('#__symbol_prize AS b ON a.prize_id=b.id');
			$db->setQuery($query);
			$row = $db->loadObject();
			$date = date('Y-m-d');
			if($row){
				$query_save = $db->getQuery(TRUE);
				$query_save->insert("#__ap_prize_claim");
				$query_save->set("winner_id='".$winner_id."'");
				$query_save->set("package_id='".$user_info->package_id."'");
				$query_save->set("prize_value='".$row->prize_value."'");
				$query_save->set("prize_name='".$row->prize_name."'");
				$query_save->set("claimed_date='".$date."'");
				$query_save->set("claimed_status='".$claimed."'");
				$db->setQuery($query_save);
				return $db->query();
			}
		}else{
			$query_save = $db->getQuery(TRUE);
			$query_save->update("#__ap_prize_claim");
			$query_save->set("claimed_status='".$claimed."'");
			$query_save->where("winner_id='".$winner_id."'");
			$db->setQuery($query_save);
			return $db->query();
		}
	}
	
	public function getPrizeSent(){
		$db = &JFactory::getDbo();
		$user = &JFactory::getUser();
		$query = $db->getQuery(TRUE);
		$query ->select('*');
		$query->from("#__ap_prize_claim AS a");
		$query->innerJoin("#__ap_winners_actual AS b ON a.winner_id=b.id");
		$query->innerJoin("#__symbol_prize AS c ON b.prize_id=c.id");
		$query->where("b.user_id='".$user->id."'");
		$query->where("a.send_status='1'");
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		//echo $query;
		return $rows;
	}
	
	public function getSymbolSymbolPrize($limit, $limitstart){
		$query = "	SELECT a.symbol_prize_id, c.prize_name, c.prize_image, c.prize_value, b.symbol_name, b.symbol_image, b.pieces, b.cols, b.rows, d.presentation_id, d.status, c.unlocked_status 
					FROM #__symbol_symbol_prize a 
					LEFT JOIN #__symbol_symbol b ON b.symbol_id = a.symbol_id 
					LEFT JOIN #__symbol_prize c ON c.id = a.id
					INNER JOIN #__symbol_presentation d ON d.presentation_id = a.presentation_id
					LIMIT ".$limitstart.", ".$limit." ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getTotalSymbol($userId,$status){
		$query = "	SELECT * 
					FROM #__gc_recieve_user  
					WHERE user_id='".$userId."' AND status='".$status."' ";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
}

?>