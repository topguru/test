<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
class AwardpackageUsersModelShoppingcreditplan extends JModelLegacy {
	function __construct() {
		parent::__construct();
	}
	
	public function get_shopping_credit_plan($package_id,$ids = array(), $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest('com_awardpackage.shoppingcreditplan.filter_order', 'filter_order', 'b.name', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest('com_awardpackage.shoppingcreditcategory.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.shoppingcreditcategory.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

		$query = '
        		SELECT b.`name`, a.`id`, a.`date_created`, a.`note`, a.`published`, a.`sc_plan`, c.* FROM #__shopping_credit_plan a
				INNER JOIN #__shopping_credit_category b ON b.`id` = a.`category`    
				INNER JOIN #__shopping_credit_from_donation c ON c.shopping_credit_plan_id = a.sc_plan    
        		WHERE a.package_id = '.$package_id.' AND a.`sc_plan` > 0 '.$order;
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['shoppings'] = $this->_db->loadObjectList();

		/************ pagination *****************/
		$query = '
        		SELECT count(*) FROM #__shopping_credit_plan a
				INNER JOIN #__shopping_credit_category b ON b.`id` = a.`category`       		
        		WHERE a.package_id = '.$package_id.' AND a.`sc_plan` > 0 ';

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
		/************ pagination *****************/

		$return['lists'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit,
    			'order'=>$filter_order,
    			'order_dir'=>$filter_order_dir
		);

		return $return;
	}

	public function get_shopping_credit_plan_detail($id) {
		$this->_db = &JFactory::getDBO ();
		$query = ' 	SELECT a.`category`, b.`id`, b.`uniq_key`, b.`start_date`, b.`end_date`, b.`contribution_range`, b.`progress_check`,
					       CONCAT( \'$\', pc.`min_amount`, \' to \', \'$\', pc.`max_amount`) AS contribution_range_value,
					       CONCAT( \'Every \', pc2.`every`, \' \', pc2.`type`) AS progress_check_value,
					       pc2.`every` AS progress_check_every,
					       pc2.`type` AS progress_check_type
					FROM #__shopping_credit_plan a 
					INNER JOIN #__shopping_credit_plan_detail b ON b.`id` = a.`sc_plan` 
					LEFT JOIN #__contribution_range pc ON pc.`id` = b.`contribution_range`
					LEFT JOIN #__progress_check pc2 ON pc2.`id` = b.`progress_check`
					WHERE a.`id` = \''.$id.'\'			
				 ';
		$this->_db->setQuery($query);
		$plans = $this->_db->loadObjectList();
		return $plans;
	}

	public function get_list_contribution_range($uniq_id , $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.shoppingcreditcategory.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT * FROM #__contribution_range a WHERE a.`uniq_key` = \''.$uniq_id.'\'
        		 ';
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['contribution_range'] = $this->_db->loadObjectList();

		$query = '
        		select
        			count(*)
        		from
        			#__contribution_range a 
        		WHERE a.`uniq_key` = \''.$uniq_id.'\'     		
        		';
		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination_contribution_range'] = new JPagination( $total, $limitstart, $limit );
		$return['lists_contribution_range'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit
		);

		return $return;
	}

	public function get_list_progress_check($uniq_id, $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.shoppingcreditcategory.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT * FROM #__progress_check a WHERE a.`uniq_key` = \''.$uniq_id.'\'
        		 ';
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['progress_check'] = $this->_db->loadObjectList();

		$query = '
        		select
        			count(*)
        		from
        			#__progress_check a 
        		WHERE a.`uniq_key` = \''.$uniq_id.'\'     		
        		';
		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination_progress_check'] = new JPagination( $total, $limitstart, $limit );
		$return['lists_progress_check'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit
		);
		return $return;
	}

	public function get_credit_from_donation($uniq_id, $contribution_range, $progress_check){
		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT * FROM #__shopping_credit_from_donation a WHERE a.`uniq_key` = \''.$uniq_id.'\'
					AND `contribution_range` = \''.$contribution_range.'\'
					AND `progress_check` = \''.$progress_check.'\'
				 ';		
		$this->_db->setQuery($query);
		$donations = $this->_db->loadObjectList();
		return $donations;
	}

	public function get_credit_from_award($uniq_id, $contribution_range, $progress_check){
		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT * FROM #__shopping_credit_from_purchase_award_symbol a WHERE a.`uniq_key` = \''.$uniq_id.'\'
					AND `contribution_range` = \''.$contribution_range.'\'
					AND `progress_check` = \''.$progress_check.'\'
				 ';
		$this->_db->setQuery($query);
		$awards = $this->_db->loadObjectList();
		return $awards;
	}

	public function get_giftcode_category($uniq_id){
		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT b.`id`, b.`name`, a.`quantity`, a.`fee` FROM #__shopping_credit_giftcode a
					INNER JOIN #__giftcode_category b ON b.id = a.`giftcode_id`
					WHERE a.`uniq_key` = \''.$uniq_id.'\'
				 ';
		$this->_db->setQuery($query);
		$giftcodes = $this->_db->loadObjectList();
		return $giftcodes;
	}
	
	public function get_contribution_range_by_id($id) {
		$this->_db = &JFactory::getDBO ();
		$query = ' 	SELECT CONCAT( \'$\', pc.`min_amount`, \' to \', \'$\', pc.`max_amount`) AS contribution_range_value 
					FROM #__contribution_range pc WHERE pc.`id` = \''.$id.'\'	
				 ';
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();		
		return $data;
	}
	
	public function get_progress_check_by_id($id) {
		$this->_db = &JFactory::getDBO ();
		$query = ' 	SELECT CONCAT( \'Every \', pc.`every`, \' \', pc.`type`) AS progress_check_value 
					FROM #__progress_check pc WHERE pc.`id` = \''.$id.'\'	
				 ';
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();		
		return $data;
	}
	
	public function getShoppingCredit($packageId) {
        $db = JFactory::getDbo();
        $user = &JFactory::getUser();
        $user_id = $user->id;
		$query = "select a.*, b.start_date, b.end_date, c.min_amount, c.max_amount, e.every, e.type
		  from #__shopping_credit_from_donation a 
		 		 INNER JOIN #__shopping_credit_plan_detail b ON b.uniq_key = a.uniq_key
				 INNER JOIN #__shopping_credit_plan d ON d.sc_plan = b.id
		 		 INNER JOIN #__contribution_range c ON c.id = a.contribution_range
				 INNER JOIN #__progress_check e ON e.id = a.progress_check
				  WHERE a.package_id='".$packageId."'  AND d.published='1'
				 "
				 ;
		/*$query = "SELECT a.* , b.published, c.min_amount, c.max_amount, d.every, d.type, e.start_date, e.end_date 
				  FROM #__shopping_credit_from_donation a
				  INNER JOIN #__shopping_credit_plan b ON b.sc_plan = a.shopping_credit_plan_id
   			      INNER JOIN #__shopping_credit_plan_detail e ON e.id = b.sc_plan
				  INNER JOIN #__contribution_range c ON e.id = a.contribution_range
				  INNER JOIN #__progress_check d ON e.id = d.shopping_credit_plan_id
				  WHERE a.package_id='".$packageId."'  AND b.published='1'";*/
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    public function getDonationTotal($user_id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__ap_donation_transactions WHERE user_id='$user_id'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $total = 0;
        foreach ($rows as $row) {
            $total = $row->credit + $total;
        }
        return $total;
    }

    public function getTransactionTotal($user_id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__ap_donation_transactions WHERE user_id='$user_id'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $total = 0;
        foreach ($rows as $row) {
            $total = $row->debit + $total;
        }
        return $total;
    }

    public function updateStatus($distribution_id) {
        $db = JFactory::getDbo();
        $query = "UPDATE #__shopping_credit_distribution_list SET status='1' WHERE distribution_list_id='$distribution_id'";
        $db->setQuery($query);
        if ($db->query()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function saveOrder($data) {
        $db = JFactory::getDbo();
        $query = $db->insertObject('#__shopping_record', $data);
        if ($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
	
	
	public function get_donation_history($user_id){	
	$db = JFactory::getDbo();
        $user = &JFactory::getUser();
        $user_id = $user->id;
        $query = "SELECT a.* FROM #__funding_history a 
		          INNER JOIN #__funding_user b ON a.funding_id = b.funding_id 
                   WHERE b.user_id='".$user_id."' AND a.transaction_type='DONATION' ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
		 return $rows;
	}
}