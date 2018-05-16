<?php
/**
 * @version		$Id: survey.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.models
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
 
defined('_JEXEC') or die();

jimport('joomla.application.component.modelitem');
require_once JPATH_COMPONENT . '/helpers/awardpackage.php';

class AwardpackageUsersModelSurvey extends JModelLegacy {

	protected $_error = '';

	function __construct() {

		parent::__construct();
	}

	public function getError($i = null, $toString = true){

		return $this->_error;
	}

	public function setError($error){

		$this->_error = $error;
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

		function get_question_list($id){
		$query = '
    		select
    			*
    		from
    			#__survey_questions
    		where
    			survey_id='.$id;
			
		$this->_db->setQuery($query);
			$answers = $this->_db->loadObjectList();
		return $answers;
		}


	public function authorize_survey_response($rid, $key, $userid){

		$query = 'select count(*) from #__survey_responses where id = '.$rid.' and created_by = '.$userid.' and survey_key = '.$this->_db->quote($key);
		$this->_db->setQuery($query);
		$count = $this->_db->loadResult();

		if(!$count) $this->setError($this->_db->getErrorMsg());

		return $count > 0;
	}

	public function get_surveys($action = 1, $options = array(), $params = null, $packageId){

		$user = JFactory::getUser();

		$return = array();
		$wheres = array();

		$order = !empty($options['order']) ? $options['order'] : 'a.created';
		$order_dir = !empty($options['order_dir']) ? $options['order_dir'] : 'desc';
		$limitstart = !empty($options['limitstart']) ? $options['limitstart'] : 0;
		$limit = !empty($options['limit']) ? $options['limit'] : 20;

		if(!empty($options['userid'])){

			if(is_array($options['userid'])){

				$wheres[] = 'a.created_by in ('.implode(',', $options['userid']).')';
			} else if($options['userid'] > 0){

				$wheres[] = 'a.created_by = '.$options['userid'];
			}
		}


		if(!empty($options['catid'])){

			if(is_array($options['catid'])){

				$wheres[] = 'a.catid in ('.implode(',', $options['catid']).')';
			} else if($options['catid'] > 0){

				$wheres[] = 'a.catid = '.$options['catid'];
			}
		}

	//	$wheres[] = (!empty($options['userid']) || $action == 3) ? 'a.published >= 0' : 'a.published = 1';
		$wheres[] = !empty($options['userid']) ? 'a.published >= 0' : 'a.published = 1';


		switch ($action){

			case 1: // Latest surveys

				//$wheres[] = ' a.private_survey = 0';
				$order = 'a.created';
				break;

			case 2: // Most popular

				//$wheres[] = ' a.private_survey = 0';
				$wheres[] = ' a.responses > 1';
				$order = 'a.responses';
				break;

			case 3:

				$wheres[] = ' a.package_id='.$packageId;
				$order = 'a.created';
				break;

			case 4: // User surveys

				$wheres[] = ' a.created_by='.$user->id;
				break;

			case 5: // User responses

				$wheres[] = 'a.id in (select r.survey_id from #__survey_responses r where r.created_by = '.$user->id.')';
				$order = 'a.created';
				break;

			case 7: //related/search surveys

				$wheres[] = ' a.private_survey = 0';
				$search_params = $options['search_params'];

				if($search_params['type'] == 1){ // search user responded surveyzes

					$wheres[] = 'a.id in (select r.survey_id from #__survey_responses r where r.created_by = '.$user->id.')';
				} // else search all survey

				if(!empty($search_params['q'])){

					$keywords = explode(' ', $search_params['q']);
					$stopwords = ",a's,accordingly,again,allows,also,amongst,anybody,anyways,appropriate,aside,available,because,before,below,between,by,can't,certain,com,consider,corresponding,definitely,different,don't,each,else,et,everybody,exactly,fifth,follows,four,gets,goes,greetings,has,he,her,herein,him,how,i'm,immediate,indicate,instead,it,itself,know,later,lest,likely,ltd,me,more,must,nd,needs,next,none,nothing,of,okay,ones,others,ourselves,own,placed,probably,rather,regarding,right,saying,seeing,seen,serious,she,so,something,soon,still,t's,th,that,theirs,there,therein,they'd,third,though,thus,toward,try,under,unto,used,value,vs,way,we've,weren't,whence,whereas,whether,who's,why,within,wouldn't,you'll,yourself ,able,across,against,almost,although,an,anyhow,anywhere,are,ask,away,become,beforehand,beside,beyond,c'mon,cannot,certainly,come,considering,could,described,do,done,edu,elsewhere,etc,everyone,example,first,for,from,getting,going,had,hasn't,he's,here,hereupon,himself,howbeit,i've,in,indicated,into,it'd,just,known,latter,let,little,mainly,mean,moreover,my,near,neither,nine,noone,novel,off,old,only,otherwise,out,particular,please,provides,rd,regardless,said,says,seem,self,seriously,should,some,sometime,sorry,sub,take,than,that's,them,there's,theres,they'll,this,three,to,towards,trying,unfortunately,up,useful,various,want,we,welcome,what,whenever,whereby,which,whoever,will,without,yes,you're,yourselves,about,actually,ain't,alone,always,and,anyone,apart,aren't,asking,awfully,becomes,behind,besides,both,c's,cant,changes,comes,contain,couldn't,despite,does,down,eg,enough,even,everything,except,five,former,further,given,gone,hadn't,have,hello,here's,hers,his,however,ie,inasmuch,indicates,inward,it'll,keep,knows,latterly,let's,look,many,meanwhile,most,myself,nearly,never,no,nor,now,often,on,onto,ought,outside,particularly,plus,que,re,regards,same,second,seemed,selves,seven,shouldn't,somebody,sometimes,specified,such,taken,thank,thats,themselves,thereafter,thereupon,they're,thorough,through,together,tried,twice,unless,upon,uses,very,wants,we'd,well,what's,where,wherein,while,whole,willing,won't,yet,you've,zero,above,after,all,along,am,another,anything,appear,around,associated,be,becoming,being,best,brief,came,cause,clearly,concerning,containing,course,did,doesn't,downwards,eight,entirely,ever,everywhere,far,followed,formerly,furthermore,gives,got,happens,haven't,help,hereafter,herself,hither,i'd,if,inc,inner,is,it's,keeps,last,least,like,looking,may,merely,mostly,name,necessary,nevertheless,nobody,normally,nowhere,oh,once,or,our,over,per,possible,quite,really,relatively,saw,secondly,seeming,sensible,several,since,somehow,somewhat,specify,sup,tell,thanks,the,then,thereby,these,they've,thoroughly,throughout,too,tries,two,unlikely,us,using,via,was,we'll,went,whatever,where's,whereupon,whither,whom,wish,wonder,you,your,according,afterwards,allow,already,among,any,anyway,appreciate,as,at,became,been,believe,better,but,can,causes,co,consequently,contains,currently,didn't,doing,during,either,especially,every,ex,few,following,forth,get,go,gotten,hardly,having,hence,hereby,hi,hopefully,i'll,ignored,indeed,insofar,isn't,its,kept,lately,less,liked,looks,maybe,might,much,namely,need,new,non,not,obviously,ok,one,other,ours,overall,perhaps,presumably,qv,reasonably,respectively,say,see,seems,sent,shall,six,someone,somewhere,specifying,sure,tends,thanx,their,thence,therefore,they,think,those,thru,took,truly,un,until,use,usually,viz,wasn't,we're,were,when,whereafter,wherever,who,whose,with,would,you'd,yours,";
					$wheres2 = array();

					foreach ($keywords as $keyword){

						$keyword = preg_replace('/[^\p{L}\p{N}\s]/u', '', $keyword);

						if(strlen($keyword) > 2 && strpos($stopwords, ','.$keyword.',') === false){

							if($search_params['qt'] == 1){ // search titles and description

								$wheres2[] = '
										a.title rlike \'[[:<:]]'.$this->_db->escape($keyword).'[[:>:]]\' or
										a.description rlike \'[[:<:]]'.$this->_db->escape($keyword).'[[:>:]]\'';
							} else { // titles only

								$wheres2[] = 'a.title rlike \'[[:<:]]'.$this->_db->escape($keyword).'[[:>:]]\'';
							}
						}
					}

					if(!empty($wheres2)){

						$wheres[] = $search_params['all'] == 1 ? '('.implode(') and (', $wheres2).')' : '('.implode(') or (', $wheres2).')';
					}
				}

				if(!empty($search_params['u'])){

					if($search_params['m'] == 0){

						$wheres[] = 'u.username like \'%'.$this->_db->escape($search_params['u']).'%\'';
					} else {

						$wheres[] = 'u.username = '.$this->_db->quote($search_params['u']);
					}
				}

				break;
		}

		$language = JFactory::getLanguage();
		//$wheres[] = 'c.'.$this->_db->quoteName('language').' in (' . $this->_db->quote( $language->getTag() ) . ',' . $this->_db->quote('*') . ')';
		$where = '('.implode(' ) and (', $wheres).')';

		$users = AwardPackageHelper::getUserData();

		$where .= ' and a.package_id = \'' .$users->package_id. '\' ';

		$query = '
				select 
					a.id, a.title, a.alias,a.package_id,  a.introtext, a.created, a.responses, a.published, a.publish_up, a.publish_down, a.private_survey,
					a.max_responses, a.anonymous, a.public_permissions, a.survey_key, a.redirect_url, a.display_template, a.skip_intro,
					 a.ip_address, 
					c.id as catid, c.title as category_title, c.alias as category_alias,
					u.id as created_by, u.username as created_by_alias, u.email, u.'.$params->get('user_display_name', 'name').' as username,
					e.id as question_id, e.complete_giftcode, e.complete_giftcode_quantity, e.complete_giftcode_cost_response,
					e.incomplete_giftcode, e.incomplete_giftcode_quantity, e.incomplete_giftcode_cost_response
				from
					#__survey as a
				left join
					#__survey_categories as c on a.catid = c.id
				left join
					#__users as u on a.created_by = u.id
				left join
					#__survey_question_giftcode as e on e.survey_id = a.id	
				where
					'.$where.'
				order by
					'.$order.' '.$order_dir;

		$this->_db->setQuery($query, $limitstart, $limit);
		$return['surveys'] = $this->_db->loadObjectList('id');

		/************ pagination *****************/
		$query = '
				select
					count(*)
				from
					#__survey as a
				left join
					#__survey_categories c on a.catid = c.id
				left join
					#__users as u on a.created_by = u.id
				where
					'.$where;

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();


		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
		/************ pagination *****************/
		$query = "delete from #__survey_question_giftcode where complete_giftcode = 'NEW' ";
		$this->_db->setQuery($query);
		if(!$this->_db->query()){
			return false;
			}
		$query = "delete from #__survey_pages where sid = 0 ";
		$this->_db->setQuery($query);
		if(!$this->_db->query()){
			return false;
			}	

		$return['state'] = array('order'=>$order, 'order_dir'=>$order_dir);

		return $return;
	}

	 function get_survey_details($id){
	 	 
		$query = '
    			SELECT
        			a.id, a.title, a.alias, a.created_by, a.created, a.catid, a.published, a.responses, a.skip_intro, a.ip_address, 
    				a.survey_key, a.publish_up, a.publish_down, a.private_survey, a.max_responses, a.anonymous, a.public_permissions, 
    				a.display_template, a.introtext, a.endtext, a.custom_header, a.redirect_url,
        			c.title as category, c.alias as calias,
					u.name, u.username, u.email
        		FROM
        			#__survey a
        		LEFT JOIN
        			#__survey_categories c ON a.catid = c.id
        		LEFT JOIN
        			#__users u ON a.created_by = u.id
    			WHERE
    				a.id='.$id;
			
		$this->_db->setQuery($query);
		$results = $this->_db->loadObject();

		return $results;
	}

    function get_question2($sid){
			
		$query = '
    		select
    			*
    		from
    			#__survey_questions
    		where
    			survey_id='.$sid;
			
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		return $result;
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
					id, question_id, answer_type, answer_label, sort_order 
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

	function create_edit_survey() {

	 $query = " DELETE FROM #__survey_question_giftcode WHERE complete_giftcode = 'NEW' ";
	 $this->_db->setQuery($query);
	
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

			$survey->key = CJFunctions::generate_random_key();
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

	function move_question($survey_id, $qid, $pid) {
		if(!$this->authorize_survey($survey_id)){
			$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10030<br>query: ' . $query . '<br><br>' );
			return false;
		}
		return true;
		$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10031<br>query: ' . $query . '<br><br>' );
		return false;
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

	function get_pages_list($survey_id){
			
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
			
		$query->select('id, title, sort_order,uniq_key')->from('#__survey_pages')->where('sid = '.$survey_id)->order('sort_order asc');
		$db->setQuery($query);
			
		try{

			$pages = $db->loadObjectList();
			return $pages;
		} catch(Exception $e){

			return false;
		}
			
		return false;
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

	function save_question() {
		$app = JFactory::getApplication();
		$user = JFactory::getUser ();
		$post = $_SESSION['surveys'];

		$params = JComponentHelper::getParams(S_APP_NAME);
		$query = '';

		$survey_id = $post['id'];
		$task = $_GET['task'];
		if (!empty($_GET['id'])){
			$survey_id =$_GET['id'];
		}
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
		$uniq_id = $post['uniq_id'];//JRequest::getVar('uniq_id');		
		$questionSelectedId = $post['uniq_id'];
		if ($qtype > 16 || $qtype <= 0 || $user->guest || strlen ( $title ) <= 0) {

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

						$query_insert = 'insert into #__survey_answers (survey_id, question_id, answer_type, answer_label, sort_order, image) values '.substr($queries['query_insert'], 0, - 1);
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
					$image = trim($tokens[3]);

					$image = ( !empty ( $image ) ) ? $this->_db->quote( $image ) : 'null';

					if($answer_id > 0){

						$update_columns['answer_label'] .= "when `id`='".$answer_id. "' THEN " . $this->_db->quote($tokens[2]) . " ";
						$update_columns['sort_order'] .= "when `id`='".$answer_id. "' THEN '" . $sort_order . "' ";
						$update_columns['image'] .= "when `id`='".$answer_id. "' THEN " . $image . " ";

						$update_ids[] = $answer_id;
					}else{

						$query_insert = $query_insert . '('.$fields.','.$this->_db->quote ( $tokens[2] ) .','.$sort_order.','.$image.'),';
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

	function get_contact_groups($user_id){

		$query = 'select id, name, contacts from #__survey_contactgroups where created_by='.$user_id;
		$this->_db->setQuery($query);
		$groups = $this->_db->loadObjectList();

		return !empty($groups) ? $groups : array();
	}

	function get_contacts($user_id, $type = 1, $gid = 0, $include_emails = false, $limitstart = 0, $limit = 20, $search = null){

		$query = 'select id, name '.($include_emails ? ',email' : '').' from #__survey_contacts where created_by = '.$user_id;

		if($search){

			$query = $query.' and (name like \'%'.$this->_db->escape($search).'%\' or email like \'%'.$this->_db->escape($search).'%\')';
		}

		switch($type){

			case 1: // unassigned

				$query = $query . ' and id not in (select contact_id from #__survey_contact_group_map where group_id = '.$gid.') order by name asc';
				$this->_db->setQuery($query);

				break;

			case 2: // assigned

				$query = $query . ' and id in (select contact_id from #__survey_contact_group_map where group_id = '.$gid.') order by name asc';
				$this->_db->setQuery($query);

				break;

			case 3: // all

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

	public function update_contact_keys($contacts){

		if(empty($contacts)) return false;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$cases = array();

		foreach ($contacts as $contact){

			$cases[] = 'when key_name = '.$db->q($contact->key).' then '.$contact->id;
		}

		$query->update('#__survey_keys')->set('contact_id = case '.implode(' ', $cases).' end');
		$db->setQuery($query);

		try {

			$db->execute();
			return true;
		} catch (Exception $e){

			return false;
		}
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

	function create_survey_keys($sid, $count, $unlimited = false, $skip_user_check = false){

		$user = JFactory::getUser();

		$query = 'select title, created_by from #__survey where id='.$sid;
		$this->_db->setQuery($query);
		$survey = $this->_db->loadObject();

		//if(!$skip_user_check && (!$survey || ($survey->created_by != $user->id)) && !$user->authorise('core.manage', S_APP_NAME)){
		//	return false;
		//}

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

	function use_credits($userid, $credits, $info){

		$params = JComponentHelper::getParams(S_APP_NAME);
		$points_per_credit = (int)$params->get('points_per_credit', 0);
		$points = -($credits * $points_per_credit);

		$function = '';

		switch ($params->get('points_system', 'none')){

			case 'cjblog':
			case 'jomsocial':

				$function = 'com_communitysurveys.credits';
				break;

			case 'aup':

				$function = 'sysplgaup_survey_credits';
				break;

			default:
				return false;
		}

		CJFunctions::award_points($params->get('points_system', 'none'), $userid, array('points'=>$points, 'reference'=>'', 'info'=>$info, 'function'=>$function));
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

	/**
	 * @deprecated use do_create_or_update_response instead
	 */
	function do_create_update_response() {

		$app = JFactory::getApplication ();
		$user = JFactory::getUser ();
		$config = JComponentHelper::getParams(S_APP_NAME);

		$sid = $app->input->getInt('id', 0);

		$key = trim($app->input->getCmd('key', null));
		$skey = trim($app->input->getCmd('skey', null));

		$response_id = 0;
		$obj_response = null;

		if (empty ( $skey ) && empty ( $sid ) && empty($key)) {

			$this->setError ( 'Error: 10301 - '.JText::_('MSG_SURVEY_NOT_FOUND') );
			return false;
		}

		if(!$sid) {

			if(!empty($key)){

				$query = 'select survey_id from #__survey_keys where key_name=' . $this->_db->quote ( $key );
				$this->_db->setQuery ( $query );
				$sid = $this->_db->loadResult();
			}else if(!empty($skey)){
					
				$query = 'select id from #__survey where survey_key='.$this->_db->quote($skey);
				$this->_db->setQuery($query);
				$sid = $this->_db->loadResult();
			}
		}

		// Get the survey.
		$survey = $this->get_survey_details( $sid, 0, false, true, true );

		if(empty($survey) || !$survey->id){

			// Looks like a deleted survey or some error occurred
			$this->setError ( 'Error: 10305 - '.JText::_('MSG_ERROR_PROCESSING') );
			return false;
		}

		if($survey->private_survey && (empty($key) && empty($skey))){

			$this->setError ( JText::_('MSG_PRIVATE_SURVEY_WITH_NO_KEY') );
			return false;
		}

		if($survey->publish_up != '0000-00-00 00:00:00'){

			$date = JFactory::getDate($survey->publish_up);
			//$date->setTimezone(new DateTimeZone("Asia/Jakarta"));
			$compareTo = JFactory::getDate();
			$compareTo->setTimezone(new DateTimeZone("Asia/Jakarta"));

			if(($compareTo->toUnix() - $date->toUnix()) < 0){

				$this->setError ( JText::_('MSG_SURVEY_NOT_YET_UP') );
				return false;
			}
		}

		if($survey->publish_down != '0000-00-00 00:00:00'){

			$date = JFactory::getDate($survey->publish_down);
			$compareTo = JFactory::getDate();

			if(($compareTo->toUnix() - $date->toUnix()) > 0){

				$this->setError ( JText::_('MSG_SURVEY_CLOSED') );
				return false;
			}
		}

		if(!empty($key)){

			$query = 'select id, completed > created as completed, survey_key, created_by from #__survey_responses where survey_key='.$this->_db->quote($key);
			$this->_db->setQuery($query);
			$obj_response = $this->_db->loadObject();

			if(!empty($obj_response) && $obj_response->completed == 1){

				$this->setError ( 'Error: 10304 - '.JText::_('MSG_SURVEY_ALREADY_TAKEN').(S_DEBUG_ENABLED ? print_r($obj_response, true) : '') );
				return false;
			}
		} else {

			// now let us find the response id based on the restriction method used
			if(empty($obj_response) && strpos($survey->restriction, 'cookie') !== false){

				//now we have the sid and we need response id, first check if there is a key in cookie with the sid
				$cookieName = trim(CJFunctions::get_hash( $app->getName() . S_COOKIE_PREFIX . $sid ));
				$cookie_key = trim(JRequest::getVar($cookieName, null, 'COOKIE', 'CMD'));
					
				if(!empty($cookie_key)){

					$query = 'select id, completed > created as completed, survey_key, created_by from #__survey_responses where survey_key='.$this->_db->quote($cookie_key);
					$this->_db->setQuery($query);
					$obj_response = $this->_db->loadObject();
				}

				if(!empty($obj_response) && $obj_response->completed == 1){

					$this->setError ( 'Error: 103041 - '.JText::_('MSG_SURVEY_ALREADY_TAKEN').(S_DEBUG_ENABLED ? print_r($obj_response, true) : '') );
					return false;
				}
			}

			if(empty($obj_response) && strpos($survey->restriction, 'ip') !== false){

				$ip_address = CJFunctions::get_user_ip_address();
				$query = '
						select 
							id, completed > created as completed, survey_key, created_by 
						from 
							#__survey_responses 
						where 
							survey_id='.$sid.' and ip_address='.$this->_db->quote($ip_address);

				$this->_db->setQuery ( $query );
				$obj_response = $this->_db->loadObject();

				if(!empty($obj_response) && $obj_response->completed == 1){

					$this->setError ( 'Error: 103042 - '.JText::_('MSG_SURVEY_ALREADY_TAKEN') );
					return false;
				}
			}

			if(!$user->guest && empty($obj_response)){
					
				$query = '
						select 
							id, completed > created as completed, survey_key, created_by
						from 
							#__survey_responses 
						where 
							survey_id='.$sid.' and created_by='.$user->id.'
						order by
							created desc';

				$this->_db->setQuery ( $query );
				$obj_response = $this->_db->loadObject();
					
				if(!empty($obj_response) && $obj_response->completed == 1){

					if(strpos($survey->restriction, 'username') !== false){

						$this->setError ( 'Error: 103043 - '.JText::_('MSG_SURVEY_ALREADY_TAKEN') );
						return false;
					} else {

						$obj_response = null;
					}
				}
			}
		}

		if(!empty($obj_response)){

			if(($survey->anonymous == 1) || ($obj_response->created_by == $user->id)){

				$response_id = $obj_response->id;
				$key = $obj_response->survey_key;
			}
		} else if(!empty($key)){

			// new survey with created key from invite page. no response should exist with this key so new response is created next
			// check if this key is legimate
			$query = 'select count(*) from #__survey_keys where key_name = '.$this->_db->quote($key);
			$this->_db->setQuery($query);
			$count = $this->_db->loadResult();

			if($count <= 0) {

				$this->setError ( 'Error: 103044 - '.JText::_('MSG_ERROR_PROCESSING') );
				return false;
			}
		}

		if(!$response_id || empty($key)){

			// No response id, so there is no way to track if he has responded. Create a survey response now.
			$query = 'select count(*) from #__survey_responses where survey_id='.$sid;
			$this->_db->setQuery($query);
			$max_responses = $this->_db->loadResult();

			if(($survey->max_responses > 0) && ($survey->max_responses <= $max_responses)) {

				$this->setError ( JText::_('MSG_EXCEED_RESPONSE_LIMIT') );
				return false;
			}

			if(!$key){

				$key = $this->create_survey_keys($sid, 1, false, true);
			}

			if(empty($key) || count($key) == 0){

				$this->setError ( JText::_('MSG_NO_CREDITS') );
				return false;
			}else{

				$key = is_array($key) ? $key[0] : $key;
				$cookieName	= CJFunctions::get_hash( $app->getName() . S_COOKIE_PREFIX . $sid );
				$expire = time() + 60 * 60 * 24 * (intval($config->get('cookie_expiration_days', 365)));
				setcookie($cookieName, $key, $expire, '/');
			}

			$createdate = JFactory::getDate()->toSql();
			$ip_address = CJFunctions::get_user_ip_address();
			$location = CJFunctions::get_user_location($ip_address);
			$browser = CJFunctions::get_browser();

			if ($survey->anonymous) {

				$query = 'insert into #__survey_responses(survey_id, created, survey_key, country, browser, os) ' . 'values (' .
				$sid . ',' .
				$this->_db->quote ( $createdate ) . ',' .
				$this->_db->quote ( $key ) . ',' .
				$this->_db->quote ( $location['country_code'] ) .','.
				$this->_db->quote ( $browser['name'] . ' ' . $browser['version'] ) . ','.
				$this->_db->quote ( $browser['platform'] ) . ')';
			} else {

				$query = 'insert into #__survey_responses(survey_id, created, survey_key, country, city, created_by, ip_address, browser, os) values (' .
				$sid . ',' .
				$this->_db->quote ( $createdate ) . ',' .
				$this->_db->quote ( $key ) . ',' .
				$this->_db->quote ( $location['country_code'] ) . ',' .
				$this->_db->quote ( $location['city'] ) . ',' .
				$user->id . ',' .
				$this->_db->quote ( $ip_address ) . ',' .
				$this->_db->quote ( $browser['name'] . ' ' . $browser['version'] ) . ','.
				$this->_db->quote ( $browser['platform'] ) . ')';
			}

			$this->_db->setQuery ( $query );

			if ($this->_db->query ()) {

				$response_id = $this->_db->insertid ();

				$query = '
					update 
						#__survey_keys
					set 
						response_id=' . $response_id . ',
						response_status = 2
					where 
						key_name=' . $this->_db->quote ( $key ) . ' and survey_id='.$sid;

				$this->_db->setQuery ( $query );

				if (! $this->_db->query ()) {

					$this->setError ( 'Error: 10310 - ' .JText::_('MSG_ERROR_PROCESSING'));
					return false;
				}
			} else {

				$this->setError ( 'Error: 10311 - ' . JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $this->_db->getErrorMsg() : '') );
				return false;
			}
		}

		$survey->response_id = $response_id;
		$survey->key = $key;

		return $survey;
	}

	public function do_create_or_update_response($create_response = true){

		$app = JFactory::getApplication ();
		$user = JFactory::getUser ();
		$config = JComponentHelper::getParams(S_APP_NAME);

		$sid = $app->input->getInt('id', 0);
		$key = trim($app->input->getCmd('key', null));
		$skey = $_GET['skey'];//trim($app->input->getCmd('skey', null));
		$response_id = 0;
		$obj_response = null;
		$survey = new stdClass();
		$survey->error = false;

		if (empty ( $skey ) && empty ( $sid ) && empty($key)) {

			$this->setError ( 'Error: 10301 - '.JText::_('MSG_SURVEY_NOT_FOUND') );
			$survey->error = 1;
			return $survey;
		}

		if(!$sid) {

			if(!empty($key)){

				$query = 'select survey_id from #__survey_keys where key_name=' . $this->_db->quote ( $key );
				$this->_db->setQuery ( $query );
				$sid = $this->_db->loadResult();
			}else if(!empty($skey)){
					
				$query = 'select id from #__survey where survey_key='.$skey;
				$this->_db->setQuery($query);
				$sid = $this->_db->loadResult();
			}
		}

		// Get the survey.
		$survey = $this->get_survey_details( $sid);
		$survey->error = false;
		$survey->skey = $skey;
		$survey->key = $key;

		if(empty($survey) || !$survey->id){

			// Looks like a deleted survey or some error occurred
			$this->setError ( 'Error: 10305 - '.JText::_('MSG_ERROR_PROCESSING') );
			$survey = new stdClass();
			$survey->error = 2;
			return $survey;
		}

		if($survey->published != 1){

			$survey->error = 3;
			return $survey;
		}

		/*
		 if(!$user->authorise('core.respond', S_APP_NAME.'.category.'.$survey->catid)) {

			$survey->error = $user->guest ? 4 : 5;
			return $survey;
			}
			*/

		/*
		 if($survey->private_survey == '1' && empty($key) && empty($skey)){

			$this->setError ( JText::_('MSG_PRIVATE_SURVEY_WITH_NO_KEY').'| SKey: '.$skey.'| Key'.$key );
			$survey->error = 6;
			return $survey;
			}
			*/

		if($survey->publish_up != '0000-00-00 00:00:00'){

			$date = JFactory::getDate($survey->publish_up);
			//$date->setTimezone(new DateTimeZone("Asia/Jakarta"));
			$compareTo = JFactory::getDate();
			$compareTo->setTimezone(new DateTimeZone("Asia/Jakarta"));

			if(($compareTo->toUnix() - $date->toUnix()) < 0){
			//if(($compareTo < $date)){
				$this->setError ( JText::_('MSG_SURVEY_NOT_YET_UP') );
				$survey->error = 7;
				return $survey;
			}
		}

		if($survey->publish_down != '0000-00-00 00:00:00'){

			$date = JFactory::getDate($survey->publish_down);
			$compareTo = JFactory::getDate();

			if(($compareTo->toUnix() - $date->toUnix()) > 0){

				$this->setError ( JText::_('MSG_SURVEY_CLOSED') );
				$survey->error = 8;
				return $survey;
			}
		}

		if($create_response == false) {

			// do not create or check for the response
			return $survey;
		}

		if(!empty($key)){

			$query = 'select id, completed > created as completed, survey_key, created_by from #__survey_responses where survey_key='.$this->_db->quote($key);
			$this->_db->setQuery($query);
			$obj_response = $this->_db->loadObject();

			if(!empty($obj_response) && $obj_response->completed == 1){

				$this->setError ( 'Error: 10304 - '.JText::_('MSG_SURVEY_ALREADY_TAKEN').(S_DEBUG_ENABLED ? print_r($obj_response, true) : '') );
				$survey->error = 9;
				return $survey;
			}
		} else {

			// now let us find the response id based on the restriction method used
			if(empty($obj_response) && strpos($survey->restriction, 'cookie') !== false){

				//now we have the sid and we need response id, first check if there is a key in cookie with the sid
				$cookieName = trim(CJFunctions::get_hash( $app->getName() . S_COOKIE_PREFIX . $sid ));
				$cookie_key = trim(JRequest::getVar($cookieName, null, 'COOKIE', 'CMD'));
					
				if(!empty($cookie_key)){

					$query = 'select id, completed > created as completed, survey_key, created_by from #__survey_responses where survey_key='.$this->_db->quote($cookie_key);
					$this->_db->setQuery($query);
					$obj_response = $this->_db->loadObject();
				}

				if(!empty($obj_response) && $obj_response->completed == 1){

					$this->setError ( 'Error: 103041 - '.JText::_('MSG_SURVEY_ALREADY_TAKEN').(S_DEBUG_ENABLED ? print_r($obj_response, true) : '') );
					$survey->error = 10;
					return $survey;
				}
			}

			if(empty($obj_response) && strpos($survey->restriction, 'ip') !== false){

				$ip_address = CJFunctions::get_user_ip_address();
				$query = '
						select
							id, completed > created as completed, survey_key, created_by
						from
							#__survey_responses
						where
							survey_id='.$sid.' and ip_address='.$this->_db->quote($ip_address);

				$this->_db->setQuery ( $query );
				$obj_response = $this->_db->loadObject();

				if(!empty($obj_response) && $obj_response->completed == 1){

					$this->setError ( 'Error: 103042 - '.JText::_('MSG_SURVEY_ALREADY_TAKEN') );
					$survey->error = 11;
					return $survey;
				}
			}

			if(!$user->guest && empty($obj_response)){
					
				$query = '
						select
							id, completed > created as completed, survey_key, created_by
						from
							#__survey_responses
						where
							survey_id='.$sid.' and created_by='.$user->id.'
						order by
							created desc';

				$this->_db->setQuery ( $query );
				$obj_response = $this->_db->loadObject();
					
				if(!empty($obj_response) && $obj_response->completed == 1){

					if(strpos($survey->restriction, 'username') !== false){

						$this->setError ( 'Error: 103043 - '.JText::_('MSG_SURVEY_ALREADY_TAKEN') );
						$survey->error = 12;
						return $survey;
					} else {

						$obj_response = null;
					}
				}
			}
		}

		if(!empty($obj_response)){

			if(($survey->anonymous == 1) || ($obj_response->created_by == $user->id)){

				$response_id = $obj_response->id;
				$key = $obj_response->survey_key;
			}
		} else if(!empty($key)){

			// new survey with created key from invite page. no response should exist with this key so new response is created next
			// check if this key is legimate
			$query = 'select count(*) from #__survey_keys where key_name = '.$this->_db->quote($key);
			$this->_db->setQuery($query);
			$count = $this->_db->loadResult();

			if($count <= 0) {

				$this->setError ( 'Error: 103044 - '.JText::_('MSG_ERROR_PROCESSING') );
				$survey->error = 13;
				return $survey;
			}
		}

		if(!$response_id || empty($key)){

			// No response id, so there is no way to track if he has responded. Create a survey response now.
			$query = 'select count(*) from #__survey_responses where survey_id='.$sid;
			$this->_db->setQuery($query);
			$max_responses = $this->_db->loadResult();

			if(($survey->max_responses > 0) && ($survey->max_responses <= $max_responses)) {

				$this->setError ( JText::_('MSG_EXCEED_RESPONSE_LIMIT') );
				$survey->error = 14;
				return $survey;
			}

			if(!$key){

				$key = $this->create_survey_keys($sid, 1, false, true);
			}

			if(empty($key) || count($key) == 0){

				$this->setError ( JText::_('MSG_NO_CREDITS') );
				$survey->error = 15;
				return $survey;
			}else{

				$key = is_array($key) ? $key[0] : $key;
				$cookieName	= CJFunctions::get_hash( $app->getName() . S_COOKIE_PREFIX . $sid );
				$expire = time() + 60 * 60 * 24 * (intval($config->get('cookie_expiration_days', 365)));
				setcookie($cookieName, $key, $expire, '/');
			}

			$createdate = JFactory::getDate()->toSql();
			$ip_address = CJFunctions::get_user_ip_address();
			//$location = CJFunctions::get_user_location($ip_address);
			$location = '';
			$browser = CJFunctions::get_browser();

			if ($survey->anonymous) {

				$query = 'insert into #__survey_responses(survey_id, created, created_by, ip_address, survey_key, completed, country, browser, os) ' . 'values (' .
				$sid . ',' .
				$this->_db->quote ( $createdate ) . ',' .
				$user->id . ',' .
				$this->_db->quote($ip_address). ',' .
				$this->_db->quote ( $key ) . ',' .
				$this->_db->quote ( $createdate ) . ',' .
				$this->_db->quote ( $location['country_code'] ) .','.
				$this->_db->quote ( $browser['name'] . ' ' . $browser['version'] ) . ','.
				$this->_db->quote ( $browser['platform'] ) . ')';
			} else {

				$query = 'insert into #__survey_responses(survey_id, created, survey_key, country, city, created_by, ip_address, browser, os) values (' .
				$sid . ',' .
				$this->_db->quote ( $createdate ) . ',' .
				$this->_db->quote ( $key ) . ',' .
				$this->_db->quote ( $location['country_code'] ) . ',' .
				$this->_db->quote ( $location['city'] ) . ',' .
				$user->id . ',' .
				$this->_db->quote ( $ip_address ) . ',' .
				$this->_db->quote ( $browser['name'] . ' ' . $browser['version'] ) . ','.
				$this->_db->quote ( $browser['platform'] ) . ')';
			}

			$this->_db->setQuery ( $query );

			if ($this->_db->query ()) {

				$response_id = $this->_db->insertid ();

				$query = '
					update
						#__survey_keys
					set
						response_id=' . $response_id . ',
						response_status = 2
					where
						key_name=' . $this->_db->quote ( $key ) . ' and survey_id='.$sid;

				$this->_db->setQuery ( $query );

				if (! $this->_db->query ()) {

					$this->setError ( 'Error: 10310 - ' .JText::_('MSG_ERROR_PROCESSING'));
					$survey->error = 16;
					return $survey;
				}
			} else {

				$this->setError ( 'Error: 10311 - ' . JText::_('MSG_ERROR_PROCESSING').(S_DEBUG_ENABLED ? $this->_db->getErrorMsg() : '') );
				$survey->error = 17;
				return $survey;
			}
		}

		$survey->response_id = $response_id;
		$survey->key = $key;

		if(!$survey->response_id || empty($survey->key)){

			$survey->error = 18;
		}

		return $survey;
	}

	function get_previous_page($sid, $current_page){

		$wheres = array();
		$wheres[] = 'sid = '.$sid;
		$wheres[] = 'sort_order < '.$current_page;

		$where = implode(' and ', $wheres);

		$query = 'select id, sort_order from #__survey_pages where '.$where.' order by sort_order desc';
		$this->_db->setQuery($query, 0, 2);

		return $this->_db->loadObjectList();
	}

	function get_next_page($id, $current_page, $force_id = 0){

		$wheres = array();
		$wheres[] = 'sid = '.$id;

		if($force_id > 0){

			$wheres[] = 'sort_order >= (select sort_order from #__survey_pages where id = '.$force_id.')';
		} else {

			$wheres[] = 'sort_order > '.$current_page;
		}

		$where = ' where '.implode(' and ', $wheres);

		$query = 'select id, sort_order from #__survey_pages'.$where.' order by sort_order asc';
		$this->_db->setQuery($query, 0, 2);

		$pages = $this->_db->loadObjectList();

		if(empty($pages)){

			$this->setError($this->_db->getErrorMsg().$query);
		}

		return $pages;
	}

	function get_response_details($response_id, $sid=0, $page_id = 0, $validate = true, $answers_only = true){

		$user = JFactory::getUser();
		$params = JComponentHelper::getParams(S_APP_NAME);

		$query = '
				select 
					a.created_by, a.created,
					case 
						when 
							a.created_by > 0 
						then 
							u.'.$params->get('user_display_name', 'name').' 
						else 
							'.$this->_db->quote(JText::_('LBL_GUEST')).' 
					end as username, u.email 
				from 
					#__survey_responses as a 
				left join
					#__users u on a.created_by = u.id
				where 
					a.id='.$response_id;

		$this->_db->setQuery($query);
		$response = $this->_db->loadObject();

		if($validate && ($user->id != $response->created_by) && ($sid > 0) && !$user->authorise('core.manage', S_APP_NAME)){

			$query = 'select created_by from #__survey where id='.$sid;
			$this->_db->setQuery($query);
			$survey_author = (int) $this->_db->loadResult();

			if($user->id != $survey_author) return false;
		}

		$query = '
			select 
				a.question_id, a.answer_id, a.column_id, a.free_text
			from 
				#__survey_response_details a 
			where 
				a.response_id='.$response_id.( $page_id > 0 ? ' and question_id in (select id from #__survey_questions where page_number = '.$page_id.')' : '' ).' 
			order by 
				a.question_id';

		$this->_db->setQuery($query);
		$response->answers = $this->_db->loadObjectList();

		return $answers_only ? $response->answers : $response;
	}

	function save_response($sid, $pid, $rid, $ignore_error = false){

		$app = JFactory::getApplication();
		$user = JFactory::getUser ();

		$questions = $this->get_questions( $sid, $pid );
		$config = JComponentHelper::getParams(S_APP_NAME);
		$html_allowed = $user->authorise('core.wysiwyg', S_APP_NAME) && ($config->get('default_editor', 'bbcode') == 'wysiwyg');

		// validate if legimate user
		if(!$user->guest && !$user->authorise('core.manage', S_APP_NAME)){

			$query = 'select created_by from #__survey_responses where id = '.$rid;
			$this->_db->setQuery($query);
			$created_by = (int) $this->_db->loadResult();

			if($created_by > 0 && $created_by != $user->id) {

				if(!$ignore_error) {

					CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
				} else {

					$this->setError(JText::_('MSG_UNAUTHORIZED').'| Error: 1');
				}

				return false;
			}
		}

		$rules = $this->get_conditional_rules($sid, $pid, null, true);
		$return = new stdClass();
		$return->page_id = $return->finalize = 0;

		if (! empty ( $questions )) {

			$answers = array ();

			foreach ( $questions as $question ) {

				$free_text = null;

				switch ($question->question_type) {

					case 2 : // Choice - Radio
					case 4 : // Choice - Select box
					case 11: // Image - Radio

						$answer_id = $app->input->post->getInt('answer-'.$question->id, 0);
						$free_text = $app->input->post->getString('free-text-'.$question->id, null);

						if ($answer_id) {

							$answer = array ();
							$answer['question_id'] = $question->id ;
							$answer['answer_id'] = $answer_id;
							$answer['column_id'] = 0;
							$answer['free_text'] = null;
							array_push ( $answers, $answer );
						}

						if(!empty($rules) && empty($return->finalize) && empty($return->page_id)){

							$this->validate_rules($question->id, array($answer_id), $rules, $return, 1);
						}

						break;

					case 3 : // Choice - Checkbox
					case 12: // Image - Checkbox

						$answer_ids = $app->input->post->getArray(array('answer-'.$question->id=>'array'));
						$free_text = $app->input->post->getString('free-text-'.$question->id, null);

						$answer_ids = $answer_ids['answer-'.$question->id];
						JArrayHelper::toInteger ( $answer_ids );

						if (! empty ( $answer_ids )) {

							foreach ( $answer_ids as $answer_id ) {

								$answer = array ();
								$answer ['question_id'] = $question->id;
								$answer ['answer_id'] = $answer_id;
								$answer ['column_id'] = 0;
								$answer ['free_text'] = null;
								array_push ( $answers, $answer );
							}
						}

						if(!empty($rules) && empty($return->finalize) && empty($return->page_id)){

							$this->validate_rules($question->id, $answer_ids, $rules, $return, 1);
						}

						break;

					case 5 : // Grid - Radio

						$rows = array ();
						$columns = array ();
						$grid_answers = array();

						foreach ( $question->answers as $answer ) {

							if ($answer->answer_type == 'x') {

								$rows[] = $answer;
							} else if ($answer->answer_type == 'y') {

								$columns[] = $answer;
							}
						}

						$free_text = $app->input->post->getString( 'free-text-'.$question->id, null);

						foreach ( $rows as $row ) {

							$column_id = $app->input->post->getInt( 'answer-'.$question->id.'-'.$row->id, 0);

							if ($column_id) {

								$answer = array ();

								$answer ['question_id'] = $question->id;
								$answer ['answer_id'] = $row->id;
								$answer ['column_id'] = $column_id;
								$answer ['free_text'] = null;

								array_push ( $grid_answers, array($row->id => $column_id) );
								array_push ( $answers, $answer );
							}
						}

						if(!empty($rules) && empty($return->finalize) && empty($return->page_id)){

							$this->validate_rules($question->id, $grid_answers, $rules, $return, 2);
						}

						break;

					case 6 : // Grid - Checkbox

						$rows = array ();
						$columns = array ();
						$grid_answers = array();

						foreach ( $question->answers as $answer ) {

							if ($answer->answer_type == 'x') {

								$rows [] = $answer;
							} else if ($answer->answer_type == 'y') {

								$columns [] = $answer;
							}
						}

						$free_text = $app->input->post->getString('free-text-'.$question->id, null);

						foreach ( $rows as $row ) {

							$column_ids = $app->input->post->getArray(array('answer-'.$question->id.'-'.$row->id=>'array'));

							$column_ids = $column_ids['answer-'.$question->id.'-'.$row->id];
							JArrayHelper::toInteger ( $column_ids );

							if (! empty ( $column_ids )) {

								foreach ( $column_ids as $column_id ) {

									$answer = array ();

									$answer ['question_id'] = $question->id;
									$answer ['answer_id'] = $row->id;
									$answer ['column_id'] = $column_id;
									$answer ['free_text'] = null;

									array_push ( $grid_answers, array($row->id => $column_id) );
									array_push ( $answers, $answer );
								}
							}
						}

						if(!empty($rules) && empty($return->finalize) && empty($return->page_id)){

							$this->validate_rules($question->id, $grid_answers, $rules, $return, 2);
						}

						break;

					case 7 : // Freetext - Singleline
					case 8 : // Freetext - Multiline
					case 9 : // Freetext - Password
					case 14: // Special - Email
					case 15: // Special - Calendar

						$free_text = $app->input->post->getString('free-text-'.$question->id, null);

						if(!empty($rules) && empty($return->finalize) && empty($return->page_id)){

							$this->validate_rules($question->id, array($free_text), $rules, $return, 3);
						}

						break;

					case 10 : // Freetext - Rich text

						$free_text = CJFunctions::get_clean_var('free-text-'.$question->id, $html_allowed);

						if(!empty($rules) && empty($return->finalize) && empty($return->page_id)){

							$text = strip_tags($free_text);
							$this->validate_rules($question->id, array($text), $rules, $return, 3);
						}

						break;

					case 13: // Special - Name

						$names = $app->input->getArray(array('user-name-'.$question->id=>'array'));

						if(count($names['user-name-'.$question->id]) == 3){

							$free_text = implode('|', $names['user-name-'.$question->id]);
							$this->validate_rules($question->id, array($free_text), $rules, $return, 3);
						}

						break;

					case 16: // Special - Address

						$addr_name = $app->input->post->getString('address-name-'.$question->id, '');
						$addr_line1 = $app->input->post->getString('address-line1-'.$question->id, '');
						$addr_line2 = $app->input->post->getString('address-line2-'.$question->id, '');
						$addr_city = $app->input->post->getString('address-city-'.$question->id, '');
						$addr_state = $app->input->post->getString('address-state-'.$question->id, '');
						$addr_country = $app->input->post->getString('address-country-'.$question->id, '');
						$addr_zip = $app->input->post->getString('address-zip-'.$question->id, '');

						if(!empty($addr_name) && !empty($addr_line1) && !empty($addr_city) && !empty($addr_state) && !empty($addr_country) && !empty($addr_zip)){

							$free_text = $addr_name.'|||'.$addr_line1.'|||'.$addr_line2.'|||'.$addr_city.'|||'.$addr_state.'|||'.$addr_country.'|||'.$addr_zip;
						}

						break;
				}

				if($free_text) {

					$answer = array ();

					$answer ['question_id'] = $question->id;
					$answer ['answer_id'] = 0;
					$answer ['column_id'] = 0;
					$answer ['free_text'] = $free_text;

					array_push ( $answers, $answer );
				}
			}

			$query = '
				delete from
					#__survey_response_details
				where
					response_id='.$rid.' and question_id in (select id from #__survey_questions where survey_id='.$sid.' and page_number='.$pid.')';

			$this->_db->setQuery($query);

			if($this->_db->query()){

				$query = '';
				foreach ( $answers as $answer ) {

					if (empty ( $answer ['free_text'] )) {

						$answer ['free_text'] = 'null';
					} else {

						$answer ['free_text'] = $this->_db->quote ( $answer ['free_text'] );
					}

					$query = $query . '(' . $rid . ',' . $answer ['question_id'] . ',' . $answer ['answer_id'] . ',' . $answer ['column_id'] . ',' . $answer ['free_text'] . '),';
				}

				if(!empty($query)){

					$query = 'insert into #__survey_response_details (response_id, question_id, answer_id, column_id, free_text) values '.$query;
					$query = substr($query, 0, -1);
					$this->_db->setQuery($query);

					if($this->_db->query()){

						return $return;
					}
				}else{

					return $return;
				}
			}
		}

		$this->setError($this->_db->getErrorMsg());

		return false;
	}

	// type: 1 = choice, 2 = grid, 3 = free text
	function validate_rules($question_id, $answer_ids, $rules, &$return, $type){

		foreach ($rules as $rule){

			if($rule->question_id == $question_id){

				if($rule->name == 'unanswered' || ( ($type == 3 && empty($answer_ids[0]))  || empty($answer_ids) ) ){

					if($rule->finalize == '1'){

						$return->finalize = 1;
					}else{

						if(!empty($rule->page)){

							$return->page_id = intval($rule->page);
						}
					}

					break;
				}

				if($return->finalize > 0 || $return->page_id > 0) break;

				$selected = false;

				foreach($answer_ids as $answer_id){

					switch ($rule->name){

						case 'answered':

							if(!empty($answer_id)){

								if($rule->finalize == '1'){

									$return->finalize = 1;
								}else{

									if(!empty($rule->page)){

										$return->page_id = intval($rule->page);
									}
								}
							}

							break;

						case 'selected':

							if(
							( ($type == 1) && ($answer_id == $rule->answer_id) ) ||
							( ($type == 2) && (key($answer_id) == $rule->answer_id) && ($answer_id[key($answer_id)] == $rule->column_id) )
							)
							{
								if($rule->finalize == '1'){

									$return->finalize = 1;
								} else if(!empty($rule->page)){

									$return->page_id = intval($rule->page);
								}
							}

							break;

						case 'unselected':

							if(
							( ($type == 1) && ($answer_id == $rule->answer_id) ) ||
							( ($type == 2) && (key($answer_id) == $rule->answer_id) && ($answer_id[key($answer_id)] == $rule->column_id) )
							)
							{
								$selected = true;
							}

							break;
					}
				}

				if($rule->name == 'unselected' && !$selected){

					if($rule->finalize == '1'){
							
						$return->finalize = 1;
					} else if(!empty($rule->page)){
							
						$return->page_id = intval($rule->page);
					}
				}
			}
		}
	}

	function is_response_expired($sid, $response_id){

		$query = 'select created, completed, completed > created as closed from #__survey_responses where id='.$response_id;
		$this->_db->setQuery($query);
		$response = $this->_db->loadObject();

		if(empty($response->created)) return true;

		if($response->closed == 1){

			return true;
		}

		return false;
	}

	function finalize_response($sid, $response_id){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$createdate = JFactory::getDate()->toSql();

		$query = 'update #__survey_responses set completed = '.$this->_db->quote($createdate).' where id = '.$response_id;
		$this->_db->setQuery($query);

		if($this->_db->query()){

			$query = 'update #__survey_keys set response_status = 1 where response_id = '.$response_id;
			$this->_db->setQuery($query);
			$this->_db->query();

			$query = 'update #__survey set responses = responses + 1 where id=(select survey_id from #__survey_responses where id='.$response_id.')';
			$this->_db->setQuery($query);
			$this->_db->query();

			return true;
		}

		$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10001<br>query: ' . $query . '<br><br>' );
		return false;
	}

	function get_survey_keys($sid, $limitstart = 0, $limit = 20){

		$keys = null;



		//if($this->authorize_survey($sid)){
			
		$query = 'select key_name, survey_id, response_id, response_status, created from #__survey_keys where survey_id = '.$sid.' order by created desc';
		$this->_db->setQuery($query, $limitstart, $limit);
		$keys = $this->_db->loadObjectList();
		//}

		return !empty($keys) ? $keys : array();
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
				$error = $this->_db->getErrorMsg ();
				if (! empty ( $error )) {
					$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10252<br>query: ' . $query . '<br><br>' );
				}
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

	function copy_survey($sid){
			
		$user = JFactory::getUser();
		$key = CJFunctions::generate_random_key();
		$createdate = JFactory::getDate()->toSql();

		$query = '
    		insert into 
    			#__survey(
    				title, alias, catid, introtext, endtext, created_by, created, publish_up, publish_down, responses, private_survey, max_responses,
    				anonymous, custom_header, public_permissions, published, survey_key, redirect_url, display_template, skip_intro,
    				backward_navigation, display_notice, display_progress, notification
    			)
    		(
    			select
    				concat(title, \'_Copy\'), concat(alias, \'_copy\'), catid, introtext, endtext, '.$user->id.','.$this->_db->quote($createdate).', publish_up, 
    				publish_down, 0, private_survey, max_responses, anonymous, custom_header, public_permissions, published, '.$this->_db->quote($key).', redirect_url, 
    				display_template, skip_intro, backward_navigation, display_notice, display_progress, notification
    			from 
    				#__survey
    			where
    				id = '.$sid.'
    		)';
			
		$this->_db->setQuery($query);

		if($this->_db->query()){

			$newid = $this->_db->insertid();

			if($newid > 0){
					
				$query = 'select id, sort_order from #__survey_pages where sid = '.$sid.' order by sort_order';
				$this->_db->setQuery($query);
				$pages = $this->_db->loadObjectList();
					
				$query = '
    				select
    					id, title, description, question_type, page_number, sort_order, mandatory, custom_choice, orientation
    				from
    					#__survey_questions
    				where
    					survey_id = '.$sid;
					
				$this->_db->setQuery($query);
				$questions = $this->_db->loadObjectList();

				if(empty($pages) || empty($questions)){

					return false;
				}
					
				foreach ($pages as $page){

					$query = 'insert into #__survey_pages (sid, sort_order) values ('.$newid.','.$page->sort_order.')';
					$this->_db->setQuery($query);

					if($this->_db->query()){
							
						$newpage = $this->_db->insertid();
							
						if($newpage <= 0){

							return false;
						}
							
						foreach ($questions as $question){

							if($question->page_number == $page->id){
									
								$query = '
    								insert into 
    									#__survey_questions(
    										title, description, survey_id, question_type, page_number, responses, sort_order, mandatory, created_by, custom_choice, orientation)
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
								$this->_db->quote($question->orientation).'
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
    											survey_id = '.$sid.' and question_id = '.$question->id.'
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

	public function get_admin_emails($groupId){

		if(!$groupId) return false;

		$userids = JAccess::getUsersByGroup($groupId);

		if(empty($userids)) return false;

		$query = 'select email from #__users where id in ('.implode(',', $userids).')';
		$this->_db->setQuery($query);
		$users = $this->_db->loadColumn();

		return $users;
	}

	public function update_fund_history($userid, $survey_id, $response_id){
		$now = JFactory::getDate();
		$username = JFactory::getUser();
		if($username != null){
			$username = $username->username;
		}
		$user = $this->getUsers($userid);
		if($user != null){
			$user_id = $user->id;
		}
		
		$account = AwardPackageHelper::getUserData();
			
		$query = "
    			SELECT 
				CASE
					WHEN b.answer_id IS NOT NULL 
					THEN a.complete_giftcode
					ELSE a.incomplete_giftcode
				END  AS giftcode,
				CASE
					WHEN b.answer_id IS NOT NULL 
					THEN a.complete_giftcode_quantity
					ELSE a.incomplete_giftcode_quantity
				END  AS giftcode_quantity,
				CASE
					WHEN b.answer_id IS NOT NULL 
					THEN a.complete_giftcode_cost_response
					ELSE a.incomplete_giftcode_cost_response
				END  AS cost_response
				FROM
				#__survey_question_giftcode a
				INNER JOIN #__survey_responses c ON c.survey_id = a.survey_id AND c.id = '".$response_id."'
				LEFT JOIN #__survey_response_details b ON b.response_id = c.id 
				WHERE a.package_id = '".$account->package_id."'
				AND a.survey_id = '".$survey_id."'
				AND a.page_number > 0		
    		";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		$costResponse = 0;
		foreach ($results as $result){
			$costResponse = ($costResponse + (int) $result->cost_response)/100;
			$giftcode = $result->giftcode;
			$giftcode_quantity = $result->giftcode_quantity;
			
		}
		$query2="SELECT a.*
				FROM #__ap_categories a
				WHERE a.category_name = '".$giftcode."'
				AND a.package_id = '".$account->package_id."'
    		";
		$this->_db->setQuery ( $query2 );
		$results2 = $this->_db->loadObjectList();
		
		foreach ($results2 as $result2){
			$category_id = $result2->category_id;
			$colour_code = $result2->colour_code;
			$setting_id = $result2->setting_id;		
		}
		
		$query4 = "insert into #__funding_user(funding_last_update, package_id, user_id)
			values ('".$now."', '".$account->package_id."', '".$user->id."') ";
			$this->_db->setQuery($query4);
		$this->_db->query();
		
		$fund = $this->getFund();

		if($fund != null){
			$fundingId = $fund->funding_id;
		}
		$query = "insert into #__funding_history(funding_id, description, credit, debit, created_date, method, transaction_type)
					values ('".$fundingId."', '".$username." - Survey - Survey taken - Award Category = ".$giftcode." x ".$giftcode_quantity."', '0', '".$costResponse."', '".$now."', null, 'SURVEY')";				
		$this->_db->setQuery($query);
		$this->_db->query();
		
		$data = $this->getFundHistory();
		if($data != null){
			$dataId = $data->funding_history_id;
		}
		
		$giftcode_user = $this->CekGiftcode($setting_id,$user->id);

		$jml_gc = count($giftcode_user);
		$tot_gc = $jml_gc + $giftcode_quantity;
				
						$giftcode = $this->getGiftcode($setting_id, $jml_gc, $tot_gc);	
						$tmp = 0;
						
						foreach ($giftcode as $row)
						if ($tmp++ < $giftcode_quantity)
						{		
							$query3 = "insert into #__gc_recieve_user(category_id,user_id,gcid,date_time,status,giftcode_type)
							values ('".$setting_id."','".$user->id."','".$row->id."','".$now."',0,0)";				
								$this->_db->setQuery($query3);
								$this->_db->query();
								}
		}
	
	public function update_giftcode_history($userid, $survey_id, $response_id){
		$now = JFactory::getDate();
		$username = JFactory::getUser();
		if($username != null){
			$username = $username->username;
		}
		$user = $this->getUsers($userid);
		if($user != null){
			$user_id = $user->id;
		}
		
		$account = AwardPackageHelper::getUserData();
			
		$query = "
    			SELECT 
				CASE
					WHEN b.answer_id IS NOT NULL 
					THEN a.complete_giftcode
					ELSE a.incomplete_giftcode
				END  AS giftcode,
				CASE
					WHEN b.answer_id IS NOT NULL 
					THEN a.complete_giftcode_quantity
					ELSE a.incomplete_giftcode_quantity
				END  AS giftcode_quantity,
				CASE
					WHEN b.answer_id IS NOT NULL 
					THEN a.complete_giftcode_cost_response
					ELSE a.incomplete_giftcode_cost_response
				END  AS cost_response
				FROM
				#__survey_question_giftcode a
				INNER JOIN #__survey_responses c ON c.survey_id = a.survey_id AND c.id = '".$response_id."'
				LEFT JOIN #__survey_response_details b ON b.response_id = c.id 
				WHERE a.package_id = '".$account->package_id."'
				AND a.survey_id = '".$survey_id."'
				AND a.page_number > 0		
    		";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		$costResponse = 0;
		foreach ($results as $result){
			$costResponse = ($costResponse + (int) $result->cost_response)/100;
			$giftcode = $result->giftcode;
			$giftcode_quantity = $result->giftcode_quantity;
			
		}
		$query2="SELECT a.*
				FROM #__ap_categories a
				WHERE a.category_name = '".$giftcode."'
				AND a.package_id = '".$account->package_id."'
    		";
		$this->_db->setQuery ( $query2 );
		$results2 = $this->_db->loadObjectList();
		
		foreach ($results2 as $result2){
			$category_id = $result2->category_id;
			$colour_code = $result2->colour_code;
			$setting_id = $result2->setting_id;		
		}
		
/*		$query4 = "insert into #__funding_user(funding_last_update, package_id, user_id)
			values ('".$now."', '".$account->package_id."', '".$user->id."') ";
			$this->_db->setQuery($query4);
		$this->_db->query();
		
		$fund = $this->getFund();

		if($fund != null){
			$fundingId = $fund->funding_id;
		}
		$query = "insert into #__funding_history(funding_id, description, credit, debit, created_date, method, transaction_type)
					values ('".$fundingId."', '".$username." - Survey - Survey taken - Award Category = ".$giftcode." x ".$giftcode_quantity."', '0', '".$costResponse."', '".$now."', null, 'SURVEY')";				
		$this->_db->setQuery($query);
		$this->_db->query();
		
		$data = $this->getFundHistory();
		if($data != null){
			$dataId = $data->funding_history_id;
		}
	*/	
		$giftcode_user = $this->CekGiftcode($setting_id,$user->id);

		$jml_gc = count($giftcode_user);
		$tot_gc = $jml_gc + $giftcode_quantity;
				
						$giftcode = $this->getGiftcode($setting_id, $jml_gc, $tot_gc);	
						$tmp = 0;
						
						foreach ($giftcode as $row)
						if ($tmp++ < $giftcode_quantity)
						{		
							$query3 = "insert into #__gc_recieve_user(category_id,user_id,gcid,date_time,status,giftcode_type)
							values ('".$setting_id."','".$user->id."','".$row->id."','".$now."',0,0)";				
								$this->_db->setQuery($query3);
								$this->_db->query();
								}
		}
	
	function CekGiftcode($category_id,$userid){
		$db 	= JFactory::getDbo();
		$query = "select * FROM #__gc_recieve_user WHERE category_id = '".$category_id."' AND user_id = '".$userid."'";
			$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;			
		} 
		
	function getGiftcode($category_id, $jml_gc, $tot_gc){
		$db 	= JFactory::getDbo();
		$query = "select id, giftcode FROM #__giftcode_giftcode WHERE giftcode_category_id = '".$category_id."' LIMIT ".$jml_gc.",".$tot_gc." ";
			$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;			
		} 
		
	public function getSurvey($surveyid){
		$query = "
				SELECT q.* FROM #__survey q WHERE q.id='".$surveyid."' order by q.id DESC
			";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		if(!empty($results)) {
			return $results[0];
		}
		return null;
		}

		public function getUsers($userid){
		$query = "
				SELECT u.* FROM #__users u WHERE u.id='".$userid."' order by u.id DESC
			";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		if(!empty($results)) {
			return $results[0];
		}
		return null;
		}

	public function getFund(){
		$user = AwardPackageHelper::getUserData();

		$query = "
				SELECT fu.* FROM #__funding_user fu order by fu.funding_id DESC
			";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		if(!empty($results)) {
			return $results[0];
		}
		return null;
	}
	
	public function getFundHistory(){
		$user = AwardPackageHelper::getUserData();

		$query = "
				SELECT fh.* FROM #__funding_history fh order by fh.funding_history_id DESC
			";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		if(!empty($results)) {
			return $results[0];
		}
		return null;
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

	function insert_survey_question_giftcode($package_id, $survey_id,
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

	function get_question_details($uniq_key, $question_id, $page_id, $survey_id){
			
		$user = JFactory::getUser ();
		$where = '';
		if($page_id){
			//$where = " page_number= '". $page_id."' and uniq_key = '".$uniq_key."' and question_id = '".$question_id."' " ;
		}
		$query = '
			select 
				*
			from 
				#__survey_questions 
			where
			page_number = "'. $page_id.'"
			and uniq_key = "'.$uniq_key.'"
			and id = "'.$question_id.'"
			order by 
				page_number asc';
		$this->_db->setQuery ( $query );
		$questions = $this->_db->loadObjectList('id');
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

	function get_max_id_pages($uniq_id) {
		$this->_db = &JFactory::getDBO ();
		$query = "select max(id) as id from #__survey_pages where uniq_key = '".$uniq_id."' ";
		$this->_db->setQuery ( $query );
		$id = $this->_db->loadResult ();
		return $id;
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
	
	function get_giftcode($qid) {
		$this->_db = &JFactory::getDBO ();
		$query = "SELECT * FROM #__quiz_question_giftcode WHERE quiz_id = '".$qid."'";		
		$this->_db->setQuery($query);
		$giftcode = $this->_db->loadObjectList();		
		return $giftcode;
	}
	
	function count_giftcode($qid) {
		$this->_db = &JFactory::getDBO ();
		$query = "SELECT * FROM #__quiz_question_giftcode WHERE quiz_id = '".$qid."'";		
		$this->_db->setQuery($query);
		$giftcode = $this->_db->loadObjectList();		
		return $giftcode;
	}
	
	function get_result($rid,$qid,$userid) {
		$this->_db = &JFactory::getDBO ();
			
		$query = "SELECT DISTINCT a.survey_id, a.created_by, a.completed, b.complete_giftcode, b.incomplete_giftcode, b.complete_giftcode_quantity, 
		           b.incomplete_giftcode_quantity, c.colour_code , c.category_id
		FROM #__survey_responses a
		LEFT JOIN #__survey_question_giftcode b ON b.survey_id = a.survey_id
		LEFT JOIN #__ap_categories as c ON LOWER(c.category_name) = LOWER(b.complete_giftcode)
		WHERE a.survey_id = '".$qid."'  AND a.created_by = '".$userid."'  " ;
		$this->_db->setQuery ( $query );
		$respon = $this->_db->loadObjectList();
		return $respon;
	}

	function get_total_giftcode($id) {
		$this->_db = &JFactory::getDBO ();
			
		$query = "SELECT b.survey_id, b.complete_giftcode, b.incomplete_giftcode, b.complete_giftcode_quantity, 
		           b.incomplete_giftcode_quantity
		FROM #__survey_question_giftcode b
		WHERE b.survey_id = '".$id."'  " ;
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	function update_answers($survey_id, $uniq_id) {
		$query = "UPDATE #__survey_answers a 
		LEFT JOIN #__survey_questions b ON a.question_id = b.id SET a.survey_id = '".$survey_id."' 
		WHERE b.uniq_key='".$uniq_id."' ";
		$this->_db->setQuery ( $query );
		$this->_db->query ();
		return true;
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
}