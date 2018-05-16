<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class ShuffleModelShuffle extends JModelLegacy {

	var $_data;
	var $_jml;
	var $_detail;

	function queue($data){		
	
		$db =& JFactory::getDBO();
		$query = "delete from #__symbol_queue_detail where symbol_pieces_id in (select symbol_pieces_id from #__symbol_symbol_pieces a left join #__symbol_symbol_prize b on a.symbol_id=b.symbol_id where b.id=".$data['symbol_prize_id'].")";
		$db->setQuery($query);
		
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
					$dt = array('symbol_pieces_id' => $val, 'queue_id' => $que_id, 'status'=> 0, 'symbol_prize_id' => $data['symbol_prize_id'] );
					//var_dump($dt); echo "<br/>";
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
}