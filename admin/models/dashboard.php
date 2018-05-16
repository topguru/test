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

class AwardpackageModelDashboard extends JModelList {
	
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
    	$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;
    
    	$query = '
        		select
        			a.id, a.title, a.alias, a.introtext, a.created_by, a.created, a.catid, a.published, a.responses, a.survey_key,
    				a.publish_up, a.publish_down, a.private_survey, a.max_responses, a.anonymous, a.public_permissions, a.display_template,
        			c.title as category, c.alias as calias,
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

    function get_survey_responses($id, $limit=20, $limitstart=0){
    	 
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
        			#__categories c ON a.catid = c.id
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
				id, survey_id, title, description, answer_explanation, question_type, page_number, 
				responses, sort_order, include_custom, mandatory, orientation 
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
					id, question_id, answer_type, title, correct_answer, sort_order 
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
}
?>