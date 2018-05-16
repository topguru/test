<?php
/**
 * @version		$Id: surveys.php 01 2012-04-21 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.admin
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die();

// Import Joomla! libraries
jimport('joomla.application.component.modellist');
jimport('joomla.application.component.helper');
defined('S_APP_NAME') or define('S_APP_NAME', 'com_awardpackage');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
//require_once JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cjlib'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'functions.php';
class AwardpackageModelSurveys extends JModelLegacy {

	function __construct() {
		parent::__construct();
	}	
	
	function get_categories($package_id) {
        
            if (empty($this->_categories)) {
              $this->_categories = $this->_getList("SELECT * FROM #__survey_categories
                                                    WHERE package_id = '$package_id' ORDER BY id ASC");
            }            
            return $this->_categories;    
      }
	
	public function populateState($ordering = null, $direction = null){			
		$app = JFactory::getApplication('administrator');

		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$accessId = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', null, 'int');
		$this->setState('filter.access', $accessId);

		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', 3, 'int');
		$this->setState('filter.state', $published);

		$categoryId = $app->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', '');
		$this->setState('filter.category_id', $categoryId);

		$language = $app->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		$params = JComponentHelper::getParams(S_APP_NAME);
		$this->setState('params', $params);

		parent::populateState('a.title', 'asc');
	}
	
	public function get_surveys_response($user_id, $ids = array(), $limit = 20, $limitstart = 0, $published = -1){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest( S_APP_NAME.'.surveys.filter_order', 'filter_order', 'a.created', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( S_APP_NAME.'.surveys.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		$limitstart = $app->getUserStateFromRequest( S_APP_NAME.'.surveys.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
			
		$this->populateState($filter_order, $filter_order_dir);

		$catid = $this->state->get('filter.category_id');
		$state = ($published >= 0 ? $published : $this->state->get('filter.state'));
		
		$search = JRequest::getVar('search');
		$userid = $app->input->post->getInt('uid', 0);

		$wheres = array();
		$return = array();
			
		if(!empty($ids)){

			$wheres[] = 'a.id in ('.implode(',', $ids).')';
		}

		if($catid){

			$wheres[] = 'a.catid = '.$catid;
		}
		
		if($state < 3) {
			$wheres[] = 'a.published = '.$state;
		}
		
		if($userid){

			$wheres[] = 'a.created_by = '.$userid;
		}
		if(!empty($search)){

			$wheres[] = 'a.title like \'%'.$this->_db->escape($search).'%\'';
		}
		
		$wheres[] = 'a.package_id = \''.JRequest::getVar('package_id').'\'';

		$where = ((count($wheres) > 0) ? ' where ('.implode(' ) and ( ', $wheres).')' : '');		
		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

		$query = '
        		select
        			a.id, a.title, a.alias, a.introtext, a.created_by, a.created, a.catid, a.published, a.responses, a.survey_key,
    				a.publish_up, a.publish_down, a.private_survey, a.max_responses, a.anonymous, a.public_permissions, a.display_template,
        			c.title as category, c.alias as calias,
					u.name, u.username, u.email, a.package_id
        		from
        			#__survey a
        		inner join #__survey_responses r on r.survey_id = a.id and r.created_by = \''.$user_id.'\'
        		left join
        			#__survey_categories c ON a.catid = c.id
        		left join
        			#__users u ON a.created_by = u.id
    			'.$where.$order;
				
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['surveys'] = $this->_db->loadObjectList();

		$query = '
        		select
        			count(*)
        		from
        			#__survey a
        		inner join #__survey_responses r on r.survey_id = a.id and r.created_by = \''.$user_id.'\'
        		left join
        			#__survey_categories c on a.catid = c.id
        		left join
        			#__users u on a.created_by = u.id
        		'.$where;

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
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
		$state = ($published >= 0 ? $published : $this->state->get('filter.state'));
		
		$search = JRequest::getVar('search');
		$userid = $app->input->post->getInt('uid', 0);

		$wheres = array();
		$return = array();
			
		if(!empty($ids)){

			$wheres[] = 'a.id in ('.implode(',', $ids).')';
		}

		if($catid){

			$wheres[] = 'a.catid = '.$catid;
		}
		
		if($state < 3) {
			$wheres[] = 'a.published = '.$state;
		}
		
		if($userid){

			$wheres[] = 'a.created_by = '.$userid;
		}
		if(!empty($search)){

			$wheres[] = 'a.title like \'%'.$this->_db->escape($search).'%\'';
		}
		
		$wheres[] = 'a.package_id = \''.JRequest::getVar('package_id').'\'';

		$where = ((count($wheres) > 0) ? ' where ('.implode(' ) and ( ', $wheres).')' : '');		
		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

		$query = '
        		select
        			a.id, a.title, a.alias, a.introtext, a.created_by, a.created, a.catid, a.published, a.responses, a.survey_key,
    				a.publish_up, a.publish_down, a.private_survey, a.max_responses, a.anonymous, a.public_permissions, a.display_template,
        			c.title as category, c.alias as calias,
					u.name, u.username, u.email, a.package_id
        		from
        			#__survey a
        		left join
        			#__survey_categories c ON a.catid = c.id
        		left join
        			#__users u ON a.created_by = u.id
    			'.$where.$order;
				
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['surveys'] = $this->_db->loadObjectList();

		$query = '
        		select
        			count(*)
        		from
        			#__survey a
        		left join
        			#__survey_categories c on a.catid = c.id
        		left join
        			#__users u on a.created_by = u.id
        		'.$where;

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
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
	public function get_survey_responses($id, $limit=20, $limitstart=0){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$limitstart = $limitstart ? $limitstart : $app->getUserStateFromRequest( S_APP_NAME.'.limitstart','limitstart','','int' );
		$limit = $limit ? $limit : $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$filter_order = $app->getUserStateFromRequest( S_APP_NAME.'.responses_order','filter_order','a.created','cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( S_APP_NAME.'.responses_order_dir','filter_order_dir','DESC','word' );

		$result = new stdClass();
		$result->survey = $this->get_survey_details($id);

		jimport('joomla.html.pagination');
		$query = 'select count(*) from #__survey_responses a where a.survey_id='.$id;
		$this->_db->setQuery( $query );
		$total = $this->_db->loadResult();
		$result->pagination = new JPagination( $total, $limitstart, $limit );

		$query = '
    			select 
    				a.id, a.created, a.created_by, a.completed >= a.created as finished, u.username, u.name
    			from 
    				#__survey_responses a
    			left join 
    				#__users u on a.created_by = u.id
    			where 
    				a.survey_id='.$id.' 
    			order by 
    				'.$filter_order.' '.$filter_order_dir;
			
		$this->_db->setQuery($query, $limitstart, $limit);
		$result->responses = $this->_db->loadObjectList();

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_dir;

		$result->lists = $lists;

		return $result;
	}
	function save_survey(&$survey){
			
		$survey->alias = JFilterOutput::stringURLUnicodeSlug($survey->alias);
			
		$query = '
    			update 
    				#__survey
    			set
    				title = '.$this->_db->quote($survey->title).',
    				alias = '.$this->_db->quote($survey->alias).',
    				description = '.$this->_db->quote($survey->description).',
    				catid = '.$survey->catid.',
    				published = '.$survey->published.',
    				duration = '.$survey->duration.',
    				skip_intro = '.$survey->skip_intro.',
    				multiple_responses = '.$survey->multiple_responses.',
    				show_answers = '.$survey->show_answers.',
    				show_template = '.$survey->show_template.'
    			where
    				id = '.$survey->id;
			
		$this->_db->setQuery($query);
			
		if($this->_db->query()){

			JFactory::getApplication()->enqueueMessage($this->_db->getErrorMsg());
			return true;
		}
			
		return false;

	}
	function set_status($id, $column, $status){
		if(is_array($id)){
			$id = implode(',', $id);
		}			
		$col_name = $this->_db->quoteName($column);
		$query = 'update #__survey set '.$col_name.' = '.($status ? 1 : 0).' where id in ('.$id.')';
		$this->_db->setQuery($query);
		if(!$this->_db->query()){
			return false;
		}else{
			return true;
		}
	}
	
	function set_state($id, $status){
		if(is_array($id)){
			$id = implode(',', $id);
		}			
		$col_name = $this->_db->quoteName($column);
		$query = 'update #__survey set published = '.($status ? 1 : 0).' where id in ('.$id.')';
		$this->_db->setQuery($query);
		if(!$this->_db->query()){
			return false;
		}else{
			return true;
		}
	}
	
	function set_approval_surveys($id, $status){
			
		$approved_surveys = null;
			
		if($status == true){

			$query = 'select id from #__survey where id in ('.$id.') and published = 2';
			$this->_db->setQuery($query);
			$approved_surveys = $this->_db->loadResultArray();
		}
			
		$query = 'update #__survey set published = '.($status ? 1 : 0).' where id in ('.$id.')';
		$this->_db->setQuery($query);
			
		if(!$this->_db->query()){

			return false;
		}else{

			if($count = $this->_db->getAffectedRows()){
					
				return false;
			}

			return !empty($approved_surveys) ? $approved_surveys : true;
		}
	}

	function delete_surveys($id){			
		$queries = array();
		$id = implode(',', $id);			
		$queries[] = 'delete from #__survey_response_details where response_id in (select id from #__survey_responses where survey_id in ('.$id.'))';
		$queries[] = 'delete from #__survey_responses where survey_id in ('.$id.')';
		$queries[] = 'delete from #__survey_answers where survey_id in ('.$id.')';
		$queries[] = 'delete from #__survey_questions where survey_id in ('.$id.')';
		$queries[] = 'delete from #__survey_pages where sid in ('.$id.')';
		$queries[] = 'delete from #__survey where id in ('.$id.')';
		foreach ($queries as $query){
			$this->_db->setQuery($query);
			if(!$this->_db->query()){					
				return false;
			}		
		}			
		return true;
	}

	function remove_survey_responses($id_csv, $survey_id){

		$query = 'delete from #__survey_response_details where response_id in ('.$id_csv.')';
		$this->_db->setQuery($query);

		if($this->_db->query()){

			$query = 'delete from #__survey_responses where survey_id = '.$survey_id.' and id in ('.$id_csv.')';
			$this->_db->setQuery($query);

			if($this->_db->query()){

				$query = 'delete from #__survey_keys where survey_id = '.$survey_id.' and response_id in ('.$id_csv.')';
				$this->_db->setQuery($query);

				if($this->_db->query()){

					$query = '
    						update 
    							#__survey 
    						set 
    							responses = (
    								select 
    									count(*) 
    								from 
    									#__survey_responses 
    								where 
    									survey_id='.$survey_id.' and 
    									completed >= created
    							) 
    						where 
    							id='.$survey_id;

					$this->_db->setQuery($query);
					$this->_db->query();

					return true;
				}
			}
		}

		return false;
	}	
	function get_survey_details($id){
			
		$query = '
    			select
        			a.id, a.title, a.alias, a.created_by, a.created, a.catid, a.published, a.responses, a.skip_intro, a.ip_address, 
    				a.survey_key, a.publish_up, a.publish_down, a.private_survey, a.max_responses, a.anonymous, a.public_permissions, 
    				a.display_template, a.introtext, a.endtext, a.custom_header, a.redirect_url,
        			c.title as category, c.alias as calias,
					u.name, u.username, u.email
        		from
        			#__survey a
        		left join
        			#__survey_categories c ON a.catid = c.id
        		left join
        			#__users u ON a.created_by = u.id
    			where
    				a.id='.$id;
			
		$this->_db->setQuery($query);
			
		return $this->_db->loadObject();
	}
	
	function get_questions($survey_id, $page_id=0){
			
		$user = JFactory::getUser ();
		$where = '';

		if($page_id){

			$where = ' and page_number=' . $page_id;
		}

		$query = '
			select 
				id, survey_id, title, description, question_type, page_number, 
				responses, sort_order, mandatory, orientation 
			from 
				#__survey_questions 
			where 
				survey_id='.$survey_id.$where.' 
			order by 
				page_number, sort_order asc';

		$this->_db->setQuery ( $query );
		$questions = $this->_db->loadObjectList ( 'id' );

		if ($questions) {

			$query = '
				select 
					id, question_id, answer_type, sort_order 
				from 
					#__survey_answers 
				where 
					survey_id='.$survey_id.' and question_id in (select id from #__survey_questions where survey_id=' . $survey_id . $where . ') 
				order by 
					id asc';

			$this->_db->setQuery ( $query );
			$answers = $this->_db->loadObjectList ();

			if ($answers && (count ( $answers ) > 0)) {

				foreach ( $answers as $answer ) {

					$questions [$answer->question_id]->answers [] = $answer;
				}

				return $questions;
			} else {

				$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10075<br>query: ' . $query . '<br><br>' );

				return false;
			}
		} else {

			$error = $this->_db->getErrorMsg ();

			if (! empty ( $error )) {

				$this->setError ( $error . '<br><br> Error code: 10076<br>query: ' . $query . '<br><br>' );
			}

			return false;
		}
	}
	
	function get_question_details($uniq_key, $question_id, $page_id=0, $survey_id=0){
			
		$user = JFactory::getUser ();
		$where = '';
		if($page_id){
			$where = " page_number= '". $page_id."' and uniq_key = '".$uniq_key."' and question_id = '".$question_id."' " ;
		}
		$query = '
			select 
				id, survey_id, title, description, question_type, page_number, 
				responses, sort_order, mandatory, orientation 
			from 
				#__survey_questions 
			where 
				'.$where.' 
			order by 
				page_number, sort_order asc';
		$this->_db->setQuery ( $query );
		$questions = $this->_db->loadObjectList ('id');
	
		if ($questions) {
			foreach ($questions as $question) {
				$query = '
					select 
						id, question_id, answer_type, sort_order, answer_label, image 
					from 
						#__survey_answers 
					where 
						question_id = '.$question->id.' 
					order by 
						id asc';
				$this->_db->setQuery ( $query );
				$answers = $this->_db->loadObjectList ();
				if ($answers && (count ( $answers ) > 0)) {
					$question->answers = $answers;
				}	
				$query = '
						select
							id, survey_id, question_id, rulecontent
						from #__survey_rules
						where question_id = '.$question->id.'
					';
				$this->_db->setQuery ( $query );
				$rules = $this->_db->loadObjectList ();
				if ($rules && (count ( $rules ) > 0)) {
					$question->rules = $rules;								
				}
			}	
			return $questions;		
		} else {
			$error = $this->_db->getErrorMsg ();
			if (! empty ( $error )) {
				$this->setError ( $error . '<br><br> Error code: 10076<br>query: ' . $query . '<br><br>' );
			}
			return false;
		}
	}

	function get_question_by_question_id($qid, $uniq_key, $page) {
		$query = "
			select * from #__survey_questions where question_id = '".$qid."' and page_number = '".$page."'
			and uniq_key = '".$uniq_key."'
		";
		$this->_db->setQuery($query);
		$question = $this->_db->loadObject();
		return $question;
	}

	function get_question($qid){
		$query = '
    		select
    			id, survey_id, question_type, page_number, sort_order, custom_choice, mandatory, title, description, orientation
    		from
    			#__survey_questions
    		where
    			id='.$qid;
			
		$this->_db->setQuery($query);
		$question = $this->_db->loadObject();
			
		if(!empty($question)){

			$query = '
    			select
    				id, answer_type, answer_label, image
    			from
    				#__survey_answers
    			where
    				question_id = '.$qid.'
    			order by
    				sort_order';

			$this->_db->setQuery($query);
			$answers = $this->_db->loadObjectList();

			if(!empty($answers)){

				$question->answers = $answers;
			} else {
					
				$question->answers = array();
			}

			return $question;
		}
			
			
		$error = $this->_db->getErrorMsg ();

		if (! empty ( $error )) {

			$this->setError ( $error . '<br><br> Error code: 10050<br>query: ' . $query . '<br><br>' );
		}
			
		return false;
	}

	function refresh_response_counters(){

		$query = '
    			update 
    				#__survey a 
    			set 
    				responses = (
    					select 
    						count(*) 
    					from 
    						#__survey_responses 
    					where 
    						survey_id=a.id and 
    						completed >= created
    				)';
			
		$this->_db->setQuery($query);
		$this->_db->query();
	}
	function get_contact_groups($user_id){

		$query = 'select id, name, contacts from #__survey_contactgroups where created_by='.$user_id;
		$this->_db->setQuery($query);
		$groups = $this->_db->loadObjectList();

		return !empty($groups) ? $groups : array();
	}
	function check_user_credits($userid=0){
		$app = JFactory::getApplication ();
		if(!$userid){
			$user = JFactory::getUser ();
			$userid = $user->id;
		}
		$params = JComponentHelper::getParams(S_APP_NAME);
		$points_per_credit = (int)$params->get('points_per_credit', 0);
		if(!$points_per_credit) return -1;
		switch ($params->get('points_system', 'none')){
			case 'cjblog':
				$api = JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cjblog'.DIRECTORY_SEPARATOR.'api.php';
				if(file_exists($api)){
					include_once $api;
					$profile = CjBlogApi::get_user_profile($userid);
					if(!empty($profile)){
						return floor($profile['points'] / $points_per_credit);
					}
				}
				break;					
			case 'aup':
				$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
				if ( file_exists($api_AUP)){
					require_once ($api_AUP);
					$profile = AlphaUserPointsHelper::getUserInfo('', $userid);
					if(!empty($profile)){
						return floor($profile->points / $points_per_credit);
					}
				}
				break;
			case 'jomsocial':
				$query = 'select points from #__community_users where userid='.$userid;
				$this->_db->setQuery($query);
				$points = (int)$this->_db->loadResult();
				return floor($points / $points_per_credit);
		}

		return -1;
	}
	function get_contacts($user_id, $type = 1, $gid = 0, $include_emails = false, $limitstart = 0, $limit = 20, $search = null){

		$query = 'select id, name '.($include_emails ? ',email' : '').' from #__survey_contacts where created_by = '.$user_id;

		if($search){

			$query = $query.' and (name like \'%'.$this->_db->escape($search).'%\' or email like \'%'.$this->_db->escape($search).'%\')';
		}

		switch($type){

			case 1: 

				$query = $query . ' and id not in (select contact_id from #__survey_contact_group_map where group_id = '.$gid.') order by name asc';
				$this->_db->setQuery($query);

				break;

			case 2: 

				$query = $query . ' and id in (select contact_id from #__survey_contact_group_map where group_id = '.$gid.') order by name asc';
				$this->_db->setQuery($query);

				break;

			case 3:

				if($gid){

					$query = $query . ' and id in (select contact_id from #__survey_contact_group_map where group_id = '.$gid.')';
				}

				$query = $query . ' order by name asc';
				$this->_db->setQuery($query, $limitstart, $limit);

				break;
		}

		$contacts = $this->_db->loadObjectList();

		return !empty($contacts) ? $contacts : array();
	}

	function get_survey_keys($sid, $limitstart = 0, $limit = 20){

		$keys = null;

		if($this->authorize_survey($sid)){

			$query = 'select key_name, survey_id, response_id, response_status, created from #__survey_keys where survey_id = '.$sid.' order by created desc';
			$this->_db->setQuery($query, $limitstart, $limit);
			$keys = $this->_db->loadObjectList();
		}

		return !empty($keys) ? $keys : array();
	}

	function authorize_survey($id){

		$user = JFactory::getUser();

		if($user->authorise('core.manage', S_APP_NAME)){
			return true;
		}else{

			$query = 'select count(*) from #__survey where id='.$id.' and created_by='.$user->id;
			$this->_db->setQuery($query);
			$count = (int)$this->_db->loadResult();
			if($count){
				return true;
			}
		}
		return false;
	}
    function create_edit_survey() {

		$config = JComponentHelper::getParams(S_APP_NAME);
		$app = JFactory::getApplication ();
		$user = JFactory::getUser ();

		$survey = new stdClass();
		$html = ($user->authorise('core.wysiwyg', S_APP_NAME) && $config->get('default_editor', 'bbcode') == 'wysiwyg');

		$survey->id = $app->input->post->getInt('id', 0);
		$survey->title = $app->input->post->getString('title', null);
		$survey->package_id = $app->input->post->getString('package_id', null);
		$survey->alias = $app->input->post->getString('alias', null);
		$survey->catid = $app->input->post->getInt('catid', 0);
		$survey->created_by = $app->input->post->getInt('userid', 0);
		$survey->private_survey = $app->input->post->getInt('survey-type', 1);
		$survey->anonymous = $app->input->post->getInt('response-type', 1);
		$survey->public_permissions = $app->input->post->getInt('show-result', 0);
		$survey->display_template = $app->input->post->getInt('show-template', 1);
		$survey->skip_intro = $app->input->post->getInt('skip-intro', 0);
		$survey->display_notice = $app->input->post->getInt('display-notice', 1);
		$survey->display_progress = $app->input->post->getInt('display-progress', 1);
		$survey->notification = $app->input->post->getInt('notification', 1);
		$survey->backward_navigation = $app->input->post->getInt('backward-navigation', 1);
		$survey->publish_up = $app->input->post->getString('publish-up', '0000-00-00 00:00:00');
		$survey->publish_down = $app->input->post->getString('publish-down', '0000-00-00 00:00:00');
		$survey->max_responses = $app->input->post->getInt('max-responses', 1);
		$survey->redirect_url = $app->input->post->getString('redirect-url', '');
		$survey->introtext = $app->input->post->getString('introtext', '');
		$survey->endtext = $app->input->post->getString('endtext', '');
		$survey->custom_header = $app->input->post->getString('custom_header', '');
		$survey->restriction = $app->input->post->getArray(array('restriction'=>'array'));
			
		$survey->restriction = implode(',', $survey->restriction['restriction']);
		$created_by = $app->isAdmin() ? ($survey->created_by > 0 ? $survey->created_by : $user->id) : $user->id;
		$survey->username = JFactory::getUser($created_by)->username;
		$survey->alias = empty($survey->alias) ? JFilterOutput::stringURLUnicodeSlug($survey->title) : JFilterOutput::stringURLUnicodeSlug($survey->alias);

		if(empty($survey->title)){

			$survey->error = JText::_('MSG_REQUIRED_FIELDS_MISSING');
			return $survey;
		}
			
		$publish_up = (!empty($survey->publish_up) && $survey->publish_up != '0000-00-00 00:00:00') ? JFactory::getDate($survey->publish_up, $app->getCfg('offset'))->toSql() : '0000-00-00 00:00:00';
		$publish_down = (!empty($survey->publish_down) && $survey->publish_down != '0000-00-00 00:00:00') ? JFactory::getDate ($survey->publish_down, $app->getCfg('offset'))->toSql() : '0000-00-00 00:00:00';
		$mySqlRedirectUrl = empty($survey->redirect_url) ? 'null' : $this->_db->quote($survey->redirect_url);

		if($survey->id > 0){

			if(!$user->authorise('core.edit.own', S_APP_NAME.'.category.'.$survey->catid)){
					
				$survey->error = JText::_('JERROR_ALERTNOAUTH');
				return $survey;
			}

			$query = '
				update
					#__survey
				set
					title='.$this->_db->quote($survey->title).',
					alias='.$this->_db->quote($survey->alias).',
					catid='.$survey->catid.',
					'.($app->isAdmin() ? 'created_by='.$created_by.',' : '').'
					introtext='.$this->_db->quote($survey->introtext).',
					endtext='.$this->_db->quote($survey->endtext).',
					custom_header='.$this->_db->quote($survey->custom_header).',
					private_survey='.$survey->private_survey.',
					anonymous='.$survey->anonymous.',
					public_permissions='.$survey->public_permissions.',
					skip_intro='.$survey->skip_intro.',
					display_notice='.$survey->display_notice.',
					display_progress='.$survey->display_progress.',
					notification='.$survey->notification.',
					backward_navigation='.$survey->backward_navigation.',
					publish_up='.$this->_db->quote($publish_up).',
					publish_down='.$this->_db->quote($publish_down).',
					max_responses='.$survey->max_responses.',
					redirect_url='.$mySqlRedirectUrl.',
					restriction='.$this->_db->quote($survey->restriction).',
					display_template='.$survey->display_template.',
					package_id = '.$survey->package_id.'
				where
					id='.$survey->id;

			$this->_db->setQuery($query);

			if(!$this->_db->query()){

				$this->setError($this->_db->getErrorMsg());
				$survey->error = JText::_('MSG_ERROR_PROCESSING');
			}
		} else {

			if(!$user->authorise('core.create', S_APP_NAME.'.category.'.$survey->catid)){

				$survey->error = JText::_('JERROR_ALERTNOAUTH');
				return $survey;
			}

			$survey->key = JRequest::getVar('uniq_id');//CJFunctions::generate_random_key();
			$createdate = JFactory::getDate()->toSql();

			$query = '
				insert into
					#__survey
					(
						title, alias, introtext, endtext, custom_header, catid, created_by, created, publish_up, publish_down, max_responses, display_notice, display_progress,
						anonymous, private_survey, public_permissions, survey_key, redirect_url, display_template, skip_intro, backward_navigation, notification, restriction, published,package_id
					)
				values
					(
						'.$this->_db->quote($survey->title).',
						'.$this->_db->quote($survey->alias).',
						'.$this->_db->quote($survey->introtext).',
						'.$this->_db->quote($survey->endtext).',
						'.$this->_db->quote($survey->custom_header).',
						'.$survey->catid.',
						'.$created_by.',
						'.$this->_db->quote($createdate).',
						'.$this->_db->quote($publish_up, false).',
						'.$this->_db->quote($publish_down, false).',
						'.$survey->max_responses.',
						'.$survey->display_notice.',
						'.$survey->display_progress.',
						'.$survey->anonymous.',
						'.$survey->private_survey.',
						'.$survey->public_permissions.',
						'.$this->_db->quote($survey->key).',
						'.$mySqlRedirectUrl.',
						'.$survey->display_template.',
						'.$survey->skip_intro.',
						'.$survey->backward_navigation.',
						'.$survey->notification.',
						'.$this->_db->quote($survey->restriction).',
						2,
						'.$survey->package_id.'
					)';

			$this->_db->setQuery($query);

			if(!$this->_db->query()){

				$this->setError($this->_db->getErrorMsg());
				$survey->error = JText::_('MSG_ERROR_PROCESSING');

				return $survey;
			}

			$survey->id = $this->_db->insertid();
			$survey->uniq_id = $app->input->post->getInt('uniq_id', 0);			
		}		
		return $survey;
	}
	
	function update_all_process($survey_id, $uniq_id) {
		$query = " update #__survey_pages set sid = '".$survey_id."' where uniq_key = '".$uniq_id."' ";
		$this->_db->setQuery ( $query );
		if($this->_db->query ()) {
			$query = " update #__survey_question_giftcode set survey_id = '".$survey_id."' where uniq_key = '".$uniq_id."' ";
			$this->_db->setQuery ( $query );
			if($this->_db->query ()) {
				$query = " update #__survey_questions set survey_id = '".$survey_id."' where uniq_key = '".$uniq_id."' ";
				$this->_db->setQuery ( $query );
				if($this->_db->query ()) {
					return true;
				}
			}
		}
		return false;
	}

	function update_answers($survey_id, $uniq_id) {
		$query = "UPDATE #__survey_answers a 
		LEFT JOIN #__survey_questions b ON a.question_id = b.id SET a.survey_id = '".$survey_id."' 
		WHERE b.uniq_key='".$uniq_id."' ";
		$this->_db->setQuery ( $query );
		$this->_db->query ();
		return true;
		}

 
	function get_pages_list($survey_id){
			
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
			
		$query->select('id, title, sort_order, uniq_key')->from('#__survey_pages')->where('sid = '.$survey_id)->order('sort_order asc');
		$db->setQuery($query);
			
		try{

			$pages = $db->loadObjectList();
			return $pages;
		} catch(Exception $e){
			return false;
		}			
		return false;
	}
		
	function save_question() {
		$app = JFactory::getApplication();
		$user = JFactory::getUser ();
		$params = JComponentHelper::getParams(S_APP_NAME);
		$query = '';
		$post = $_SESSION['surveys'];
		// Request parameters
		$survey_id = $post['id'];//app->input->post->getInt('id', 0);
		$pid = $app->input->getInt('pid', 0);
		$qid = $app->input->post->getInt('qid', 0);
		$order = $app->input->post->getInt('order', 0);
		$qtype = $app->input->post->getInt('qtype', 0);
		$title = $app->input->post->getString('title', null);
		$mandatory = $app->input->post->getInt('mandatory', 0);
		$custom_choice = $app->input->post->getInt('custom_answer', 0);
		$orientation = $app->input->post->getWord('orientation', 'H');
		$min_selections = $app->input->post->getInt('min_selections', 0);
		$max_selections = $app->input->post->getInt('max_selections', 0);
		$description = CJFunctions::get_clean_var('description', ($user->authorise('core.wysiwyg', S_APP_NAME) && $params->get('default_editor', 'bbcode') == 'wysiwyg'));
		$uniq_id = JRequest::getVar('uniq_id');
		$questionSelectedId = JRequest::getVar('questionSelectedId');
		if ($pid == 0  || $qtype <= 0 || $user->guest || strlen ( $title ) <= 0) {
			
			$this->setError (JText::_ ( 'MSG_UNAUTHORIZED' ) . '<br><br>Error code: 10001<br>id: ' . $survey_id . '<br>pid: ' . $pid . '<br>qtype: ' . $qtype . '<br>title: ' . $title . '<br><br>' );
			
			return false;
		}
		
		if(!in_array($qtype, array(3, 12))) {

			$min_selections = $max_selections = 0;
		}
		$order = ($qtype == 1) ? 1 : ($order < 2 ? 2 : $order);				
		if ($qid > 0) {
			$query = 'select count(*) from #__survey_questions where id=' . $qid . ' and survey_id='.$survey_id;
			$this->_db->setQuery ( $query );
			$count = ( int )$this->_db->loadResult ();
			if ( !$count ) {
				$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10003<br>query: ' . $query . '<br><br>' );
				return false;
			}
			$query = '
				update 
					#__survey_questions 
				set 
					title = '.$this->_db->quote ( $title ).', 
					description = '.$this->_db->quote( $description ).', 
					mandatory = '.$mandatory.', 
					question_type = '.$qtype.', 
					custom_choice = '.$custom_choice.',
					orientation = '.$this->_db->quote($orientation).',
					min_selections = '.$this->_db->quote($min_selections).',
					max_selections = '.$this->_db->quote($max_selections).'
				where 
					id='.$qid;
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10004<br>query: ' . $query . '<br><br>' );
				return false;
			}
		} else {
			$query = "
					select * from #__survey_questions where question_id = '".$questionSelectedId."' and page_number = '".$pid."'
					and uniq_key = '".$uniq_id."'
				";
			$this->_db->setQuery($query);
			$question = $this->_db->loadObject();
			if(!empty($question)) {
				$this-> setError ('<b>Only one question can create for each</b>');
				return false;
			}
			
			$query = '
				insert into 
					#__survey_questions (title, description, survey_id, page_number, question_type, created_by, mandatory, custom_choice, sort_order, orientation, min_selections, max_selections, uniq_key, question_id) 
				values ('. 
			$this->_db->quote ( $title ).','.
			$this->_db->quote ( $description ).','.
			$survey_id.','.
			$pid.','.
			$qtype.','.
			$user->id.','.
			$mandatory.','.
			$custom_choice.','.
			$order.','.
			$this->_db->quote($orientation).','.
			$this->_db->quote($min_selections).','.
			$this->_db->quote($max_selections).','.
			$this->_db->quote($uniq_id).','.
			$this->_db->quote($questionSelectedId).
				')';

			$this->_db->setQuery ( $query );

			if (! $this->_db->query ()) {

				$this->setError ($this->_db->getErrorMsg() . '<br><br> Error code: 10005<br>query: ' . $query . '<br><br>' );

				return false;
			} else {
				$qid = $this->_db->insertid ();
			}		
		}		
		switch ($qtype) {
			case S_PAGE_HEADER : 
				return $qid;
			case S_CHOICE_RADIO : 
				$choices = $app->input->post->getArray(array('answers'=>'array'));
				$choices = $choices['answers'];

				if (count ( $choices ) <= 0) {
					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10007' );
					return false;				}
				$existing = array();
				foreach ($choices as $row){
					$row = trim($row);
					if (!empty($row) && strpos($row, '_') !== false) {							
						$tokens = explode('_', $row, 4);							
						if(count($tokens) == 4){
							$answer_id = (int) $tokens[0];
							if($answer_id > 0){
								$existing[] = $answer_id;
							}
						}
					}
				}

				if(!empty($existing)){
					$query = 'delete from #__survey_answers where survey_id = '.$survey_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ()) {

						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10023<br>query: ' . $query . '<br><br>' );

						return false;

					}
				}

				$update_columns = Array('answer_label' => '`answer_label` = CASE ', 'sort_order' => '`sort_order` = CASE ', 'image' => '`image` = CASE ');
				$queries = $this->_get_answer_update_queries($update_columns, $choices, $survey_id.','.$qid.','.$this->_db->quote ( 'x' ));

				if(!empty($queries['query_update'])){

					$query_update = 'update #__survey_answers set '.$queries['query_update'].' where id in ('.implode(',', $queries['update_ids']).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ()) {
							
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10008<br>query: ' . $query_update . '<br><br>' );
							
						return false;
							
					}
				}

				if(!empty($queries['query_insert'])){

					$query_insert = 'insert into #__survey_answers ( survey_id, question_id, answer_type, answer_label, sort_order, image) values '.substr($queries['query_insert'], 0, - 1);
					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ()) {

						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10009<br>query: ' . $query_insert . '<br><br>' );

						return false;

					}
				}

				return $qid; 
			case S_CHOICE_CHECKBOX : 
				$choices = $app->input->post->getArray(array('answers'=>'array'));
				$choices = $choices['answers'];
				if (count ( $choices ) <= 0) {
					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10007' );
					return false;				}
				$existing = array();
				foreach ($choices as $row){
					$row = trim($row);
					if (!empty($row) && strpos($row, '_') !== false) {							
						$tokens = explode('_', $row, 4);							
						if(count($tokens) == 4){
							$answer_id = (int) $tokens[0];
							if($answer_id > 0){
								$existing[] = $answer_id;
							}
						}
					}
				}

				if(!empty($existing)){
					$query = 'delete from #__survey_answers where survey_id = '.$survey_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ()) {

						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10023<br>query: ' . $query . '<br><br>' );

						return false;

					}
				}

				$update_columns = Array('answer_label' => '`answer_label` = CASE ', 'sort_order' => '`sort_order` = CASE ', 'image' => '`image` = CASE ');
				$queries = $this->_get_answer_update_queries($update_columns, $choices, $survey_id.','.$qid.','.$this->_db->quote ( 'x' ));

				if(!empty($queries['query_update'])){

					$query_update = 'update #__survey_answers set '.$queries['query_update'].' where id in ('.implode(',', $queries['update_ids']).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ()) {
							
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10008<br>query: ' . $query_update . '<br><br>' );
							
						return false;
							
					}
				}

				if(!empty($queries['query_insert'])){

					$query_insert = 'insert into #__survey_answers (survey_id, question_id, answer_type, answer_label, sort_order, image) values '.substr($queries['query_insert'], 0, - 1);
					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ()) {

						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10009<br>query: ' . $query_insert . '<br><br>' );

						return false;

					}
				}

				return $qid; 
			case S_CHOICE_SELECT :
				$choices = $app->input->post->getArray(array('answers'=>'array'));
				$choices = $choices['answers'];
				if (count ( $choices ) <= 0) {
					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10007' );
					return false;				}
				$existing = array();
				foreach ($choices as $row){
					$row = trim($row);
					if (!empty($row) && strpos($row, '_') !== false) {							
						$tokens = explode('_', $row, 4);							
						if(count($tokens) == 4){
							$answer_id = (int) $tokens[0];
							if($answer_id > 0){
								$existing[] = $answer_id;
							}
						}
					}
				}

				if(!empty($existing)){
					$query = 'delete from #__survey_answers where survey_id = '.$survey_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ()) {

						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10023<br>query: ' . $query . '<br><br>' );

						return false;

					}
				}

				$update_columns = Array('answer_label' => '`answer_label` = CASE ', 'sort_order' => '`sort_order` = CASE ', 'image' => '`image` = CASE ');
				$queries = $this->_get_answer_update_queries($update_columns, $choices, $survey_id.','.$qid.','.$this->_db->quote ( 'x' ));

				if(!empty($queries['query_update'])){

					$query_update = 'update #__survey_answers set '.$queries['query_update'].' where id in ('.implode(',', $queries['update_ids']).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ()) {
							
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10008<br>query: ' . $query_update . '<br><br>' );
							
						return false;
							
					}
				}

				if(!empty($queries['query_insert'])){

					$query_insert = 'insert into #__survey_answers (survey_id, question_id, answer_type, answer_label, sort_order, image) values '.substr($queries['query_insert'], 0, - 1);
					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ()) {

						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10009<br>query: ' . $query_insert . '<br><br>' );

						return false;

					}
				}

				return $qid;

			case S_IMAGE_CHOOSE_IMAGE: 
				$choices = $app->input->post->getArray(array('answers'=>'array'));
				$choices = $choices['answers'];
				if (count ( $choices ) <= 0) {
					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10007' );
					return false;
				}

				$existing = array();

				foreach ($choices as $row){

					$row = trim($row);

					if (!empty($row) && strpos($row, '_') !== false) {
							
						$tokens = explode('_', $row, 4);
							
						if(count($tokens) == 4){

							$answer_id = (int) $tokens[0];

							if($answer_id > 0){

								$existing[] = $answer_id;
							}

							$image = $tokens[3];
							/*if(!empty($image) && JFile::exists(S_TEMP_STORE.DS.$image)){
								JFile::move(S_TEMP_STORE.DS.$image, S_IMAGES_UPLOAD_DIR.DS.$image);
							}*/
						}
					}
				}

				if(!empty($existing)){

					$query = 'delete from #__survey_answers where survey_id = '.$survey_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ()) {

						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10023<br>query: ' . $query . '<br><br>' );

						return false;

					}
				}

				$update_columns = Array('answer_label' => '`answer_label` = CASE ', 'sort_order' => '`sort_order` = CASE ', 'image' => '`image` = CASE ');
				$queries = $this->_get_answer_update_queries($update_columns, $choices, $survey_id.','.$qid.','.$this->_db->quote ( 'x' ));
					
				if(!empty($queries['query_update'])){

					$query_update = 'update #__survey_answers set '.$queries['query_update'].' where id in ('.implode(',', $queries['update_ids']).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ()) {
							
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10008<br>query: ' . $query_update . '<br><br>' );
							
						return false;
							
					}
				}

				if(!empty($queries['query_insert'])){

					$query_insert = '
							insert into 
								#__survey_answers (survey_id, question_id, answer_type, answer_label, sort_order, image) 
							values 
								'.substr($queries['query_insert'], 0, - 1);

					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ()) {
							
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10009<br>query: ' . $query_insert . '<br><br>' );
							
						return false;
							
					}
				}					
				return $qid;
			case S_IMAGE_CHOOSE_IMAGES:
					
				$choices = $app->input->post->getArray(array('answers'=>'array'));
				$choices = $choices['answers'];
					
				if (count ( $choices ) <= 0) {

					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10007' );

					return false;
				}

				$existing = array();

				foreach ($choices as $row){

					$row = trim($row);

					if (!empty($row) && strpos($row, '_') !== false) {
							
						$tokens = explode('_', $row, 4);
							
						if(count($tokens) == 4){

							$answer_id = (int) $tokens[0];

							if($answer_id > 0){

								$existing[] = $answer_id;
							}

							$image = $tokens[3];

							if(!empty($image) && JFile::exists(S_TEMP_STORE.DS.$image)){

								JFile::move(S_TEMP_STORE.DS.$image, S_IMAGES_UPLOAD_DIR.DS.$image);
							}
						}
					}
				}

				if(!empty($existing)){

					$query = 'delete from #__survey_answers where survey_id = '.$survey_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ()) {

						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10023<br>query: ' . $query . '<br><br>' );

						return false;

					}
				}

				$update_columns = Array('answer_label' => '`answer_label` = CASE ', 'sort_order' => '`sort_order` = CASE ', 'image' => '`image` = CASE ');
				$queries = $this->_get_answer_update_queries($update_columns, $choices, $survey_id.','.$qid.','.$this->_db->quote ( 'x' ));
					
				if(!empty($queries['query_update'])){

					$query_update = 'update #__survey_answers set '.$queries['query_update'].' where id in ('.implode(',', $queries['update_ids']).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ()) {
							
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10008<br>query: ' . $query_update . '<br><br>' );
							
						return false;
							
					}
				}

				if(!empty($queries['query_insert'])){

					$query_insert = '
							insert into 
								#__survey_answers (survey_id, question_id, answer_type, answer_label, sort_order, image) 
							values 
								'.substr($queries['query_insert'], 0, - 1);

					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ()) {
							
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10009<br>query: ' . $query_insert . '<br><br>' );
							
						return false;
							
					}
				}					
				return $qid;

			case S_GRID_RADIO : 
				$grid_rows = $app->input->post->getArray(array('answers'=>'array'));
				$grid_columns = $app->input->post->getArray(array('columns'=>'array'));
				$grid_rows = $grid_rows['answers'];
				$grid_columns = $grid_columns['columns'];

				if ((count ( $grid_rows ) <= 0) || (count ( $grid_columns ) <= 0)) {

					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10010' );

					return false;
				}

				$update_queries = array();
				$insert_queries = array();
				$update_columns = Array('answer_label' => '`answer_label` = CASE ', 'sort_order' => '`sort_order` = CASE ', 'image' => '`image` = CASE ');

				$row_queries = $this->_get_answer_update_queries($update_columns, $grid_rows, $survey_id.','.$qid.','.$this->_db->quote ( 'x' ));
				$col_queries = $this->_get_answer_update_queries($update_columns, $grid_columns, $survey_id.','.$qid.','.$this->_db->quote ( 'y' ));

				$existing = array();
				$rows = array_merge($grid_rows, $grid_columns);

				foreach ($rows as $row){
					$row = trim($row);

					if (!empty($row) && strpos($row, '_') !== false) {
							
						$tokens = explode('_', $row, 4);
							
						if(count($tokens) == 4){

							$answer_id = (int) $tokens[0];

							if($answer_id > 0){

								$existing[] = $answer_id;
							}
						}
					}
				}

				if(!empty($existing)){

					$query = 'delete from #__survey_answers where survey_id = '.$survey_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ()) {

						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10013<br>query: ' . $query . '<br><br>' );

						return false;

					}
				}

				if(!empty($row_queries['query_update'])){

					$update_queries[] = $row_queries['query_update'];
				}

				if(!empty($col_queries['query_update'])){

					$update_queries[] = $col_queries['query_update'];
				}
					
				$update_ids = array_merge($row_queries['update_ids'], $col_queries['update_ids']);

				if(count($update_queries) > 0){

					$query_update = '
						update 
							#__survey_answers 
						set 
							'.implode(',', $update_queries).' 
						where 
							id in ('.implode(',', $update_ids).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ()) {
							
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10011<br>query: ' . $query_update . '<br><br>' );
							
						return false;
							
					}
				}

				if(!empty($row_queries['query_insert'])){

					$insert_queries[] = $row_queries['query_insert'];
				}

				if(!empty($col_queries['query_insert'])){

					$insert_queries[] = $col_queries['query_insert'];
				}

				if(count($insert_queries) > 0){

					$query_insert = '
						insert into 
							#__survey_answers (survey_id, question_id, answer_type, answer_label, sort_order, image) 
						values 
							'.substr($row_queries['query_insert'].$col_queries['query_insert'], 0, - 1);

					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ()) {

						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10012<br>query: ' . $query_insert . '<br><br>' );

						return false;

					}
				}
				return $qid;
			case S_GRID_CHECKBOX : 

				$grid_rows = $app->input->post->getArray(array('answers'=>'array'));
				$grid_columns = $app->input->post->getArray(array('columns'=>'array'));
				$grid_rows = $grid_rows['answers'];
				$grid_columns = $grid_columns['columns'];

				if ((count ( $grid_rows ) <= 0) || (count ( $grid_columns ) <= 0)) {

					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10010' );

					return false;
				}

				$update_queries = array();
				$insert_queries = array();
				$update_columns = Array('answer_label' => '`answer_label` = CASE ', 'sort_order' => '`sort_order` = CASE ', 'image' => '`image` = CASE ');

				$row_queries = $this->_get_answer_update_queries($update_columns, $grid_rows, $survey_id.','.$qid.','.$this->_db->quote ( 'x' ));
				$col_queries = $this->_get_answer_update_queries($update_columns, $grid_columns, $survey_id.','.$qid.','.$this->_db->quote ( 'y' ));

				$existing = array();
				$rows = array_merge($grid_rows, $grid_columns);

				foreach ($rows as $row){
					$row = trim($row);

					if (!empty($row) && strpos($row, '_') !== false) {
							
						$tokens = explode('_', $row, 4);
							
						if(count($tokens) == 4){

							$answer_id = (int) $tokens[0];

							if($answer_id > 0){

								$existing[] = $answer_id;
							}
						}
					}
				}

				if(!empty($existing)){

					$query = 'delete from #__survey_answers where survey_id = '.$survey_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ()) {

						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10013<br>query: ' . $query . '<br><br>' );

						return false;

					}
				}

				if(!empty($row_queries['query_update'])){

					$update_queries[] = $row_queries['query_update'];
				}

				if(!empty($col_queries['query_update'])){

					$update_queries[] = $col_queries['query_update'];
				}
					
				$update_ids = array_merge($row_queries['update_ids'], $col_queries['update_ids']);

				if(count($update_queries) > 0){

					$query_update = '
						update 
							#__survey_answers 
						set 
							'.implode(',', $update_queries).' 
						where 
							id in ('.implode(',', $update_ids).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ()) {
							
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10011<br>query: ' . $query_update . '<br><br>' );
							
						return false;
							
					}
				}

				if(!empty($row_queries['query_insert'])){

					$insert_queries[] = $row_queries['query_insert'];
				}

				if(!empty($col_queries['query_insert'])){

					$insert_queries[] = $col_queries['query_insert'];
				}

				if(count($insert_queries) > 0){

					$query_insert = '
						insert into 
							#__survey_answers (survey_id, question_id, answer_type, answer_label, sort_order, image) 
						values 
							'.substr($row_queries['query_insert'].$col_queries['query_insert'], 0, - 1);

					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ()) {

						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10012<br>query: ' . $query_insert . '<br><br>' );

						return false;

					}
				}
				return $qid;

			case S_FREE_TEXT_SINGLE_LINE : return $qid;
			case S_FREE_TEXT_MULTILINE : return $qid;
			case S_FREE_TEXT_PASSWORD : return $qid;
			case S_FREE_TEXT_RICH_TEXT : return $qid;
			case S_SPECIAL_NAME: return $qid;
			case S_SPECIAL_EMAIL: return $qid;
			case S_SPECIAL_CALENDAR: return $qid;
			case S_SPECIAL_ADDRESS:

				$query = 'insert into #__survey_answers (survey_id, question_id, answer_type) values ' . '(' . $survey_id . ',' . $qid . ',' . $this->_db->quote ( 'text' ) . ')';
				$this->_db->setQuery ( $query );

				if (! $this->_db->query ()) {

					$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10014<br>query: ' . $query . '<br><br>' );

					return false;

				} else {

					return $qid;
				}

				break;
		}		
	}
	
	function _get_answer_update_queries($update_columns, $values, $fields){
		
		$query_insert = '';
		$query_update = '';
		
		$update_ids = array();
		
		foreach ( $values as $choice ) {
				
			$choice = trim($choice);
				
			if (!empty($choice) && strpos($choice, '_') !== false) {
		
				$tokens = explode('_', $choice, 4);

				if(count($tokens) == 4){
						
					$answer_id = (int) $tokens[0];
					$sort_order = (int) $tokens[1];
					$survey_id = (int) $tokens[2];
					$image = (empty($tokens[3]) ? 'null' : $this->_db->quote($tokens[3]));

					if($answer_id > 0){
		
						$update_columns['answer_label'] .= "when `id`='".$answer_id. "' THEN " . $this->_db->quote($tokens[2]) . " ";
						$update_columns['sort_order'] .= "when `id`='".$answer_id. "' THEN '" . $sort_order . "' ";
						$update_columns['image'] .= "when `id`='".$answer_id. "' THEN " . $image . " ";
						
						$update_ids[] = $answer_id;
					}else{
		
						$query_insert = $query_insert . '('.$fields.','.$this->_db->quote($tokens[2]) .','.$sort_order.','.$image.'),';
					}
				}
			}
		}

		foreach($update_columns as $column_name => $query_part){

			$update_columns[$column_name] .= " ELSE `$column_name` END ";
		}

		if(count($update_ids) > 0){

			$query_update = implode(', ', $update_columns);
		}

		return array('query_insert'=>$query_insert, 'query_update'=>$query_update, 'update_ids'=>$update_ids);
	}
	
	function delete_question($sid, $pid, $qid){
		$query = 'delete from #__survey_rules where question_id='.$qid;
		$this->_db->setQuery($query);
		if($this->_db->query()){
			$query = 'delete from #__survey_answers where survey_id='.$sid.' and question_id='.$qid;
			$this->_db->setQuery($query);
			if($this->_db->query()){
				$query = 'delete from #__survey_questions where survey_id='.$sid.' and id='.$qid;
				$this->_db->setQuery($query);
				if($this->_db->query()){
					$query = 'select id from #__survey_questions where survey_id='.$sid.' and page_number='.$pid.' order by sort_order asc';
					$this->_db->setQuery($query);
					$questions = $this->_db->loadColumn();
					$order = 1;
					foreach ($questions as $question){
						$query = 'update #__survey_questions set sort_order='.$order.' where id='.$question;
						$this->_db->setQuery($query);
						$this->_db->query();
						$order++;
					}
					return true;
				}
			}
		}
		$this->setError('DB Error: '.$this->_db->getErrorMsg());
		return false;
	}
	function update_ordering($id, $pid, $ordering){
		if(!$this->authorize_survey($id)){
			$this->setError( '<br>'.$this->_db->getErrorMsg () . '<br><br> Error code: 10051<br>query: ' . $query . '<br><br>' );
			return false;
		}

		$query = 'update #__survey_questions set sort_order = case id ';
		$updates = array();

		foreach($ordering as $order){

			$tokens = explode('_', trim($order));

			if(count($tokens) == 2){

				$updates[] = sprintf('when %d then %d', $tokens[1], $tokens[0]);
			}
		}

		if(count($updates) > 0){
			$query = $query.implode(' ', $updates).' end where survey_id='.$id.' ';
			$this->_db->setQuery($query);
			if($this->_db->query()){
				return true;
			}
		} else {
			$this->setError('No updates.');
		}
		$this->setError('<br>DB Error: '.$this->_db->getErrorMsg().'<br>Ordering: '.print_r($ordering, true).'<br>Updates: '.print_r($updates, true));
		return false;
	}

	function move_question($survey_id, $qid, $pid) {
		if(!$this->authorize_survey($survey_id)){
			$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10030<br>query: ' . $query . '<br><br>' );
			return false;
		}
		return true;		
		$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10031<br>query: ' . $query . '<br><br>' );
		return false;
	}
	function get_conditional_rules($survey_id, $page_id=null, $question_id=null, $rulesonly=false){
		$wheres = array();
		$wheres[] = 'survey_id='.$survey_id;
		if(!empty($page_id)){
			$wheres[] = 'question_id in (select id from #__survey_questions where page_number = '.$page_id.')';
		}
		if(!empty($question_id)){
			$wheres[] = 'question_id='.$question_id;
		}
		$where =  ' where '.implode(' and ', $wheres);
		$query = 'select id, rulecontent, question_id from #__survey_rules'.$where.' order by id';
		$this->_db->setQuery($query);
		$rawrules = $this->_db->loadObjectList();
		$rules = array();
		if(!empty($rawrules)){
			foreach ($rawrules as $rawrule){
				$rule = json_decode($rawrule->rulecontent);
				$rule = (object)array_merge((array)$rawrule, (array)$rule);
				$rules[] = $rule;
			}
		}

		if(($rulesonly == false) && !empty($rules)){
			$wheres = array();
			foreach($rules as $rule){
				$wheres[] = $rule->answer_id;
				if(!empty($rule->column_id)){
					$wheres[] = $rule->column_id;
				}
			}

			$answers = array();
			if(!empty($wheres)){
				$query = 'select id, answer_label from #__survey_answers where id in ('.implode(',', $wheres).')';
				$this->_db->setQuery($query);
				$answers = $this->_db->loadAssocList('id');
			}

			foreach($rules as &$rule){
				switch ($rule->name){
					case 'answered':
						if($rule->page > 0){
							$rule->rule =  JText::sprintf('TXT_IF_ANSWERED_SKIP_TO_PAGE', $rule->page);
						}else if($rule->finalize == 1){
							$rule->rule =  JText::_('TXT_IF_ANSWERED_FINALIZE_SURVEY');
						}
						break;
					case 'unanswered':
						if($rule->page > 0){
							$rule->rule =  JText::sprintf('TXT_IF_NOT_ANSWERED_SKIP_TO_PAGE', $rule->page);
						}else if($rule->finalize == 1){
							$rule->rule =  JText::_('TXT_IF_NOT_ANSWERED_FINALIZE_SURVEY');
						}
						break;
					case 'selected':
						$answer = !empty($answers[$rule->answer_id]) ? $answers[$rule->answer_id]['answer_label'] : 'NA';
						$column = !empty($rule->column_id) ? $answers[$rule->column_id]['answer_label'] : null;
						if($rule->page > 0){
							if(empty($column)){
								$rule->rule =  JText::sprintf('TXT_IF_SELECTED_ANSWER_SKIP_TO_PAGE', $answer, $rule->page);
							} else {
								$rule->rule =  JText::sprintf('TXT_IF_SELECTED_ANSWER_COLUMN_SKIP_TO_PAGE', $answer, $column, $rule->page);
							}
						}else if($rule->finalize == 1){
							if(empty($column)){
								$rule->rule =  JText::sprintf('TXT_IF_SELECTED_ANSWER_FINALIZE_SURVEY', $answer, $rule->page);
							} else {
								$rule->rule =  JText::sprintf('TXT_IF_SELECTED_ANSWER_COLUMN_FINALIZE_SURVEY', $answer, $column, $rule->page);
							}
						}

						break;

					case 'unselected':
						$answer = !empty($answers[$rule->answer_id]) ? $answers[$rule->answer_id]['answer_label'] : 'NA';
						$column = !empty($rule->column_id) ? $answers[$rule->column_id]['answer_label'] : null;
						if($rule->page > 0){
							if(empty($column)){
								$rule->rule =  JText::sprintf('TXT_IF_NOT_SELECTED_ANSWER_SKIP_TO_PAGE', $answer, $rule->page);
							} else {
								$rule->rule =  JText::sprintf('TXT_IF_NOT_SELECTED_ANSWER_COLUMN_SKIP_TO_PAGE', $answer, $column, $rule->page);
							}
						}else if($rule->finalize == 1){
							if(empty($column)){
								$rule->rule =  JText::sprintf('TXT_IF_NOT_SELECTED_ANSWER_FINALIZE_SURVEY', $answer, $rule->page);
							} else {
								$rule->rule =  JText::sprintf('TXT_IF_NOT_SELECTED_ANSWER_COLUMN_FINALIZE_SURVEY', $answer, $column, $rule->page);
							}
						}
						break;
				}
			}
		}

		return $rules;
	}
	function save_conditional_rule($survey_id, $question_id, $rule){
		$query = 'insert into #__survey_rules(survey_id, question_id, rulecontent) values ('.$survey_id.','.$question_id.','.$this->_db->quote($rule).')';
		$this->_db->setQuery($query);
		if($this->_db->query()){
			return $this->_db->insertid();
		}else{
			$this->setError ( 'Error: 10804 - '.JText::_('MSG_ERROR_PROCESSING') );
			return false;
		}
	}
	function remove_conditional_rule($survey_id, $question_id, $rule_id){
		$query = 'delete from #__survey_rules where survey_id='.$survey_id.' and question_id='.$question_id.' and id='.$rule_id;
		$this->_db->setQuery($query);
		if($this->_db->query()){
			return true;
		}else{
			$this->setError ( 'Error: 10806 - '.JText::_('MSG_ERROR_PROCESSING') );
			return false;
		}
	}
	public function rename_page($survey_id, $pid, $title){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__survey_pages')->set('title = '.$db->q($title))->where('id = '.$pid.' and sid = '.$survey_id);
		$db->setQuery($query);
		try{
			$db->execute();
			return true;
		} catch (Exception $e){
			return false;
		}
		return false;
	}
	public function rename_page2($pid, $uniq_key, $title){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__survey_pages')->set('title = '.$db->q($title))->where('id = '.$pid.' and uniq_key = '.$db->q($uniq_key));
		$db->setQuery($query);
		try{
			$db->execute();
			return true;
		} catch (Exception $e){
			return false;
		}
		return false;
	}
	public function reorder_pages($survey_id, $ordering){
		if(empty($ordering)) return true;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->update('#__survey_pages');
		$sql_updates = '';
		foreach ($ordering as $order=>$pid){
			$sql_updates = $sql_updates . ' when '.$pid.' then '.($order + 1);
		}
		$query->set('sort_order = case id '.$sql_updates.' end')->where('sid = '.$survey_id);
		$db->setQuery($query);
		try{
			$db->execute();
		} catch (Exception $e){
			$this->setError ( $db->getErrorMsg () . '<br><br> Error code: 10033<br>query: ' . $query->dump() . '<br><br>' );
			return false;
		}
		return true;
	}
	public function reorder_pages_2($ordering){
		if(empty($ordering)) return true;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->update('#__survey_pages');
		$sql_updates = '';
		foreach ($ordering as $order=>$pid){
			$sql_updates = $sql_updates . ' when '.$pid.' then '.($order + 1);
		}
		$query->set('sort_order = case id '.$sql_updates.' end')->where('1=1');
		$db->setQuery($query);
		try{
			$db->execute();
		} catch (Exception $e){
			$this->setError ( $db->getErrorMsg () . '<br><br> Error code: 10033<br>query: ' . $query->dump() . '<br><br>' );
			return false;
		}
		return true;
	}
	function finalize_survey($id, $status){
		if( $this->authorize_survey($id) ){
			$query = 'update #__survey set published = '.$status.' where id = '.$id;
			$this->_db->setQuery($query);
			if($this->_db->query()){
				return true;
			}
		}
		$this->setError($this->_db->getErrorMsg());
		return false;
	}	
	function create_page($survey_id, $uniq_id='0') {		
		$this->_db = &JFactory::getDBO ();
		$query = 'select max(sort_order)+1  as sort_order from #__survey_pages where sid=' . $survey_id;
		$query .= ($uniq_id != '0' ? ' and uniq_key = \''.$uniq_id.'\'' : '');
		$this->_db->setQuery ( $query );
		$sort_order = $this->_db->loadResult ();
		if (! $sort_order) {
			$sort_order = 1;
		}
		$query = 'insert into #__survey_pages (sid, sort_order, uniq_key) values (' . $survey_id . ',' . $sort_order . ', \''.$uniq_id.'\')';
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
			return false;
		} else {
			return $this->_db->insertid ();
		}
	}	
	function get_max_id_pages($uniq_id) {
		$this->_db = &JFactory::getDBO ();
		$query = "select max(id) as id from #__survey_pages where uniq_key = '".$uniq_id."' ";
		$this->_db->setQuery ( $query );
		$id = $this->_db->loadResult ();
		return $id;
	}
	function remove_page($survey_id, $pid) {
		if(!$this->authorize_survey($survey_id)){
			$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10030<br>query: ' . $query . '<br><br>' );
			return false;
		}
		$query = 'delete from #__survey_answers where question_id in (select id from #__survey_questions where page_number = '.$pid.')';
		$this->_db->setQuery($query);
		if($this->_db->query()){
			$query = 'delete from #__survey_questions where page_number = '.$pid;
			$this->_db->setQuery($query);
			if($this->_db->query()){
				$query = 'delete from #__survey_pages where sid = ' . $survey_id . ' and id=' . $pid;
				$this->_db->setQuery ( $query );
				if ($this->_db->query ()) {
					return true;
				}
			}
		}
		$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10031<br>query: ' . $query . '<br><br>' );
		return false;
	}
	function remove_page_2($pid, $uniq_key) {
		$success = false;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query = 'delete from #__survey_answers where question_id in (select id from #__survey_questions
						where uniq_key = '.$db->q($uniq_key).' and question_id in (select question_id from #__survey_question_giftcode 
								where uniq_key = '.$db->q($uniq_key).' and page_number = '.$pid.'))';
		$this->_db->setQuery($query);
		if($this->_db->query()){
			$query = 'delete from #__survey_questions
						where uniq_key = '.$db->q($uniq_key).' and question_id in (select question_id from #__survey_question_giftcode 
								where uniq_key = '.$db->q($uniq_key).' and page_number = '.$pid.')';
			$this->_db->setQuery($query);
			if($this->_db->query()){
				$query = 'delete from #__survey_question_giftcode
								where uniq_key = '.$db->q($uniq_key).' and page_number = '.$pid;
				$this->_db->setQuery($query);
				if($this->_db->query()){
					$query = 'delete from #__survey_pages where id = '.$pid.' and uniq_key = '.$db->q($uniq_key);
					$this->_db->setQuery($query);
					if($this->_db->query()){
						$success = true;
					}
				}
			}
		}
		if($success) {
			$order = array();
			$query = 'select id, sort_order from #__survey_pages where uniq_key = '.$db->q($uniq_key);
			$this->_db->setQuery ( $query );
			$ds = $this->_db->loadObjectList();
			if (!empty($ds)) {
				$i = 0;
				foreach ($ds as $d) {
					$order[$i] = $d->id;
					$i++;
				}
				$this->reorder_pages_2($order);
			}
		}
	}
	function insert_survey_question_giftcode ($package_id, $survey_id,
	$page_number, $question_id, $uniq_key)
	{
		$query = "
			insert into #__survey_question_giftcode
			(package_id, survey_id, page_number, question_id, uniq_key, complete_giftcode, incomplete_giftcode)
			values (
				'".$package_id."', '".$survey_id."', '".$page_number."', '".$question_id."', '".$uniq_key."', 'NEW', 'NEW'	
			)			
		";
		$this->_db->setQuery($query);
		$this->_db->query ();
	}
	
	function get_survey_question_giftcode($package_id, $survey_id, $page_number, $question_id, $uniq_key)
	{
		$query = "SELECT a.* FROM #__survey_question_giftcode a
					INNER JOIN #__survey_pages b ON b.id = a.page_number AND b.id = '".$page_number."' AND b.uniq_key = '".$uniq_key."'
					WHERE a.package_id = '".$package_id."' AND a.uniq_key = '".$uniq_key."'
					AND a.question_id = '".$question_id."'";
		$this->_db->setQuery ( $query );
		$data = $this->_db->loadObjectList();
		return $data;
	}	
	function get_survey_question_giftcode2($package_id, $survey_id, $page_number, $uniq_key)
	{
		$query = "SELECT a.* FROM #__survey_question_giftcode a
					INNER JOIN #__survey_pages b ON b.id = a.page_number AND b.id = '".$page_number."' AND b.uniq_key = '".$uniq_key."'
					WHERE a.package_id = '".$package_id."' AND a.uniq_key = '".$uniq_key."' order by a.question_id desc";								
		$this->_db->setQuery ( $query );
		$datas = $this->_db->loadObjectList();
		$dats = array();
		foreach ($datas as $data) {
			$questions = $this->get_question_details($data->uniq_key, $data->question_id, $data->page_number, $survey_id);
			$data->questions = $questions;
			$dats[] = $data;
		}
		return $dats;
	}
	function update_question_giftcode($package_id, $page_number, $question_id, $uniq_key,
						$completeGiftCode, $completeGiftCodeQuantity, $completeGiftCodeCostResponse,
						$incompleteGiftCode, $incompleteGiftCodeQuantity, $inCompleteGiftCodeCostResponse)
	{
		$query = "
				update #__survey_question_giftcode 
				set complete_giftcode = '".$completeGiftCode."', 
				complete_giftcode_quantity = '".$completeGiftCodeQuantity."',
				complete_giftcode_cost_response = '".$completeGiftCodeCostResponse."',
				incomplete_giftcode = '".$incompleteGiftCode."', 
				incomplete_giftcode_quantity = '".$incompleteGiftCodeQuantity."',
				incomplete_giftcode_cost_response = '".$inCompleteGiftCodeCostResponse."' 
				where package_id = '".$package_id."' and page_number = '".$page_number."'
				and question_id = '".$question_id."' and uniq_key = '".$uniq_key."'
			";
		$this->_db->setQuery($query);
		if($this->_db->query()){
			JFactory::getApplication()->enqueueMessage($this->_db->getErrorMsg());
			return true;
		}
		return false;
	}

	function delete_question_giftcode($package_id, $page_number, $question_id, $uniq_key)
	{
		$query = "
				select * from #__survey_question_giftcode where package_id = '".$package_id."' and page_number = '".$page_number."'
				and uniq_key = '".$uniq_key."'
			";
		$this->_db->setQuery ( $query );
		$questions = $this->_db->loadObjectList();
			if(!empty($questions) && count($questions) > 1) {
			$query = "
					delete from #__survey_question_giftcode where package_id = '".$package_id."' and page_number = '".$page_number."'
					and question_id = '".$question_id."' and uniq_key = '".$uniq_key."' 
				";
			$this->_db->setQuery($query);
			if($this->_db->query()){
				JFactory::getApplication()->enqueueMessage($this->_db->getErrorMsg());
				return true;
			} else {
				return false;
			}	
		} else {
			return false;
		}
		
				
	}	
	function get_pages($package_id, $uniq_key)
	{
		$query = "
				SELECT id, sort_order, title FROM #__survey_pages
				WHERE uniq_key = '".$uniq_key."' ORDER BY sort_order DESC
			";
		$this->_db->setQuery ( $query );
		$data = $this->_db->loadObjectList();
		return $data;
	}

	function copy_survey($sid){    
		$uniq_id = md5(uniqid(rand(), true));	
    	$user = JFactory::getUser();
    	$key = CJFunctions::generate_random_key();
    	$createdate = JFactory::getDate()->toSql();
    	$query = '
    		insert into 
    			#__survey(
    				title, alias, catid, introtext, endtext, created_by, created, publish_up, publish_down, responses, private_survey, max_responses,
    				anonymous, custom_header, public_permissions, published, survey_key, redirect_url, display_template, skip_intro,
    				backward_navigation, display_notice, display_progress, notification, package_id
    			)
    		(
    			select
    				concat(title, \'_Copy\'), concat(alias, \'_copy\'), catid, introtext, endtext, '.$user->id.','.$this->_db->quote($createdate).', publish_up, 
    				publish_down, 0, private_survey, max_responses, anonymous, custom_header, public_permissions, published, '.$this->_db->quote($key).', redirect_url, 
    				display_template, skip_intro, backward_navigation, display_notice, display_progress, notification, package_id
    			from 
    				#__survey
    			where
    				id = '.$sid.'
    		)';    	
    	$this->_db->setQuery($query);    	 
    	if($this->_db->query()){    		
    		$newid = $this->_db->insertid();    		
    		if($newid > 0){    			
    			$query = 'select id, sort_order, title from #__survey_pages where sid = '.$sid.' and uniq_key != \'0\' order by sort_order';
    			$this->_db->setQuery($query);
    			$pages = $this->_db->loadObjectList();
    			
    			$query = '
    				select
    					id, title, description, question_type, page_number, sort_order, mandatory, custom_choice, orientation, question_id
    				from
    					#__survey_questions
    				where
    					survey_id = '.$sid;    			
    			$this->_db->setQuery($query);
    			$questions = $this->_db->loadObjectList();
    			
    			$query = '
    					select id, package_id, survey_id, page_number, question_id, complete_giftcode, complete_giftcode_quantity, complete_giftcode_cost_response, 
							incomplete_giftcode, incomplete_giftcode_quantity, incomplete_giftcode_cost_response
						from #__survey_question_giftcode
						where 
						page_number > 0
						and survey_id = '.$sid;
    			$this->_db->setQuery($query);
				$giftcodes = $this->_db->loadObjectList();	

    			if(empty($pages) || empty($questions)){
    				return false;
    			}    			
    			foreach ($pages as $page){    				
    				$query = 'insert into #__survey_pages (sid, sort_order, title, uniq_key) values ('.$newid.','.$page->sort_order.', \''.$page->title.'\', \''.$uniq_id.'\')';
    				$this->_db->setQuery($query);    				
    				if($this->_db->query()){    					
    					$newpage = $this->_db->insertid();    					
    					if($newpage <= 0){    						
    						return false;
    					}
    					
    					foreach ($giftcodes as $giftcode) {
							if($giftcode->page_number == $page->id) {
								$query = '
										insert into #__survey_question_giftcode(package_id, survey_id, page_number, question_id, complete_giftcode,
										complete_giftcode_quantity, complete_giftcode_cost_response, incomplete_giftcode,
										incomplete_giftcode_quantity, incomplete_giftcode_cost_response, uniq_key)
										values ('.
										$giftcode->package_id	.','.
										$newid .','.
										$newpage .','.
										$giftcode->question_id .','.
										$this->_db->quote($giftcode->complete_giftcode) .','.
										(empty($giftcode->complete_giftcode_quantity) ? 'NULL,' : $giftcode->complete_giftcode_quantity .',').
										(empty($giftcode->complete_giftcode_cost_response) ? 'NULL,' : $giftcode->complete_giftcode_cost_response .',').
										$this->_db->quote($giftcode->incomplete_giftcode) .','.
									 	(empty($giftcode->incomplete_giftcode_quantity) ? 'NULL,' : $giftcode->incomplete_giftcode_quantity .',').
									 	(empty($giftcode->incomplete_giftcode_cost_response) ? 'NULL,' : $giftcode->incomplete_giftcode_cost_response .',').
									 	$this->_db->quote($uniq_id)
									 	.')';
								$this->_db->setQuery($query);
								$this->_db->query();
							}
						}
    					
    					foreach ($questions as $question){    						
    						if($question->page_number == $page->id){    							
    							$query = '
    								insert into 
    									#__survey_questions(
    										title, description, survey_id, question_type, page_number, responses, sort_order, mandatory, created_by, 
    										custom_choice, orientation, question_id, uniq_key)
    								values
    									('.
    										$this->_db->quote($question->title).','.
    										$this->_db->quote($question->description).','.
    										$newid.','.
    										$question->question_type.','.
    										$newpage.','.
    										'0,'.
    										$question->sort_order.','.
    										$question->mandatory.','.
    										$user->id.','.
    										$question->custom_choice.','.
    										$this->_db->quote($question->orientation).','.
    										$question->question_id .',\''.
											$uniq_id.'\'
    									)';
    							
    							$this->_db->setQuery($query);    							
    							if($this->_db->query()){    								
    								$newqnid = $this->_db->insertid();    								
    								if($newqnid <= 0){    									
    									return false;
    								}
    								
    								$query = '
    									insert into
    										#__survey_answers(survey_id, question_id, answer_type, answer_label, sort_order, image)
    									(
    										select
    											'.$newid.', '.$newqnid.', answer_type, answer_label, sort_order, image
    										from
    											#__survey_answers
    										where
    											question_id = '.$question->id.'
    									)';
    								
    								$this->_db->setQuery($query);
    								
    								if(!$this->_db->query()){
    									
    									return false;
    								}
    								
    								$query = '
    									insert into
    										#__survey_rules(survey_id, question_id, rulecontent)
    									( 
    										select
    											'.$newid.','.$newqnid.', rulecontent
    										from 
    											#__survey_rules
    										where
    											survey_id = '.$sid.' and question_id = '.$question->id.'
    									)';

    								$this->_db->setQuery($query);
    								
    								if(!$this->_db->query()){
    									
    									return false;
    								}
    							}
    						}
    					}
    				}
    			}
    			
    			return true;
    		}
    	}
    	
    	$this->setError($this->_db->getErrorMsg());
    	
    	return false;
    }
    
	function get_survey_statistics($id){
		
		$stats = new stdClass();
		$params = JComponentHelper::getParams(S_APP_NAME);
		
		$query = 'select count(*) from #__survey_responses where survey_id = '.$id;
		$this->_db->setQuery($query);
		$stats->total_responses = $this->_db->loadResult();
		
		$query = 'select count(distinct(browser)) from #__survey_responses where survey_id = '.$id;
		$this->_db->setQuery($query);
		$stats->browsers = $this->_db->loadResult();
		
		$query = 'select count(distinct(os)) from #__survey_responses where survey_id = '.$id;
		$this->_db->setQuery($query);
		$stats->oses = $this->_db->loadResult();

		$query = '
				select 
					count(*) as country_total, a.country,
					c.country_name 
				from 
					#__survey_responses a 
				left join
					#__survey_countries c on a.country = c.country_code
				where 
					survey_id = '.$id.' 
				group by 
					country
				order by
					country_total desc';
		
		$this->_db->setQuery($query);
		$stats->countries = $this->_db->loadObjectList();
		
		$query = '
				select
					r.id, r.created_by, r.created, r.completed, c.country_name, r.browser, r.city, r.os,
				 	u.'.$params->get('user_display_name', 'name').' as username
				from 
					#__survey_responses r
				 left join
				 	#__survey_countries c on r.country = c.country_code
				left join
				 	#__users u on r.created_by = u.id
				 where
				 	r.survey_id = '.$id.' and r.completed > r.created
				 order by
				 	r.created desc';
		
		$this->_db->setQuery($query, 0, 10);
		$stats->recent = $this->_db->loadObjectList();
		$stats->recent = !empty($stats->recent) ? $stats->recent : array();
		
		$query = '
			select
				count(*) as responses, date_format(created, '.$this->_db->quote('%d/%m').') as created_on
			from
				#__survey_responses
			where
				survey_id='.$id.'
			group by
				created_on
			order by
				created asc';
		
		$this->_db->setQuery($query);
		$stats->daily = $this->_db->loadObjectList();
		$stats->daily = !empty($stats->daily) ? $stats->daily : array();
		
		return $stats;  
	}
	
	public function get_consolidated_report($id) {
	
		$user = JFactory::getUser();

		$survey = $this->get_survey_details($id, false, true);
		$questions = $this->get_questions($id, 0, false); // don't get anwsers attached, we calculate them here.
		
		if (!empty($questions)) {
	
			$query = '
				select
					question_id, answer_id, column_id, count(*) as votes
				from
					#__survey_response_details
				where
					question_id in (select id from #__survey_questions where survey_id='.$id.')
				group by
					answer_id, column_id
				order by
					question_id';
				
			$this->_db->setQuery($query);
			$responses =  $this->_db->loadObjectList();
			
			if(empty($responses)) {
	
				$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10251<br>query: ' . $query . '<br><br>' );
				return false;
			}
	
			$query = '
				select
					id, question_id, answer_type, answer_label
				from
					#__survey_answers
				where
					survey_id = '.$id.' and question_id in (select question_id from #__survey_questions where survey_id='.$id.' )
				order by
					question_id asc, sort_order asc';
				
			$this->_db->setQuery ( $query );
			$answers = $this->_db->loadObjectList ();
			
			if ($answers && (count ( $answers ) > 0)) {
	
				foreach ( $answers as $answer ) {
						
					$total_votes = 0;
					$answer->responses = array();

					foreach ($responses as $response) {
	
						if($response->answer_id == $answer->id) {

							$answer->responses[] = $response;
							$total_votes += $response->votes;
						}
					}
						
					if($answer->answer_type == 'y'){
							
						$questions[$answer->question_id]->columns[] = $answer;
					} else {
	
						$questions[$answer->question_id]->answers[] = $answer;
					}

					$questions[$answer->question_id]->total_votes = !empty($questions[$answer->question_id]->total_votes) ? $questions[$answer->question_id]->total_votes + $total_votes : $total_votes;
				}
	
				$survey->questions = $questions;
	
				return $survey;
			} else {
	
				$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10252<br>query: ' . $query . '<br><br>' );
				return false;
			}
		} else {
				
			$error = $this->_db->getErrorMsg ();
				
			if (! empty ( $error )) {
	
				$this->setError ( $error . '<br><br> Error code: 10253<br>query: ' . $query . '<br><br>' );
			}
				
			return false;
		}
	}
	
	function get_responses($survey_id = 0, $userid = 0, $limitstart = 'start'){
	
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams(S_APP_NAME);
		 
		$filter_order = $app->getUserStateFromRequest( S_APP_NAME.'.responses.filter_order', 'filter_order', 'r.created', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( S_APP_NAME.'.responses.filter_order_dir', 'filter_order_Dir', 'desc', 'word' );
		$state = $app->getUserStateFromRequest( S_APP_NAME.'.responses.state', 'state', 3, 'int' );
		$search = $app->getUserStateFromRequest( S_APP_NAME.'.responses.search', 'search', '', 'string' );
		$limitstart = $app->getUserStateFromRequest( S_APP_NAME.'.responses.limitstart', $limitstart, 0, 'int' );
		$limit = $app->getUserStateFromRequest(S_APP_NAME.'.responses.limit', 'limit', 50, 'int');
		$limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0;
		$catid = $app->input->post->getInt('catid', 0);
		 
		$wheres = array();
		$return = array();
		 
		if($survey_id){
	
			$wheres[] = 'r.survey_id = '.$survey_id;
		}
		 
		if($catid){
			 
			$wheres[] = 'a.catid = '.$catid;
		}
		 
		if($userid){
			 
			$wheres[] = 'r.created_by = '.$userid;
		}
		 
		if($state == 0){
			 
			$wheres[] = 'r.completed = '.$this->_db->quote($this->_db->getNullDate());
		} else if($state == 1){
			
			$wheres[] = 'r.completed > r.created ';
		}
		 
		if(!empty($search)){
			 
			$wheres[] = 'u.name like \'%'.$this->_db->escape($search).'%\' or u.username like \'%'.$this->_db->escape($search).'%\' or r.survey_key like \''.$this->_db->escape($search).'%\'';
		}
		 
		$where = ((count($wheres) > 0) ? ' where ('.implode(' ) and ( ', $wheres).')' : '');
		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;
	
		$result = new stdClass();
	
		$query = '
			select
				a.id, a.title, a.alias, a.introtext, a.catid, a.created, a.responses, a.public_permissions, r.ip_address, a.display_template, a.published,
				c.title as category_name, c.alias as category_alias,
				u.email, case when r.created_by > 0 then u.'.$params->get('user_display_name', 'name').' else \''.JText::_('LBL_GUEST').'\' end as username,
				r.created_by, r.id as response_id, r.created as responded_on, r.completed, r.completed >= r.created as finished, r.survey_key,
				cr.country_name
			from
				#__survey_responses r
			left join
				#__survey a on a.id = r.survey_id
			left join
				#__survey_categories c ON a.catid = c.id
			left join
				#__survey_countries cr on r.country = cr.country_code
			left join
				#__users u ON r.created_by = u.id'.
			$where .
			$order;
	
		$this->_db->setQuery($query, $limitstart, $limit);
		$result->rows = $this->_db->loadObjectList();

		$query = '
				select
					count(*)
				from
					#__survey_responses r
				left join
					#__survey a on a.id = r.survey_id
				left join
					#__users u on a.created_by = u.id
				'.$where;
	
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();
	
		jimport('joomla.html.pagination');
		$result->pagination = new JPagination($total, $limitstart, $limit);
	
		$result->lists = array(
				'limitstart'=>$limitstart,
				'limit'=>$limit,
				'order'=>$filter_order,
				'order_dir'=>$filter_order_dir,
				'catid'=>$catid,
				'search'=>$search,
				'uid'=>$userid,
				'state'=>$state);
	
		return $result;
	}
	
	function get_location_report($id, $limitstart = 'start'){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams(S_APP_NAME);
		 
		$filter_order = $app->getUserStateFromRequest( S_APP_NAME.'.locations.filter_order', 'filter_order', 'a.country', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( S_APP_NAME.'.locations.filter_order_dir', 'filter_order_Dir', 'asc', 'word' );
		$limitstart = $app->getUserStateFromRequest( S_APP_NAME.'.locations.limitstart', $limitstart, 0, 'int' );
		$limit = $app->getUserStateFromRequest(S_APP_NAME.'.locations.limit', 'limit', 50, 'int');
		$limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0;
		 
		$search = $app->input->post->getString('search', '');
		 
		$wheres = array();
		$return = array();
		$wheres[] = 'a.survey_id = '.$id;
		 
		if(!empty($search)){
			 
			$wheres[] = 'c.country_name like \'%'.$this->_db->escape($search).'%\' or a.city like \'%'.$this->_db->escape($search).'%\'';
		}
		 
		$where = implode(' ) and ( ', $wheres);
		$order = $filter_order . ' ' . $filter_order_dir;
		
		$result = new stdClass();

		$query = '
			select
				count(*) as responses, c.country_name, a.country, a.city
			from
				#__survey_responses a
			left join
				#__survey_countries c on a.country = c.country_code
			where
				'.$where.'
			group by
				a.country, a.city
			order by
				'.$order;
		
		$this->_db->setQuery($query, $limitstart, $limit);
		$result->rows = $this->_db->loadObjectList();

		$query = '
				select
					count(*)
				from 
					(
					select
						count(*)
					from
						#__survey_responses a
					left join
						#__survey_countries c on a.country = c.country_code
					where
						'.$where.'
					group by
						a.country, a.city
					) as resulttable';

		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		jimport('joomla.html.pagination');
		$result->pagination = new JPagination($total, $limitstart, $limit);
		
		$result->lists = array(
				'limitstart'=>$limitstart,
				'limit'=>$limit,
				'order'=>$filter_order,
				'order_dir'=>$filter_order_dir,
				'search'=>$search);
		
		return $result;
	}
	
	function get_device_report($id, $limitstart = 'start'){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams(S_APP_NAME);
			
		$filter_order = $app->getUserStateFromRequest( S_APP_NAME.'.devices.filter_order', 'filter_order', 'a.browser', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( S_APP_NAME.'.devices.filter_order_dir', 'filter_order_Dir', 'asc', 'word' );
		$limitstart = $app->getUserStateFromRequest( S_APP_NAME.'.devices.limitstart', $limitstart, 0, 'int' );
		$limit = $app->getUserStateFromRequest(S_APP_NAME.'.devices.limit', 'limit', 50, 'int');
		$limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0;
			
		$search = $app->input->post->getString('search', '');
			
		$wheres = array();
		$return = array();
		$wheres[] = 'a.survey_id = '.$id;
			
		if(!empty($search)){
		
			$wheres[] = 'a.browser like \'%'.$this->_db->escape($search).'%\'';
		}
			
		$where = implode(' ) and ( ', $wheres);
		$order = $filter_order . ' ' . $filter_order_dir;
		
		$result = new stdClass();
		
		$query = '
			select
				count(*) as responses, a.browser
			from
				#__survey_responses a
			where
				'.$where.'
			group by
				a.browser
			order by
				'.$order;
		
		$this->_db->setQuery($query, $limitstart, $limit);
		$result->rows = $this->_db->loadObjectList();

		$query = '
				select
					count(*)
				from
					(
					select
						count(*)
					from
						#__survey_responses a
					where
						'.$where.'
					group by
						a.browser
					) as resulttable';
		
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();
		
		jimport('joomla.html.pagination');
		$result->pagination = new JPagination($total, $limitstart, $limit);
		
		$result->lists = array(
				'limitstart'=>$limitstart,
				'limit'=>$limit,
				'order'=>$filter_order,
				'order_dir'=>$filter_order_dir,
				'search'=>$search);
		
		return $result;
	}
	
	function get_os_report($id, $limitstart = 'start'){
	
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams(S_APP_NAME);
			
		$filter_order = $app->getUserStateFromRequest( S_APP_NAME.'.devices.filter_order', 'filter_order', 'a.os', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( S_APP_NAME.'.devices.filter_order_dir', 'filter_order_Dir', 'asc', 'word' );
		$limitstart = $app->getUserStateFromRequest( S_APP_NAME.'.devices.limitstart', $limitstart, 0, 'int' );
		$limit = $app->getUserStateFromRequest(S_APP_NAME.'.devices.limit', 'limit', 50, 'int');
		$limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0;
			
		$search = $app->input->post->getString('search', '');
			
		$wheres = array();
		$return = array();
		$wheres[] = 'a.survey_id = '.$id;
			
		if(!empty($search)){
	
			$wheres[] = 'a.os like \'%'.$this->_db->escape($search).'%\'';
		}
			
		$where = implode(' ) and ( ', $wheres);
		$order = $filter_order . ' ' . $filter_order_dir;
	
		$result = new stdClass();
	
		$query = '
			select
				count(*) as responses, a.os
			from
				#__survey_responses a
			where
				'.$where.'
			group by
				a.os
			order by
				'.$order;
	
		$this->_db->setQuery($query, $limitstart, $limit);
		$result->rows = $this->_db->loadObjectList();
	
		$query = '
				select
					count(*)
				from
					(
					select
						count(*)
					from
						#__survey_responses a
					where
						'.$where.'
					group by
						a.os
					) as resulttable';
	
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();
	
		jimport('joomla.html.pagination');
		$result->pagination = new JPagination($total, $limitstart, $limit);
	
		$result->lists = array(
				'limitstart'=>$limitstart,
				'limit'=>$limit,
				'order'=>$filter_order,
				'order_dir'=>$filter_order_dir,
				'search'=>$search);
	
		return $result;
	}
	
	function get_response_ids($sid){
    	
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	
    	$query
    		->select('r.id')
    		->from('#__survey_responses as r')
    		->where('r.survey_id='.$sid.' and r.completed > r.created');
    	
    	$db->setQuery($query);
    	$ids = $db->loadColumn();
    	
    	return !empty($ids) ? $ids : array();
    }
    
	function get_reponse_data_for_csv($sid, $cids = array()){
		
		$user = JFactory::getUser();

		$query = 'select title, created_by from #__survey where id='.$sid;
		$this->_db->setQuery($query);
		$survey = $this->_db->loadObject();
		
		if(($survey->created_by != $user->id) && !$user->authorise('core.manage', S_APP_NAME)) {
			
			$this->setError ( 'Error: 10295 - '.JText::_('MSG_ERROR_PROCESSING') );
			return false;
		}
		
		$null_date = $this->_db->getNullDate();
		$cid_csv = !empty($cids) ? implode(',', $cids) : 'select id from #__survey_responses t where t.survey_id='.$sid.' and t.completed > t.created'; 

		$query = '
			select 
				r.response_id, r.question_id, q.title as question, a.answer_label as answer, a.image as answer_image, b.answer_label as answer2, r.free_text
			from 
				#__survey_response_details r
			left join 
				#__survey_questions q on r.question_id=q.id
			left join 
				#__survey_answers a on r.answer_id=a.id
			left join 
				#__survey_answers b on r.column_id=b.id
			where 
				r.response_id in ('.$cid_csv.')
			order by 
				r.response_id, r.question_id';
		
		$this->_db->setQuery($query);
		$entries = $this->_db->loadObjectList();

		$query = 'select q.id, q.title, q.description, q.question_type from #__survey_questions q where q.survey_id='.$sid. ' order by page_number, sort_order';
		$this->_db->setQuery($query);
		$questions = $this->_db->loadObjectList();
		
		$cid_csv = !empty($cids) ? ' and r.id in ('.implode(',', $cids).')' : '';
		
		$query = '
			select 
				r.id, r.survey_key, r.created_by, r.created, r.country, r.city, r.browser, r.os, c.country_name, u.username, u.name, u.email
			from 
				#__survey_responses r
			left join
				#__survey_countries c on r.country = c.country_code 
			left join 
				#__users u on r.created_by=u.id
			where 
				r.survey_id='.$sid.' and r.completed > r.created'.$cid_csv;
		
		$this->_db->setQuery($query);
		$responses = $this->_db->loadObjectList();
		
		$return = new stdClass();
		$return->title = $survey->title;
		$return->entries = $entries;
		$return->questions = $questions;
		$return->responses = $responses;

		return $return;
	}
	
	public function delete_responses($id, $cids){
    	
    	$query = 'delete from #__survey_responses where survey_id = '.$id.' and id in ('.implode(',', $cids).')';
    	$this->_db->setQuery($query);
    	
    	if($this->_db->query()){
    		
    		$query = 'delete from #__survey_response_details where response_id in ('.implode(',', $cids).')';
    		$this->_db->setQuery($query);
    		$this->_db->query();
    		
    		$query = 'update #__survey q set q.responses = (select count(*) from #__survey_responses r where r.survey_id = '.$id.' and r.completed > r.created) where q.id = '.$id;
    		$this->_db->setQuery($query);
    		$this->_db->query();
    		
    		return true;
    	}
    	
    	$this->setError($this->_db->getErrorMsg());
    	
    	return false;
    }
    
	function add_contact_group($group_name){
	
		$user = JFactory::getUser();
	
		$query = 'insert into #__survey_contactgroups (name, created_by) values ('. $this->_db->quote($group_name) . ',' . $user->id . ')';
		$this->_db->setQuery($query);
	
		if($this->_db->query()){
				
			$group = new stdClass();
			$group->id = $this->_db->insertid();
			$group->name = $group_name;
				
			return $group;
		}else{
				
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	}
	
	function delete_contact_group($gid){

		$user = JFactory::getUser();
	
		$query = 'delete from #__survey_contactgroups where id = '.$gid.' and created_by = '.$user->id;
		$this->_db->setQuery($query);
	
		if($this->_db->query()){
	
			$count = $this->_db->getAffectedRows();
				
			if($count > 0){
	
				$query = 'delete from #__survey_contact_group_map where group_id = '.$gid;
				$this->_db->setQuery($query);
	
				if($this->_db->query()){
						
					return true;
				}
			}
		}
	
		return false;
	}
	
	function add_contacts($contacts){
	
		$user = JFactory::getUser();
		$values = array();
		
		foreach ($contacts as $contact){
			
			if(JMailHelper::isEmailAddress($contact['email'])){
			
				$values[] = '(' . $this->_db->quote($contact['email']) . ',' . $this->_db->quote($contact['name']) . ',' . $user->id . ')';
			}
		}

		if(!empty($values)){
			
			$query = 'insert into #__survey_contacts (email, name, created_by) values '.implode(', ', $values).' on duplicate key update name = values(name)';
			$this->_db->setQuery($query);
		
			if($this->_db->query()){

				$count = $this->_db->getAffectedRows();
				return $count;
			}
		} 
		
		return 0;
	}
	
	function delete_contacts($cids){
	
		$user = JFactory::getUser();
		$cid = implode(',', $cids);

		$query = 'delete from #__survey_contacts where id in ('.$cid.') and created_by = '.$user->id;
		$this->_db->setQuery($query);
	
		if($this->_db->query()){
				
			$count = $this->_db->getAffectedRows();
	
			if($count > 0){
	
				$query = 'delete from #__survey_contact_group_map where contact_id in ('.$cid.')';
				$this->_db->setQuery($query);
				$this->_db->query();
	
				$query = 'update #__survey_contactgroups a set a.contacts = (select count(*) from #__survey_contact_group_map m where m.group_id = a.id group by group_id) where created_by = '.$user->id;
				$this->_db->setQuery($query);
				$this->_db->query();
			}
				
			return $count;
		}
	
		$this->setError($this->_db->getErrorMsg());
		return 0;
	}
	
	function assign_contacts($gid, $cids){
	
		$query = 'delete from #__survey_contact_group_map where group_id = '.$gid;
		$this->_db->setQuery($query);
	
		if($this->_db->query()){
				
			$count = 0;
				
			if(count($cids) > 0){
					
				$updates = array();
	
				foreach ($cids as $contact){
						
					$updates[] = '('.$gid.','.$contact.')';
				}
	
				$query = 'insert into #__survey_contact_group_map(group_id, contact_id) values '.implode(',', $updates);
				$this->_db->setQuery($query);
	
				if($this->_db->query()){
						
					$count = $this->_db->getAffectedRows();
				} else {
						
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
				
			$query = 'update #__survey_contactgroups set contacts = '.$count.' where id = '.$gid;
			$this->_db->setQuery($query);
				
			if($this->_db->query()){
	
				return $count;
			}
		}
	
		$this->setError($this->_db->getErrorMsg());
		return false;
	}
	
	function create_survey_keys($sid, $count, $unlimited = false, $skip_user_check = false){		
		$user = JFactory::getUser();		
		$query = 'select title, created_by from #__survey where id='.$sid;
		$this->_db->setQuery($query);
		$survey = $this->_db->loadObject();		
		if(!$skip_user_check && (!$survey || ($survey->created_by != $user->id)) && !$user->authorise('core.manage', S_APP_NAME)){
			return false;
		}
		$params = JComponentHelper::getParams(S_APP_NAME);
		$points_system = $params->get('points_system', 'none');
		$points_per_credit = (int)$params->get('points_per_credit', 0);		
		if(!$unlimited && $points_per_credit > 0 && $points_system != 'none'){			
			//Check user has enough credits
			$credits = $this->check_user_credits($survey->created_by);			
			if($credits == 0){
				return false;
			}else{				
				if($credits > 0 && $credits < $count){					
					$count = $credits;
				}
			}
		}		
		$keys = array();
		$created = JFactory::getDate()->toSql();
		$created = $this->_db->quote($created);
		$query = 'insert into #__survey_keys(key_name, survey_id, response_id, created) values ';		
		for ($i=0; $i < $count; $i++){			
			$key = CJFunctions::generate_random_key ();
			array_push($keys, $key);
			$query = $query . '('.$this->_db->quote($key).','.$sid.', 0, '.$created.'),';
		}		
		$query = substr($query, 0, -1);
		$this->_db->setQuery($query);		
		if($this->_db->query()){			
			if(!$unlimited && $points_per_credit > 0 && $points_system != 'none'){			
				$this->use_credits($survey->created_by, $count, JText::sprintf('TXT_SURVEY', $survey->title));
			}
		} else {			
			$this->setError($this->_db->getErrorMsg());
		}		
		return $keys;
	}
	
	function add_messages_to_queue($asset_id, $subject, $body, $emails, $template, $message_id = 0){
		
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		
		if($message_id == 0){
			
			$preferences_link = '';
			$site_name = $app->getCfg('sitename');
			$site_url = JUri::root();
			
			$msg_params = json_encode(array(
					'template'=>$template, 
					'placeholders'=>array(
							'{preferences}'=>$preferences_link,
							'{sitename}'=>$site_name,
							'{siteurl}'=>JUri::root(),
							'{title}'=>JText::_('EMAIL_TITLE')
							)));
			$created = JFactory::getDate()->toSql();
			
			$query = '
					insert into
						#__corejoomla_messages (asset_id, asset_name, subject, description, params, created) values
					(
						'.$asset_id.',
						'.$this->_db->quote(S_APP_NAME.'.invitation').',
						'.$this->_db->quote($subject).',
						'.$this->_db->quote($body).',
						'.$this->_db->quote($msg_params).',
						'.$this->_db->quote($created).'
					)';
			
			$this->_db->setQuery($query);
			
			if($this->_db->query()){
			
				$messageid = $this->_db->insertid();
			}
		}
		
			
		if($messageid > 0){
			
			foreach ($emails as $email){

				if($email->subid <= 0) $unsubscribe_link = '';
				$params = json_encode(array('placeholders'=>array('{name}'=>$email->name, '{link}'=>$email->link, '{unsubscribe}'=>$unsubscribe_link)));
				$inserts[] = '('.$this->_db->quote($email->email).','.$messageid.', 0, 1,'.$this->_db->quote($params).')';
			}
			
			$query = 'insert into #__corejoomla_messagequeue(to_addr, message_id, `status`, html, params) values '.implode(',', $inserts);
			$this->_db->setQuery($query);
			
			if($this->_db->query()){
				
				$sent = $this->_db->getAffectedRows();
				
				return $sent;
			}
		}
				
		$this->setError($this->_db->getErrorMsg());
		return false;
	}
	
	function save_settings($post){
		$db =& JFactory::getDBO();	
		foreach($post as $key => $value){
			echo $key .' => '.$value.'<br>';
			$query = "REPLACE INTO `#__ap_donation_variables` (name,value) VALUES ('$key','$value')";
			$db->setQuery($query);
			$db->query();
		}
		$message = JText::_('Your setting has been saved...');	
	}
	
	function save_categories($cid,$data){
		
		$db 		=& JFactory::getDBO();
		
		$QueryCheck 	= "SELECT * FROM ".$db->QuoteName('#__ap_categories')." WHERE ".$db->QuoteName('setting_id')."='".$cid."' AND unlocked='1'";
		
		$db->setQuery($QueryCheck);
		
		if($db->query()){
			
			$numRows	= $db->getNumRows();
			
		}
		
		if($numRows>0){
			if (!empty($data['survey_price'])){
		$query		= "UPDATE ".$db->QuoteName('#__ap_categories')." SET ".$db->QuoteName('survey_price')."='".$data['survey_price']."', ".$db->QuoteName('unlocked')."='0' WHERE ".					  
					  $db->QuoteName('setting_id')."='".$cid."'";
					  } else {
			$query		= "UPDATE ".$db->QuoteName('#__ap_categories')." SET ".$db->QuoteName('user_survey_price')."='".$data['user_survey_price']."', ".$db->QuoteName('unlocked')."='0' WHERE ".					  
					  $db->QuoteName('setting_id')."='".$cid."'";
					   }
			$db->setQuery($query);
			
			
			if($db->query()){
				
				return true;
			
			}else{
				
				return false;
			
			}
		}
		
	}	
	
	function invar($name,$value){
		
		$db =& JFactory::getDBO();
		
		$query = "SELECT * FROM `#__ap_donation_variables` WHERE name = '$name' LIMIT 1";
		
		$db->setQuery($query);
		
		$rs=$db->loadObject();
		
		if($rs->name){
			if($rs->value){
				$result = $rs->value;
			}else{
				$result = $value; 
			}
			return $result;
		}else{
			return $value;
		}
	}
	
	function registered_survey($package_id){
		$db 	= JFactory::getDBO();
		
		$query 	= $db->getQuery(TRUE);
		
		$query->select("*");
		
		$query->from("#__survey");
		
		$query->where("package_id='".$package_id."'");
		
		$db->setQuery($query);
		
		return count($db->loadObjectList());
	}
}
?>