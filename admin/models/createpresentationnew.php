<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
class AwardPackageModelCreatepresentationnew extends JModelLegacy {
	
	function __construct() {			
		parent::__construct();
	}
	
	function _getDataQuery($ids = array(), $limit = 20, $limitstart = 0) {
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$filter_o=JRequest::getVar('filter_order');
		$filter_order = empty($filter_0) ? 'symbol_name' : $filter_o;
		$filter_o_d=JRequest::getVar('filter_order_Dir');
		$filter_order_dir = empty($filter_o_d) ? 'desc' : $filter_o_d;
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.createpresentationnew.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$order = ' order by `' . $filter_order . '` ' . $filter_order_dir;
		
		$query='select * from #__symbol_symbol where package_id = \''.JRequest::getVar('package_id').'\'   
			' . $order;
		
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['symbols'] = $this->_db->loadObjectList();		
		$query = '
        		select
        			count(*)
        		from
        			#__symbol_symbol where package_id = \''.JRequest::getVar('package_id').'\'        		
        		';

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
		$return['lists'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit,
    			'order'=>$filter_order,
    			'order_dir'=>($filter_order_dir == 'desc' ? 'asc' : 'desc')
		);
		return $return;		
	}
	
	function getData() {
		if(empty($this->_data))		{
			$query=$this->_getDataQuery();			
			$this->_data=$this->_getList( $query );
		}
		return $this->_data;
	}
	
	function getDataDetail($gcid) {		
		if(empty($this->_data)) {
			$query=$this->_getDataQuery();
			$this->_data=$this->_getList( "SELECT * FROM #__symbol_symbol WHERE symbol_id = '".$gcid."'" );
		}
		return $this->_data;
	}
	
	function _getDataPrizes($ids = array(), $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$filter_o_2=JRequest::getVar('filter_order_2');
		$filter_order = empty($filter_o_2) ? 'prize_name' : $filter_o_2;
		$filter_o_d_2=JRequest::getVar('filter_order_Dir_2');
		$filter_order_dir = empty($filter_o_d_2) ? 'desc' : $filter_o_d_2;
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.createpresentationnew.limitstart_2', 'limitstart_2', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$order = ' order by `' . $filter_order . '` ' . $filter_order_dir;
		
		$query='select * from #__symbol_prize where package_id = \''.JRequest::getVar('package_id').'\'   
			' . $order;		
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['prizes'] = $this->_db->loadObjectList();		
		$query = '
        		select
        			count(*)
        		from
        			#__symbol_prize where package_id = \''.JRequest::getVar('package_id').'\'        		
        		';

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination_2'] = new JPagination( $total, $limitstart, $limit );
		$return['lists_2'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit,
    			'order'=>$filter_order,
    			'order_dir'=>($filter_order_dir == 'desc' ? 'asc' : 'desc')
		);
		return $return;		
	}
	
	public function save_data($prize_id, $symbol_id){
		$this->_db = &JFactory::getDBO ();
		$present_id=JRequest::getVar('presentation_id');
		$createdate = JFactory::getDate()->toSql();		
		$query = 'insert into #__symbol_symbol_prize (id, symbol_id, presentation_id)
			values (\''.$prize_id.'\', \''.$symbol_id.'\', \''.(empty($present_id) ? '0' : $present_id).'\' ) ';
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
			return false;
		} else {
			return true;
		}		
	}
	
	public function delete_data($id) {
		$this->_db = &JFactory::getDBO ();
		$createdate = JFactory::getDate()->toSql();		
		$query = 'delete from #__symbol_symbol_prize where symbol_prize_id = \''.$id.'\' ';
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
			return false;
		} else {
			return true;
		}
	}
	
	public function get_data($ids = array(), $limit = 20, $limitstart = 0) {
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$present_id=JRequest::getVar('presentation_id');
		$filter_o_3=JRequest::getVar('filter_order_3');
		$filter_order = empty($filter_o_3) ? 'prize_name' :$filter_o_3;
		$filter_o_d_3=JRequest::getVar('filter_order_Dir_3');
		$filter_order_dir = empty($filter_o_d_3) ? 'desc' : $filter_o_d_3;
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.createpresentationnew.limitstart_3', 'limitstart_3', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$order = ' order by `' . $filter_order . '` ' . $filter_order_dir;
		
		$query='SELECT a.`symbol_prize_id`, c.`prize_name`, c.`prize_image`, c.`prize_value`, b.`symbol_name`, b.`symbol_image`, b.`pieces`,
				b.`cols`, b.`rows`
				FROM #__symbol_symbol_prize a
				INNER JOIN #__symbol_symbol b ON b.`symbol_id` = a.`symbol_id`
				INNER JOIN #__symbol_prize c ON c.`id` = a.`id`' .
				'WHERE a.`presentation_id` = '.(empty($present_id) ? '0' : $present_id).' ' . $order;
		
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['data'] = $this->_db->loadObjectList();		
		$query = ' 	SELECT count(c.`prize_name`)
					FROM #__symbol_symbol_prize a
					INNER JOIN #__symbol_symbol b ON b.`symbol_id` = a.`symbol_id`
					INNER JOIN #__symbol_prize c ON c.`id` = a.`symbol_prize_id`' .
					'WHERE a.`presentation_id` = '.(empty($present_id) ? '0' : $present_id).' ';
		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();
		
		$return['pagination_3'] = new JPagination( $total, $limitstart, $limit );
		$return['lists_3'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit,
    			'order'=>$filter_order,
    			'order_dir'=>$filter_order_dir
		);
		return $return;			
	}
	
	public function get_data_user_groups($ids = array(), $limit = 20, $limitstart = 0) {
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$filter_o=JRequest::getVar('filter_order');
		$filter_o_3=JRequest::getVar('filter_order_3');
		$filter_o_d=JRequest::getVar('filter_order_Dir');
		$filter_o_d_3=JRequest::getVar('filter_order_Dir_3');
		$filter_order = empty($filter_o) ? 'prize_name' : $filter_o_3;
		$filter_order_dir = empty($filter_o_d) ? 'desc' : $filter_o_d_3;
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.presentnew.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$order = ' order by `' . $filter_order . '` ' . $filter_order_dir;
		
		$query = 'SELECT * FROM #__ap_usergroup a WHERE a.`package_id` = \''.JRequest::getVar('package_id').'\' ';
		
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['data'] = $this->_db->loadObjectList();		
		$query = 'SELECT COUNT(*) FROM #__ap_usergroup a WHERE a.`package_id` = \''.JRequest::getVar('package_id').'\' ';
		
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
	
	public function get_data_presentation($ids = array(), $limit = 20, $limitstart = 0) {
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$filter_o_2=JRequest::getVar('filter_order_2');
		$filter_o_d_2=JRequest::getVar('filter_order_d_2');
		$filter_order = empty($filter_o_2) ? 'presentation_id' : $filter_o_2;
		$filter_order_dir = empty($filter_o_d_2) ? 'desc' : $filter_o_d_2;
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.presentnew.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$order = ' order by `' . $filter_order . '` ' . $filter_order_dir;
		
		$query = 'SELECT * FROM #__symbol_presentation a WHERE a.`package_id` = \''.JRequest::getVar('package_id').'\' ';
		
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['data'] = $this->_db->loadObjectList();		
		$query = 'SELECT COUNT(*) FROM #__symbol_presentation a WHERE a.`package_id` = \''.JRequest::getVar('package_id').'\' ';
		
		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();
		
		$return['pagination_2'] = new JPagination( $total, $limitstart, $limit );
		$return['lists_2'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit,
    			'order'=>$filter_order,
    			'order_dir'=>$filter_order_dir
		);
		return $return;		
		
	}
	
	public function get_user_from_usergroups($criteria, $filter1, $filter2, $package_id) {
		$query = 'SELECT 0 AS cnt';
		switch ($criteria){
			case 'email' : 
				$query = 'SELECT COUNT(*) AS cnt FROM #__ap_useraccounts WHERE package_id = \''.$package_id.'\'';
				break;			
			case 'age' :
				$query = 'SELECT COUNT(*) AS cnt FROM #__ap_useraccounts WHERE package_id = \''.$package_id.'\' and
				      TIMESTAMPDIFF(YEAR,birthday,NOW()) BETWEEN '.$filter1.' AND '.$filter2.' ';
				break;
			case 'gender' :
				$query = 'SELECT COUNT(*) AS cnt FROM #__ap_useraccounts WHERE package_id = \''.$package_id.'\' and
				     gender = \''.$filter1.'\' ';
				break;
			case 'name' :
				$query = 'SELECT COUNT(*) AS cnt FROM #__ap_useraccounts WHERE package_id = \''.$package_id.'\' and
				     LOWER(firstname) LIKE \'%'.strtolower($filter1).'%\' OR LOWER(lastname) LIKE \'%'.strtolower($filter2).'%\' ';
				break;
		}
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();
		return !empty($data) ? $data[0]->cnt : '0';
	}
	
	public function get_user_from_usergroups_by_location($street, $city, $state, $post_code, $country, $package_id) {
		$query = $query = 'SELECT COUNT(*) AS cnt FROM #__ap_useraccounts WHERE package_id = \''.$package_id.'\' and
				     LOWER(street) LIKE \'%'.strtolower($street).'%\' OR LOWER(city) LIKE \'%'.strtolower($city).'%\'
				     OR LOWER(state) LIKE \'%'.strtolower($state).'%\' OR LOWER(country) LIKE \'%'.$country.'%\'
				     OR post_code = \''.$post_code.'\' ';
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();
		return !empty($data) ? $data[0]->cnt : '0';
	}
	
	public function insert_new_usergroups($package_id) {
		$this->_db = &JFactory::getDBO ();
		$query = 'INSERT INTO #__ap_usergroup (package_id, field) VALUES (\''.$package_id.'\', \'New\')';
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
			return false;
		} else {
			return true;
		}	
	}
	
	public function insert_new_presentation($package_id) {				
		$db	= &JFactory::getDbo();
		$date = date('Y-m-d H:i:s');
		$query = 'INSERT INTO #__symbol_presentation (presentation_create, presentation_modify, presentation_publish, package_id, status) 
				  VALUES (\''.$date.'\', \''.$date.'\', \'0\', \''.$package_id.'\', \'0\')';
		$db->setQuery($query);
		$db->query();
		return $db->insertId();		
	}
	
	public function insert_new_process_symbol($presentation_id) {
		$db	= &JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process");
		$query->set("presentation_id = '".$presentation_id."'");		
		$db->setQuery($query);
		$db->query();
		return $db->insertId();	
	}
	
	public function check_prize($presentation_id) {
		$db		= &JFactory::getDbo();
		$query = '
			SELECT a.*,b.*,c.id AS prize_id, a.id AS process_symbol
			FROM
			#__ap_symbol_process AS a
			INNER JOIN #__symbol_symbol_prize AS b ON a.presentation_id=b.presentation_id
			INNER JOIN #__symbol_prize AS c ON c.`id`=b.`id`
			WHERE
			1=1
			AND b.presentation_id= \''.$presentation_id.'\'
			AND c.unlocked_status=\'0\'
		';
		$db->setQuery($query);
		$db->query();
		$rows	= $db->loadObjectList();
		return $rows;
	}
	
	public function getExtract_2($process_id, $prize_id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__ap_symbol_process_prize_extracted");
		$query->where("prize_id='".$prize_id."' and process_id='".$process_id."'");
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
	}
	
	public function delete_extract_detail($extract_id) {
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->delete("#__ap_symbol_process_extract");
		$query->where("extract_id='".$extract_id."'");
		$db->setQuery($query);
		$db->query();
	}
	
	public function deleteExtract($id) {
		$db	= &JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->delete("#__ap_symbol_process_prize_extracted");
		$query->where("id='".$id."' ");
		$db->setQuery($query);
		$db->query();		 
	}
	
	public function activeStatusAll($symbol_id) {
		$db	= &JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->update("#__symbol_symbol_pieces");
		$query->set("is_lock='0'");
		$query->where("symbol_id='".$symbol_id."'");
		$db->setQuery($query);
		$db->query();
	}
	
	public function saveExtract($process_id,$prize_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_prize_extracted");
		$query->set("prize_id='".$prize_id."'");
		$query->set("process_id='".$process_id."'");		
		$db->setQuery($query);
		$db->query();
		return $db->insertId();
	}
	
	public function getPiecesAll($symbol_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_symbol_pieces AS a");
		$query->order("RAND()");
		$query->where("a.symbol_id='".$symbol_id."'");
		$query->where("a.is_lock='0'");
		$query->order("a.symbol_pieces_id");
		$db->setQuery($query);
		$rows	= $db->loadObjectList();
		return $rows;
	}
	
	public function save_extract_detail($extract_id,$pieces_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_extract");
		$query->set("pieces_id='".$pieces_id."'");
		$query->set("extract_id='".$extract_id."'");
		$db->setQuery($query);
		$db->query();
	}
	
	public function updateStatus($pieces_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__symbol_symbol_pieces");
		$query->set("is_lock='1'");
		$query->where("symbol_pieces_id='".$pieces_id."'");
		$db->setQuery($query);
		$db->query();
	}
	
    public function get_extracted_pieces($presentation_id) {
		$query = '
			SELECT d.`symbol_pieces_id`, d.`symbol_id`, d.`symbol_pieces_image`, d.`is_lock` FROM 
			#__ap_symbol_process_prize_extracted a 
			INNER JOIN #__ap_symbol_process_extract b ON b.`extract_id` = a.`id` 
			INNER JOIN #__symbol_symbol_prize c ON c.`id` = a.`prize_id` AND c.`presentation_id` = \''.$presentation_id.'\'
			INNER JOIN #__symbol_symbol_pieces d ON d.`symbol_pieces_id` = b.`pieces_id`
		 ';		
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();
		return $data;
	}
	
	public function get_clone_prize($prize_id,$process_id,$symbol_id) {
		$db		= &JFactory::getDbo();
		$query  = "
			SELECT *
			FROM #__ap_symbol_process_process_clone AS a
			WHERE process_id = '".$process_id."' AND prize_id = '".$prize_id."' AND symbol_id = '".$symbol_id."'
		";	
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
	}

	public function delete_clone_pieces($symbol_id, $clone_id) {
		$db		= &JFactory::getDbo();
		$query  = "
			DELETE FROM #__symbol_symbol_pieces WHERE symbol_pieces_id NOT IN 
			(
				SELECT pieces_id FROM #__ap_symbol_process_clone WHERE clone_id = '".$clone_id."'
			) AND symbol_id = '".$symbol_id."' AND is_lock = 0
		";	
		$db->setQuery($query);
		$db->query();		 
	}
	
	public function delete_clone_detail($clone_id){
		$db	= &JFactory::getDbo();
		$query = "
			DELETE FROM #__ap_symbol_process_clone
			WHERE clone_id = '".$clone_id."'
		";
		$db->setQuery($query);
		$db->query();
	}
	
	public function delete_clone_prize($prize_id,$process_id,$symbol_id) {
		$db	= &JFactory::getDbo();
		$query = "
			DELETE FROM #__ap_symbol_process_process_clone
			WHERE process_id = '".$process_id."' AND prize_id = '".$prize_id."' AND symbol_id = '".$symbol_id."'
		";
		$db->setQuery($query);
		$db->query();
	}
	
	public function save_clone_prize($prize_id,$process_id,$symbol_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_process_clone");
		$query->set("process_id='".$process_id."'");
		$query->set("prize_id='".$prize_id."'");
		$query->set("symbol_id='".$symbol_id."'");
		$db->setQuery($query);
		$db->query();
		return $db->insertId();		
	}
	
	public function getPieces($symbol_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__symbol_symbol_pieces AS a");
		$query->order("RAND()");
		$query->where("a.symbol_id='".$symbol_id."'");
		$query->where("a.is_lock='0'");
		$db->setQuery($query);
		$rows	= $db->loadObjectList();
		return $rows;
	}
	
	public function save_clone_pieces($symbol_id,$symbol_pieces_image){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__symbol_symbol_pieces");
		$query->set("symbol_id='".$symbol_id."'");
		$query->set("symbol_pieces_image='".$symbol_pieces_image."'");
		$db->setQuery($query);
		$db->query();		
	}
	
	public function save_clone_detail($clone_id,$pieces_id){
		$db		= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->insert("#__ap_symbol_process_clone");
		$query->set("pieces_id='".$pieces_id."'");
		$query->set("clone_id='".$clone_id."'");
		$db->setQuery($query);
		$db->query();
	}
	
	public function saveUpdateExtractData($extra_from, $id){
		$db	= &JFactory::getDbo();
		$query	= $db->getQuery(TRUE);
		$query->update("#__ap_symbol_process");
		$query->set("extra_from = '".$extra_from."'");		
		$query->where("id = '".$id."'");		
		$db->setQuery($query);
		return $db->query();
	}
}