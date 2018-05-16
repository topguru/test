<?php
//resdirect
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

class AwardPackageModellistpersentation extends JModelList{
	
	//set variable
	var $_data;
	var $_jml;
	var $_detail;
		
	function _getDataQuery()
	{

		$query='SELECT a.presentation_id, a.presentation_create, a.presentation_modify, a.presentation_publish,a.package_id, count(b.presentation_id) as cnt_pair from #__symbol_presentation a left join #__symbol_symbol_prize b on a.presentation_id=b.presentation_id group by a.presentation_id';
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

	function Create(){		
		$package_id = JRequest::getVar('package_id');
		$row =& $this->getTable('Symbolpresentation');
		
		$data = array ('presentation_create' => date('Y-m-d h:i:s'), 'presentation_modify' => date('Y-m-d h:i:s'), 'presentation_publish' => 0,'package_id'=>$package_id);

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
		
		$id = $this->_db->insertid();
				
		return $id;
	}
	
	
	
	
	function delete($ids)
	{
	    $row =& $this->getTable('Symbolpresentation');
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
	
	function publish($publish,$id){
		
		$db = &$this->getDBO();
		
		if($publish){
		
			$query = "UPDATE #__symbol_presentation SET ".$db->QuoteName('presentation_publish')."='1' WHERE ".$db->QuoteName('presentation_id')."='".$id."'";
		}else{
		
			$query = "UPDATE #__symbol_presentation SET ".$db->QuoteName('presentation_publish')."='0' WHERE ".$db->QuoteName('presentation_id')."='".$id."'";
		}
		
		$db->setQuery($query);
			
		if($db->query()){
			
			return true;
		
		}else{
			return false;
		}
	}
}