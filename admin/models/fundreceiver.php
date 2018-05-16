<?php
// no direct access
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
class AwardpackageModelFundReceiver extends JModelLegacy {

	function __construct() {
		parent::__construct();
	}
	
	public function get_fund_receiver($package_id, $ids = array(), $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest('com_awardpackage.fundreceiver.filter_order', 'filter_order', 'a.name', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest('com_awardpackage.fundreceiver.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.fundreceiver.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

		$query = '
        		select
        			*
        		from
        			#__fund_receiver a     
				where a.package_id = '.$package_id.'	   		
    			'.$order;
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['fundprizes'] = $this->_db->loadObjectList();
		
		$query = '
        		select
        			count(*)
        		from
        			#__fund_receiver a        		
        		';

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
		$return['lists'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit,
    			'order'=>$filter_order,
    			'order_dir'=>$filter_order_dir
		);

		return $return;
	}
	
	public function get_fund_receiver_byid($id){
		$query = '
        		select
        			*
        		from
        			#__fund_receiver_list a        		
    			where a.criteria_id = \''.$id.'\' ';
		$this->_db->setQuery($query);
		$category = $this->_db->loadObjectList ();
		return $category;
	}
	
	public function get_fund_receiver_list_byid($id){
		$query = '
        		select
        			*
        		from
        			#__fund_receiver a        		
    			where a.receiver = \''.$id.'\' ';
		$this->_db->setQuery($query);
		$category = $this->_db->loadObjectList ();
		return $category;
	}
	
	public function get_fund_receiver_bytitle($title){
		$query = '
        		select
        			a.*
        		from
        			#__fund_receiver a        		
    			where a.title = \''.$title.'\' ';
		$this->_db->setQuery($query);
		$category = $this->_db->loadObjectList ();		
		return $category;
	}
	
	public function get_fund_receiver1($package_id ){
		$query = '
        		select a.criteria_id, a.package_id, a.title, a.filter, a.randoma,a.randomb,a.randomc,a.randomd,a.randome
        		from
        			#__fund_receiver_list a        		
    			where a.package_id = \''.$package_id.'\' ';
		$this->_db->setQuery($query);
		$category = $this->_db->loadObjectList ();		
		return $category;
	}
	

		public function get_fundreceiver_id(){
$query = 'select
        			a.*
        		from
        			#__fund_receiver_list a        		
    			order by a.criteria_id desc LIMIT 1';
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();		
		return $result;
	}

	public function save_update_fundreceiver($id, $package_id, $title, $filter, $receiver, $from_year, $to_year, $from_month, $to_month, $from_day, $to_day, $gender, $randoma, $randomb, $randomc, $randomd, $randome){

		$this->_db = &JFactory::getDBO();
		$createdate = JFactory::getDate()->toSql();


			$query = 'insert into #__fund_receiver (package_id,title,from_day,to_day,from_month,to_month,from_year,to_year,gender,filter, randoma, randomb, randomc, randomd, randome, receiver)
					values ("'.$package_id.'","'.$title.'","'.$from_day.'","'.$to_day.'","'.$from_month.'","'.$to_month.'","'.$from_year.'","'.$to_year.'","'.$gender.'","'.$filter.'", "'.$randoma.'", "'.$randomb.'","'.$randomc.'","'.$randomd.'","'.$randome.'","'.$id.'")';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
//			$this->save_create_fund($id, $package_id, $title, $filter);
				return true;				
			}
	}
	
	public function save_create_fund ($id, $package_id, $title, $filter, $receiver, $gender, $randoma, $randomb, $randomc, $randomd, $randome) {
		$this->_db = &JFactory::getDBO();
		$createdate = JFactory::getDate()->toSql();
		if($id != '') {
			$query = 'update #__fund_receiver_list set title = \''.$title.'\'  
			,filter = \''.$filter.'\'
			,randoma = \''.$randoma.'\'
			,randomb = \''.$randomb.'\'
			,randomc = \''.$randomc.'\'					
			,randomd = \''.$randomd.'\'			
			,randome = \''.$randome.'\'	 where title = \''.$title.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		} else {
			$query = 'insert into #__fund_receiver_list (package_id,title,filter, randoma, randomb, randomc, randomd, randome)
					values ("'.$package_id.'","'.$title.'","'.$filter.'", "'.$randoma.'", "'.$randomb.'","'.$randomc.'","'.$randomd.'","'.$randome.'")';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		}
	}
	
	public function delete_categories($id){
		$queries = array();
		$id = implode(',', $id);			
		$queries[] = 'delete from #__fund_receiver_list where criteria_id in ('.$id.')';			
		foreach ($queries as $query){
			$this->_db->setQuery($query);
			if(!$this->_db->query()){					
				return false;
			}
		}			
		return true;
	}
	
	public function delete_categories2($id){
		$queries = array();
		$id = implode(',', $id);			
		$queries[] = 'delete from #__fund_receiver where criteria_id in ('.$id.')';			
		foreach ($queries as $query){
			$this->_db->setQuery($query);
			if(!$this->_db->query()){					
				return false;
			}
		}			
		return true;
	}
	
	function set_status($id, $status){
		$query = 'update #__fund_receiver set published = \''.($status ? 1 : 0).'\' where id in ('.$id.')';
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
        			#__fund_receiver a  
				where package_id ='.$package_id.'	      		
    			order by a.`id` asc ';
		$this->_db->setQuery($query);
		$categories = $this->_db->loadObjectList ();
		return $categories;
	}
	
}