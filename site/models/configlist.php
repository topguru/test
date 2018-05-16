<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class GiftcodeModelConfiglist extends JModelLegacy{

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
			$this->_data=$this->_getList( "SELECT #__gc_configlist.*,#__gc_schedule.sch_id,#__gc_schedule.scstart_date,#__gc_schedule.scend_date FROM #__gc_configlist LEFT JOIN #__gc_schedule USING (config_id) ORDER BY #__gc_configlist.config_id DESC" );
            
		}

		return $this->_data;

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
	
	function simpan($data1){		
		$row =& $this->getTable('Configlist');	
		
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
	
	function hapus($config_id){
		$row =& $this->getTable('Configlist');	
		$row->delete($config_id);
		$this->_getList( "DELETE FROM #__gc_schedule WHERE config_id = '".$config_id."'" );
	}
	
	function hapusX($sch_id){
		$row =& $this->getTable('Schedule');	
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