<?php
/**
 * @version		$Id: quiz.php 01 2012-06-30 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die();
jimport('joomla.application.component.modelitem');
require_once JPATH_SITE.'/components/com_cjlib/tree/nestedtree.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/constants.php';
require_once JPATH_SITE . '/components/com_awardpackage/helpers/awardpackage.php';

class AwardpackageUsersModelQuiz extends JModelLegacy {

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

	public function get_quizzes($action = 1, $options = array(), $params = null){
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

		$wheres[] = !empty($options['userid']) ? 'a.published >= 0' : 'a.published = 1';

		switch ($action){

			case 1: // Latest Quizzes

				$order = 'a.created';
				break;

			case 2: // Most popular

				$wheres[] = ' a.responses > 1';
				$order = 'a.responses';
				break;

			case 3: // Top rated quizzes

				$order = 'rtg.rating';
				break;

			case 4: // User quizzes

				$wheres[] = ' a.created_by='.$user->id;
				break;

			case 5: // User responses

				$wheres[] = 'a.id in (select r.quiz_id from #__quiz_responses r where r.created_by = '.$user->id.')';
				$order = 'a.created';
				break;

			case 6: //tagged quizzes

				$wheres[] = 'a.id in (select item_id from #__quiz_tagmap where tag_id='.$options['tagid'].')';
				break;

			case 7: //related/search quizzes

				$search_params = $options['search_params'];

				if($search_params['type'] == 1){ // search user responded quizzes

					$wheres[] = 'a.id in (select r.quiz_id from #__quiz_responses r where r.created_by = '.$user->id.')';
				} // else search all quizzes

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

			case 8: // user questions

				$wheres[] = 'a.created_by = '.$options['userid'];

				break;
		}

		$where = '('.implode(' ) and (', $wheres).')';
			
		$users = AwardPackageHelper::getUserData();
			
		//$where .= ' and a.package_id = \'' .$users->package_id. '\' ';

		$query = '
				select
					a.id, a.title, a.alias, a.description, a.created,a.package_id, a.responses, a.published, a.ip_address,
					c.id as catid, c.title as category_title, c.alias as category_alias, a.skip_intro,
    				rtg.rating ,d.uniq_key, d.id as qid, d.page_number
				from
					#__quiz_quizzes as a
				left join
					#__quiz_categories as c on a.catid = c.id and c.package_id = a.package_id
				left join
					#__quiz_questions as d on a.id = d.quiz_id 
				left join
					#__users as u on a.created_by = u.id
				left join
					#__corejoomla_rating rtg on rtg.asset_id='.CQ_ASSET_ID.' and rtg.item_id = a.id
				where
					'.$where.'
				order by
					'.$order.' '.$order_dir;
		$this->_db->setQuery($query, $limitstart, $limit);
		$quizzes = $this->_db->loadObjectList('id');

		/************ pagination *****************/
		$query = '
				select
					count(*)
				from
					#__quiz_quizzes as a
				left join
					#__quiz_categories c on a.catid = c.id and c.package_id = a.package_id
				left join
					#__users as u on a.created_by = u.id
				where
					'.$where;

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();
		
		$query = "delete from #__quiz_question_giftcode where complete_giftcode = 'NEW' ";
			$this->_db->setQuery($query);
			if(!$this->_db->query()){
				//JFactory::getApplication()->enqueueMessage($this->_db->getErrorMsg());
				return false;
			}

		$query = "delete from #__quiz_pages where quiz_id = 0 ";
			$this->_db->setQuery($query);
			if(!$this->_db->query()){
				//JFactory::getApplication()->enqueueMessage($this->_db->getErrorMsg());
				return false;
			}
		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
		/************ pagination *****************/

		if(!empty($quizzes)){

			$ids = array();

			foreach($quizzes as &$quiz){

				$ids[] = $quiz->id;
				$quiz->tags = array();
			}

			$tags = $this->get_tags_by_itemids($ids);

			if(!empty($tags)){

				foreach($tags as $tag){

					if(array_key_exists($tag->item_id, $quizzes)){

						$quizzes[$tag->item_id]->tags[] = $tag;
					}
				}
			}
		}

		$return['quizzes'] = $quizzes;
		$return['state'] = array('order'=>$order, 'order_dir'=>$order_dir);

		return $return;
	}

    function get_total_giftcode($id) {
		$this->_db = &JFactory::getDBO ();			
		$query = "SELECT b.quiz_id, b.complete_giftcode, b.incomplete_giftcode, b.complete_giftcode_quantity, 
		           b.incomplete_giftcode_quantity
		FROM #__quiz_question_giftcode b
		WHERE b.quiz_id = '".$id."'  " ;
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
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

	public function remove_responses($id, $cids){
			
		$query = 'delete from #__quiz_responses where quiz_id = '.$id.' and id in ('.implode(',', $cids).')';
		$this->_db->setQuery($query);
			
		if($this->_db->query()){

			$query = 'update #__quiz_quizzes q set q.responses = (select count(*) from #__quiz_responses r where r.quiz_id = '.$id.' and r.completed = 1) where q.id = '.$id;
			$this->_db->setQuery($query);
			$this->_db->query();

			return true;
		}
			
		$this->setError($this->_db->getErrorMsg());
			
		return false;
	}

	public function search_tags_by_name($search){

		$query = "select id, tag_text, description from #__quiz_tags where tag_text like '%".$this->_db->escape($search)."%'";
		$this->_db->setQuery($query);
		$tags = $this->_db->loadObjectList();

		return $tags;
	}

	function search_tags($search, $limit=10){

		$tags = new CjTags($this->_db, '#__quiz_tags', '#__quiz_tagmap', '#__quiz_tags_stats');

		return $tags->get_related_tags($search, $limit);
	}

	public function get_tags($limitstart, $limit, $params, $keywords){

		$return = array();
		$wheres = array();

		if(!empty($keywords)){

			$wheres[] = 'a.tag_text like \'%'.$this->_db->escape($keywords).'%\'';
		}

		$where = !empty($wheres) ? ' where ('.implode(' ) and ( ', $wheres).')' : '';

		$query = '
				select
					a.id, a.tag_text, a.alias, a.description,
					s.num_items
				from
					#__quiz_tags as a
				left join
					#__quiz_tags_stats as s on a.id = s.tag_id
				'.$where;

		$this->_db->setQuery($query, $limitstart, $limit);
		$return['tags'] = $this->_db->loadObjectList();

		/************ pagination *****************/
		$query = 'select count(*) from #__quiz_tags';

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
		/************ pagination *****************/

		return $return;
	}

	public function get_tag_details($tag_id){

		$query = 'select id, tag_text, alias, description from #__quiz_tags where id = '.$tag_id;
		$this->_db->setQuery($query);

		return $this->_db->loadObject();
	}

	public function save_tag_details($tag){

		if(empty($alias)) $alias = JFilterOutput::stringURLUnicodeSlug($tag->title);

		$query = '
				update
					#__quiz_tags
				set
					tag_text = '.$this->_db->quote($tag->title).',
					alias = '.$this->_db->quote($tag->alias).',
					description = '.$this->_db->quote($tag->description).'
				where
					id = '.$tag->id;

		$this->_db->setQuery($query);

		if($this->_db->query()){

			return true;
		}

		return false;
	}

	public function delete_tag($tagid){

		$query = 'delete from #__quiz_tags where id = '.$tagid;
		$this->_db->setQuery($query);

		if($this->_db->query() && $this->_db->getAffectedRows() > 0){

			$query = 'delete from #__quiz_tagmap where tag_id = '.$tagid;
			$this->_db->setQuery($query);
			$this->_db->query();

			$query = 'delete from #__quiz_tags_stats where tag_id = '.$tagid;
			$this->_db->setQuery($query);
			$this->_db->query();

			return true;
		}

		return false;
	}

	function get_quiz_details($id, $no_tags = false, $pages = false){
			
		$params = JComponentHelper::getParams(Q_APP_NAME);
			
		$query = '
    		select 
    			a.id, a.title, a.alias, a.description, a.catid, a.created_by, a.created, a.responses, a.ip_address, a.duration, a.published, 
    			a.show_answers, a.show_template, a.multiple_responses, a.skip_intro, a.cutoff,
    			c.title as category, c.alias as calias, 
    			u.'.$params->get('user_display_name', 'name').' as username, u.email, 
    			\'0\' asrating 
    		from 
    			#__quiz_quizzes a 
    		left join 
    			#__quiz_categories c on a.catid = c.id 
    		left join 
    			#__users u on a.created_by = u.id 
    		where a.id='.$id;
		$this->_db->setQuery($query);
		$quiz = $this->_db->loadObject();

		if (!empty($quiz) && $no_tags == false) {

			$quiz->tags = $this->get_tags_by_itemids(array($quiz->id));
		}

		if($pages){

			$quiz->pages = $this->get_pages($id);
		}

		return $quiz;
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
			
			$upcatq ="update #__quiz_categories set quizzes=quizzes+1 where id=".$quiz->catid;
			$this->_db->setQuery($upcatq);
			$this->_db->execute();
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

	private function insert_tags($quiz_id, $strtags){

		$tags = explode(',', $strtags);

		// first filter out the tags
		foreach($tags as $i=>$tag){

			$tag = preg_replace('/[^-\pL.\x20]/u', '', $tag);
			if(empty($tag)) unset($tags[$i]);
		}

		// now if there are any new tags, insert them.
		if(!empty($tags)){

			$inserts = array();
			$sqltags = array();

			foreach($tags as $tag){

				$alias = JFilterOutput::stringURLUnicodeSlug($tag);
				$inserts[] = '('.$this->_db->quote($tag).','.$this->_db->quote($alias).')';
				$sqltags[] = $this->_db->quote($tag);
			}

			$query = 'insert ignore into #__quiz_tags (tag_text, alias) values '.implode(',', $inserts);
			$this->_db->setQuery($query);

			if(!$this->_db->query()){

				return false;
			}

			// we need to get all tag ids matching the input tags
			$query = 'select id from #__quiz_tags where tag_text in ('.implode(',', $sqltags).')';
			$this->_db->setQuery($query);
			$insertids = $this->_db->loadColumn();

			if(!empty($insertids)){

				$mapinserts = array();
				$statinserts = array();

				foreach($insertids as $insertid){

					$mapinserts[] = '('.$insertid.','.$quiz_id.')';
					$statinserts[] = '('.$insertid.','.'1)';
				}

				$query = 'insert ignore into #__quiz_tagmap(tag_id, item_id) values '.implode(',', $mapinserts);
				$this->_db->setQuery($query);

				if(!$this->_db->query()){

					return false;
				}

				$query = 'insert ignore into #__quiz_tags_stats(tag_id, num_items) values '.implode(',', $statinserts);
				$this->_db->setQuery($query);

				if(!$this->_db->query()){

					return false;
				}
			}

			// now remove all non-matching tags ids from the map
			$where = '';

			if(!empty($insertids)){

				$where = ' and tag_id not in ('.implode(',', $insertids).')';
			}

			$query = 'select tag_id from #__quiz_tagmap where item_id = '.$quiz_id.$where;
			$this->_db->setQuery($query);
			$removals = $this->_db->loadColumn();

			$where = '';

			if(!empty($removals)){

				$query = 'delete from #__quiz_tagmap where tag_id in ('.implode(',', $removals).')';
				$this->_db->setQuery($query);
				$this->_db->query();

				$where = ' or s.tag_id in ('.implode(',', $removals).')';
			}

			// now update the stats
			$query = '
				update
					#__quiz_tags_stats s
				set
					s.num_items = (select count(*) from #__quiz_tagmap m where m.tag_id = s.tag_id)
				where
					s.tag_id in (select tag_id from #__quiz_tagmap m1 where m1.item_id = '.$quiz_id.')'.$where;

			$this->_db->setQuery($query);
			$this->_db->query();
		} else {

			$query = 'delete from #__quiz_tagmap where item_id = '.$quiz_id;
			$this->_db->setQuery($query);
			$this->_db->query();
		}
	}

	function get_category_flat_list($parent = 0) {

		$tree = new CjNestedTree($this->_db, '#__quiz_categories');

		return $tree->get_children($parent, true);
	}

	function get_categories() {

		$tree = new CjNestedTree($this->_db, '#__quiz_categories');

		return $tree->get_tree();
	}

	function get_category_tree(){

		$tree = new CjNestedTree($this->_db, '#__quiz_categories');
			
		return $tree->get_selectables();
	}

	function get_category($id){

		$tree = new CjNestedTree($this->_db, '#__quiz_categories');

		return $tree->get_node($id);
	}

	function get_breadcrumbs($id){

		$tree = new CjNestedTree($this->_db, '#__quiz_categories');

		return $tree->get_path($id);
	}
	
		function get_question_list($id){
		$query = '
    		select
    			*
    		from
    			#__quiz_questions
    		where
    			quiz_id='.$id;
			
		$this->_db->setQuery($query);
			$answers = $this->_db->loadObjectList();
		return $answers;
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

	function get_questions($quiz_id, $page_id = 0, $get_answers = true){
			
		$user = JFactory::getUser ();
		$where = '';

		if($page_id){

			$where = ' and page_number=' . $page_id;
		}

		$query = '
			select 
				id, quiz_id, question_type, page_number, sort_order, include_custom, mandatory, title, description, answer_explanation, orientation, 0 as total_votes
			from 
				#__quiz_questions 
			where 
				quiz_id='.$quiz_id.$where.' 
			order by 
				page_number, sort_order, question_type asc';
		$this->_db->setQuery ( $query );
		$questions = $this->_db->loadObjectList ( 'id' );

		if (!empty($questions)) {

			if($get_answers) {

				$query = '
					select 
						id, question_id, answer_type, title, correct_answer, marks, sort_order, image
					from 
						#__quiz_answers 
					where 
						quiz_id='.$quiz_id.' and 
						question_id in (
							select 
								id 
							from 
								#__quiz_questions 
							where 
								quiz_id='.$quiz_id.$where.'
						) 
					order by 
						question_id, sort_order asc';

				$this->_db->setQuery ( $query );
				$answers = $this->_db->loadObjectList ();

				if ($answers && (count ( $answers ) > 0)) {

					foreach ( $answers as $answer ) {

						$questions [$answer->question_id]->answers[] = $answer;
					}

					return $questions;
				} else {

					$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10075<br>query: ' . $query . '<br><br>' );

					return false;
				}
			} else {

				return $questions;
			}
		} else {

			$error = $this->_db->getErrorMsg ();

			if (! empty ( $error )) {

				$this->setError ( $error . '<br><br> Error code: 10076<br>query: ' . $query . '<br><br>' );
			}

			return false;
		}
	}

	function remove_page($quiz_id, $pid) {

		if(!$this->authorize_quiz($quiz_id)){

			$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10030<br>query: ' . $query . '<br><br>' );

			return false;
		}

		$query = 'delete from #__quiz_answers where question_id in (select id from #__quiz_questions where page_number='.$pid.')';
		$this->_db->setQuery($query);

		if($this->_db->query()){

			$query = 'delete from #__quiz_questions where page_number='.$pid;
			$this->_db->setQuery($query);

			if($this->_db->query()){

				$query = 'delete from #__quiz_pages where quiz_id=' . $quiz_id . ' and id=' . $pid;
				$this->_db->setQuery ( $query );

				if ($this->_db->query ()) {

					return true;
				}
			}
		}

		$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10031<br>query: ' . $query . '<br><br>' );

		return false;
	}

	function move_question($quiz_id, $qid, $pid) {

		if(!$this->authorize_quiz($quiz_id)){

			$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10030<br>query: ' . $query . '<br><br>' );

			return false;
		}

		$query = 'update #__quiz_questions set page_number='.$pid.' where id = '.$qid.' and quiz_id = '.$quiz_id;
		$this->_db->setQuery($query);
			
		if($this->_db->query()){

			return true;
		}

		$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10031<br>query: ' . $query . '<br><br>' );

		return false;
	}

	function get_pages($package_id, $uniq_key){
		$query = "
				SELECT id, sort_order, title FROM #__quiz_pages
				WHERE uniq_key = '".$uniq_key."' ORDER BY sort_order DESC
			";
		$this->_db->setQuery ( $query );
		$data = $this->_db->loadObjectList();
		return $data;
	}

	function delete_question($quiz_id, $pid, $qid){

		if(!$this->authorize_quiz($quiz_id)){

			$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10050<br>query: ' . $query . '<br><br>' );

			return false;
		}

		$query = 'delete from #__quiz_answers where quiz_id='.$quiz_id.' and question_id='.$qid;
		$this->_db->setQuery($query);

		if($this->_db->query()){

			$query = 'delete from #__quiz_questions where quiz_id='.$quiz_id.' and id='.$qid;
			$this->_db->setQuery($query);

			if($this->_db->query()){

				$query = 'select id from #__quiz_questions where quiz_id='.$quiz_id.' and page_number='.$pid.' order by sort_order asc';
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

	function reorder_question($quiz_id,$qid,$direction){

		if(!$this->authorize_quiz($quiz_id)){

			$this->setError ( $this->_db->getErrorMsg () . '<br><br> Error code: 10051<br>query: ' . $query . '<br><br>' );

			return false;
		}

		$query = 'select sort_order from #__quiz_questions where id='.$qid. ' and quiz_id='.$quiz_id;
		$this->_db->setQuery($query);
		$my_order = (int)$this->_db->loadResult();
		$swap = null;

		if($direction == 1){

			$query = '
				select 
					id, sort_order 
				from 
					#__quiz_questions 
				where 
					quiz_id='.$quiz_id.' and sort_order < '.$my_order. ' 
				order by 
					sort_order desc';

			$this->_db->setQuery($query, 0, 1);
			$swap = $this->_db->loadObject();
		}else{

			$query = '
				select 
					id, sort_order 
				from 
					#__quiz_questions 
				where 
					quiz_id='.$quiz_id.' and sort_order > '.$my_order.' 
				order by 
					sort_order asc';

			$this->_db->setQuery($query, 0, 1);
			$swap = $this->_db->loadObject();
		}

		if($swap && $swap->id){

			$query = 'update #__quiz_questions set sort_order='.$my_order.' where id='.$swap->id;
			$this->_db->setQuery($query);

			if($this->_db->query()){

				$query = 'update #__quiz_questions set sort_order='.$swap->sort_order.' where id='.$qid.' and quiz_id='.$quiz_id;
				$this->_db->setQuery($query);

				if($this->_db->query()){

					return $swap->sort_order;
				}
			}
		}

		$this->setError($this->_db->getErrorMsg());

		return false;
	}

	function update_ordering($id, $pid, $ordering){

		if(!$this->authorize_quiz($id)){

			$this->setError( '<br>'.$this->_db->getErrorMsg () . '<br><br> Error code: 10051<br>query: ' . $query . '<br><br>' );

			return false;
		}

		$query = 'update #__quiz_questions set sort_order = case id ';
		$updates = array();

		foreach($ordering as $order){

			$tokens = explode('_', trim($order));

			if(count($tokens) == 2){

				$updates[] = sprintf('when %d then %d', $tokens[1], $tokens[0]);
			}
		}

		if(count($updates) > 0){

			$query = $query.implode(' ', $updates).' end where quiz_id='.$id;
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

		if ( $qtype <= 0 || $user->guest || strlen ( $title ) <= 0)
		{
			$this->setError (JText::_ ( 'MSG_UNAUTHORIZED' ) . '<br><br>Error code: 10001<br>id: ' . $quiz_id . '<br>pid: ' . $pid . '<br>qtype: ' . $qtype . '<br>title: ' . $title . '<br><br>' );
			return false;
		}

		$order = ($qtype == 1) ? 1 : ($order < 2 ? 2 : $order);

		// First manipulate with question related changes
		if ($qid > 0)
		{
			// Be on safe side, check if user sending manipulated values.
			$query = 'select count(*) from #__quiz_questions where id=' . $qid . ' and quiz_id = '. $quiz_id;
			$this->_db->setQuery ( $query );
			echo $count = ( int )$this->_db->loadResult ();
			exit;
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

	function finalize_quiz($id){

		$user = JFactory::getUser();
		$config = JComponentHelper::getParams(Q_APP_NAME);

		if($id > 0){

			if(!$this->authorize_quiz($id)){

				$this->setError(JText::_('MSG_UNAUTHORIZED'));
				return false;
			}

			if(!$user->authorise('quiz.autoapprove', Q_APP_NAME)){

				$query = 'update #__quiz_quizzes set published = 2 where id='.$id.' and published != 1';
				$this->_db->setQuery($query);

				if($this->_db->query()){

					return true;
				}
			}else{

				$query = 'update #__quiz_quizzes set published = 1 where id='.$id.' and published = 3';
				$this->_db->setQuery($query);

				if($this->_db->query()){

					$count = $this->_db->getAffectedRows();

					if( $count > 0 ){

						$query = '
								select 
									id, nleft, nright 
								from 
									#__quiz_categories 
								where 
									id=(select catid from #__quiz_quizzes where id='.$id.' and published = 1)';

						$this->_db->setQuery($query);
						$category = $this->_db->loadObject();
							
						if(!empty($category)){

							$query = '
									update 
										#__quiz_categories 
									set 
										quizzes = quizzes + 1 
									where 
										nleft<='.$category->nleft.' and nright>='.$category->nright;

							$this->_db->setQuery($query);
							$this->_db->query();
						}

						$query = '
								update 
									#__quiz_users 
								set 
									quizzes = (select count(*) from #__quiz_quizzes where created_by = '.$user->id.' and published = 1) 
								where 
									id = '.$user->id;

						$this->_db->setQuery($query);
						$this->_db->query();

						// Trigger badges
						$params = JComponentHelper::getParams('com_communityquiz');
						$badge_system = $params->get('badge_system', 'none');

						if($badge_system == 'cjblog' && file_exists(JPATH_ROOT.'/components/com_cjblog/api.php')){

							require_once JPATH_ROOT.'/components/com_cjblog/api.php';

							$db = JFactory::getDbo();
							$query = 'select id, quizzes from #__quiz_users where id = '.$user->id;
							$db->setQuery($query);
							$my = $db->loadObject();

							// trigger badges rule
							CjBlogApi::trigger_badge_rule('com_communityquiz.num_quizzes', array('num_quizzes'=>$my->quizzes, 'ref_id'=>$id), $user->id);
						}

					}

					return true;
				}
			}

			$this->setError($this->_db->getErrorMsg());
		}

		$this->setError("Invalid quiz.");

		return false;
	}

	function do_create_update_response(){

		$app = JFactory::getApplication();
		$user = JFactory::getUser();

		$quiz_id = $app->input->getInt('id', 0);
		$response_id = $app->input->getInt('rid', 0);
		$page_id = $app->input->getVar('page', 0, 'post', 'int');
		$created = 'now';

		if($quiz_id && !$response_id){

			if(!$user->guest){

				$query = 'select id, created from #__quiz_responses where created_by='.$user->id.' and quiz_id='.$quiz_id.' and completed=0';
				$this->_db->setQuery($query);
				$response = $this->_db->loadObject();

				if(!empty($response)){

					$response_id = $response->id;
					$created = $response->created;
				}

				$page_id = 0;
			}
		}

		if($response_id){

			$query = 'select created, quiz_id from #__quiz_responses where id='.$response_id;
			$this->_db->setQuery($query);
			$response = $this->_db->loadObject();

			if(!empty($response)){

				$quiz_id = $response->quiz_id;
				$created = $response->created;
			}
		}

		if(!$quiz_id){

			$this->setError(JText::_('MSG_NO_QUIZ_FOUND'));

			return false;
		}

		// Check if quiz is published
		$query = 'select count(*) from #__quiz_quizzes where published = 1 and id='.$quiz_id;
		$this->_db->setQuery($query);
		$result = (int)$this->_db->loadResult();

		if(!$result){

			$this->setError(JText::_('MSG_UNAUTHORIZED'));

			return false;
		}

		// Get the quiz details
		$quiz = $this->get_quiz_details($quiz_id, true, true);
		if($quiz->multiple_responses != '1' && !$user->guest){

			$query = 'select count(*) from #__quiz_responses where quiz_id='.$quiz->id.' and created_by='.$user->id.' and completed=1';
			$this->_db->setQuery($query);
			$count = (int)$this->_db->loadResult();

			if($count > 0){

				$this->setError(JText::_('MSG_ALREADY_TAKEN'));

				return false;
			}
		}

		// Create new response if not exists
		if(!$response_id){

			$ip_address = '0.0.0.0';//CJFunctions::get_user_ip_address();
			$location = '';//CJFunctions::get_user_location($ip_address);
			$browser = CJFunctions::get_browser();
			$created = JFactory::getDate()->toSql();

			$query = '
				insert into 
					#__quiz_responses(quiz_id, created_by, completed, created, ip_address, country, city, browser_info, os) 
				values ('
				. $quiz_id . ','
				. $user->id . ','
				. '0,'
				. $this->_db->quote($created) . ','
				. $this->_db->quote($ip_address) . ','
				. $this->_db->quote($location['country_code']) . ','
				. $this->_db->quote($location['city']) . ','
				. $this->_db->quote($browser['name'] . ' ' . $browser['version']).','
				. $this->_db->quote($browser['platform']).'
				)';
					
				$this->_db->setQuery($query);
					
				if(!$this->_db->query()){

					$this->setError(JText::_('MSG_UNABLE_TO_CREATE_RESPONSE').(Q_DEBUG_ENABLED ? $this->_db->getErrorMsg() : ''));

					return false;
				}
					
				$response_id = $this->_db->insertid();
				$page_id = 0;
		}

		$quiz->response_id = $response_id;
		$quiz->response_created = $created;
		$quiz->current_page = $page_id;

		return $quiz;
	}

	function get_next_page($quiz_id, $current_page){

		$wheres = array();
		$wheres[] = 'quiz_id = '.$quiz_id;

		if($current_page <= 0){

			$wheres[] = 'sort_order > 0';
		}else{

			$wheres[] = 'sort_order > '.$current_page;
		}

		$where = ' where '.implode(' and ', $wheres);

		$query = 'select id, sort_order from #__quiz_pages'.$where.' order by sort_order asc';
		$this->_db->setQuery($query, 0, 2);

		$pages = $this->_db->loadObjectList();

		if(empty($pages)){

			$this->setError($this->_db->getErrorMsg().$query);
		}

		return $pages;
	}

	function get_previous_page($quiz_id, $current_page){

		$wheres = array();
		$wheres[] = 'quiz_id = '.$quiz_id;

		if($current_page <= 0){

			return false;
		}else{

			$wheres[] = 'sort_order < '.$current_page;
		}

		$where = implode(' and ', $wheres);

		$query = 'select id, sort_order from #__quiz_pages where '.$where.' order by sort_order desc';
		$this->_db->setQuery($query, 0, 2);

		return $this->_db->loadObjectList();
	}

	function is_response_expired($quiz_id, $response_id){

		$query = 'select duration from #__quiz_quizzes where id='.$quiz_id;
		$this->_db->setQuery($query);
		$duration = (int)$this->_db->loadResult();

		if($duration == 0) return false;

		$query = 'select created, completed, finished > created as closed from #__quiz_responses where id='.$response_id;
		$this->_db->setQuery($query);
		$response = $this->_db->loadObject();

		if(empty($response->created)) return true;

		if($response->completed == 1){

			return true;
		}

		$created = JFactory::getDate($response->created);
		$now = JFactory::getDate();

		if(($now->toUnix() - $created->toUnix()) > ($duration*60 + 10)){

			return true;
		}

		return false;
	}

	function save_response($quiz_id, $pid, $rid, $ignore_error = false){

		$app = JFactory::getApplication();
		$user = JFactory::getUser ();

		$questions = $this->get_questions( $quiz_id, $pid );
		$config = JComponentHelper::getParams(Q_APP_NAME);
		$html_allowed = $user->authorise('quiz.wysiwyg', Q_APP_NAME) && $config->get('default_editor', 'bbcode') == 'default';
		// validate if legimate user
		if(!$user->authorise('response.save_response', Q_APP_NAME)){

			$query = 'select created_by from #__quiz_responses where id = '.$rid;
			$this->_db->setQuery($query);
			$created_by = (int) $this->_db->loadResult();

			if($created_by != $user->id) {
				if(!$ignore_error) {
					CJFunctions::throw_error(JText::_('MSG_UNAUTHORIZED'), 401);
				} else {
					$this->setError(JText::_('MSG_UNAUTHORIZED').'| Error: 1');
				}

				return false;
			}
		}
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

						break;

					case 5 : // Grid - Radio

						$rows = array ();
						$columns = array ();

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

								array_push ( $answers, $answer );
							}
						}

						break;

					case 6 : // Grid - Checkbox

						$rows = array ();
						$columns = array ();

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

									array_push ( $answers, $answer );
								}
							}
						}

						break;

					case 7 : // Freetext - Singleline
					case 8 : // Freetext - Multiline
					case 9 : // Freetext - Password

						$free_text = $app->input->post->getString('free-text-'.$question->id, null);

						break;

					case 10 : // Freetext - Rich text

						$free_text = CJFunctions::get_clean_var('free-text-'.$question->id, $html_allowed);

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
					#__quiz_response_details
				where
					response_id='.$rid.' and question_id in (select id from #__quiz_questions where quiz_id='.$quiz_id.' and page_number='.$pid.')';

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

					$query = 'insert into #__quiz_response_details (response_id, question_id, answer_id, column_id, free_text) values '.$query;
					$query = substr($query, 0, -1);
					$this->_db->setQuery($query);

					if($this->_db->query()){

						return true;
					}
				}else{

					return true;
				}
			}
		}

		return false;
	}

	function save_correct_answers($quiz_id){

		$user = JFactory::getUser ();
		$questions = $this->get_questions( $quiz_id );
		$config = QuizHelper::getConfig();

		if(!$this->authorize_quiz($quiz_id)){

			return false;
		}

		if (! empty ( $questions )) {

			$answers = array ();
			$queries = array();
			$queries[] = 'update #__quiz_answers set correct_answer=0 where quiz_id='.$quiz_id;

			foreach ( $questions as $question ) {

				switch ($question->question_type) {

					case 2 : // Choice - Radio

					case 4 : // Choice - Select box

						$answer_id = JRequest::getVar ( 'answer' . $question->id, 0, 'post', 'int' );
						$queries[] = 'update #__quiz_answers set correct_answer=1 where id='.$answer_id;

						break;

					case 3 : // Choice - Checkbox

						$answer_ids = JRequest::getVar ( 'answer' . $question->id, array (), 'post', 'array' );
						JArrayHelper::toInteger ( $answer_ids );

						if (! empty ( $answer_ids )) {

							$queries[] = 'update #__quiz_answers set correct_answer=1 where id in ('.implode(',', $answer_ids).')';
						}

						break;

					case 5 : // Grid - Radio

						$rows = array ();
						$columns = array ();

						foreach ( $question->answers as $answer ) {

							if ($answer->answer_type == 'x') {

								$rows [] = $answer;
							} else if ($answer->answer_type == 'y') {

								$columns [] = $answer;
							}
						}

						foreach ( $rows as $row ) {

							$column_id = JRequest::getVar ( 'answer' . $question->id . $row->id, 0, 'post', 'int' );
							$queries[] = 'update #__quiz_answers set correct_answer='.$column_id.' where id='.$row->id;
						}

						break;

					case 6 : // Grid - Checkbox

						$rows = array ();
						$columns = array ();

						foreach ( $question->answers as $answer ) {

							if ($answer->answer_type == 'x') {

								$rows [] = $answer;
							} else if ($answer->answer_type == 'y') {

								$columns [] = $answer;
							}
						}

						foreach ( $rows as $row ) {

							$column_ids = JRequest::getVar ( 'answer' . $question->id . $row->id, array (), 'post', 'array' );
							JArrayHelper::toInteger ( $column_ids );

							if (! empty ( $column_ids )) {

								$queries[] = 'update #__quiz_answers set correct_answer='.implode(',', $column_ids).' where id='.$row->id;
							}
						}

						break;
				}

				$explanation = CJFunctions::get_clean_var('explanation'. $question->id, ($user->authorise('quiz.wysiwyg', Q_APP_NAME) && $config[CQ_DEFAULT_EDITOR] == 'default'));

				$queries[] = 'update #__quiz_questions set answer_explanation='.$this->_db->quote($explanation).' where id='.$question->id;
			}

			foreach ($queries as $query){

				$this->_db->setQuery($query);

				if(!$this->_db->query()){

					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		return true;
	}

	function finalize_response($quiz_id, $response_id){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$createdate = JFactory::getDate()->toSql();
		$params = JComponentHelper::getParams(Q_APP_NAME);
		$score = $this->get_score($quiz_id, $response_id);

		$query = 'update #__quiz_responses set score = '.$score.', completed = 1, finished='.$this->_db->quote($createdate).' where id = '.$response_id;
		$this->_db->setQuery($query);

		if($this->_db->query()){

			$query = 'update #__quiz_quizzes set responses = responses + 1 where id=(select quiz_id from #__quiz_responses where id='.$response_id.')';
			$this->_db->setQuery($query);
			$this->_db->query();

			$query = '
					update 
						#__quiz_users 
					set 
						responses = (select count(*) from #__quiz_responses where created_by = '.$user->id.' and completed = 1) 
					where 
						id = '.$user->id;

			$this->_db->setQuery($query);
			$this->_db->query();

			if($params->get('enable_ratings', 1) == 1 && $user->authorise('quiz.rate', Q_APP_NAME)){

				$rating = $app->input->getInt('quiz-rating', 0);

				if($rating){

					$query = '
						insert into 
							#__corejoomla_rating_details(asset_id, item_id, action_id, rating, created_by, created) 
						values 
							('.CQ_ASSET_ID.','.$quiz_id .','.$response_id.','.$rating.','.$user->id.','.$this->_db->quote($createdate).')';

					$this->_db->setQuery($query);

					if($this->_db->query()){

						$query = '
							insert into 
								'.T_CJ_RATING.'(item_id, asset_id, total_ratings, sum_rating, rating) 
							values
								('.$quiz_id .','.CQ_ASSET_ID.', 1, '.$rating.','.$rating.')
							on duplicate key update 
								total_ratings = total_ratings + 1, 
								rating = ( sum_rating + '.$rating.' ) / total_ratings, 
								sum_rating = sum_rating + '.$rating;

						$this->_db->setQuery($query);
						$this->_db->query();
					}
				}
			}

			return $score;
		}

		$this->setError ($this->_db->getErrorMsg () . '<br><br> Error code: 10001<br>query: ' . $query . '<br><br>' );
		return false;
	}

	public function get_user_responses($userId)
	{
		$db = JFactory::getDbo();
		$query = 'select responses from #__quiz_users where id = '.$userId;
		$db->setQuery($query);
		$my = $db->loadResult();

		return $my;
	}
	
	public function get_answer_responses($response_id, $question_id)
	{
		$db = JFactory::getDbo();
		$query = 'select response_id,question_id, answer_id, column_id from #__quiz_response_details where response_id='.$response_id.'  order by question_id';

		$db->setQuery($query);
		$my = $db->loadObjectList();

		return $my;
	}
	
	function get_score($quiz_id, $response_id){

		$score = 0; $flag = false;
		$questions = $this->get_questions($quiz_id);

		if(empty($questions)) return $score;

		$query = 'select question_id, answer_id, column_id from #__quiz_response_details where response_id='.$response_id.' order by question_id';
		$this->_db->setQuery($query);
		$response_details = $this->_db->loadObjectList();

		if ( empty ( $response_details ) )
		{
			return $score;
		}

		foreach ( $questions as $question_id=>$question )
		{
			switch ( $question->question_type )
			{
				case 2: // Choice - Radio
				case 3: // Choice - Checkbox
				case 4: // Choice - Select box
				case 11: // Image - Radio
				case 12: // Image - Checkbox

					foreach ( $question->answers as $answer )
					{
						if( $answer->correct_answer == 1 )
						{
							foreach ( $response_details as $response )
							{
								if( $response->answer_id == $answer->id )
								{
									$score = $score + $answer->marks;
									break;
								}
							}
						}
					}

					break;

				case 5: // Grid - Radio
				case 6: // Grid checkbox

					$answers = array ();

					foreach ( $question->answers as $answer ) {
							
						if ( $answer->answer_type == 'x' ) {

							$answers [] = $answer;
						}
					}

					foreach ($response_details as $response)
					{
						// compare only if the response is for this question to optimize performance
						if($response->question_id == $question_id)
						{
							foreach ($answers as $answer)
							{
								if ($response->answer_id == $answer->id && $response->column_id == $answer->correct_answer)
								{
									$score = $score + $answer->marks;
									break;
								}
							}
						}
					}

					break;
			}
		}

		return $score;
	}

	function get_quiz_id($response_id){

		$query = 'select quiz_id from #__quiz_responses where id='.$response_id;
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	function get_response_details($response_id, $quiz_id=0, $page_id = 0, $validate = true){

		$user = JFactory::getUser();

		//if($validate && !$user->authorise('quiz.manage', Q_APP_NAME)){

			$query = 'select created_by from #__quiz_responses where id='.$response_id;
			$this->_db->setQuery($query);
			$result = (int) $this->_db->loadResult();

			/*
			if($user->id == $result){

				if($quiz_id > 0){

					$query = 'select created_by from #__quiz_quizzes where id='.$quiz_id;
					$this->_db->setQuery($query);
					$result = (int) $this->_db->loadResult();
				}

				if($user->id != $result) return false;
			}
			*/
			/* Above code modified to below code by Sushil on 01-12-2015 */
			if($user->id == $result){

				if($quiz_id > 0){

					$query = 'select created_by from #__quiz_quizzes where id='.$quiz_id;
					$this->_db->setQuery($query);
					$result = (int) $this->_db->loadResult();
				}else{
					return false;
				}
			}
		//}

		$query = '
			select 
				a.question_id, a.answer_id, a.column_id, a.free_text 
			from 
				#__quiz_response_details a 
			where 
				a.response_id='.$response_id.( $page_id > 0 ? ' and question_id in (select id from #__quiz_questions where page_number = '.$page_id.')' : '' ).' 
			order by 
				a.question_id';

		$this->_db->setQuery($query);
		$responses = $this->_db->loadObjectList();

		return $responses;
	}

	function get_response_created_time($rid){

		$query = 'select created from #__quiz_responses where id = '.$rid;
		$this->_db->setQuery($query);
		$created = $this->_db->loadResult();

		return $created;
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

	public function authorize_quiz_response($rid, $userid){

		$query = 'select count(*) from #__quiz_responses where id = '.$rid.' and created_by = '.$userid;
		$this->_db->setQuery($query);
		$count = $this->_db->loadResult();

		if(!$count) $this->setError($this->_db->getErrorMsg());

		return $count > 0;
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

	public function subscribe($type, $userid, $itemid){

		$query = 'select subid from #__quiz_users where id = '.$userid;
		$this->_db->setQuery($query);
		$subid = $this->_db->loadResult();

		if(empty($subid)){

			$subid = CJFunctions::generate_random_key(12);

			$query = '
					insert into 
						#__quiz_users (id, subid) 
					values 
						('.$userid.','.$this->_db->quote($subid).') 
					on duplicate key 
						update subid = values(subid)';

			$this->_db->setQuery($query);

			if(!$this->_db->query()){

				return false;
			}
		}

		$query = 'insert ignore into #__quiz_subscribes (subscriber_id, subscription_type, subscription_id) values ('.$userid.','.$type.','.$itemid.')';
		$this->_db->setQuery($query);

		if($this->_db->query()){

			return true;
		}

		return false;
	}

	public function unsubscribe($type, $userid, $itemid){

		$query = 'delete from #__quiz_subscribes where subscriber_id = '.$userid.' and subscription_id = '.$itemid.' and subscription_type = '.$type;
		$this->_db->setQuery($query);

		if($this->_db->query()){

			return true;
		}

		return false;
	}

	public function check_subscription($type, $userid, $catid){

		$query = '
				select 
					count(*) 
				from 
					#__quiz_subscribes 
				where 
					subscriber_id='.$userid.' and 
					subscription_id='.$catid.' and 
					subscription_type in ('.implode(',', $type).')';

		$this->_db->setQuery($query);
		$count = (int)$this->_db->loadResult();

		return $count > 0;
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

	public function update_fund_history($userid, $quiz_id, $response_id){
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
				FROM #__quiz_question_giftcode a
				LEFT JOIN #__quiz_responses c ON c.quiz_id = a.quiz_id AND c.id = '".$response_id."'
				LEFT JOIN #__quiz_response_details b ON b.response_id = c.id 
				WHERE a.package_id = '".$account->package_id."'
				AND a.quiz_id = '".$quiz_id."'
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
					values ('".$fundingId."', '".$username." - Quiz - Quiz taken - Award Category = ".$giftcode." x ".$giftcode_quantity."', '0', '".$costResponse."', '".$now."', null, 'QUIZ')";				
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
								$this->_db->query();}
							}
	
	public function update_giftcode_history($userid, $quiz_id, $response_id){
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
				FROM #__quiz_question_giftcode a
				LEFT JOIN #__quiz_responses c ON c.quiz_id = a.quiz_id AND c.id = '".$response_id."'
				LEFT JOIN #__quiz_response_details b ON b.response_id = c.id 
				WHERE a.package_id = '".$account->package_id."'
				AND a.quiz_id = '".$quiz_id."'
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
					values ('".$fundingId."', '".$username." - Quiz - Quiz taken - Award Category = ".$giftcode." x ".$giftcode_quantity."', '0', '".$costResponse."', '".$now."', null, 'QUIZ')";				
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
								$this->_db->query();}
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
		

		public function getQuiz($quizid){
		$query = "
				SELECT q.* FROM #__quiz_quizzes q WHERE q.id='".$quizid."' order by q.id DESC
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

	function get_quiz_question_giftcode($package_id, $quiz_id, $page_number, $question_id, $uniq_key){
		$query = "SELECT a.* FROM #__quiz_question_giftcode a
					LEFT JOIN #__quiz_pages b ON b.id = a.page_number AND b.id = '".$page_number."' AND b.uniq_key = '".$uniq_key."'
					WHERE a.package_id = '".$package_id."' AND a.uniq_key = '".$uniq_key."'
				   AND a.question_id = '".$question_id."'";
		$this->_db->setQuery ( $query );
		$data = $this->_db->loadObjectList();
		return $data;
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

	function insert_quiz_question_giftcode ($package_id, $quiz_id, $page_number, $question_id, $uniq_key){
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

	function get_quiz_question_giftcode2($package_id, $quiz_id, $page_number, $uniq_key)	{
		$query = "SELECT a.* FROM #__quiz_question_giftcode a
					LEFT JOIN #__quiz_pages b ON b.id = a.page_number AND b.id = '".$page_number."' AND b.uniq_key = '".$uniq_key."'
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

	public function get_questions_2($uniq_id, $question_id, $page_id){
		$user = JFactory::getUser ();
		$query = "
			select 
				* 
			from 
				#__quiz_questions 
			where 
				uniq_key = '".$uniq_id."' and id = '".$question_id."' and page_number = '".$page_id."' 
			order by 
				page_number asc";	
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
	
	function get_result($rid,$qid,$userid) {
		$this->_db = &JFactory::getDBO ();
			
		$query = "SELECT DISTINCT a.quiz_id, a.completed, a.score,a.created_by, b.complete_giftcode, b.incomplete_giftcode, b.complete_giftcode_quantity, 
		           b.incomplete_giftcode_quantity, c.colour_code , c.category_id
		FROM #__quiz_responses a
		LEFT JOIN #__quiz_question_giftcode b ON b.quiz_id = a.quiz_id
		LEFT JOIN #__ap_categories as c ON LOWER(c.category_name) = LOWER(b.complete_giftcode)
		WHERE a.quiz_id = '".$qid."'  AND a.created_by = '".$userid."'  " ;
		$this->_db->setQuery ( $query );
		$respon = $this->_db->loadObjectList();
		
		return $respon;
	}
	
	function get_giftcode($qid) {
		$this->_db = &JFactory::getDBO ();
		$query = "SELECT a.*, b.color_code 
		          FROM #__quiz_question_giftcode as a
				  LEFT JOIN #__giftcode_category as b ON LOWER(b.name) = LOWER(a.complete_giftcode)			
     			WHERE quiz_id = '".$qid."'";		
		$this->_db->setQuery($query);
		$giftcode = $this->_db->loadObjectList();		
		return $giftcode;
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
}
?>