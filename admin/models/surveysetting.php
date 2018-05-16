<?php
// no direct access
defined('_JEXEC') or die();

// Import Joomla! libraries
jimport('joomla.application.component.modellist');

class AwardpackageModelSurveysetting extends JModelList {

	function __construct() {
		parent::__construct();
	}

	protected function populateState($ordering = null, $direction = null){		 
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$accessId = $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', null, 'int');
		$this->setState('filter.access', $accessId);

		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', 3, 'int');
		$this->setState('filter.state', $published);

		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', '');
		$this->setState('filter.category_id', $categoryId);

		$language = $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		// Load the parameters.
		$params = JComponentHelper::getParams(S_APP_NAME);
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.title', 'asc');
	}

	public function get_surveys($ids = array(), $limit = 20, $limitstart = 0, $published = -1){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest( S_APP_NAME.'.surveys.filter_order', 'filter_order', 'a.created', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( S_APP_NAME.'.surveys.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		$limitstart = $app->getUserStateFromRequest( S_APP_NAME.'.surveys.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
			
		$this->populateState($filter_order, $filter_order_dir);

		$catid = $this->state->get('filter.category_id');
		$state = $published >= 0 ? $published : $this->state->get('filter.state');
		$search = $this->state->get('filter.search');
		$userid = $app->input->post->getInt('uid', 0);

		$wheres = array();
		$return = array();
			
		if(!empty($ids)){

			$wheres[] = 'a.id in ('.implode(',', $ids).')';
		}

		if($catid){

			$wheres[] = 'a.catid = '.$catid;
		}

		if($userid){

			$wheres[] = 'a.created_by = '.$userid;
		}

		if($state >= 0 && $state < 3){

			$wheres[] = 'a.published = '.$state;
		}

		if(!empty($search)){

			$wheres[] = 'a.title like \'%'.$this->_db->escape($search).'%\'';
		}

		$where = ((count($wheres) > 0) ? ' where ('.implode(' ) and ( ', $wheres).')' : '');
		$wheres[] = 'a.package_id = \''.JRequest::getVar('package_id').'\'';
		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

		$query = '
        		select
        			a.id, a.title, a.alias, a.introtext, a.created_by, a.created, a.catid, a.published, a.responses, a.survey_key,
    				a.publish_up, a.publish_down, a.private_survey, a.max_responses, a.anonymous, a.public_permissions, a.display_template,
        			c.title as category, c.alias as calias,a.package_id,
					u.name, u.username, u.email
        		from
        			#__survey a
        		left join
        			#__categories c ON a.catid = c.id
        		left join
        			#__users u ON a.created_by = u.id
    			'.$where.$order;

		$this->_db->setQuery($query, $limitstart, $limit);
		$return['surveys'] = $this->_db->loadObjectList();

		/************ pagination *****************/
		$query = '
        		select
        			count(*)
        		from
        			#__survey a
        		left join
        			#__categories c on a.catid = c.id
        		left join
        			#__users u on a.created_by = u.id
        		'.$where;

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
		/************ pagination *****************/

		$return['lists'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit,
    			'order'=>$filter_order,
    			'order_dir'=>$filter_order_dir,
    			'catid'=>$catid,
    			'search'=>$search,
    			'uid'=>$userid,
    			'state'=>$state);

		return $return;
	}	
	
}
?>