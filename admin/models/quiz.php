<?php
/**
 * @version		$Id: quiz.php 01 2012-04-21 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components.admin
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die();

// Import Joomla! libraries
jimport('joomla.application.component.model');
//require_once JPATH_SITE.'/components/com_cjlib/tree/nestedtree.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
class AwardpackageModelQuiz extends JModelLegacy {

	function __construct() {
			
		parent::__construct();
	}

         function get_categories($package_id) {
        
             $query ="SELECT * FROM #__quiz_categories
                                                    WHERE package_id = '".$package_id."' ORDER BY id ASC";
			$this->_db->setQuery($query);
			$result = $this->_db->loadObjectList();
            return $result;
      }
	  
	public function get_quizzes_by_responses($user_id, $ids = array(), $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest( Q_APP_NAME.'.quizzes.filter_order', 'filter_order', 'a.created', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( Q_APP_NAME.'.quizzes.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		$limitstart = $app->getUserStateFromRequest( Q_APP_NAME.'.quizzes.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$catid = $app->input->post->getInt('catid', 0);
		$userid = $app->input->post->getInt('uid', 0);
		$state = $app->input->post->getInt('state', 3);
		$search = $app->input->post->getString('search', '');

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

		$wheres[] = 'a.package_id = \''.JRequest::getVar('package_id').'\'';

		$where = ((count($wheres) > 0) ? ' where ('.implode(' ) and ( ', $wheres).')' : '');
		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

		$query = '
        		select
        			a.id, a.title, a.alias, a.description, a.created_by, a.created, a.catid, a.published, a.responses,
        			c.title as category, c.alias as calias,
					u.name, u.username, u.email
        		from
        			#__quiz_quizzes a
        		inner join 
        			#__quiz_responses r on r.quiz_id = a.id and r.created_by = \''.$user_id.'\'
        		left join
        			#__quiz_categories c ON a.catid=c.id
        		left join
        			#__users u ON a.created_by=u.id
    			'.$where.$order;

		$this->_db->setQuery($query, $limitstart, $limit);
		$return['quizzes'] = $this->_db->loadObjectList();

		/************ pagination *****************/
		$query = '
        		select
        			count(*)
        		from
        			#__quiz_quizzes a
        		inner join
        			#__quiz_responses r on r.quiz_id = a.id and r.created_by = \''.$user_id.'\'
        		left join
        			#__quiz_categories c on a.catid = c.id
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

	public function get_quizzes($ids = array(), $limit = 20, $limitstart = 0){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest( Q_APP_NAME.'.quizzes.filter_order', 'filter_order', 'a.created', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( Q_APP_NAME.'.quizzes.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		$limitstart = $app->getUserStateFromRequest( Q_APP_NAME.'.quizzes.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$catid = $app->input->post->getInt('catid', 0);
		$userid = $app->input->post->getInt('uid', 0);
		$state = $app->input->post->getInt('state', 3);
		$search = $app->input->post->getString('search', '');

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

		$wheres[] = 'a.package_id = \''.JRequest::getVar('package_id').'\'';

		$where = ((count($wheres) > 0) ? ' where ('.implode(' ) and ( ', $wheres).')' : '');
		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

		$query = '
        		select
        			a.id, a.title, a.alias, a.description, a.created_by, a.created, a.catid, a.published, a.responses,
        			c.title as category, c.alias as calias,
					u.name, u.username, u.email
        		from
        			#__quiz_quizzes a
        		left join
        			#__quiz_categories c ON a.catid=c.id
        		left join
        			#__users u ON a.created_by=u.id
    			'.$where.$order;

		$this->_db->setQuery($query, $limitstart, $limit);
		$return['quizzes'] = $this->_db->loadObjectList();

		/************ pagination *****************/
		$query = '
        		select
        			count(*)
        		from
        			#__quiz_quizzes a
        		left join
        			#__quiz_categories c on a.catid = c.id
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

	function save_quiz(&$quiz){
			
		$quiz->alias = CJFunctions::get_unicode_alias($quiz->title);
			
		$query = '
    			update 
    				#__quiz_quizzes
    			set
    				title = '.$this->_db->quote($quiz->title).',
    				alias = '.$this->_db->quote($quiz->alias).',
    				description = '.$this->_db->quote($quiz->description).',
    				catid = '.$quiz->catid.',
    				published = '.$quiz->published.',
    				duration = '.$quiz->duration.',
    				skip_intro = '.$quiz->skip_intro.',
    				multiple_responses = '.$quiz->multiple_responses.',
    				show_answers = '.$quiz->show_answers.',
    				show_template = '.$quiz->show_template.'
    			where
    				id = '.$quiz->id;
			
		$this->_db->setQuery($query);
			
		if($this->_db->query()){

			JFactory::getApplication()->enqueueMessage($this->_db->getErrorMsg());
			return true;
		}
			
		return false;

	}

	function get_category_tree(){
			
		$tree = new CjNestedTree($this->_db, '#__quiz_categories');
		return $tree->get_indented_nodes();
	}

	function set_status($id, $status){
			
		$approved_quizzes = null;
			
		if($status == true){

			$query = 'select id from #__quiz_quizzes where id in ('.$id.') and published = 2';
			$this->_db->setQuery($query);
			$approved_quizzes = $this->_db->loadResultArray();
		}
			
		$query = 'update #__quiz_quizzes set published = '.($status ? 1 : 0).' where id in ('.$id.')';
		$this->_db->setQuery($query);
			
		if(!$this->_db->query()){

			return false;
		}else{

			if($count = $this->_db->getAffectedRows()){
					
				$query = '
    					update 
    						#__quiz_categories a 
    					set 
    						quizzes=(select count(*) from #__quiz_quizzes b where published=1 and b.catid=a.id group by b.catid) 
    					where 
    						a.parent_id > 0';
					
				$this->_db->setQuery($query);
					
				if(!$this->_db->query()){

					return false;
				}
			}

			return !empty($approved_quizzes) ? $approved_quizzes : true;
		}
	}

        function save_settings($post){
		$db =& JFactory::getDBO();
		
		foreach($post as $key => $value){
				}

			//echo $key .' => '.$value.'<br>';
			//break;
			$query = "REPLACE INTO `#__ap_donation_variables` (name,value) VALUES ('unlock','0')";
			$db->setQuery($query);
			$db->query();
		$message = JText::_('Your setting has been saved...');	
	}
	
	function save_categories($cid,$data){
		$db 		=& JFactory::getDBO();
		
		$QueryCheck 	= "SELECT * FROM ".$db->QuoteName('#__ap_categories')." WHERE ".$db->QuoteName('setting_id')."='".$cid."' AND unlocked = '1'";
		
		$db->setQuery($QueryCheck);
		
		if($db->query()){
			
			$numRows	= $db->getNumRows();
			
		}
		if($numRows>0){
			if (!empty($data['quiz_price'])){
		$query		= "UPDATE ".$db->QuoteName('#__ap_categories')." SET ".$db->QuoteName('quiz_price')."='".$data['quiz_price']."', ".$db->QuoteName('unlocked')."='0' WHERE ".					  
					  $db->QuoteName('setting_id')."='".$cid."'";
					  } else {
			$query		= "UPDATE ".$db->QuoteName('#__ap_categories')." SET ".$db->QuoteName('user_quiz_price')."='".$data['user_quiz_price']."', ".$db->QuoteName('unlocked')."='0' WHERE ".					  
					  $db->QuoteName('setting_id')."=".$cid."";
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
	
	
	function delete_quizzes($id){
			
		$queries = array();
		$id = implode(',', $id);
			
		$queries[] = 'delete from #__quiz_response_details where response_id in (select id from #__quiz_responses where quiz_id in ('.$id.'))';
		$queries[] = 'delete from #__quiz_responses where quiz_id in ('.$id.')';
		$queries[] = 'delete from #__quiz_answers where quiz_id in ('.$id.')';
		$queries[] = 'delete from #__quiz_questions where quiz_id in ('.$id.')';
		$queries[] = 'delete from #__quiz_pages where quiz_id in ('.$id.')';
		$queries[] = 'update #__quiz_categories set quizzes=quizzes-1 where quizzes > 0 and id in (select catid from #__quiz_quizzes where id in ('.$id.'))';
		$queries[] = 'delete from #__quiz_quizzes where id in ('.$id.')';
			
		foreach ($queries as $query){

			$this->_db->setQuery($query);

			if(!$this->_db->query()){
					
				return false;
			}
		}
			
		return true;
	}

	function get_quiz_details($id, $get_questions = false){
			
		$query = '
    		select 
    			a.id, a.title, a.alias, a.description, a.catid, a.created_by, a.created, a.responses, a.ip_address, a.duration, a.published, a.skip_intro, a.cutoff,
    			a.show_answers, a.show_template, a.multiple_responses, c.title as category, c.alias as calias, u.name, u.username 
    		from 
    			#__quiz_quizzes a 
    		left join 
    			#__quiz_categories c on a.catid=c.id
    		left join 
    			#__users u on a.created_by=u.id
    		where 
    			a.id='.$id;
		$this->_db->setQuery($query);
			
		$quiz = $this->_db->loadObject();
			
		if($get_questions) {

			$quiz->questions = $this->get_questions($id);
				
		}
			
		return $quiz;
	}

	function get_quiz_details_2($id, $no_tags = false, $pages = false){
			
		$params = JComponentHelper::getParams(Q_APP_NAME);
			
		$query = '
    		select 
    			a.id, a.title, a.alias, a.description, a.catid, a.created_by, a.created, a.responses, a.ip_address, a.duration, a.published, 
    			a.show_answers, a.show_template, a.multiple_responses, a.skip_intro, a.cutoff,
    			c.title as category, c.alias as calias, 
    			u.'.$params->get('user_display_name', 'name').' as username, u.email, 
    			rtg.rating 
    		from 
    			#__quiz_quizzes a 
    		left join 
    			#__quiz_categories c on a.catid = c.id 
    		left join 
    			#__users u on a.created_by = u.id 
    		left join 
    			'.T_CJ_RATING.' rtg on rtg.asset_id='.CQ_ASSET_ID.' and rtg.item_id=a.id 
    		where a.id='.$id;
			
		$this->_db->setQuery($query);
		$quiz = $this->_db->loadObject();

		if (!empty($quiz) && $no_tags == false) {

			$quiz->tags = $this->get_tags_by_itemids(array($quiz->id));
		}

		if($pages){

			$quiz->pages = $this->get_pages_2($id);
		}

		return $quiz;
	}

	function get_questions($quiz_id, $page_id=0){
		$user = JFactory::getUser ();
		$where = '';
		if($page_id){
			$where = ' and page_number=' . $page_id;
		}
		$query = '
			select 
				id, quiz_id, title, description, answer_explanation, question_type, page_number, responses, sort_order, include_custom, mandatory, orientation 
			from 
				#__quiz_questions 
			where 
				quiz_id='.$quiz_id.$where.' 
			order by 
				page_number, sort_order asc';	
		$this->_db->setQuery ( $query );
		$questions = $this->_db->loadObjectList ( 'id' );
		if ($questions) {
			$query = '
				select 
					id, question_id, answer_type, title, correct_answer, sort_order, image 
				from 
					#__quiz_answers 
				where 
					quiz_id='.$quiz_id.' and question_id in (select id from #__quiz_questions where quiz_id=' . $quiz_id . $where . ') 
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

	public function get_questions_2($uniq_id, $question_id, $page_id){
		$user = JFactory::getUser ();
		$query = "
			select 
				id, quiz_id, title, description, answer_explanation, question_type, page_number, responses, sort_order, include_custom, mandatory, orientation 
			from 
				#__quiz_questions 
			where 
				uniq_key = '".$uniq_id."' and question_id = '".$question_id."' and page_number = '".$page_id."' 
			order by 
				page_number, sort_order asc";	
		$this->_db->setQuery ( $query );
		$questions = $this->_db->loadObjectList ();
		if ($questions) {
			$question = $questions[0];
			$query = "
				select 
					* 
				from 
					#__quiz_answers 
				where 
					question_id = '".$question->id."' 
				order by 
					id asc";	
			$this->_db->setQuery ( $query );
			$answers = $this->_db->loadObjectList ();
			$question->answers = $answers;
			//foreach ( $answers as $answer ) {
			//$questions [$answer->question_id]->answers [] = $answer;
			//$questions ['answers'] = $answer;
			//}
			return $questions;
		} else {
			$error = $this->_db->getErrorMsg ();
			if (! empty ( $error )) {
				$this->setError ( $error . '<br><br> Error code: 10076<br>query: ' . $query . '<br><br>' );
			}
			return false;
		}
	}

	public function copy_quiz($id){
		$uniq_id = md5(uniqid(rand(), true));
		$user = JFactory::getUser();
		$created = JFactory::getDate()->toSql();
		$ip_address = '0.0.0.0';
			
		$query = '
    		insert into
    			#__quiz_quizzes(
    				title, alias, created_by, created, catid, description, show_answers, show_template, published, duration, multiple_responses, skip_intro, ip_address, package_id
    			)
    		(
    			select
    				concat(title, \'_Copy\'), concat(alias, \'_copy\'), '.$user->id.','.$this->_db->quote($created).', catid, 
    				description, show_answers, show_template, 2, duration, multiple_responses, skip_intro, '.$this->_db->quote($ip_address).', package_id
    			from
    				#__quiz_quizzes
    			where
    				id = '.$id.'
    		)';

		$this->_db->setQuery($query);
			
		if($this->_db->query()){

			$newid = $this->_db->insertid();
				
			if($newid > 0){

				$query = 'select id, sort_order, title from #__quiz_pages where uniq_key != \'0\' and quiz_id = '.$id;

				$this->_db->setQuery($query);
				$pages = $this->_db->loadObjectList();

				$query = '
    				select
    					id, title, description, question_type, page_number, sort_order, mandatory, include_custom, orientation, question_id
    				from
    					#__quiz_questions
    				where
    					quiz_id = '.$id;

				$this->_db->setQuery($query);
				$questions = $this->_db->loadObjectList();

				$query = '
					select id, package_id, quiz_id, page_number, question_id, complete_giftcode, complete_giftcode_quantity, complete_giftcode_cost_response, 
							incomplete_giftcode, incomplete_giftcode_quantity, incomplete_giftcode_cost_response
					from #__quiz_question_giftcode
					where 
					page_number > 0
					and quiz_id = '.$id;
				$this->_db->setQuery($query);
				$giftcodes = $this->_db->loadObjectList();
					
				if(empty($pages) || empty($questions)){
					return false;
				}
				foreach ($pages as $page){
					$query = 'insert into #__quiz_pages (quiz_id, sort_order, title, uniq_key) values ('.$newid.','.$page->sort_order.',
							\''.$page->title.'\', \''.$uniq_id.'\')';
					$this->_db->setQuery($query);
					if($this->_db->query()){
						$newpage = $this->_db->insertid();

						if($newpage <= 0){
							return false;
						}

						foreach ($giftcodes as $giftcode) {
							if($giftcode->page_number == $page->id) {
								$query = '
										insert into #__quiz_question_giftcode(package_id, quiz_id, page_number, question_id, complete_giftcode,
										complete_giftcode_quantity, complete_giftcode_cost_response, incomplete_giftcode,
										incomplete_giftcode_quantity, incomplete_giftcode_cost_response, uniq_key)
										values ('.
								$giftcode->package_id	.','.
								$newid .','.
								$newpage .','.
								$giftcode->question_id .','.
								$this->_db->quote($giftcode->complete_giftcode) .','.
								(empty($giftcode->complete_giftcode_quantity) ? 'NULL,' : $giftcode->complete_giftcode_quantity . ',') .
								(empty($giftcode->complete_giftcode_cost_response) ? 'NULL,' : $giftcode->complete_giftcode_cost_response . ',') .
								$this->_db->quote($giftcode->incomplete_giftcode) .','.
								(empty($giftcode->incomplete_giftcode_quantity ) ? 'NULL,' : $giftcode->incomplete_giftcode_quantity  . ',') .
								(empty($giftcode->incomplete_giftcode_cost_response ) ? 'NULL,' : $giftcode->incomplete_giftcode_cost_response  . ',') .
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
    									#__quiz_questions(
    										title, description, quiz_id, question_type, page_number, responses, sort_order, mandatory, 
    										created_by, include_custom, orientation, question_id, uniq_key)
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
								$question->include_custom.','.
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
    										#__quiz_answers(quiz_id, question_id, answer_type, title, correct_answer, sort_order, image, marks)
    									(
    										select
    											'.$newid.', '.$newqnid.', answer_type, title, correct_answer, sort_order, image, marks
    										from
    											#__quiz_answers
    										where
    											question_id = '.$question->id.'
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
					
				$query = 'insert ignore into #__quiz_tagmap(tag_id, item_id) (select tag_id, '.$newid.' from #__quiz_tagmap where item_id = '.$id.')';
					
				$this->_db->setQuery($query);
				$this->_db->query();
					
				$query = '
					update
						#__quiz_tags_stats s
					set
						s.num_items = (select count(*) from #__quiz_tagmap m where m.tag_id = s.tag_id)
					where
						s.tag_id in (select tag_id from #__quiz_tagmap m1 where m1.item_id = '.$newid.')';

				$this->_db->setQuery($query);
				$this->_db->query();

				return true;
			}
		}

		$this->setError($this->_db->getErrorMsg());

		return false;
	}
	//*************** method tambahan dari Adit ******************//
	function get_pages_list($quiz_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, title, sort_order, uniq_key')->from('#__quiz_pages')->where("quiz_id = ".$quiz_id . " and uniq_key != '0' ")->order('sort_order asc');
		$db->setQuery($query);
		try{
			$pages = $db->loadObjectList();
			return $pages;
		} catch(Exception $e){
			return false;
		}
		return false;
	}

	function get_pages($package_id, $uniq_key)
	{
		$query = "
				SELECT id, sort_order, title FROM #__quiz_pages
				WHERE uniq_key = '".$uniq_key."' ORDER BY sort_order DESC
			";
		$this->_db->setQuery ( $query );
		$data = $this->_db->loadObjectList();
		return $data;
	}

	function get_pages_2($quiz_id)
	{
		$query = "
				SELECT id, sort_order, title FROM #__quiz_pages
				WHERE quiz_id = '".$quiz_id."' ORDER BY sort_order DESC
			";
		$this->_db->setQuery ( $query );
		$data = $this->_db->loadObjectList();
		return $data;
	}

	function get_quiz_question_giftcode($package_id, $quiz_id, $page_number, $question_id, $uniq_key)
	{
		$query = "SELECT a.* FROM #__quiz_question_giftcode a
					INNER JOIN #__quiz_pages b ON b.id = a.page_number AND b.id = '".$page_number."' AND b.uniq_key = '".$uniq_key."'
					WHERE a.package_id = '".$package_id."' AND a.uniq_key = '".$uniq_key."'
				   AND a.question_id = '".$question_id."'";		
		$this->_db->setQuery ( $query );
		$data = $this->_db->loadObjectList();
		return $data;
	}

	function get_quiz_question_giftcode2($package_id, $quiz_id, $page_number, $uniq_key)
	{
		$query = "SELECT a.* FROM #__quiz_question_giftcode a
					INNER JOIN #__quiz_pages b ON b.id = a.page_number AND b.id = '".$page_number."' AND b.uniq_key = '".$uniq_key."'
					WHERE a.package_id = '".$package_id."' AND a.uniq_key = '".$uniq_key."' order by a.question_id desc";								
		$this->_db->setQuery ( $query );
		$datas = $this->_db->loadObjectList();
		$dats = array();
		foreach ($datas as $data) {
			$questions = $this->get_questions_2($data->uniq_key, $data->question_id, $data->page_number);
			$data->questions = $questions;
			$dats[] = $data;
		}
		return $dats;
	}

	function create_page($quiz_id, $uniq_id='0') {
		$this->_db = &JFactory::getDBO ();
		$query = 'select max(sort_order)+1  as sort_order from #__quiz_pages where quiz_id = ' . $quiz_id;
		$query .= ($uniq_id != '0' ? ' and uniq_key = \''.$uniq_id.'\'' : '');
		$this->_db->setQuery ( $query );
		$sort_order = $this->_db->loadResult ();
		if (! $sort_order) {
			$sort_order = 1;
		}
		$query = 'insert into #__quiz_pages (quiz_id, sort_order, uniq_key) values (' . $quiz_id . ',' . $sort_order . ', \''.$uniq_id.'\')';
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10021<br>query: ' . $query . '<br><br>' );
			return false;
		} else {
			return $this->_db->insertid ();
		}
	}

	function insert_quiz_question_giftcode ($package_id, $quiz_id, $page_number, $question_id, $uniq_key)
	{
		$query = "
			insert into #__quiz_question_giftcode
			(package_id, quiz_id, page_number, question_id, uniq_key, complete_giftcode, incomplete_giftcode)
			values (
				'".$package_id."', '".$quiz_id."', '".$page_number."', '".$question_id."', '".$uniq_key."', 'NEW', 'NEW'	
			)			
		";
		$this->_db->setQuery($query);
		$this->_db->query ();
	}

	function update_question_giftcode($package_id, $page_number, $question_id, $uniq_key,
	$completeGiftCode, $completeGiftCodeQuantity, $completeGiftCodeCostResponse,
	$incompleteGiftCode, $incompleteGiftCodeQuantity, $inCompleteGiftCodeCostResponse)
	{
		$query = "
				update #__quiz_question_giftcode 
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

	function get_max_id_pages($uniq_id) {
		$this->_db = &JFactory::getDBO ();
		$query = "select max(id) as id from #__quiz_pages where uniq_key = '".$uniq_id."' ";
		$this->_db->setQuery ( $query );
		$id = $this->_db->loadResult ();
		return $id;
	}

	function delete_question_giftcode($package_id, $page_number, $question_id, $uniq_key){
		$query = "
				select * from #__quiz_question_giftcode where package_id = '".$package_id."' and page_number = '".$page_number."'
				and uniq_key = '".$uniq_key."'
			";
		$this->_db->setQuery ( $query );
		$questions = $this->_db->loadObjectList();
		if(!empty($questions) && count($questions) > 1) {
			$query = "
					delete from #__quiz_question_giftcode where package_id = '".$package_id."' and page_number = '".$page_number."'
					and question_id = '".$question_id."' and uniq_key = '".$uniq_key."' 
				";
			$this->_db->setQuery($query);
			if(!$this->_db->query()){
				//JFactory::getApplication()->enqueueMessage($this->_db->getErrorMsg());
				return false;
			}else {
				return true;
			}
		} else {
			return false;
		}
	}

	function remove_page_2($pid, $uniq_key) {
		$success = false;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query = 'delete from #__quiz_answers where question_id in (select id from #__quiz_questions
						where uniq_key = '.$db->q($uniq_key).' and question_id in (select question_id from #__quiz_question_giftcode 
								where uniq_key = '.$db->q($uniq_key).' and page_number = '.$pid.'))';
		$this->_db->setQuery($query);
		if($this->_db->query()){
			$query = 'delete from #__quiz_questions
						where uniq_key = '.$db->q($uniq_key).' and question_id in (select question_id from #__quiz_question_giftcode 
								where uniq_key = '.$db->q($uniq_key).' and page_number = '.$pid.')';
			$this->_db->setQuery($query);
			if($this->_db->query()){
				$query = 'delete from #__quiz_question_giftcode
								where uniq_key = '.$db->q($uniq_key).' and page_number = '.$pid;
				$this->_db->setQuery($query);
				if($this->_db->query()){
					$query = 'delete from #__quiz_pages where id = '.$pid.' and uniq_key = '.$db->q($uniq_key);
					$this->_db->setQuery($query);
					if($this->_db->query()){
						$success = true;
					}
				}
			}
		}
		if($success) {
			$order = array();
			$query = 'select id, sort_order from #__quiz_pages where uniq_key = '.$db->q($uniq_key);
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
	public function reorder_pages_2($ordering){
		if(empty($ordering)) return true;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->update('#__quiz_pages');
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

	public function rename_page2($pid, $uniq_key, $title){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__quiz_pages')->set('title = '.$db->q($title))->where('id = '.$pid.' and uniq_key = '.$db->q($uniq_key));
		$db->setQuery($query);
		try{
			$db->execute();
			return true;
		} catch (Exception $e){
			return false;
		}
		return false;
	}

	function save_question(){
		$app = JFactory::getApplication();
		$user = JFactory::getUser ();
		$params = JComponentHelper::getParams(Q_APP_NAME);
		$query = '';

		// Request parameters
		$quiz_id = $app->input->getInt('id', 0);
		$pid = $app->input->getInt('pid', 0);
		$qid = $app->input->post->getInt('qid', 0);
		$order = $app->input->post->getInt('order', 0);
		$qtype = $app->input->post->getInt('qtype', 0);
		$title = JRequest::getVar('title');
		$mandatory = $app->input->post->getInt('mandatory', 0);
		$custom_choice = $app->input->post->getInt('custom_answer', 0);
		$orientation = $app->input->post->getWord('orientation', 'H');
		$description = JRequest::getVar('description');
		$explanation = JRequest::getVar('explanation');
		$uniq_key = JRequest::getVar('uniq_id');
		$question_id =JRequest::getVar('question_id');
		$pid = JRequest::getVar('pid');
		$page_number = JRequest::getVar('page_number');

		if ($qtype > 12 || $qtype <= 0 || $user->guest || strlen ( $title ) <= 0)
		{
			$this->setError (JText::_ ( 'MSG_UNAUTHORIZED' ) . '<br><br>Error code: 10001<br>id: ' . $quiz_id . '<br>pid: ' . $pid . '<br>qtype: ' . $qtype . '<br>title: ' . $title . '<br><br>' );
			return false;
		}

		$order = ($qtype == 1) ? 1 : ($order < 2 ? 2 : $order);

		if(!$this->authorize_quiz($quiz_id))
		{
			$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10002<br>query: ' . $query . '<br><br>' );
			return false;
		}

		// First manipulate with question related changes
		if ($qid > 0)
		{
			// Be on safe side, check if user sending manipulated values.
			$query = 'select count(*) from #__quiz_questions where id=' . $qid . ' and quiz_id = '. $quiz_id;
			$this->_db->setQuery ( $query );
			$count = ( int )$this->_db->loadResult ();
			if ( !$count )
			{
				$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10003<br>query: ' . $query . '<br><br>' );
				return false;
			}

			// Genuine request, update question
			$query = "
				update 
					#__quiz_questions 
				set 
					title=" . $this->_db->quote ( $title ).", 
					description=".$this->_db->quote( $description ).", 
					mandatory=" . $mandatory.", 
					question_type=" . $qtype.", 
					include_custom=" . $custom_choice.",
					orientation=" . $this->_db->quote($orientation).",
					answer_explanation = ".$this->_db->quote( $explanation )."
				where 
					id = '" .$qid. "'
					";

			$this->_db->setQuery ( $query );

			if (! $this->_db->query ())
			{
				$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10004<br>query: ' . $query . '<br><br>' );
				return false;
			}
		}
		else
		{
			$query = "
					select * from #__quiz_questions where question_id = '".$question_id."' and page_number = '".$page_number."'
					and uniq_key = '".$uniq_key."'
				";
			$this->_db->setQuery($query);
			$question = $this->_db->loadObject();
			if(!empty($question)) {
				$this-> setError ('<b>Only one question can create for each</b>');
				return false;
			}

			// New question, insert
			$query = "
				insert into 
					#__quiz_questions (title, description, quiz_id, page_number, question_type, created_by, mandatory, include_custom, sort_order, orientation, answer_explanation, question_id, uniq_key) 
				values (" 
				. $this->_db->quote ( $title ) . ","
				. $this->_db->quote ( $description ) . ","
				. $quiz_id . ","
				. $page_number . ","
				. $qtype . ","
				. $user->id . ","
				. $mandatory . ","
				. $custom_choice . ","
				. $order . ","
				. $this->_db->quote($orientation).","
				. $this->_db->quote ( $explanation ) . ","
				. $question_id . ","
				. "'" .$uniq_key. "'"
				. ")";
				$this->_db->setQuery ( $query );
					
				if (! $this->_db->query ())
				{
					$this->setError ($this->_db->getErrorMsg() . '<br><br> Error code: 10005<br>query: ' . $query . '<br><br>' );
					return false;
				}
				else
				{
					// Success, get the inserted id for further operation
					$qid = $this->_db->insertid ();
				}
		}
		// Move on to question answers (do not confuse with responses please, these are answers for questions like labels for multiple choice)
		switch ($qtype)
		{
			case CQ_PAGE_HEADER : // Page header
				return $qid;
			case CQ_CHOICE_RADIO :
				$choices = $app->input->post->getArray(array('answers'=>'array'));
				$choices = $choices['answers'];
				if (count ( $choices ) <= 0)
				{
					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10007' );
					return false;
				}
				/**************************** DELETIONS *******************************/
				$existing = array();
				foreach ($choices as $row)				{
					$row = trim($row);
					if (!empty($row) && strpos($row, '_') !== false)
					{
						$tokens = explode('_', $row, 6);
						if(count($tokens) == 6)
						{
							$answer_id = (int) $tokens[0];
							if($answer_id > 0)
							{
								$existing[] = $answer_id;
							}
						}
					}
				}
				if(!empty($existing))
				{
					$query = 'delete from #__quiz_answers where quiz_id = '.$quiz_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10023<br>query: ' . $query . '<br><br>' );
						return false;
					}
				}
				/**************************** UPDATES *******************************/
				$update_columns = Array(
						'title' => '`title` = CASE ', 
						'sort_order' => '`sort_order` = CASE ', 
						'correct_answer' => '`correct_answer` = CASE ',
						'marks' => '`marks` = CASE ',
						'image' => '`image` = CASE ');
				$queries = $this->_get_answer_update_queries($update_columns, $choices, $quiz_id.','.$qid.','.$this->_db->quote ( 'x' ));
				if(!empty($queries['query_update']))
				{
					$query_update = 'update #__quiz_answers set '.$queries['query_update'].' where id in ('.implode(',', $queries['update_ids']).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10008<br>query: ' . $query_update . '<br><br>' );
						return false;
					}
				}
				/**************************** INSERTS *******************************/
				if(!empty($queries['query_insert']))
				{
					$query_insert = 'insert into #__quiz_answers (quiz_id, question_id, answer_type, title, sort_order, correct_answer, marks, image) values '.substr($queries['query_insert'], 0, - 1);
					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10009<br>query: ' . $query_insert . '<br><br>' );
						return false;
					}
				}
				/** return */
				return $qid;
			case CQ_CHOICE_CHECKBOX :
				$choices = $app->input->post->getArray(array('answers'=>'array'));
				$choices = $choices['answers'];
				if (count ( $choices ) <= 0)
				{
					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10007' );
					return false;
				}
				/**************************** DELETIONS *******************************/
				$existing = array();
				foreach ($choices as $row)				{
					$row = trim($row);
					if (!empty($row) && strpos($row, '_') !== false)
					{
						$tokens = explode('_', $row, 6);
						if(count($tokens) == 6)
						{
							$answer_id = (int) $tokens[0];
							if($answer_id > 0)
							{
								$existing[] = $answer_id;
							}
						}
					}
				}
				if(!empty($existing))
				{
					$query = 'delete from #__quiz_answers where quiz_id = '.$quiz_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10023<br>query: ' . $query . '<br><br>' );
						return false;
					}
				}
				/**************************** UPDATES *******************************/
				$update_columns = Array(
						'title' => '`title` = CASE ', 
						'sort_order' => '`sort_order` = CASE ', 
						'correct_answer' => '`correct_answer` = CASE ',
						'marks' => '`marks` = CASE ',
						'image' => '`image` = CASE ');
				$queries = $this->_get_answer_update_queries($update_columns, $choices, $quiz_id.','.$qid.','.$this->_db->quote ( 'x' ));
				if(!empty($queries['query_update']))
				{
					$query_update = 'update #__quiz_answers set '.$queries['query_update'].' where id in ('.implode(',', $queries['update_ids']).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10008<br>query: ' . $query_update . '<br><br>' );
						return false;
					}
				}
				/**************************** INSERTS *******************************/
				if(!empty($queries['query_insert']))
				{
					$query_insert = 'insert into #__quiz_answers (quiz_id, question_id, answer_type, title, sort_order, correct_answer, marks, image) values '.substr($queries['query_insert'], 0, - 1);
					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10009<br>query: ' . $query_insert . '<br><br>' );
						return false;
					}
				}
				/** return */
				return $qid;
			case CQ_CHOICE_SELECT : // Multiple choice - select
				$choices = $app->input->post->getArray(array('answers'=>'array'));
				$choices = $choices['answers'];
				if (count ( $choices ) <= 0)
				{
					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10007' );
					return false;
				}
				/**************************** DELETIONS *******************************/
				$existing = array();
				foreach ($choices as $row)				{
					$row = trim($row);
					if (!empty($row) && strpos($row, '_') !== false)
					{
						$tokens = explode('_', $row, 6);
						if(count($tokens) == 6)
						{
							$answer_id = (int) $tokens[0];
							if($answer_id > 0)
							{
								$existing[] = $answer_id;
							}
						}
					}
				}
				if(!empty($existing))
				{
					$query = 'delete from #__quiz_answers where quiz_id = '.$quiz_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10023<br>query: ' . $query . '<br><br>' );
						return false;
					}
				}
				/**************************** UPDATES *******************************/
				$update_columns = Array(
						'title' => '`title` = CASE ', 
						'sort_order' => '`sort_order` = CASE ', 
						'correct_answer' => '`correct_answer` = CASE ',
						'marks' => '`marks` = CASE ',
						'image' => '`image` = CASE ');
				$queries = $this->_get_answer_update_queries($update_columns, $choices, $quiz_id.','.$qid.','.$this->_db->quote ( 'x' ));
				if(!empty($queries['query_update']))
				{
					$query_update = 'update #__quiz_answers set '.$queries['query_update'].' where id in ('.implode(',', $queries['update_ids']).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10008<br>query: ' . $query_update . '<br><br>' );
						return false;
					}
				}
				/**************************** INSERTS *******************************/
				if(!empty($queries['query_insert']))
				{
					$query_insert = 'insert into #__quiz_answers (quiz_id, question_id, answer_type, title, sort_order, correct_answer, marks, image) values '.substr($queries['query_insert'], 0, - 1);
					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10009<br>query: ' . $query_insert . '<br><br>' );
						return false;
					}
				}
				/** return */
				return $qid;

			case CQ_IMAGE_CHOOSE_IMAGE:
				$choices = $app->input->post->getArray(array('answers'=>'array'));
				$choices = $choices['answers'];
					
				if (count ( $choices ) <= 0)
				{
					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10007' );
					return false;
				}

				/**************************** DELETIONS *******************************/
				$existing = array();

				foreach ($choices as $row)
				{
					$row = trim($row);

					if (!empty($row) && strpos($row, '_') !== false)
					{
						$tokens = explode('_', $row, 6);
							
						if(count($tokens) == 6)
						{
							$answer_id = (int) $tokens[0];

							if($answer_id > 0)
							{
								$existing[] = $answer_id;
							}

							// move the images if any in temp store.
							$image = $tokens[4];

							//if(!empty($image) && JFile::exists(CQ_TEMP_STORE.DS.$image))
							//{
							JFile::move(CQ_TEMP_STORE.DS.$image, CQ_IMAGES_UPLOAD_DIR.DS.$image);
							//}
						}
					}
				}

				if(!empty($existing))
				{
					$query = 'delete from #__quiz_answers where quiz_id = '.$quiz_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10023<br>query: ' . $query . '<br><br>' );
						return false;
					}
				}

				/**************************** UPDATES *******************************/

				$update_columns = Array(
						'title' => '`title` = CASE ', 
						'sort_order' => '`sort_order` = CASE ', 
						'correct_answer' => '`correct_answer` = CASE ',
						'marks' => '`marks` = CASE ',
						'image' => '`image` = CASE ');
				$queries = $this->_get_answer_update_queries($update_columns, $choices, $quiz_id.','.$qid.','.$this->_db->quote ( 'x' ));
					
				if(!empty($queries['query_update']))
				{
					$query_update = 'update #__quiz_answers set '.$queries['query_update'].' where id in ('.implode(',', $queries['update_ids']).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10008<br>query: ' . $query_update . '<br><br>' );
						return false;
					}
				}

				/**************************** INSERTS *******************************/
					
				if(!empty($queries['query_insert']))
				{
					$query_insert = '
							insert into 
								#__quiz_answers (quiz_id, question_id, answer_type, title, sort_order, correct_answer, marks, image) 
							values 
								'.substr($queries['query_insert'], 0, - 1);

					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10009<br>query: ' . $query_insert . '<br><br>' );
						return false;
					}
				}
					
				/** return */
				return $qid;
			case CQ_IMAGE_CHOOSE_IMAGES:
				$choices = $app->input->post->getArray(array('answers'=>'array'));
				$choices = $choices['answers'];
					
				if (count ( $choices ) <= 0)
				{
					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10007' );
					return false;
				}

				/**************************** DELETIONS *******************************/
				$existing = array();

				foreach ($choices as $row)
				{
					$row = trim($row);

					if (!empty($row) && strpos($row, '_') !== false)
					{
						$tokens = explode('_', $row, 6);
							
						if(count($tokens) == 6)
						{
							$answer_id = (int) $tokens[0];

							if($answer_id > 0)
							{
								$existing[] = $answer_id;
							}

							// move the images if any in temp store.
							$image = $tokens[4];

							//if(!empty($image) && JFile::exists(CQ_TEMP_STORE.DS.$image))
							//{
							JFile::move(CQ_TEMP_STORE.DS.$image, CQ_IMAGES_UPLOAD_DIR.DS.$image);
							//}
						}
					}
				}

				if(!empty($existing))
				{
					$query = 'delete from #__quiz_answers where quiz_id = '.$quiz_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10023<br>query: ' . $query . '<br><br>' );
						return false;
					}
				}

				/**************************** UPDATES *******************************/

				$update_columns = Array(
						'title' => '`title` = CASE ', 
						'sort_order' => '`sort_order` = CASE ', 
						'correct_answer' => '`correct_answer` = CASE ',
						'marks' => '`marks` = CASE ',
						'image' => '`image` = CASE ');
				$queries = $this->_get_answer_update_queries($update_columns, $choices, $quiz_id.','.$qid.','.$this->_db->quote ( 'x' ));
					
				if(!empty($queries['query_update']))
				{
					$query_update = 'update #__quiz_answers set '.$queries['query_update'].' where id in ('.implode(',', $queries['update_ids']).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10008<br>query: ' . $query_update . '<br><br>' );
						return false;
					}
				}

				/**************************** INSERTS *******************************/
					
				if(!empty($queries['query_insert']))
				{
					$query_insert = '
							insert into 
								#__quiz_answers (quiz_id, question_id, answer_type, title, sort_order, correct_answer, marks, image) 
							values 
								'.substr($queries['query_insert'], 0, - 1);

					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10009<br>query: ' . $query_insert . '<br><br>' );
						return false;
					}
				}
					
				/** return */
				return $qid;

			case CQ_GRID_RADIO :
				$grid_rows = $app->input->post->getArray(array('answers'=>'array'));
				$grid_columns = $app->input->post->getArray(array('columns'=>'array'));
				$grid_rows = $grid_rows['answers'];
				$grid_columns = $grid_columns['columns'];

				if ((count ( $grid_rows ) <= 0) || (count ( $grid_columns ) <= 0))
				{
					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10010' );
					return false;
				}

				$update_queries = array();
				$insert_queries = array();
				$update_columns = Array(
							'title' => '`title` = CASE ', 
							'sort_order' => '`sort_order` = CASE ', 
							'correct_answer' => '`correct_answer` = CASE ',
							'marks' => '`marks` = CASE ',
							'image' => '`image` = CASE ');

				/************************** GET_QUERIES ********************************/

				$row_queries = $this->_get_answer_update_queries($update_columns, $grid_rows, $quiz_id.','.$qid.','.$this->_db->quote ( 'x' ));
				$col_queries = $this->_get_answer_update_queries($update_columns, $grid_columns, $quiz_id.','.$qid.','.$this->_db->quote ( 'y' ));

				/**************************** DELETIONS *******************************/
				$existing = array();
				$rows = array_merge($grid_rows, $grid_columns);

				foreach ($rows as $row)
				{
					$row = trim($row);

					if (!empty($row) && strpos($row, '_') !== false)
					{
						$tokens = explode('_', $row, 6);

						if(count($tokens) == 6)
						{
							$answer_id = (int) $tokens[0];

							if($answer_id > 0)
							{
								$existing[] = $answer_id;
							}
						}
					}
				}

				if(!empty($existing))
				{
					$query = 'delete from #__quiz_answers where quiz_id = '.$quiz_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10013<br>query: ' . $query . '<br><br>' );
						return false;
					}
				}

				/**************************** UPDATES *************************************/
				if(!empty($row_queries['query_update']))
				{
					$update_queries[] = $row_queries['query_update'];
				}

				if(!empty($col_queries['query_update']))
				{
					$update_queries[] = $col_queries['query_update'];
				}

				$update_ids = array_merge($row_queries['update_ids'], $col_queries['update_ids']);

				if(count($update_queries) > 0)
				{
					$query_update = '
							update 
								#__quiz_answers 
							set 
								'.implode(',', $update_queries).' 
							where 
								id in ('.implode(',', $update_ids).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10011<br>query: ' . $query_update . '<br><br>' );
						return false;
					}
				}

				/**************************** INSERTS **********************************/

				if(!empty($row_queries['query_insert']))
				{
					$insert_queries[] = $row_queries['query_insert'];
				}

				if(!empty($col_queries['query_insert']))
				{
					$insert_queries[] = $col_queries['query_insert'];
				}

				if(count($insert_queries) > 0)
				{
					$query_insert = '
							insert into 
								#__quiz_answers (quiz_id, question_id, answer_type, title, sort_order, correct_answer, marks, image) 
							values 
								'.substr($row_queries['query_insert'].$col_queries['query_insert'], 0, - 1);

					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10012<br>query: ' . $query_insert . '<br><br>' );
						return false;
					}
				}

				/** return */
				return $qid;
					
			case CQ_GRID_CHECKBOX : // Grid Checkbox

				$grid_rows = $app->input->post->getArray(array('answers'=>'array'));
				$grid_columns = $app->input->post->getArray(array('columns'=>'array'));
				$grid_rows = $grid_rows['answers'];
				$grid_columns = $grid_columns['columns'];

				if ((count ( $grid_rows ) <= 0) || (count ( $grid_columns ) <= 0))
				{
					$this->setError ( JText::_ ( 'MSG_NO_CHOICES_FOUND' ) . ' Error code: 10010' );
					return false;
				}

				$update_queries = array();
				$insert_queries = array();
				$update_columns = Array(
						'title' => '`title` = CASE ', 
						'sort_order' => '`sort_order` = CASE ', 
						'correct_answer' => '`correct_answer` = CASE ',
						'marks' => '`marks` = CASE ',
						'image' => '`image` = CASE ');

				/************************** GET_QUERIES ********************************/

				$row_queries = $this->_get_answer_update_queries($update_columns, $grid_rows, $quiz_id.','.$qid.','.$this->_db->quote ( 'x' ));
				$col_queries = $this->_get_answer_update_queries($update_columns, $grid_columns, $quiz_id.','.$qid.','.$this->_db->quote ( 'y' ));

				/**************************** DELETIONS *******************************/
				$existing = array();
				$rows = array_merge($grid_rows, $grid_columns);

				foreach ($rows as $row)
				{
					$row = trim($row);

					if (!empty($row) && strpos($row, '_') !== false)
					{
						$tokens = explode('_', $row, 6);

						if(count($tokens) == 6)
						{
							$answer_id = (int) $tokens[0];

							if($answer_id > 0)
							{
								$existing[] = $answer_id;
							}
						}
					}
				}

				if(!empty($existing))
				{
					$query = 'delete from #__quiz_answers where quiz_id = '.$quiz_id.' and question_id = '.$qid.' and id not in('.implode(',', $existing).')';
					$this->_db->setQuery($query);

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10013<br>query: ' . $query . '<br><br>' );
						return false;
					}
				}

				/**************************** UPDATES *************************************/
				if(!empty($row_queries['query_update']))
				{
					$update_queries[] = $row_queries['query_update'];
				}

				if(!empty($col_queries['query_update']))
				{
					$update_queries[] = $col_queries['query_update'];
				}

				$update_ids = array_merge($row_queries['update_ids'], $col_queries['update_ids']);

				if(count($update_queries) > 0)
				{
					$query_update = '
						update 
							#__quiz_answers 
						set 
							'.implode(',', $update_queries).' 
						where 
							id in ('.implode(',', $update_ids).')';
					$this->_db->setQuery ( $query_update );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10011<br>query: ' . $query_update . '<br><br>' );
						return false;
					}
				}

				/**************************** INSERTS **********************************/

				if(!empty($row_queries['query_insert']))
				{
					$insert_queries[] = $row_queries['query_insert'];
				}

				if(!empty($col_queries['query_insert']))
				{
					$insert_queries[] = $col_queries['query_insert'];
				}

				if(count($insert_queries) > 0)
				{
					$query_insert = '
						insert into 
							#__quiz_answers (quiz_id, question_id, answer_type, title, sort_order, correct_answer, marks, image) 
						values 
							'.substr($row_queries['query_insert'].$col_queries['query_insert'], 0, - 1);
						
					$this->_db->setQuery ( $query_insert );

					if (! $this->_db->query ())
					{
						$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10012<br>query: ' . $query_insert . '<br><br>' );
						return false;
					}
				}

				/** return */
				return $qid;

			case CQ_FREE_TEXT_SINGLE_LINE : return $qid; // Free text - Single line
			case CQ_FREE_TEXT_MULTILINE : return $qid;// Free text - Multiline
			case CQ_FREE_TEXT_PASSWORD : return $qid;// Free text - Password
			case CQ_FREE_TEXT_RICH_TEXT : // Free text - Rich Text

				$query = 'insert into #__quiz_answers (quiz_id, question_id, answer_type) values ' . '(' . $quiz_id . ',' . $qid . ',' . $this->_db->quote ( 'text' ) . ')';
				$this->_db->setQuery ( $query );

				if (! $this->_db->query ())
				{
					$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10014<br>query: ' . $query . '<br><br>' );
					return false;
				}
				else
				{
					return $qid;
				}

				break;
		}
		//echo $query;
	}

	function get_question($qid){
			
		$query = '
    		select
    			id, quiz_id, question_type, page_number, sort_order, include_custom, mandatory, title, description, answer_explanation, orientation
    		from
    			#__quiz_questions
    		where
    			id='.$qid;
			
		$this->_db->setQuery($query);
		$question = $this->_db->loadObject();
			
		if(!empty($question)){

			$query = '
    			select
    				id, answer_type, title, correct_answer, image
    			from 
    				#__quiz_answers 
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

	function authorize_quiz($id){
		$user = JFactory::getUser();
		if($user->authorise('quiz.manage', Q_APP_NAME)){
			return true;
		}else{
			$query = 'select count(*) from #__quiz_quizzes where id='.$id.' and created_by='.$user->id;
			$this->_db->setQuery($query);
			$count = (int)$this->_db->loadResult();
			if($count){
				return true;
			}
		}
		return false;
	}

	function delete_question($quiz_id, $pid, $qid, $uniq_id, $page_number){

		if(!$this->authorize_quiz($quiz_id)){

			$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10050<br>query: ' . $query . '<br><br>' );

			return false;
		}

		$query = 'delete from #__quiz_answers where question_id='.$qid;
		$this->_db->setQuery($query);

		if($this->_db->query()){

			$query = 'delete from #__quiz_questions where id='.$qid;
			$this->_db->setQuery($query);

			if($this->_db->query()){

				$query = "select id from #__quiz_questions where quiz_id=0 and page_number='".$page_number."' and uniq_key = '" .$uniq_id. "' order by sort_order asc";
				$this->_db->setQuery($query);
				$questions = APP_VERSION == '1.5' ? $this->_db->loadResultArray() : $this->_db->loadColumn();
				$order = 1;

				foreach ($questions as $question){

					$query = 'update #__quiz_questions set sort_order='.$order.' where id='.$question;
					$this->_db->setQuery($query);
					$this->_db->query();
					$order++;
				}

				return true;
			}
		}

		$this->setError('DB Error: '.$this->_db->getErrorMsg());

		return false;
	}

	function get_question_details($uniq_key, $question_id, $page_id=0, $quiz_id=0){
			
		$user = JFactory::getUser ();
		$where = '';

		if($page_id){
			$where = " page_number= '". $page_id."' and uniq_key = '".$uniq_key."' and question_id = '".$question_id."' " ;
		}
		$query = '
			select 
				id, quiz_id, title, description, answer_explanation, question_type, page_number, 
				responses, sort_order, mandatory, orientation 
			from 
				#__quiz_questions 
			where 
				'.$where.' 
			order by 
				page_number, sort_order asc';
		$this->_db->setQuery ( $query );
		$questions = $this->_db->loadObjectList ( 'id' );

		if ($questions) {
			foreach ($questions as $question) {
				$query = '
					select 
						*
					from 
						#__quiz_answers 
					where question_id = '.$question->id.' 
					order by 
						id asc';
				$this->_db->setQuery ( $query );
				$answers = $this->_db->loadObjectList ();
				if ($answers && (count ( $answers ) > 0)) {
					//foreach ( $answers as $answer ) {
					//	$questions [$answer->question_id]->answers [] = $answer;
					//}
					$question->answers = $answers;

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

	function create_edit_quiz(){
			
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$config = JComponentHelper::getParams(Q_APP_NAME);
			
		$quiz = new stdClass();
		$quiz->id = $app->input->post->getInt('id', 0);
		$quiz->title = $app->input->post->getString('title', null);
		$quiz->alias = $app->input->post->getString('alias', null);
		$quiz->catid = $app->input->post->getInt('catid', 0);
		$quiz->show_answers = $app->input->post->getInt('show-result', 0);
		$quiz->show_template = $app->input->post->getInt('show-template', 1);
		$quiz->multiple_responses = $app->input->post->getInt('multiple-responses', 1);
		$quiz->duration = $app->input->post->getInt('duration', 0);
		$quiz->skip_intro = $app->input->post->getInt('skip_intro', 0);
		$quiz->cutoff = $app->input->post->getInt('cutoff', 0);
		$quiz->tags = $app->input->post->getString('tags', '');
		$quiz->description =  JRequest::getVar('description');
		//CJFunctions::get_clean_var('description', ($user->authorise('quiz.wysiwyg', Q_APP_NAME) && $config->get('default_editor', 'bbcode') == 'default'));
			
		if(empty($quiz->title) || empty($quiz->catid)){

			JFactory::getApplication()->enqueueMessage(JText::_('MSG_REQUIRED_FIELDS_MISSING'));

			return false;
		}

		if(empty($quiz->alias)){

			//$quiz->alias = CJFunctions::get_unicode_alias($quiz->title);
		}

		$ip_address = '0.0.0.0';//CJFunctions::get_user_ip_address();
		$query = '';

		if($quiz->id){

			if(!$this->authorize_quiz($quiz->id)){

				return false;
			}

			$query = '
				update 
					#__quiz_quizzes 
				set
					title = '.$this->_db->quote($quiz->title) . ', 
					alias = '.$this->_db->quote($quiz->alias) . ',
					description = '.$this->_db->quote($quiz->description) . ',
					catid = '.$quiz->catid . ',
					duration = '.$quiz->duration.',
					show_answers = '.$quiz->show_answers . ',
					show_template = '.$quiz->show_template . ',
					multiple_responses = '.$quiz->multiple_responses.',
					skip_intro = '.$quiz->skip_intro.', 
					cutoff = '.$quiz->cutoff.',
					package_id = \''.JRequest::getVar('package_id').'\'
				where 
					id='.$quiz->id;
		}else{

			$createdate = JFactory::getDate()->toSql();

			$query = '
				insert into 
					#__quiz_quizzes(title, alias, description, created, catid, created_by, show_answers, show_template, multiple_responses, skip_intro, cutoff, ip_address, duration, published, package_id) 
				values ('. 
			$this->_db->quote($quiz->title).','.
			$this->_db->quote($quiz->alias).','.
			$this->_db->quote($quiz->description).','.
			$this->_db->quote($createdate).','.
			$quiz->catid.','.
			$user->id.','.
			$quiz->show_answers.','.
			$quiz->show_template.','.
			$quiz->multiple_responses.','.
			$quiz->skip_intro.','.
			$quiz->cutoff.','.
			$this->_db->quote($ip_address).','.
			$quiz->duration.', 2, \''.JRequest::getVar('package_id').'\')';
		}

		$this->_db->setQuery($query);

		if($this->_db->query()){

			if(!$quiz->id){ //New quiz

				$quiz->id = $this->_db->insertid();
				//$this->create_page($quiz->id);

				// 				if($user->authorise('quiz.autoapprove', Q_APP_NAME)){
					
				// 					$query = 'update #__quiz_categories set quizzes = quizzes + 1 where id='.$quiz->catid;
				// 					$this->_db->setQuery($query);
				// 					$this->_db->query();
				// 				}
			}

			if(!empty($quiz->tags)) $this->insert_tags($quiz->id, $quiz->tags);

			return $quiz;
		}

		return false;
	}

	function update_all_process($quiz_id, $uniq_id) {
		$query = " update #__quiz_pages set quiz_id = '".$quiz_id."' where uniq_key = '".$uniq_id."' ";
		$this->_db->setQuery ( $query );
		if($this->_db->query ()) {
			$query = " update #__quiz_question_giftcode set quiz_id = '".$quiz_id."' where uniq_key = '".$uniq_id."' ";
			$this->_db->setQuery ( $query );
			if($this->_db->query ()) {
				$query = " update #__quiz_questions set quiz_id = '".$quiz_id."' where uniq_key = '".$uniq_id."' ";
				$this->_db->setQuery ( $query );
				if($this->_db->query ()) {
					$query = "
							update #__quiz_answers set quiz_id = '".$quiz_id."' where question_id in (
							select id from #__quiz_questions where quiz_id = '".$quiz_id."')
						";
					$this->_db->setQuery ( $query );
					$this->_db->query ();
					return true;
				}
			}
		}
		return false;
	}

	function _get_answer_update_queries($update_columns, $values, $fields)
	{
		$query_insert = '';
		$query_update = '';
		$update_ids = array();

		foreach ( $values as $choice )
		{
			$choice = trim($choice);

			if (!empty($choice) && strpos($choice, '_') !== false)
			{
				$tokens = explode('_', $choice, 6);

				if(count($tokens) == 6)
				{
					$answer_id = (int) $tokens[0];
					$sort_order = (int) $tokens[1];
					$correct_answer = (int) $tokens[2];
					$marks = (float) $tokens[3];
					$image = (empty($tokens[4]) ? 'null' : $this->_db->quote($tokens[4]));

					if($answer_id > 0)
					{
						$update_columns['title'] .= "when `id`='".$answer_id. "' THEN " . $this->_db->quote($tokens[5]) . " ";
						$update_columns['sort_order'] .= "when `id`='".$answer_id. "' THEN '" . $sort_order . "' ";
						$update_columns['correct_answer'] .= "when `id`='".$answer_id. "' THEN '" . $correct_answer . "' ";
						$update_columns['marks'] .= "when `id`='".$answer_id. "' THEN '" . $marks . "' ";
						$update_columns['image'] .= "when `id`='".$answer_id. "' THEN " . $image . " ";

						$update_ids[] = $answer_id;
					}
					else
					{
						$query_insert = $query_insert . '('.$fields.','.$this->_db->quote ( $tokens[5] ) .','.$sort_order.','.$correct_answer.','.$marks.','.$image.'),';
					}
				}
			}
		}

		foreach($update_columns as $column_name => $query_part)
		{
			$update_columns[$column_name] .= " ELSE `$column_name` END ";
		}

		if(count($update_ids) > 0)
		{
			$query_update = implode(', ', $update_columns);
		}

		return array('query_insert'=>$query_insert, 'query_update'=>$query_update, 'update_ids'=>$update_ids);
	}

	public function get_tags_by_itemids($ids){

		$query = '
			select
				map.item_id,
				tag.id as tag_id, tag.tag_text, tag.alias, tag.description
			from
				#__quiz_tagmap map
			left join
				#__quiz_tags tag on tag.id = map.tag_id
			where
				map.item_id in ('.implode(',', $ids).')';

		$this->_db->setQuery($query);
		$tags = $this->_db->loadObjectList();

		return !empty($tags) ? $tags : array();
	}

	function get_quiz_statistics($id){

		$stats = new stdClass();
		$params = JComponentHelper::getParams(Q_APP_NAME);

		$query = 'select responses from #__quiz_quizzes where id = '.$id;
		$this->_db->setQuery($query);
		$stats->completed = $this->_db->loadResult();

		$query = 'select count(*) from #__quiz_responses where quiz_id = '.$id;
		$this->_db->setQuery($query);
		$stats->responses = $this->_db->loadResult();

		$query = 'select count(distinct(browser_info)) from #__quiz_responses where quiz_id = '.$id;
		$this->_db->setQuery($query);
		$stats->browsers = $this->_db->loadResult();

		$query = 'select count(distinct(country)) from #__quiz_responses where quiz_id = '.$id;
		$this->_db->setQuery($query);
		$stats->countries = $this->_db->loadResult();

		$query = 'select count(distinct(os)) from #__quiz_responses where quiz_id = '.$id;
		$this->_db->setQuery($query);
		$stats->oses = $this->_db->loadResult();

		$query = '
				select
					r.created_by, r.created, r.finished, r.score, c.country_name, r.browser_info, r.city, r.os,
				 	u.'.$params->get('user_display_name', 'name').' as username
				from 
					#__quiz_responses r
				 left join
				 	#__quiz_countries c on r.country = c.country_code
				left join
				 	#__users u on r.created_by = u.id
				 where
				 	r.quiz_id = '.$id.' and r.completed = 1 and r.finished > r.created
				 order by
				 	r.score desc';
		$this->_db->setQuery($query, 0, 10);
		$topscorers = $this->_db->loadObjectList();
		$stats->topscorers = !empty($topscorers) ? $topscorers : array();

		return $stats;
	}

	public function get_consolidated_report($id) {

		$user = JFactory::getUser();

		$quiz = $this->get_quiz_details($id, false, true);
		$questions = $this->get_questions($id, 0, false); // don't get anwsers attached, we calculate them here.
		if (!empty($questions)) {

			$query = '
				select
					question_id, answer_id, column_id, count(*) as votes
				from
					#__quiz_response_details
				where
					question_id in (select id from #__quiz_questions where quiz_id='.$id.')
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
					id, question_id, answer_type, title
				from
					#__quiz_answers
				where
					quiz_id = '.$id.' and question_id in (select question_id from #__quiz_questions where quiz_id='.$id.' )
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

					$questions[$answer->question_id]->total_votes += $total_votes;
				}

				$quiz->questions = $questions;

				return $quiz;
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

	function get_responses($quiz_id = 0, $userid = 0){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams(Q_APP_NAME);
			
		$filter_order = $app->getUserStateFromRequest( Q_APP_NAME.'.responses.filter_order', 'filter_order', 'r.created', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( Q_APP_NAME.'.responses.filter_order_dir', 'filter_order_Dir', 'desc', 'word' );
		$limitstart = $app->getUserStateFromRequest( Q_APP_NAME.'.responses.limitstart', 'start', 0, 'int' );
		$limit = $app->getUserStateFromRequest(Q_APP_NAME.'.responses.limit', 'limit', 50, 'int');
		$limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0;
			
		$catid = $app->input->post->getInt('catid', 0);
		$state = $app->input->post->getInt('state', 3);
		$search = $app->input->post->getString('search', '');
			
		$wheres = array();
		$return = array();
			
		if($quiz_id){

			$wheres[] = 'r.quiz_id = '.$quiz_id;
		}
			
		if($catid){

			$wheres[] = 'a.catid = '.$catid;
		}
			
		if($userid){

			$wheres[] = 'r.created_by = '.$userid;
		}
			
		if($state >= 0 && $state < 3){

			$wheres[] = 'r.completed = '.$state;
		}
			
		if(!empty($search)){

			$wheres[] = 'u.name like \'%'.$this->_db->escape($search).'%\' or u.username like \'%'.$this->_db->escape($search).'%\'';
		}
			
		$where = ((count($wheres) > 0) ? ' where ('.implode(' ) and ( ', $wheres).')' : '');
		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

		$result = new stdClass();

		$query = '
			select 
				a.id, a.title, a.alias, a.description, a.catid, a.created_by, a.created, a.responses, a.show_answers, a.ip_address, a.show_template, a.published, rtg.rating,
				c.title as category_name, c.alias as category_alias, 
				case when r.created_by > 0 then u.'.$params->get('user_display_name', 'name').' else \''.JText::_('LBL_GUEST').'\' end as username, 
				r.id as response_id, r.created as responded_on, r.finished, r.score, r.completed, r.finished - r.created as time_taken,
				cr.country_name
			from 
				#__quiz_responses r
			left join
				#__quiz_quizzes a on a.id = r.quiz_id 
			left join 
				#__quiz_categories c ON a.catid = c.id
			left join
				#__quiz_countries cr on r.country = cr.country_code 
			left join 
				#__corejoomla_rating rtg on rtg.asset_id='.CQ_ASSET_ID.' and rtg.item_id=a.id
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
					#__quiz_responses r 
				left join 
					#__quiz_quizzes a on a.id = r.quiz_id
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

	function get_location_report($id){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams(Q_APP_NAME);
			
		$filter_order = $app->getUserStateFromRequest( Q_APP_NAME.'.locations.filter_order', 'filter_order', 'a.country, a.city', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( Q_APP_NAME.'.locations.filter_order_dir', 'filter_order_Dir', 'asc', 'word' );
		$limitstart = $app->getUserStateFromRequest( Q_APP_NAME.'.locations.limitstart', 'start', 0, 'int' );
		$limit = $app->getUserStateFromRequest(Q_APP_NAME.'.locations.limit', 'limit', 50, 'int');
		$limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0;
			
		$search = $app->input->post->getString('search', '');
			
		$wheres = array();
		$return = array();
		$wheres[] = 'a.quiz_id = '.$id;
			
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
				#__quiz_responses a
			left join
				#__quiz_countries c on a.country = c.country_code
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
						#__quiz_responses a
					left join
						#__quiz_countries c on a.country = c.country_code
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

	function get_device_report($id){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams(Q_APP_NAME);
			
		$filter_order = $app->getUserStateFromRequest( Q_APP_NAME.'.devices.filter_order', 'filter_order', 'a.browser_info', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( Q_APP_NAME.'.devices.filter_order_dir', 'filter_order_Dir', 'asc', 'word' );
		$limitstart = $app->getUserStateFromRequest( Q_APP_NAME.'.devices.limitstart', 'start', 0, 'int' );
		$limit = $app->getUserStateFromRequest(Q_APP_NAME.'.devices.limit', 'limit', 50, 'int');
		$limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0;
			
		$search = $app->input->post->getString('search', '');
			
		$wheres = array();
		$return = array();
		$wheres[] = 'a.quiz_id = '.$id;
			
		if(!empty($search)){

			$wheres[] = 'a.browser_info like \'%'.$this->_db->escape($search).'%\'';
		}
			
		$where = implode(' ) and ( ', $wheres);
		$order = $filter_order . ' ' . $filter_order_dir;

		$result = new stdClass();

		$query = '
			select
				count(*) as responses, a.browser_info
			from
				#__quiz_responses a
			where
				'.$where.'
			group by
				a.browser_info
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
						#__quiz_responses a
					where
						'.$where.'
					group by
						a.browser_info
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

	function get_os_report($id){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams(Q_APP_NAME);
			
		$filter_order = $app->getUserStateFromRequest( Q_APP_NAME.'.devices.filter_order', 'filter_order', 'a.os', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( Q_APP_NAME.'.devices.filter_order_dir', 'filter_order_Dir', 'asc', 'word' );
		$limitstart = $app->getUserStateFromRequest( Q_APP_NAME.'.devices.limitstart', 'start', 0, 'int' );
		$limit = $app->getUserStateFromRequest(Q_APP_NAME.'.devices.limit', 'limit', 50, 'int');
		$limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0;
			
		$search = $app->input->post->getString('search', '');
			
		$wheres = array();
		$return = array();
		$wheres[] = 'a.quiz_id = '.$id;
			
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
				#__quiz_responses a
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
						#__quiz_responses a
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

	function get_reponse_data_for_csv($id){

		$user = JFactory::getUser();

		$query = 'select created_by from #__quiz_quizzes where id='.$id;
		$this->_db->setQuery($query);
		$quiz = $this->_db->loadObject();

		if(($quiz->created_by != $user->id) && !$user->authorise('quiz.manage', Q_APP_NAME)) {
				
			$this->setError ( 'Error: 10295 - '.JText::_('MSG_ERROR_PROCESSING') );
			return false;
		}

		$query = '
			select 
				r.response_id, r.question_id, q.title as question, a.title as answer, b.title as answer2, r.free_text from #__quiz_response_details r 
			left join 
				#__quiz_questions q on r.question_id = q.id 
			left join 
				#__quiz_answers a on r.answer_id = a.id 
			left join 
				#__quiz_answers b on r.column_id = b.id 
			where 
				r.response_id in (
					select 
						id 
					from 
						#__quiz_responses 
					where 
						quiz_id='.$id.'
				) 
			order by 
				r.response_id';

		$this->_db->setQuery($query);
		$entries = $this->_db->loadObjectList();

		$query = '
			select 
				q.id, q.title 
			from 
				#__quiz_questions q 
			where 
				q.quiz_id='.$id. ' 
			order by 
				page_number, sort_order';

		$this->_db->setQuery($query);
		$questions = $this->_db->loadObjectList();

		$query = '
			select 
				r.id, r.created_by, r.created, u.username, u.name 
			from 
				#__quiz_responses r 
			left join 
				#__users u on r.created_by = u.id 
			where 
				r.quiz_id='.$id;

		$this->_db->setQuery($query);
		$responses = $this->_db->loadObjectList();

		$return = new stdClass();

		$return->entries = $entries;
		$return->questions = $questions;
		$return->responses = $responses;

		return $return;
	}

	function move_question($uniq_id, $pid) {
		if(!$this->authorize_quiz($quiz_id)){
			$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10030<br>query: ' . $query . '<br><br>' );
			return false;
		}
		$query = 'update #__quiz_questions set page_number='.$pid.' where uniq_key = '. $uniq_id;
		$this->_db->setQuery($query);
		if($this->_db->query()){
			return true;
		}

		$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10031<br>query: ' . $query . '<br><br>' );

		return false;
	}

	function registered_quiz($package_id){
		$db 	= JFactory::getDBO();

		$query 	= $db->getQuery(TRUE);

		$query->select("*");

		$query->from("#__quiz_quizzes");

		$query->where("package_id='".$package_id."'");

		$db->setQuery($query);

		return count($db->loadObjectList());
	}

}
?>