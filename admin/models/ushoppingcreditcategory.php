<?php
// no direct access
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
class AwardpackageUsersModelUshoppingcreditcategory extends JModelLegacy {
	function __construct() {
		parent::__construct();
	}

	public function list_categories(){
		$query = '
        		select
        			id, name, published, date_created, package_id
        		from
        			#__shopping_credit_category a        		
    			order by a.`id` asc ';
		$this->_db->setQuery($query);
		$categories = $this->_db->loadObjectList ();
		return $categories;
	}
}