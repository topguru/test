<?php
// no direct access
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
class AwardpackageModelSymbolqueuegroup extends JModelLegacy {

	function __construct() {
		parent::__construct();
	}
	
	public function get_symbol_queue_group($package_id, $ids = array(), $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest('com_awardpackage.awardfundplan.filter_order', 'filter_order', 'a.name', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest('com_awardpackage.awardfundplan.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.awardfundplan.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

		$query = '
        		select
        			a.*, b.name as name_process
        		from
        			#__symbol_queue_group a     
				left join #__process_presentation b on b.id = a.selected	
				where a.package_id = '.$package_id.'	   		
    			'.$order;
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['symbolqueue'] = $this->_db->loadObjectList();
		
		$query = '
        		select
        			count(*)
        		from
        			#__symbol_queue_group a        		
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
	
	public function total_donation($package_id){
	$query = '
        		select
        			SUM(a.debit)
        		from
        			#__funding_history a  
				where a.transaction_type = "DONATION"	      		
    			 ';
		$this->_db->setQuery($query);
		$totaldonation = $this->_db->loadResult();
		return $totaldonation;
	}
	
	public function total_quiz_cost($package_id){
	$query = '
        		select
        			SUM(a.complete_giftcode_cost_response)
        		from
        			#__quiz_question_giftcode a  
				where a.package_id ='.$package_id.'		      		
    			 ';
		$this->_db->setQuery($query);
		$totalquizcost = $this->_db->loadResult();
		return $totalquizcost;
	}
	
	public function total_survey_cost($package_id){
	$query = '
        		select
        			SUM(a.complete_giftcode_cost_response)
        		from
        			#__survey_question_giftcode a  
				where a.package_id ='.$package_id.'		      		
    			 ';
		$this->_db->setQuery($query);
		$totalsurveycost = $this->_db->loadResult();
		return $totalsurveycost ;
	}
	
	public function get_symbol_queue_group_byid($id){
		$query = '
        		select
        			a.*, b.name as name_process
        		from
        			#__symbol_queue_group a    
					left join #__process_presentation b on b.id = a.selected   		
    			where a.id = \''.$id.'\' ';
		$this->_db->setQuery($query);
		$categories = $this->_db->loadObjectList ();
		if(!empty($categories)) {
			$category = $categories[0];			
		}
		return $category;
	}

        public function save_update_user($package_id, $userid, $id ){
			$query = 'update #__symbol_queue_group set published = \''.$userid.'\' 
			where id =\''.$id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
			
	}
			
	public function save_update_category($package_id, $sname,$description, $amount,$id, $userid){
		$this->_db = &JFactory::getDBO ();
		$createdate = JFactory::getDate()->toSql();
		if($id != '') {
			$query = 'update #__symbol_queue_group set name = \''.$sname.'\' , description = \''.$description.'\'  ,amount = \''.$amount.'\'  where id = \''.$id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		} else {
			$query = 'insert into #__symbol_queue_group (name, description, amount,  date_created, package_id)
					values (\'' . $sname . '\',\''.$description.'\',\''.$amount.'\', \''.$createdate.'\', \''.$package_id.'\')';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		}
	}
	
	public function getSymbolCount($groupId){
	$query = '
        		select
        			count(*)
        		from
        			#__symbol_queue
					where groupId ='.$groupId.'        		
        		';

		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();
		return $total;
	
	}
	
	
	public function delete_categories($id){
		$queries = array();
		$id = implode(',', $id);			
		$queries[] = 'delete from #__symbol_queue_group where id in ('.$id.')';			
		foreach ($queries as $query){
			$this->_db->setQuery($query);
			if(!$this->_db->query()){					
				return false;
			}
		}			
		return true;
	}
	
	function set_status($id, $status){
		$query = 'update #__symbol_queue_group set published = \''.($status ? 1 : 0).'\' where id in ('.$id.')';
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
        			id, name, description, rate, speed, duration, amount, published, date_created, package_id
        		from
        			#__symbol_queue_group a  
				where package_id ='.$package_id.'	      		
    			order by a.`id` asc ';
		$this->_db->setQuery($query);
		$categories = $this->_db->loadObjectList ();
		return $categories;
	}
	
	public function getAllUserlist($packageId){

		$query = $this->_db->getQuery(true);
		$query = " select * from #__ap_useraccounts where package_id = '".$packageId."' order by firstname asc ";
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		return $results;
		}
	
}