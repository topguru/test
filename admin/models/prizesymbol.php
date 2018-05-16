<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class AwardModelPrizesymbol extends JModelLegacy {

	var $_data;
	var $_jml;
	var $_detail;

	function _getDataQuery()
	{

		$query='SELECT #__symbol_symbol_prize.*,#__symbol_prize.prize_name,#__symbol_prize.prize_image,#__symbol_symbol.symbol_name,#__symbol_symbol.symbol_image,#__symbol_symbol.rows,#__symbol_symbol.cols FROM (#__symbol_symbol_prize INNER JOIN #__symbol_prize USING (id)) INNER JOIN #__symbol_symbol USING (symbol_id) ORDER BY symbol_prize_id DESC';
		return $query;

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

	function getDataDetail($gcid)
	{

		if(empty($this->_data))
		{

			$query=$this->_getDataQuery();
			$this->_data=$this->_getList( "SELECT #__symbol_symbol_prize.*,#__symbol_prize.prize_name,#__symbol_prize.prize_image,#__symbol_symbol.symbol_name,#__symbol_symbol.symbol_image,#__symbol_symbol.rows,#__symbol_symbol.cols FROM (#__symbol_symbol_prize INNER JOIN #__symbol_prize USING (id)) INNER JOIN #__symbol_symbol USING (symbol_id) WHERE #__symbol_symbol_prize.symbol_prize_id = '".$gcid."'" );

		}

		return $this->_data;

	}

	function saveData($data){
		$row =& $this->getTable('Symbolprize');

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
		if($data['symbol_prize_id'] == ''){
			$id = $this->_db->insertid();
		}else{
			$id = $data['symbol_prize_id'];
		}
		return $id;
	}

	function queue($data){

		$qry = "select queue_id,user_id from #__symbol_queue";
		$usr = $this->_getList( $qry );

		foreach($usr as $p)
		{
			$d = $p->user_id;

			$que_id = $p->queue_id;
			$sym_id = $data["symbol_id"];

			$qry2 = "select symbol_pieces_id from #__symbol_symbol_pieces where symbol_id=".$sym_id;
			$sym_pieces = $this->_getList( $qry2 );
			$i=0;
			foreach ($sym_pieces as $sp ) { $sym_col [$i]=$sp->symbol_pieces_id; $i++;}
			shuffle($sym_col);
			foreach ($sym_col as $sc => $val){
				$dt = array('symbol_pieces_id' => $val, 'queue_id' => $que_id, 'status'=> 0 );
				$row_que_detail =& $this->getTable('Symbolqueuedetail');
					
				if (!$row_que_detail->bind($dt)) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
					
				// Make sure the hello record is valid
				if (!$row_que_detail->check()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}

				// Store the web link table to the database
				if (!$row_que_detail->store()) {
					$this->setError( $row_que_detail->getErrorMsg() );
					return false;
				}
					
			}

		}


		return $que_id;
	}


	function delete($ids)
	{
		$row =& $this->getTable('Symbolprize');
		$db=$this->getDbo();
		if (count( $ids )) {
			foreach($ids as $id)
			{
				if (!$row->delete( $id )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

	function deleteData($id){
		$row =& $this->getTable('Symbolprize');
		$row->delete($id);

		$query = "delete from #__symbol_queue_detail where symbol_pieces_id in (select symbol_pieces_id from #__symbol_symbol_pieces a left join #__symbol_symbol_prize b on a.symbol_id=b.symbol_id where b.id=".$id.")";
		$this->setQuery($query);

	}





}