<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class AwardPackageModelQueue extends JModelLegacy {

	var $_data;
	var $_jml;
	var $_detail;


	function _getDataQuery()
	{
		$db     = $this->getDbo();

		$query  ='SELECT * FROM #__symbol_queue a INNER JOIN #__ap_useraccounts b ON a.user_id=b.id INNER JOIN #__users c ON c.id=b.id';

		$db->setQuery($query);

		$rows   = $db->loadObjectList();

		return $rows;

	}

	function getData()
	{

		if(empty($this->_data))
		{

			$query=$this->_getDataQuery();
				
			$this->_data=$this->_getList( $query );

		}

		return $this->_data;

	}

	function getUserSymbol($symbol_prize_id){

		$db = $this->getDBO();

		$query ="SELECT * FROM #__symbol_symbol_prize a INNER JOIN #__symbol_symbol b ON a.symbol_id=b.symbol_id WHERE symbol_prize_id='$symbol_prize_id'";

		$db->setQuery($query);

		$rows = $db->loadObjectList();

		foreach($rows as $row){
			return $row;
		}
	}

	function getDataAll($gcid)
	{

		if(empty($this->_data))
		{

			$this->_data=$this->_getList("SELECT a.queuedetail_id, b.symbol_pieces_image, c.symbol_image FROM #__symbol_queue_detail  a
		    Inner Join #__symbol_symbol_pieces b on a.symbol_pieces_id=b.symbol_pieces_id 
		    INNER JOIN #__symbol_symbol  c on b.symbol_id=c.symbol_id 
                    WHERE queue_id=".$gcid." order by a.queuedetail_id asc");

		}

		return $this->_data;

	}

	function getDataRecord($gcid)
	{

		if(empty($this->_data))
		{

			$query=$this->_getDataQuery();
			$this->_data=$this->_getList( "SELECT #__symbol_queue_detail.queuedetail_id, #__symbol_symbol_pieces.symbol_pieces_image, #__symbol_symbol.symbol_image FROM #__symbol_queue_detail Inner Join (#__symbol_symbol_pieces INNER JOIN #__symbol_symbol USING(symbol_id)) USING(symbol_pieces_id) WHERE queue_id = '".$gcid."' AND status = '1'" );

		}

		return $this->_data;

	}

	function getDataDetail($gcid)
	{

		if(empty($this->_data))
		{

			$query=$this->_getDataQuery();
			$this->_data=$this->_getList( "SELECT #__symbol_queue.queue_id, #__symbol_user.name, #__symbol_queue.date_created, Count(#__symbol_queue_detail.queuedetail_id) as Jumlah FROM #__symbol_queue Inner Join #__symbol_user USING(user_id)  Inner Join #__symbol_queue_detail USING(queue_id) GROUP BY #__symbol_queue.queue_id WHERE #__symbol_queue.queue_id = '".$gcid."'" );

		}

		return $this->_data;

	}

	function saveData($data){
		$row =& $this->getTable('Queue');

		if (!$row->bind($data)) {

			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the hello record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}
		if($data['queue_id'] == ''){
			$id = $this->_db->insertid();
		}else{
			$id = $data['queue_id'];
		}
		return $id;
	}

	function deleteData($id){
		$row =& $this->getTable('Prize');
		$row->delete($id);
	}





}