<?php
// no direct access
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
class AwardpackageModelShoppingcreditcategory extends JModelLegacy {

	function __construct() {
		parent::__construct();
	}
	
	public function get_shopping_credit_category($package_id, $ids = array(), $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest('com_awardpackage.shoppingcreditcategory.filter_order', 'filter_order', 'a.name', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest('com_awardpackage.shoppingcreditcategory.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.shoppingcreditcategory.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

		$query = '
        		select
        			id, name, published, date_created, package_id
        		from
        			#__shopping_credit_category a     
				where a.package_id = '.$package_id.'	   		
    			'.$order;
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['shoppings'] = $this->_db->loadObjectList();
		
		$query = '
        		select
        			count(*)
        		from
        			#__shopping_credit_category a        		
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
	
	public function get_shopping_credit_category_byid($id){
		$query = '
        		select
        			id, name, published, date_created, package_id
        		from
        			#__shopping_credit_category a        		
    			where a.id = \''.$id.'\' ';
		$this->_db->setQuery($query);
		$categories = $this->_db->loadObjectList ();
		if(!empty($categories)) {
			$category = $categories[0];			
		}
		return $category;
	}

	public function save_update_category($package_id, $sname, $id){
		$this->_db = &JFactory::getDBO ();
		$createdate = JFactory::getDate()->toSql();
		if($id != '') {
			$query = 'update #__shopping_credit_category set name = \''.$sname.'\' where id = \''.$id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		} else {
			$query = 'insert into #__shopping_credit_category (name, published, date_created, package_id)
					values (\'' . $sname . '\',' . 0 . ', \''.$createdate.'\', \''.$package_id.'\')';
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
		$queries[] = 'delete from #__shopping_credit_category where id in ('.$id.')';			
		foreach ($queries as $query){
			$this->_db->setQuery($query);
			if(!$this->_db->query()){					
				return false;
			}
		}			
		return true;
	}
	
	function set_status($id, $status){
		$query = 'update #__shopping_credit_category set published = \''.($status ? 1 : 0).'\' where id in ('.$id.')';
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
        			#__shopping_credit_category a  
				where package_id ='.$package_id.'	      		
    			order by a.`id` asc ';
		$this->_db->setQuery($query);
		$categories = $this->_db->loadObjectList ();
		return $categories;
	}
	
}