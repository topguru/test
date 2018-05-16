<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class AwardpackageModelShoppingcreditplan extends JModelLegacy {
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
        		SELECT b.`name`, a.`id`, a.`date_created`, a.`note`, a.`published`, a.`sc_plan` FROM #__shopping_credit_plan a
				INNER JOIN #__shopping_credit_category b ON b.`id` = a.`category`
                 where a.package_id = '.$package_id.'
    			'.$order;
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['shoppings'] = $this->_db->loadObjectList();

		/************ pagination *****************/
		$query = '
        		SELECT count(*) FROM #__shopping_credit_plan a
				INNER JOIN #__shopping_credit_category b ON b.`id` = a.`category`
                where a.package_id = '.$package_id.'
        		';

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

	public function create_credit_plan($package_id){
		$this->_db = &JFactory::getDBO ();
		$createdate = JFactory::getDate()->toSql();
		$query = '
        		SELECT a.`id`, a.`name`, a.`published`, a.`date_created`, a.`package_id`  FROM #__shopping_credit_category a
                 where a.package_id = '.$package_id.'
        		order by a.`id` asc ';
		$this->_db->setQuery($query);
		$categories = $this->_db->loadObjectList();
		if(!empty($categories)) {
			$category = $categories[0];
			$query = 'insert into #__shopping_credit_plan (category, sc_plan, published, note, date_created, package_id)
					values (\'' . $category->id . '\',\'0\', \'0\', \'0\', \''.$createdate.'\', \''.$package_id.'\')';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	function set_status($id, $status){
		$query = 'update #__shopping_credit_plan set published = '.($status ? 1 : 0).' where id in ('.$id.')';
		$this->_db->setQuery($query);
		if(!$this->_db->query()){
			return false;
		}else{
			if($count = $this->_db->getAffectedRows()){
				$query2 = 'update #__shopping_credit_plan set published = 0 where id NOT in ('.$id.')';
				$this->_db->setQuery($query2);
				$this->_db->query();
				return true;
			}
		}
		return false;
	}

	public function delete_plan($id){
		$queries = array();
		$id = implode(',', $id);
		$queries[] = 'delete from #__shopping_credit_plan where id in ('.$id.')';
		foreach ($queries as $query){
			$this->_db->setQuery($query);
			if(!$this->_db->query()){
				return false;
			}
		}
		return true;
	}

	public function get_list_progress_check($package_id, $uniq_id, $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.shoppingcreditcategory.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT * FROM #__progress_check a WHERE a.`package_id` = '.$package_id.' AND a.`uniq_key` = \''.$uniq_id.'\'
        		 ';
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['progress_check'] = $this->_db->loadObjectList();

		$query = '
        		select
        			count(*)
        		from
        			#__progress_check a
        		WHERE a.`package_id` = '.$package_id.' AND a.`uniq_key` = \''.$uniq_id.'\'
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

	public function insert_progress_check($package_id, $every, $type){
		$this->_db = &JFactory::getDBO ();
		$createdate = JFactory::getDate()->toSql();
		$query = ' insert into #__progress_check (every, type, date_created, package_id, uniq_key)
				values (\''.$every.'\', \''.$type.'\', \''.$createdate.'\', \''.$package_id.'\', \''.JRequest::getVar('uniq_id').'\')
			';
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
			return false;
		} else {
			return true;
		}
	}

	public function delete_progress_check($package_id, $id){
		$this->_db = &JFactory::getDBO ();
		$query = 'delete from #__progress_check where package_id = '.$package_id.' and id in ('.$id.') ';
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
			return false;
		} else {
			return true;
		}
	}

	public function get_list_contribution_range($package_id, $uniq_id , $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.shoppingcreditcategory.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT * FROM #__contribution_range a WHERE a.`package_id` = '.$package_id.' AND a.`uniq_key` = \''.$uniq_id.'\'
        		 ';
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['contribution_range'] = $this->_db->loadObjectList();

		$query = '
        		select
        			count(*)
        		from
        			#__contribution_range a
        		WHERE a.`package_id` = '.$package_id.' AND a.`uniq_key` = \''.$uniq_id.'\'
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

	public function insert_contribution_range($package_id, $min_amount, $max_amount){
		$this->_db = &JFactory::getDBO ();
		$createdate = JFactory::getDate()->toSql();
		$query = 'insert into #__contribution_range (min_amount, max_amount, date_created, package_id, uniq_key)
				  values (\'' . $min_amount . '\', \''.$max_amount.'\', \''.$createdate.'\', \''.$package_id.'\', \''.JRequest::getVar('uniq_id').'\')';
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
			return false;
		} else {
			return true;
		}
	}

	public function delete_contribution_range($package_id, $ids){
		$this->_db = &JFactory::getDBO ();
		$query = 'delete from #__contribution_range where package_id = '.$package_id.' and id in ('.$ids.') ';
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
			return false;
		} else {
			return true;
		}
	}

	public function is_contribution_inrange($min_amount, $max_amount){
		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT * FROM #__contribution_range a WHERE a.`max_amount` >= '.$min_amount.'
					AND a.`package_id` = \''.JRequest::getVar('package_id').'\'
					AND a.`uniq_key` = \''.JRequest::getVar('uniq_id').'\'
				 ';
		$this->_db->setQuery($query);
		$ranges = $this->_db->loadObjectList();
		if(!empty($ranges) && count($ranges) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function save_update_donation_fee($id, $package_id, $uniq_id, $fee, $contribution_range = '0', $progress_check = '0'){
		$createdate = JFactory::getDate()->toSql();
		$this->_db = &JFactory::getDBO ();

		$query = "SELECT * FROM #__shopping_credit_from_donation
			WHERE uniq_key = '$uniq_id'
			AND contribution_range = '$contribution_range'
			AND progress_check = '$progress_check'";

		$this->_db->setQuery($query);
		$donations = $this->_db->loadObject();
		if(!empty($donations) && count($donations) > 0) {
			//do update
			$query = 'UPDATE #__shopping_credit_from_donation SET fee = '.$fee.', contribution_range = \''.$contribution_range.'\',
					 progress_check = \''.$progress_check.'\' WHERE id = \''.$donations->id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		} else {
			//do insert
			$query = 'INSERT INTO #__shopping_credit_from_donation (`shopping_credit_plan_id`, `fee`, `date_created`, `package_id`, `uniq_key`, `contribution_range`, `progress_check`)
						VALUES (\''.$id.'\', \''.$fee.'\', \''.$createdate.'\', \''.$package_id.'\', \''.$uniq_id.'\', \''.$contribution_range.'\', \''.$progress_check.'\' ) ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
				return false;
			} else {
				return true;
			}
		}
	}

	public function save_update_donation_refunded($package_id, $uniq_id, $refunded, $contribution_range = '0', $progress_check = '0'){
		$createdate = JFactory::getDate()->toSql();
		$this->_db = &JFactory::getDBO ();

		$query = "SELECT * FROM #__shopping_credit_from_donation
			WHERE uniq_key = '$uniq_id'
			AND contribution_range = '$contribution_range'
			AND progress_check = '$progress_check'";

		$this->_db->setQuery($query);
		$donations = $this->_db->loadObject();
		if(!empty($donations) && count($donations) > 0) {
			//do update
			$query = 'UPDATE #__shopping_credit_from_donation SET refund = '.$refunded.', contribution_range = \''.$contribution_range.'\',
					progress_check = \''.$progress_check.'\' WHERE id = \''.$donations->id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		} else {
			//do insert
			$query = 'INSERT INTO #__shopping_credit_from_donation (`shopping_credit_plan_id`, `refund`, `date_created`, `package_id`, `uniq_key`, `contribution_range`, `progress_check`)
						VALUES (\'0\', \''.$refunded.'\', \''.$createdate.'\', \''.$package_id.'\', \''.$uniq_id.'\', \''.$contribution_range.'\', \''.$progress_check.'\' ) ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		}
	}

	public function save_update_donation_unlock($package_id, $uniq_id, $unlock, $contribution_range = '0', $progress_check = '0') {
		$createdate = JFactory::getDate()->toSql();
		$this->_db = &JFactory::getDBO ();

		$query = "SELECT * FROM #__shopping_credit_from_donation
			WHERE uniq_key = '$uniq_id'
			AND contribution_range = '$contribution_range'
			AND progress_check = '$progress_check'";

		$this->_db->setQuery($query);
		$donations = $this->_db->loadObject();
		if(!empty($donations) && count($donations) > 0) {
			//do update
			$query = 'UPDATE #__shopping_credit_from_donation SET `unlock` = '.$unlock.', contribution_range = \''.$contribution_range.'\',
					 progress_check = \''.$progress_check.'\' WHERE id = \''.$donations->id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		} else {
			//do insert
			$query = 'INSERT INTO #__shopping_credit_from_donation (`shopping_credit_plan_id`, `unlock`, `date_created`, `package_id`, `uniq_key`, `contribution_range`, `progress_check`)
						VALUES (\'0\', \''.$unlock.'\', \''.$createdate.'\', \''.$package_id.'\', \''.$uniq_id.'\', \''.$contribution_range.'\', \''.$progress_check.'\' ) ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		}
	}

	public function save_update_donation_expire($package_id, $uniq_id, $expire, $contribution_range = '0', $progress_check = '0') {
		$createdate = JFactory::getDate()->toSql();
		$this->_db = &JFactory::getDBO ();

		$query = "SELECT * FROM #__shopping_credit_from_donation
			WHERE uniq_key = '$uniq_id'
			AND contribution_range = '$contribution_range'
			AND progress_check = '$progress_check'";

		$this->_db->setQuery($query);
		$donations = $this->_db->loadObject();
		if(!empty($donations) && count($donations) > 0) {
			//do update
			$query = 'UPDATE #__shopping_credit_from_donation SET expire = '.$expire.', contribution_range = \''.$contribution_range.'\',
				progress_check = \''.$progress_check.'\' WHERE id = \''.$donations->id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		} else {
			//do insert
			$query = 'INSERT INTO #__shopping_credit_from_donation (`shopping_credit_plan_id`, `expire`, `date_created`, `package_id`, `uniq_key`, `contribution_range`, `progress_check`)
						VALUES (\'0\', \''.$expire.'\', \''.$createdate.'\', \''.$package_id.'\', \''.$uniq_id.'\', \''.$contribution_range.'\', \''.$progress_check.'\' ) ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		}
	}

	public function save_update_award_fee($package_id, $uniq_id, $fee, $contribution_range = '0', $progress_check = '0') {
		$createdate = JFactory::getDate()->toSql();
		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT * FROM #__shopping_credit_from_purchase_award_symbol a WHERE a.`uniq_key` = \''.$uniq_id.'\'
				 ';
		$this->_db->setQuery($query);
		$awards = $this->_db->loadObjectList();
		if(!empty($awards) && count($awards) > 0) {
			//do update
			$query = 'UPDATE #__shopping_credit_from_purchase_award_symbol SET fee = '.$fee.', contribution_range = \''.$contribution_range.'\',
				progress_check = \''.$progress_check.'\' WHERE uniq_key = \''.$uniq_id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		} else {
			//do insert
			$query = 'INSERT INTO #__shopping_credit_from_purchase_award_symbol (`shopping_credit_plan_id`, `fee`, `date_created`, `package_id`, `uniq_key`, `contribution_range`, `progress_check`)
						VALUES (\'0\', \''.$fee.'\', \''.$createdate.'\', \''.$package_id.'\', \''.$uniq_id.'\', \''.$contribution_range.'\', \''.$progress_check.'\' ) ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		}
	}

	public function save_update_award_refund($package_id, $uniq_id, $refund, $contribution_range = '0', $progress_check = '0') {
		$createdate = JFactory::getDate()->toSql();
		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT * FROM #__shopping_credit_from_purchase_award_symbol a WHERE a.`uniq_key` = \''.$uniq_id.'\'
				 ';
		$this->_db->setQuery($query);
		$awards = $this->_db->loadObjectList();
		if(!empty($awards) && count($awards) > 0) {
			//do update
			$query = 'UPDATE #__shopping_credit_from_purchase_award_symbol SET refund = '.$refund.', contribution_range = \''.$contribution_range.'\',
					progress_check = \''.$progress_check.'\' WHERE uniq_key = \''.$uniq_id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		} else {
			//do insert
			$query = 'INSERT INTO #__shopping_credit_from_purchase_award_symbol (`shopping_credit_plan_id`, `refund`, `date_created`, `package_id`, `uniq_key`, `contribution_range`, `progress_check`)
						VALUES (\'0\', \''.$refund.'\', \''.$createdate.'\', \''.$package_id.'\', \''.$uniq_id.'\', \''.$contribution_range.'\', \''.$progress_check.'\' ) ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		}
	}

	public function save_update_award_unlock($package_id, $uniq_id, $unlock, $contribution_range = '0', $progress_check = '0') {
		$createdate = JFactory::getDate()->toSql();
		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT * FROM #__shopping_credit_from_purchase_award_symbol a WHERE a.`uniq_key` = \''.$uniq_id.'\'
				 ';
		$this->_db->setQuery($query);
		$awards = $this->_db->loadObjectList();
		if(!empty($awards) && count($awards) > 0) {
			//do update
			$query = 'UPDATE #__shopping_credit_from_purchase_award_symbol SET `unlock` = '.$unlock.', contribution_range = \''.$contribution_range.'\',
					progress_check = \''.$progress_check.'\' WHERE uniq_key = \''.$uniq_id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		} else {
			//do insert
			$query = 'INSERT INTO #__shopping_credit_from_purchase_award_symbol (`shopping_credit_plan_id`, `unlock`, `date_created`, `package_id`, `uniq_key`, `contribution_range`, `progress_check`)
						VALUES (\'0\', \''.$unlock.'\', \''.$createdate.'\', \''.$package_id.'\', \''.$uniq_id.'\', \''.$contribution_range.'\', \''.$progress_check.'\' ) ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		}
	}

	public function save_update_award_expire($package_id, $uniq_id, $expire, $contribution_range = '0', $progress_check = '0'){
		$createdate = JFactory::getDate()->toSql();
		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT * FROM #__shopping_credit_from_purchase_award_symbol a WHERE a.`uniq_key` = \''.$uniq_id.'\'
				 ';
		$this->_db->setQuery($query);
		$awards = $this->_db->loadObjectList();
		if(!empty($awards) && count($awards) > 0) {
			//do update
			$query = 'UPDATE #__shopping_credit_from_purchase_award_symbol SET `expire` = '.$expire.', contribution_range = \''.$contribution_range.'\',
				progress_check = \''.$progress_check.'\' WHERE uniq_key = \''.$uniq_id.'\' ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		} else {
			//do insert
			$query = 'INSERT INTO #__shopping_credit_from_purchase_award_symbol (`shopping_credit_plan_id`, `expire`, `date_created`, `package_id`, `uniq_key`, `contribution_range`, `progress_check`)
						VALUES (\'0\', \''.$expire.'\', \''.$createdate.'\', \''.$package_id.'\', \''.$uniq_id.'\', \''.$contribution_range.'\', \''.$progress_check.'\' ) ';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				return false;
			} else {
				return true;
			}
		}
	}

	public function insert_giftcode($package_id, $uniq_id, $contribution_range = '0', $progress_check = '0') {
		$createdate = JFactory::getDate()->toSql();
		$this->_db = &JFactory::getDBO ();
		$query = '
				SELECT * FROM #__shopping_credit_giftcode WHERE `package_id` = '.$package_id.' AND `uniq_key` = \''.$uniq_id.'\'
			';
		$this->_db->setQuery($query);
		$sgiftcodes = $this->_db->loadObjectList();
		if(empty($sgiftcodes)) {
			$query = '	SELECT * FROM #__giftcode_category a WHERE a.`package_id` = \''.$package_id.'\'
					 ';
			$this->_db->setQuery($query);
			$giftcodes = $this->_db->loadObjectList();
			foreach ($giftcodes as $giftcode) {
				$query = 'INSERT INTO #__shopping_credit_giftcode (`shopping_credit_plan_id`, `giftcode_id`, `quantity`, `date_created`, `package_id`, `uniq_key`, `fee`, `contribution_range`, `progress_check`)
						VALUES (\'0\', \''.$giftcode->id.'\', \'0\', \''.$createdate.'\', \''.$package_id.'\', \''.$uniq_id.'\', \'0\', \''.$contribution_range.'\', \''.$progress_check.'\' ) ';
				$this->_db->setQuery ( $query );
				$this->_db->query ();
			}
		}
	}

	public function get_credit_from_donation($package_id, $uniq_id, $contribution_range, $progress_check){
		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT * FROM #__shopping_credit_from_donation a WHERE a.`uniq_key` = \''.$uniq_id.'\' and a.`package_id` = \''.$package_id.'\'
					AND `contribution_range` = \''.$contribution_range.'\'
					AND `progress_check` = \''.$progress_check.'\'
				 ';
		$this->_db->setQuery($query);
		$donations = $this->_db->loadObjectList();
		return $donations;
	}

	public function get_credit_from_award($package_id, $uniq_id, $contribution_range, $progress_check){
		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT * FROM #__shopping_credit_from_purchase_award_symbol a WHERE a.`uniq_key` = \''.$uniq_id.'\' and a.`package_id` = \''.$package_id.'\'
					AND `contribution_range` = \''.$contribution_range.'\'
					AND `progress_check` = \''.$progress_check.'\'
				 ';
		$this->_db->setQuery($query);
		$awards = $this->_db->loadObjectList();
		return $awards;
	}

	public function get_giftcode_category($package_id, $uniq_id){
		$this->_db = &JFactory::getDBO ();
		$query = '	SELECT b.`id`, b.`name`, a.`quantity`, a.`fee` FROM #__shopping_credit_giftcode a
					INNER JOIN #__giftcode_category b ON b.id = a.`giftcode_id`
					WHERE a.`package_id` = \''.$package_id.'\' AND a.`uniq_key` = \''.$uniq_id.'\'
				 ';
		$this->_db->setQuery($query);
		$giftcodes = $this->_db->loadObjectList();
		return $giftcodes;
	}

	public function update_giftcode_quantity($package_id, $uniq_id, $giftcode_id, $giftcode_quantity) {
		$this->_db = &JFactory::getDBO ();
		$query = 'UPDATE #__shopping_credit_giftcode SET `quantity` = \''.$giftcode_quantity.'\' WHERE `giftcode_id` = \''.$giftcode_id.'\'
				AND `package_id` = \''.$package_id.'\' AND `uniq_key` = \''.$uniq_id.'\'
				 ';
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			return false;
		} else {
			return true;
		}
	}

	public function update_giftcode_fee($package_id, $uniq_id, $fee) {
		$this->_db = &JFactory::getDBO ();
		$query = 'UPDATE #__shopping_credit_giftcode SET `fee` = \''.$fee.'\' WHERE
				 `package_id` = \''.$package_id.'\' AND `uniq_key` = \''.$uniq_id.'\'
				 ';
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			return false;
		} else {
			return true;
		}
	}

	public function save_shopping_credit_plan(){
		$plan_id = JRequest::getVar('plan_id');
		if($plan_id == '0') {
			$createdate = JFactory::getDate()->toSql();
			$this->_db = &JFactory::getDBO ();
			$query = '
					INSERT INTO #__shopping_credit_plan_detail (start_date, end_date, contribution_range, progress_check, date_created, package_id, uniq_key)
					VALUES (
					\''.JRequest::getVar('from').'\',
					\''.JRequest::getVar('to').'\',
					\''.JRequest::getVar('contrib_radio').'\',
					\''.JRequest::getVar('progress_radio').'\',
					\''.$createdate.'\',
					\''.JRequest::getVar('package_id').'\',
					\''.JRequest::getVar('uniq_id').'\'
					)
				';
			$this->_db->setQuery($query);
			if($this->_db->query()){
				if($plan_id == '0') {
					$plan_id = $this->_db->insertid();
					$query = 'UPDATE #__shopping_credit_from_donation SET `shopping_credit_plan_id` = \''.$plan_id.'\' WHERE
							 `package_id` = \''.JRequest::getVar('package_id').'\' AND `uniq_key` = \''.JRequest::getVar('uniq_id').'\'
							 ';
					$this->_db->setQuery ( $query );
					$this->_db->query();

					$query = 'UPDATE #__shopping_credit_from_purchase_award_symbol SET `shopping_credit_plan_id` = \''.$plan_id.'\' WHERE
							 `package_id` = \''.JRequest::getVar('package_id').'\' AND `uniq_key` = \''.JRequest::getVar('uniq_id').'\'
							 ';
					$this->_db->setQuery ( $query );
					$this->_db->query();

					$query = 'UPDATE #__shopping_credit_giftcode SET `shopping_credit_plan_id` = \''.$plan_id.'\' WHERE
							 `package_id` = \''.JRequest::getVar('package_id').'\' AND `uniq_key` = \''.JRequest::getVar('uniq_id').'\'
							 ';
					$this->_db->setQuery ( $query );
					$this->_db->query();

					$query = 'UPDATE #__contribution_range SET `shopping_credit_plan_id` = \''.$plan_id.'\' WHERE
							 `package_id` = \''.JRequest::getVar('package_id').'\' AND `uniq_key` = \''.JRequest::getVar('uniq_id').'\'
							 ';
					$this->_db->setQuery ( $query );
					$this->_db->query();

					$query = 'UPDATE #__progress_check SET `shopping_credit_plan_id` = \''.$plan_id.'\' WHERE
							 `package_id` = \''.JRequest::getVar('package_id').'\' AND `uniq_key` = \''.JRequest::getVar('uniq_id').'\'
							 ';
					$this->_db->setQuery ( $query );
					$this->_db->query();

					$query = 'UPDATE #__shopping_credit_plan SET `sc_plan` = \''.$plan_id.'\', `category` = \''.JRequest::getVar('catid').'\' WHERE
							 `id` = \''.JRequest::getVar('id').'\'
							 ';
					$this->_db->setQuery ( $query );
					$this->_db->query();
				}
			}
		} else {
			$this->_db = &JFactory::getDBO ();
			$query = '
					UPDATE #__shopping_credit_plan_detail SET start_date = \''.JRequest::getVar('from').'\',
					end_date = \''.JRequest::getVar('to').'\',
					contribution_range = \''.JRequest::getVar('contrib_radio').'\',
					progress_check = \''.JRequest::getVar('progress_radio').'\'
					WHERE `id` = \''.$plan_id.'\'
				';
			$this->_db->setQuery($query);
			if($this->_db->query()){
				$query = 'UPDATE #__shopping_credit_plan SET `sc_plan` = \''.$plan_id.'\', `category` = \''.JRequest::getVar('catid').'\' WHERE
						 `id` = \''.JRequest::getVar('id').'\'
						 ';
				$this->_db->setQuery ( $query );
				$this->_db->query();

				$query = 'UPDATE #__contribution_range SET `shopping_credit_plan_id` = \''.$plan_id.'\' WHERE
							 `package_id` = \''.JRequest::getVar('package_id').'\' AND `uniq_key` = \''.JRequest::getVar('uniq_id').'\'
							 ';
				$this->_db->setQuery ( $query );
				$this->_db->query();

				$query = 'UPDATE #__shopping_credit_from_donation SET contribution_range = \''.JRequest::getVar('contrib_radio').'\' WHERE
							 `package_id` = \''.JRequest::getVar('package_id').'\' AND `uniq_key` = \''.JRequest::getVar('uniq_id').'\'
							 ';
				$this->_db->setQuery ( $query );
				$this->_db->query();

				$query = 'UPDATE #__progress_check SET `shopping_credit_plan_id` = \''.$plan_id.'\' WHERE
						 `package_id` = \''.JRequest::getVar('package_id').'\' AND `uniq_key` = \''.JRequest::getVar('uniq_id').'\'
						 ';
				$this->_db->setQuery ( $query );
				$this->_db->query();


			}
		}
	}

	public function get_shopping_credit_plan_detail($package_id, $id) {
		$this->_db = &JFactory::getDBO ();
		$query = ' 	SELECT a.`category`, b.`id`, b.`uniq_key`, b.`start_date`, b.`end_date`, b.`contribution_range`, b.`progress_check`,
					       CONCAT( \'$\', pc.`min_amount`, \' to \', \'$\', pc.`max_amount`) AS contribution_range_value,
					       CONCAT( \'Every \', pc2.`every`, \' \', pc2.`type`) AS progress_check_value
					FROM #__shopping_credit_plan a
					INNER JOIN #__shopping_credit_plan_detail b ON b.`id` = a.`sc_plan`
					LEFT JOIN #__contribution_range pc ON pc.`id` = b.`contribution_range`
					LEFT JOIN #__progress_check pc2 ON pc2.`id` = b.`progress_check`
					WHERE a.`id` = \''.$id.'\' AND a.`package_id` = \''.$package_id.'\'
				 ';
		$this->_db->setQuery($query);
		$plans = $this->_db->loadObjectList();
		return $plans;
	}

	public function get_contribution_range_by_id($id) {
		$this->_db = &JFactory::getDBO ();
		$query = "SELECT 
			CASE 
			    WHEN pc.`max_amount` = 0 THEN CONCAT( 'Over $', pc.`min_amount` - 1)
			    ELSE CONCAT( '$', pc.`min_amount`, ' to ', '$', pc.`max_amount`)
			END AS contribution_range_value
			FROM #__contribution_range pc 
			WHERE pc.`id` = '$id'";

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
	public function get_shopping_credit_plan_notes($package_id, $id) {
		$this->_db = &JFactory::getDBO ();
		$query = ' 	SELECT note
					FROM #__shopping_credit_plan
					WHERE `id` = \''.$id.'\' AND `package_id` = \''.$package_id.'\'
				 ';
		$this->_db->setQuery($query);
		//echo $query;
		$plans = $this->_db->loadObjectList();
		return $plans;
	}
	public function update_notes($id, $notes,$package_id){
		$this->_db = &JFactory::getDBO ();
		$query = 'UPDATE #__shopping_credit_plan SET `note` = \''.$notes.'\' WHERE
						 `id` = \''.JRequest::getVar('id').'\' and `package_id` = \''.$package_id.'\'
						 ';
		$this->_db->setQuery ( $query );
		//echo $query;
		if (! $this->_db->query ()) {
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
			return false;
		} else {
			return true;
		}
	}
}