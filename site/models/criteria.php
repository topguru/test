<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class GiftcodeModelCriteria extends JModelLegacy{

	var $_data;
	var $_datauser;
	var $_jml;
	var $_detail;

	function _ambilQuery()
	{

		$query='SELECT * FROM #__arefun_data';
		return $query;

	}

	function getData($schid,$name)
	{

		if(empty($this->_data))
		{

			$query=$this->_ambilQuery();
			$this->_data=$this->_getList( "SELECT * FROM #__gc_filter WHERE name = '".$name."' AND sch_id = '".$schid."' ORDER BY filterid ASC" );

		}

		return $this->_data;

	}
	
	
	function getDataUser($querynya)
	{

		$this->_datauser=$this->_getList( $querynya );
		
		return $this->_datauser;

	}
	
	function getDataDetail($gcid)
	{

		if(empty($this->_data))
		{

			$query=$this->_ambilQuery();
			$this->_data=$this->_getList( "SELECT #__gc_gift_code.*,#__gc_publish_setting.*,#__gc_code_setting.* FROM (#__gc_code_setting INNER JOIN #__gc_gift_code USING(code_setting_id)) INNER JOIN #__gc_publish_setting USING (publish_setting_id) WHERE #__gc_code_setting.code_setting_id = '".$gcid."' LIMIT 1" );

		}

		return $this->_data;

	}
	
	function getGiftcode($gcid)
	{

		if(empty($this->_data))
		{

			$query=$this->_ambilQuery();
			$this->_data=$this->_getList( "SELECT #__gc_gift_code.*,#__gc_publish_setting.*,#__gc_code_setting.* FROM (#__gc_code_setting INNER JOIN #__gc_gift_code USING(code_setting_id)) INNER JOIN #__gc_publish_setting USING (publish_setting_id) WHERE #__gc_code_setting.code_setting_id = '".$gcid."'" );

		}

		return $this->_data;

	}
	
	function getDataPay($d_id)
	{

		if(empty($this->_detail))
		{

			$query=$this->_ambilQuery();
			$this->_detail=$this->_getList( "SELECT * FROM #__donation_donate_hdr WHERE donate_id = '".$d_id."' AND status = 'unconfirmed'" );

		}
		$this->_detail[0]->jumlah = $this->getJumlah($d_id);
		return $this->_detail;

	}
	
	function getJumlah($d_id)
	{	
		$this->_jml = $this->_getList( "SELECT sum(value * qty) as jum FROM #__donation_donate_det WHERE donate_id = '".$d_id."'" );
		return $this->_jml[0]->jum;

	}
	
	function verify($d_id){
		$row =& $this->getTable('Donationhdr');
		$row->load($d_id);
		$row->status = 'verified';
		$row->store();
		
	}
	
	function simpan($data){		
		$row =& $this->getTable('filter');	
		
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
		
	}
	
	function simpanschedule($data1){		
		$row =& $this->getTable('Schedule');	
		
		if (!$row->bind($data1)) {
                
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
		
	}
	
	function hapus($sch_id){
		$this->_getList( "DELETE FROM #__gc_filter WHERE sch_id = '".$sch_id."'" );
	}
	
	function hapusX($sch_id){
		$row =& $this->getTable('filter');	
		$row->delete($sch_id);
	}
	
	function publish($code_setting_id){
					
		$pubid = $this->_getList( "SELECT publish_setting_id FROM #__gc_gift_code WHERE code_setting_id = '".$code_setting_id."' LIMIT 1" );
		$this->_getList( "UPDATE #__gc_publish_setting SET status = 'Online' WHERE publish_setting_id = '".$pubid[0]->publish_setting_id."'" );
		//print_r($pubid[0]->publish_setting_id);exit();
		
	}
	
	function getlast(){
		$id = $this->_db->insertid();
		return $id;
	}
	

}