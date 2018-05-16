<?php
// no direct access
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
class AwardpackageModelPayout extends JModelLegacy {

	function __construct() {
		parent::__construct();
	}
	
	public function getSymbolSymbolPrize2(){
		$query = "	SELECT a.symbol_prize_id, c.prize_name, c.prize_image, c.prize_value, b.symbol_name, b.symbol_image, b.pieces, b.cols, b.rows, d.presentation_id, d.status , e.claimed_status, e. claimed_date, e.winner_id, e.send_status, e.prize_name, f.firstname, f.lastname
					FROM #__symbol_symbol_prize a 
					LEFT JOIN #__symbol_symbol b ON b.symbol_id = a.symbol_id 
					LEFT JOIN #__symbol_prize c ON c.id = a.id
					LEFT JOIN #__ap_prize_claim e ON e.prize_name = e.prize_name
					LEFT JOIN #__ap_useraccounts f on f.id = e.winner_id 
					INNER JOIN #__symbol_presentation d ON d.presentation_id = a.presentation_id
					";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getSymbolSymbolPrize($limit,$limitstart){
		$query = "	SELECT a.symbol_prize_id, c.prize_name, c.prize_image, c.prize_value, b.symbol_name, b.symbol_image, b.pieces, b.cols, b.rows,
            		d.claimed_status, d. claimed_date, d.winner_id, d.send_status, d.prize_name, f.firstname, f.lastname
					FROM #__symbol_symbol_prize a 
					LEFT JOIN #__symbol_symbol b ON b.symbol_id = a.symbol_id 
					LEFT JOIN #__symbol_prize c ON c.id = a.id
					LEFT JOIN #__ap_prize_claim d ON d.prize_name = c.prize_name
					LEFT JOIN #__ap_useraccounts f on f.id = d.winner_id
					LIMIT ".$limitstart.", ".$limit."					";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function get_symbol($prize_name){
	$query = 'select a.*, b.symbol_name, c.prize_name 
        		from #__symbol_symbol_prize a
				inner join #__symbol_symbol b on b.symbol_id = a.symbol_id 
				inner join #__symbol_prize	c on c.id = a.id'; 								
				$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		return $result;

	}
	
	public function get_payout($package_id, $ids = array(), $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest('com_awardpackage.payout.filter_order', 'filter_order', 'a.prize_name', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest('com_awardpackage.payout.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.payout.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

		
		
		$query = '
        		select a.*, d.id , d.firstname, d.lastname
        		from #__ap_prize_claim a    
				inner join #__ap_useraccounts d on d.id = a.winner_id 								
				where a.package_id = '.$package_id.'	   		
    			'.$order;
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['payouts'] = $this->_db->loadObjectList();
		
		$prize_name = 'Hagendazz';
		$symbol = $this->get_symbol($prize_name);
		
		$query = '
        		select
        			count(*)
        		from
        			#__ap_prize_claim a        		
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
	
	public function get_payout_byid($id){
		$query = '
        		select
        			*
        		from
        			#__fund_receiver a        		
    			where a.id = \''.$id.'\' ';
		$this->_db->setQuery($query);
		$categories = $this->_db->loadObjectList ();
		if(!empty($categories)) {
			$category = $categories[0];			
		}
		return $category;
	}

	public function save_update_category($package_id, $sname,$description,$value_from,$value_to,$id){
		$this->_db = &JFactory::getDBO ();
		$createdate = JFactory::getDate()->toSql();
		if($id != '') {
			$query = 'update #__fund_receiver set title = \''.$sname.'\' , receiver = \''.$description.'\' ,year_from = \''.$value_from.'\' ,year_to = \''.$value_to.'\'   where id = \''.$id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		} else {
			$query = 'insert into #__fund_receiver (package_id,title,receiver,firstname,lastname,birthday,email,from_day,to_day,from_month,to_month,from_year,to_year,gender,field,status,group_name,is_presentation,parent_usergroup,usergroup_id,useraccount_id,filter)
					values (\'' . $sname . '\',\''.$description.'\',\''.$value_from.'\',\''.$value_to.'\',' . 0 . ', \''.$createdate.'\', \''.$package_id.'\')';
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
		$queries[] = 'delete from #__fund_receiver where id in ('.$id.')';			
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