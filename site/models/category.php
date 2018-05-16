<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class GiftcodeModelCategory extends JModelLegacy{

	var $_data;
	var $_jml;
	var $_detail;

	function _ambilQuery()
	{

		$query='SELECT * FROM #__arefun_data';
		return $query;

	}

	function getData()
	{

		if(empty($this->_data))
		{

			$query=$this->_ambilQuery();
			$this->_data=$this->_getList( "SELECT * FROM #__gc_category ORDER BY cat_id" );

		}

		return $this->_data;

	}
	
	function getDataDetail($gcid)
	{

		if(empty($this->_data))
		{

			$query=$this->_ambilQuery();
			$this->_data=$this->_getList( "SELECT * FROM #__gc_category  WHERE cat_id = '".$gcid."'" );

		}

		return $this->_data;

	}
	
	
	function simpan($data,$edit){		
		$row =& $this->getTable('Category');	
		
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
		
		if($edit == true){
			$setid  = $data['cat_id'];
			
		}else{
			$setid  = $this->getlast();
		}
		
		
		return $setid;
	}
	
	function hapus($cat_id){
		$row =& $this->getTable('Category');	
		$row->delete($cat_id);
	}
	
	function getlast(){
		$id = $this->_db->insertid();
		return $id;
	}
	

}