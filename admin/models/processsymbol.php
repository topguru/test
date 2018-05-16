<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * UserAccount Model
 */

class AwardpackageModelProcessSymbol extends JModelList{

	public function getListQuery(){

		$presentation_id	= JRequest::getInt('presentation_id');

		$db		= &JFactory::getDbo();

		$query	= $db->getQuery(TRUE);

		$query->select("*");

		$query->from("#__ap_symbol_process");

		$query->where("presentation_id='".$presentation_id."'");

		$query->order("id ASC");

		return $query;
	}
	
	public function addItem($data){

		$row	= $this->getTable('processsymbol');
		//bind data
		if (!$row->bind($data)) {
			return JError::raiseWarning(500, $row->getError());
			return false;
		} else {
			if (!$row->store()) {
				return JError::raiseError(500, $row->getError());
			} else {
				return true;
			}
		}
		return true;
	}

	public function getItem($id){

		$db		= &JFactory::getDbo();

		$query	= $db->getQuery(TRUE);

		$query->select("*");

		$query->from("#__ap_symbol_process");

		$query->where("id='".$id."'");

		$db->setQuery($query);

		return $db->loadObject();
	}
	
	public function checkProcessSymbolPrizeRange($prize_from,$prize_to){

		$db		= &JFactory::getDbo();

		$query	= $db->getQuery(TRUE);

		$query->select("*");

		$query->from("#__ap_symbol_process");

		$query->where("prize_value_from<='".$prize_from."'");

		$query->where("prize_value_to>='".$prize_to."'");
		
		$db->setQuery($query);

		return $db->loadObject();
	}

	public function checkProcessSymbolPrizeRangeTo($prize_to){

		$db		= &JFactory::getDbo();

		$query	= $db->getQuery(TRUE);

		$query->select("*");

		$query->from("#__ap_symbol_process");

		$query->where("prize_value_from<='".$prize_to."'");

		$query->where("prize_value_to>='".$prize_to."'");

		$db->setQuery($query);

		return $db->loadObject();
	}

	public function saveProcessPrizeRange($prize_from,$prize_to,$id){

		$db		= &JFactory::getDbo();

		$query	= $db->getQuery(TRUE);

		$query->update("#__ap_symbol_process");

		$query->set("prize_value_from='".$prize_from."'");

		$query->set("prize_value_to='".$prize_to."'");

		$query->where("id = '".$id."'");
		
		$db->setQuery($query);
		return $db->query();

	}

	public function CheckPrize($prize_from,$prize_to,$presentation_id){

		$db		= &JFactory::getDbo();

		$query	= $db->getQuery(TRUE);

		$query->select("a.*,b.*,c.id AS prize_id");

		$query->from("#__ap_award_symbol_progress AS a");

		$query->innerJoin("#__symbol_symbol_prize AS b ON a.symbol_presentation=b.presentation_id");

		$query->innerJoin("#__symbol_prize AS c ON c.id=b.symbol_prize_id");

		$query->where("c.prize_value>='".$prize_from."'");

		$query->where("c.prize_value<='".$prize_to."'");

		$query->where("b.presentation_id='$presentation_id'");

		$query->where("c.unlocked_status='0'");
		
		$db->setQuery($query);

		$db->query();

		$rows	= $db->loadObjectList();

		return $rows;
	}
	
	public function CheckPrize2($presentation_id){
		$db		= &JFactory::getDbo();
		$query = "
				SELECT a.*,b.*,c.id AS prize_id, a.id as process_symbol
				FROM
				#__ap_symbol_process AS a
				INNER JOIN #__symbol_symbol_prize AS b ON a.presentation_id=b.presentation_id
				INNER JOIN #__symbol_prize AS c ON c.id=b.symbol_prize_id
				WHERE
				1=1
				AND b.presentation_id= '" .$presentation_id. "'
				AND c.unlocked_status='0'
			";
		$db->setQuery($query);
		$db->query();
		$rows	= $db->loadObjectList();
		return $rows;
	}

	public function saveProcessPrize($process_id,$prize_id,$symbol_id){

		$db		= &JFactory::getDbo();

		$query	= $db->getQuery(TRUE);

		$query->insert("#__ap_symbol_process_prize");

		$query->set("process_id='".$process_id."'");

		$query->set("prize_id='".$prize_id."'");

		$query->set("symbol_id='".$symbol_id."'");

		$db->setQuery($query);

		$db->query();
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
	
	public function getPiecesByPiecesId($symbol_pieces_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_symbol_pieces AS a");
		$query->where("a.symbol_pieces_id='".$symbol_pieces_id."'");
		$query->where("a.is_lock='0'");
		$db->setQuery($query);
		$rows	= $db->loadObjectList();
		return $rows;
	}

	public function getPiecesAll($symbol_id){
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

	public function shuffled(){

		$db		= &JFactory::getDbo();

		$query	= $db->getQuery(TRUE);

		$query->select("*");

		$query->from("#__ap_symbol_process_prize AS a");

		$query->innerJoin("#__symbol_symbol_pieces AS b ON a.symbol_id=b.symbol_id");

		$query->innerJoin("#__ap_symbol_process AS c ON a.process_id=c.id");

		$query->order("RAND()");
		
		$db->setQuery($query);

		$db->query();

		//echo $db->getErrorMsg();

		return $db->loadObjectList();
	}
	
	public function shuffled_2($presentation_id) {
		$db		= &JFactory::getDbo();
		$query  = "
			SELECT *
			FROM #__symbol_symbol_prize AS a
			INNER JOIN #__symbol_symbol_pieces AS b ON a.`symbol_id` = b.`symbol_id` AND b.`is_lock` = 0
			INNER JOIN #__ap_symbol_process AS c ON c.`presentation_id` = a.`presentation_id`
			WHERE a.presentation_id = '" .$presentation_id. "'
			ORDER BY RAND()
		";	
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
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
	
	public function delete_clone_prize($prize_id,$process_id,$symbol_id) {
		$db	= &JFactory::getDbo();
		$query = "
			DELETE FROM #__ap_symbol_process_process_clone
			WHERE process_id = '".$process_id."' AND prize_id = '".$prize_id."' AND symbol_id = '".$symbol_id."'
		";
		$db->setQuery($query);
		$db->query();
	}
	
	public function get_clone_prize($prize_id,$process_id,$symbol_id) {
		$db		= &JFactory::getDbo();
		$query  = "
			SELECT *
			FROM #__ap_symbol_process_process_clone AS a
			WHERE process_id = '".$process_id."' AND prize_id = '".$prize_id."' AND symbol_id = '".$symbol_id."'
		";	
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
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
	
	public function delete_clone_detail($clone_id){
		$db	= &JFactory::getDbo();
		$query = "
			DELETE FROM #__ap_symbol_process_clone
			WHERE clone_id = '".$clone_id."'
		";
		$db->setQuery($query);
		$db->query();
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
	
	public function delete_clone_pieces($symbol_id, $clone_id) {
		$db		= &JFactory::getDbo();
		$query  = "
			DELETE FROM #__symbol_symbol_pieces WHERE symbol_pieces_id NOT IN 
			(
				SELECT pieces_id FROM #__ap_symbol_process_clone WHERE clone_id = '".$clone_id."'
			) AND symbol_id = '".$symbol_id."' AND is_lock = 0
		";	
		$db->setQuery($query);
		$db->query();		 
	}
	
	public function get_clone_pieces($symbol_id, $clone_id) {
		$db		= &JFactory::getDbo();
		$query  = "
			SELECT * FROM #__symbol_symbol_pieces WHERE symbol_pieces_id NOT IN 
			(
				SELECT pieces_id FROM #__ap_symbol_process_clone WHERE clone_id = '".$clone_id."'
			) AND symbol_id = '".$symbol_id."' AND is_lock = 0
		";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	public function getProcessCloned($process_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("a.id as clone_id,a.process_id,a.prize_id,a.symbol_id,b.*");
		$query->from("#__ap_symbol_process_process_clone AS a");
		$query->innerJoin("#__symbol_prize AS b ON a.prize_id=b.id");
		$query->where("a.process_id='".$process_id."'");
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	public function getClonesAdd($clone_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_symbol_process_clone");
		$query->where("clone_id='".$clone_id."'");
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
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

	public function saveExtract($process_id,$prize_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_prize_extracted");
		$query->set("prize_id='".$prize_id."'");
		$query->set("process_id='".$process_id."'");
		$db->setQuery($query);
		$db->query();
		//echo $db->getErrorMsg();
		return $db->insertId();
	}
	
	public function deleteExtract($id) {
		$db	= &JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->delete("#__ap_symbol_process_prize_extracted");
		$query->where("id='".$id."' ");
		$db->setQuery($query);
		$db->query();		 
	}

	public function getExtract($process_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_symbol_process_prize_extracted");
		$query->where("process_id='".$process_id."'");
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
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
	
	public function activeStatusAll($symbol_id) {
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__symbol_symbol_pieces");
		$query->set("is_lock='0'");
		$query->where("symbol_id='".$symbol_id."'");
		$db->setQuery($query);
		$db->query();
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
	
	public function delete_extract_detail($extract_id) {
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->delete("#__ap_symbol_process_extract");
		$query->where("extract_id='".$extract_id."'");
		$db->setQuery($query);
		$db->query();
	}
	
	public function getExtractPieces($extract_id) {
		$db		= &JFactory::getDbo();
		$query  = "
			SELECT a.*, b.`symbol_pieces_image` FROM #__ap_symbol_process_extract AS a 
			INNER JOIN #__symbol_symbol_pieces b ON b.`symbol_pieces_id` = a.`pieces_id`
			WHERE a.extract_id = '".$extract_id."'
		";	
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
	}
}
?>