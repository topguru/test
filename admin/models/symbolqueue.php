<?php
// no direct access
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
class AwardpackageModelSymbolQueue extends JModelLegacy {

	function __construct() {
		parent::__construct();
	}
	
	public function get_symbolqueue ($group, $limit,$limitstart){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$query = '
        		select
        			*, b.firstname,b.lastname
        		from
        			#__symbol_queue a     
				left join #__ap_usergroup b on b.useraccount_id = a.user_id	
				WHERE a.groupId = '.$group.'
    			';
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['symbols'] = $this->_db->loadObjectList();
		return $return;
	}
	
	public function get_symbolqueue_byid($id,$limit,$limitstart){
		$query = '
        		select
        			a.symbol_name, b.symbol_pieces_image, d.prize_value
        		from
        			#__symbol_symbol a  
					inner join #__symbol_symbol_pieces b on b.symbol_id = a.symbol_id  
					inner join #__symbol_symbol_prize c on c.symbol_id = a.symbol_id  
 					inner join #__symbol_prize d on d.id = c.id  
					WHERE c.id  ='.$id.'
    			 ';
		$this->_db->setQuery($query, $limitstart, $limit);
		$category = $this->_db->loadObjectList ();
		return $category;
	}

		public function get_users($package_id){
		$query = '
        		select
        			a.*, b.username, b.name
        		from
        			#__ap_useraccounts a  
	            inner join #__users b on b.id = a.id   		
    			where a.package_id = \''.$package_id.'\' ';
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList ();
		return $rows;
	}
	
	public function save_update_category($groupId){
		$this->_db = &JFactory::getDBO ();
		$createdate = JFactory::getDate()->toSql();
		if($id != '') {
			$query = 'update #__symbol_queue set name = \''.$sname.'\' , description = \''.$description.'\' ,value_from = \''.$value_from.'\' ,value_to = \''.$value_to.'\'   where id = \''.$id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		} else {
		
		  		$query = '
        		select
        			count(*)
        		from
        			#__symbol_symbol_pieces        		
        		';

		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();
		
			$query = "insert into #__symbol_queue (piece, shufle, status, total, groupId, date_created)
					values ('".$total."','0','0','0','".$groupId."','".$createdate."')";
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		}
	}
	
	public function getSymbolGroup(){
	$query = '
        		select
        			a.*
        		from
        			#__symbol_queue_group a  
    			order by a.id DESC LIMIT 1 ';
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();

		if(!empty($results)) {
			return $results[0];
		}
		return null;
	}
	
	public function save_shuffle($id,$shufle,$processId ){
		$queries = array();
		$id = implode(',', $id);
		$queries[] = "update #__symbol_queue set shufle = '".$shufle."', selected_presentation = '".$processId."' where queue_id in (".$id.")";			
		foreach ($queries as $query){
			$this->_db->setQuery($query);
			if(!$this->_db->query()){					
				return false;
			}
		}			
		return true;
	}
	
	public function save_userid($id,$userid){
		$queries = array();
		$query = 'update #__symbol_queue set user_id = '.$userid.' where queue_id ='.$id.' ';			
			$this->_db->setQuery($query);
			if(!$this->_db->query()){					
				return false;
			}
		return true;
	}
	
	public function delete_categories($id){
		$queries = array();
		$id = implode(',', $id);
		$queries[] = 'delete from #__symbol_queue where queue_id in ('.$id.')';			
		foreach ($queries as $query){
			$this->_db->setQuery($query);
			if(!$this->_db->query()){					
				return false;
			}
		}			
		return true;
	}
	
	function set_status($id, $status){
		$query = 'update #__symbol_queue set published = \''.($status ? 1 : 0).'\' where id in ('.$id.')';
		$this->_db->setQuery($query);			
		if(!$this->_db->query()){
			return false;
		}else{
			if($count = $this->_db->getAffectedRows()){
				return true;				
			}			
		}
		return false;
	}
	
	public function list_categories($package_id){
		$query = '
        		select
        			id, name, published, date_created, package_id
        		from
        			#__symbol_queue a  
				where package_id ='.$package_id.'	      		
    			order by a.`id` asc ';
		$this->_db->setQuery($query);
		$categories = $this->_db->loadObjectList ();
		return $categories;
	}
	
}