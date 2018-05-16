<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
jimport('joomla.application.component.modellist');
jimport('joomla.form.helper');

class AwardPackageModelPrize extends JModelList{

	var $_data;
	var $_jml;
	var $_detail;

	protected function getListQuery($limit,$limitstart){
		$db				= 	&JFactory::getDbo();
		$query			= 	$db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_prize");
		$package_id 	=	JRequest::getInt('package_id');
		$query->where("package_id='".$package_id."' LIMIT ".$limitstart.", ".$limit." ");
		//echo $query;
		return $query;
	}
	
	function getListItems($limit,$limitstart){
		$db				= 	&JFactory::getDbo();
		$query			= 	$db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_prize");
		$package_id 	=	JRequest::getInt('package_id');
		$query->where("package_id='".$package_id."' ORDER BY id DESC LIMIT ".$limitstart.", ".$limit." ");
		//echo $query;
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getDataDetail($gcid,$limit,$limitstart){
		$db				= 	&JFactory::getDbo();
		$query			= 	$db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_prize");
		$query->where("id='".$gcid."' LIMIT ".$limitstart.", ".$limit." ");
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function saveData($data){

		$row =& $this->getTable('Prize');

		$db=$this->getDBO();

		$data['status']='1';

		if (!$row->bind($data)) {

			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		// check image
		if($row->id)
		{
			$result=$db->setQuery("select * from #__symbol_prize where id=".$row->id);
			 
			$prizeobj=$db->loadObject();
			 
			if($row->prize_image!=$prizeobj->prize_image) // delete prize image
			{
				unlink(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_symbol'.DS.'asset'.DS.'prize'.DS.$prizeobj->prize_image);
			}
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
		if($data['id'] == ''){

			$id = $this->_db->insertid();
				
		}else{

			$id = $data['id'];
		}
		return $id;
	}

	function delete($ids){
		$row =& $this->getTable();
		$db=$this->getDbo();
		if (count( $ids )) {
			foreach($ids as $id)
			{
				/*
				$rowprize =& $this->getTable('Symbolprize');
				 
				$getid_prize="select * from #__jos_symbol_symbol_prize where id = ".$id;
				 
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
				$db->setQuery("DELETE FROM #__symbol_symbol_prize WHERE id	 = '".$id."'");
				if(!$db->query()){
					$this->setError( $db->getErrorMsg() );
				}
				 
				$db->setQuery("select * from #__symbol_prize where id=".$id);
				 
				$prize_obj=$db->loadObject();
				 
				unlink(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_symbol'.DS.'asset'.DS.'prize'.DS.$prize_obj->prize_image);
				if (!$row->delete( $id )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}
	function deleteData($id){
		$row =& $this->getTable('Prize');
		$row->delete($id);
	}
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		jimport('joomla.form.form');

		$form = $this->loadForm('com_symbol.create', 'prize', array('control' => 'jform', 'load_data' => $loadData));
		 
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	public function getPrizeWinner($prize_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select('*');
		$query->from($db->QuoteName('#__ap_winners_actual').' AS a');
		$query->innerJoin($db->QuoteName('#__symbol_prize').' AS b ON a.prize_id=b.id');
		$query->innerJoin($db->QuoteName('#__users').' AS c ON a.user_id=c.id');
		$query->where("a.prize_id='".$prize_id."'");
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}

	public function getClaimPrize($package_id){
		$db	= JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_prize_claim AS a");
		$query->innerJoin("#__ap_winners_actual AS b ON a.winner_id=b.id");
		$query->innerJoin("#__users AS c ON c.id=b.user_id");
		$query->where("a.package_id='".$package_id."'");
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}

	public function get_winner_info($winner_id){
		$db	= JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select('*');
		$query->from("#__ap_winners_actual AS a");
		$query->innerJoin("#__ap_useraccounts AS b ON a.user_id=b.id");
		$query->where("a.id='".$winner_id."'");
		$db->setQuery($query);
		$rows = $db->loadObject();
		return $rows;
	}

	public function update_claim($winner_id){
		$db	= JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->update("#__ap_prize_claim");
		$query->set("send_status='1'");
		$query->where("winner_id='".$winner_id."'");
		$db->setQuery($query);
		return $db->query();
	}
	
	function registered_prize($package_id){
		$db 	= JFactory::getDBO();
		
		$query 	= $db->getQuery(TRUE);
		
		$query->select("*");
		
		$query->from("#__symbol_prize");
		
		$query->where("package_id='".$package_id."'");
		
		$db->setQuery($query);
		
		return count($db->loadObjectList());
	}
}