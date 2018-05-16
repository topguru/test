<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
jimport('joomla.application.component.modeladmin');
jimport('joomla.form.helper');

class AwardPackageModelAwardsymbol extends JModelAdmin{

	var $_data;
	var $_jml;
	var $_detail;

	function _getDataQuery($package_id,$limit, $limitstart)
	{

		$query="SELECT * FROM #__symbol_symbol WHERE package_id = '".$package_id."' ORDER BY symbol_id LIMIT ".$limitstart.", ".$limit."
 		  ";
		return $query;

	}

	function getData($package_id, $limit, $limitstart)
	{

		if(empty($this->_data))
		{
			$query=$this->_getDataQuery($package_id, $limit, $limitstart);

			$this->_data=$this->_getList( $query );
		}

		return $this->_data;

	}

	function getDataDetail($gcid,$limit, $limitstart)
	{
		if(empty($this->_data))
		{

			$query=$this->_getDataQuery();
			$this->_data=$this->_getList( "SELECT * FROM #__symbol_symbol WHERE symbol_id = '".$gcid."' LIMIT ".$limitstart.", ".$limit."" );

		}
		return $this->_data;

	}

	function saveData($data,$pieces){

		$db=$this->getDBO();

		$row =& $this->getTable('Awardsymbol');

		$saveedit=JRequest::getVar('saveedit','','post',string);

		$symbolimg=JRequest::getVar('symbol_image','','post',string);

		if($saveedit!='' && $saveedit!=$symbolimg)
		{
			unlink(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_symbol'.DS.'asset'.DS.'symbol'.DS.$saveedit);
		}
		if (!$row->bind($data)) {

			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}
		if($data['symbol_id'] == ''){
			$id = $this->_db->insertid();

		}else{
			$id = $data['symbol_id'];
				
		}

		if(count($pieces)) {
			$this->_getList( "SELECT symbol_pieces_image FROM #__symbol_symbol_pieces WHERE symbol_id = '".$id."'" );

			for ($i=0, $n=count( $this->_data ); $i < $n; $i++)
			{
				$row =& $this->_data[$i];
				unlink('./components/com_symbol/asset/symbol/pieces/'.$row->symbol_pieces_image);
			}
				
			$db->setQuery("DELETE FROM #__symbol_symbol_pieces WHERE symbol_id = '".$id."'");
			if(!$db->query())
			{
				$this->setError( $db->getErrorMsg() );
			}
			foreach($pieces as $roy){
				if($roy!=''){
					$row1 =& $this->getTable('Symbolpieces');
					$row1->symbol_pieces_id = '';
					$row1->symbol_id = $id;
					$row1->symbol_pieces_image = $roy;
						
					if (!$row1->check()) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}

					// Store the web link table to the database
					if (!$row1->store()) {
						$this->setError( $row1->getErrorMsg() );
						return false;
					}
					$id1=$this->_db->insertid();					
				}
			}
		}
		return $id;
			
	}
	function delete($ids){

		$row =& $this->getTable();
		$db=$this->getDbo();
		if (count( $ids )) {
			foreach($ids as $id)
			{
				$getid_pieces="select * from #__symbol_symbol_pieces where symbol_id = ".$id;
				$db->setQuery($getid_pieces);
				$id_pieces=$db->loadObjectList();
				if (count( $id_pieces )) {
					foreach($id_pieces as &$idpie) {
						$rowsimbolpieces=& $this->getTable('Symbolpieces');
						$getidqueuedetail="select * from #__symbol_queue_detail where symbol_pieces_id=".$idpie->symbol_pieces_id;
						$db->setQuery($getidqueuedetail);
						$id_queue_detail=$db->loadObjectList();
						if(count($id_queue_detail))
						{
							foreach($id_queue_detail as &$idquedetail)
							{   $rowsymbolqueuedetail= &$this->getTable('Symbolqueuedetail');
							if (!$rowsymbolqueuedetail->delete( $idquedetail->queuedetail_id )) {
								$this->setError( $rowsymbolqueuedetail->getErrorMsg() );
								return false;
							}
							}
						}
						unlink(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_symbol'.DS.'asset'.DS.'symbol'.DS.'pieces'.DS.$idpie->symbol_pieces_image);
						if (!$rowsimbolpieces->delete( $idpie->symbol_pieces_id )) {
							$this->setError( $rowsimbolpieces->getErrorMsg() );
							return false;
						}
					}
				}

				/*
				$rowprize =& $this->getTable('Symbolprize');				
				$getid_prize="select * from #__jos_symbol_symbol_prize where symbol_id = ".$id;
				$db->setQuery($getid_prize);
				$id_prizes=$db->loadObjectList();
				if (count( $id_prizes ))
				{
					foreach($id_prizes as &$idp) {
						if (!$rowprize->delete( $idp->symbol_prize_id )) {
							$this->setError( $rowprize->getErrorMsg() );
							return false;
						}
					}
				}
				*/
				
				$db->setQuery("DELETE FROM #__symbol_symbol_prize WHERE symbol_id = '".$id."'");
				if(!$db->query()){
					$this->setError( $db->getErrorMsg() );
				}
				
				$db->setQuery("select * from #__symbol_symbol where symbol_id=".$id);
				$symbol_obj=$db->loadObject();
				unlink(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_symbol'.DS.'asset'.DS.'symbol'.DS.$symbol_obj->symbol_image);
				if (!$row->delete( $id )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}
	function deleteData($ids){
		$db=$this->getDbo();
		$row =& $this->getTable('Prize');
		$getid_prize="select * from #__jos_symbol_symbol_prize where symbol_id IN (".implode( ',', $ids ).")";
		$resultprize=$db->setQuery($getid_prize);
		$id_prizes=$db->loadObjectList();

		if (count( $id_prizes )) {
			foreach($id_prizes as &$idp) {
				if (!$row->delete( $idp->symbol_prize_id )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}

		return true;
			
	}
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		jimport('joomla.form.form');

		$form = $this->loadForm('com_symbol.create', 'awardsymbol', array('control' => 'jform', 'load_data' => $loadData));
			
		if (empty($form)) {
			return false;
		}

		return $form;
	}





}