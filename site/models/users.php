<?php
/**
 * @version		$Id: users.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.modelitem');

class AwardpackageUsersModelUsers extends JModelLegacy {
	
	function __construct() {
		parent::__construct ();
	}

	public function load_users_from_items($items, $component){
		if(empty($items) || $component == 'none') return;
		$ids = array();
		foreach($items as $item){				
			$ids[] = $item->created_by;
		}
		if(!empty($ids)){				
			CJFunctions::load_users($component, $ids);
		}
	}

	function get_user_responses(){

	}

	public function get_user_subscriptions($user_id){

		$query = '
				select
					s.subscription_type, s.subscription_id,
					c.id as catid, c.title as category, c.alias as cat_alias
				from
					#__quiz_subscribes s
				left join
					#__quiz_categories c on s.subscription_id = c.id and (s.subscription_type = 2 or s.subscription_type = 3)
				where
					s.subscriber_id = '.$user_id.'
				order by
					s.subscription_type desc';

		$this->_db->setQuery($query);
		$subscribes = $this->_db->loadObjectList();

		return $subscribes;
	}
}