<?php
/**
 * @version		$Id: view.html.php 01 2013-01-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );
class AwardpackageViewQuiz extends JViewLegacy {

	protected $params;
	protected $print;
	protected $state;

	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		
					$users = AwardPackageHelper::getUserData();
					$userId = $users->id;
					$packageId = $users->package_id;
					$paypalacc = $users->paypal_account;
		$packagedate = AwardPackageHelper::getPackageId($packageId);
		foreach ($packagedate as $row ){
		$enddate = date("Y-m-d", strtotime($row->end_date));
		}
		$today = date("Y-m-d"); 
					if ($today > $enddate) {
					$expired = 1;
					}
		$this->assignRef('expired', $expired);		
		$app = JFactory::getApplication();
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		//$this->quiz_model = = & JModelLegacy::getInstance( 'quiz', 'AwardpackageUsersModel' );
		$document = JFactory::getDocument();
		$user = JFactory::getUser();

		$pathway = $app->getPathway();
		$users_model = $this->getModel('users');
		$active = $app->getMenu()->getActive();
		$itemid = CJFunctions::get_active_menu_id();

		$this->print = $app->input->getBool('print');
		$page_heading = '';

		/********************************** PARAMS *****************************/
		$appparams = JComponentHelper::getParams(Q_APP_NAME);
		$menuParams = new JRegistry;

		if ($active) {
			$menuParams->loadString($active->params);
		}

		$this->params = clone $menuParams;
		$this->params->merge($appparams);
		/********************************** PARAMS *****************************/

		$limit = $this->params->get('list_length', $app->getCfg('list_limit', 20));
		$limitstart = $app->input->getInt('start', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$catid = $app->input->getInt('id', 0);

		if(!$catid){

			$menuid = CJFunctions::get_active_menu_id(false);
			$menuparams = $app->getMenu()->getParams( $menuid );
			$catid = (int)$menuparams->get('catid', 0);
			$app->input->set('id', $catid);
		}
		switch($this->action){

			case 'latest_quizzes':
				$options = array('catid'=>$catid, 'limit'=>$limit, 'limitstart'=>$limitstart);
				$return = $model->get_quizzes(1, $options, $this->params);

				$users_model->load_users_from_items($return['quizzes'], $this->params->get('user_avatar', 'none'));

				$this->assignRef('items', $return['quizzes']);
				$this->assignRef('state', $return['state']);
				$this->assignRef('pagination', $return['pagination']);
				//$this->assignRef('giftcode', $return['totgiftcode']);
				$ttl_quiz=count($this->items);
				$this->assignRef('total', $ttl_quiz);
				if($this->params->get('display_cat_list', 1) == 1){
					$categories = $model->get_category_flat_list($catid);
					$this->assignRef('categories', $categories);
				}
				
				$this->assign('task', '');
				$this->assign('brand', JText::_('TXT_LATEST_QUIZZES'));
				$this->assign('brand_url', 'index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_latest_quizzes'.$itemid);
				$this->assign('page_url', 'index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_latest_quizzes'.$itemid);

				$page_heading = JText::_('TXT_LATEST_QUIZZES');
				break;

			case 'popular_quizzes':
					
				$options = array('catid'=>$catid, 'limit'=>$limit, 'limitstart'=>$limitstart);
				$return = $model->get_quizzes(2, $options, $this->params);
				$users_model->load_users_from_items($return['quizzes'], $this->params->get('user_avatar', 'none'));
					
				$this->assignRef('items', $return['quizzes']);
				$this->assignRef('state', $return['state']);
				$this->assignRef('pagination', $return['pagination']);
				$ttl_quiz=count($this->items);
				$this->assignRef('total', $ttl_quiz);
					
				if($this->params->get('display_cat_list', 1) == 1){

					$categories = $model->get_category_flat_list($catid);
					$this->assignRef('categories', $categories);
				}
					
				$this->assign('task', '');
				$this->assign('brand', JText::_('TXT_MOST_POPULAR_QUIZZES'));
				$this->assign('brand_url', 'index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_popular_quizzes'.$itemid);
				$this->assign('page_url', 'index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_popular_quizzes'.$itemid);
					
				$page_heading = JText::_('TXT_MOST_POPULAR_QUIZZES');
				break;

			case 'top_rated_quizzes':
					
				$options = array('catid'=>$catid, 'limit'=>$limit, 'limitstart'=>$limitstart);
				$return = $model->get_quizzes(3, $options, $this->params);
				$users_model->load_users_from_items($return['quizzes'], $this->params->get('user_avatar', 'none'));
					
				$this->assignRef('items', $return['quizzes']);
				$this->assignRef('state', $return['state']);
				$this->assignRef('pagination', $return['pagination']);
				$ttl_quiz=count($this->items);
				$this->assignRef('total', $ttl_quiz);
					
				if($this->params->get('display_cat_list', 1) == 1){

					$categories = $model->get_category_flat_list($catid);
					$this->assignRef('categories', $categories);
				}
					
				$this->assign('task', '');
				$this->assign('brand', JText::_('TXT_TOP_RATED_QUIZZES'));
				$this->assign('brand_url', 'index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_top_rated_quizzes'.$itemid);
				$this->assign('page_url', 'index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_top_rated_quizzes'.$itemid);
					
				$page_heading = JText::_('TXT_TOP_RATED_QUIZZES');
				break;
					
			case 'tagged_quizzes':

				$id = $app->input->getInt('id', 0);
				$options = array('tagid'=>$id, 'limit'=>$limit, 'limitstart'=>$limitstart);
				$return = $model->get_quizzes(6, $options, $this->params);
				$users_model->load_users_from_items($return['quizzes'], $this->params->get('user_avatar', 'none'));
				$tag = $model->get_tag_details($id);

				$this->assignRef('items', $return['quizzes']);
				$this->assignRef('state', $return['state']);
				$this->assignRef('pagination', $return['pagination']);
				$ttl_quiz=count($this->items);
				$this->assignRef('total', $ttl_quiz);

				if($this->params->get('display_cat_list', 1) == 1){

					$this->assign('categories', array());
				}

				$this->assign('task', null);
				$this->assign('brand', JText::_('LBL_HOME'));
				$this->assign('brand_url', 'index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_latest_quizzes'.$itemid);
				$this->assign('page_url', 'index.php?option='.Q_APP_NAME.'&view=quiz&task=quiz.get_tagged_quizzes'.$itemid);

				if(!empty($tag->description)){

					$this->assign('page_description', $tag->description);
				}

				$page_heading = JText::sprintf('LBL_TAGGED_QUIZZES', $this->escape($tag->tag_text));
				$this->assign('page_header', $page_heading);
				break;

			case 'search':

				$search_keywords = $app->input->getString('q', ''); // search keywords
				$search_username = $app->input->getString('u', ''); // username
				$search_query_type = $app->input->getInt('qt', 0); // query type, search in titles or titles+description
				$search_exact_match = $app->input->getInt('m', 0); // exact match of username
				$match_all_words = $app->input->getInt('all', 0); // search in all, solved or unsolved
				$search_type = $app->input->getInt('type', 0); // search in all, solved or unsolved
				$search_order = $app->input->getInt('ord', 0); // search order type
				$search_order_dir = $app->input->getInt('dir', 0); // direction
				$search_categories = $app->input->getArray(array('cid'=>'array')); // category ids

				JArrayHelper::toInteger($search_categories['cid']);
				$search_categories = array_filter($search_categories['cid']);

				$order = $search_order == 1 ? 'a.answers' : ($search_order == 2 ? 'a.hits' : ($search_order == 3 ? 'c.title' : 'a.created'));
				$order_dir = $search_order_dir == 0 ? 'asc' : 'desc';
				$search_params = array('q'=>$search_keywords, 'u'=>$search_username, 'qt'=>$search_query_type, 'm'=>$search_exact_match, 'type'=>$search_type, 'all'=>$match_all_words);

				$options = array(
						'catid'=>$search_categories, 
						'search_params'=>$search_params,
						'limit'=>$limit, 
						'limitstart'=>$limitstart, 
						'order'=>$order, 
						'order_dir'=>$order_dir);

				$return = $model->get_quizzes(7, $options, $this->params);

				$query =
					'&q='.$search_keywords.
					'&u='.$search_username.
					'&qt='.$search_query_type.
					'&m='.$search_exact_match.
					'&type='.$search_type.
					'&ord='.$search_order.
					'&dir='.$search_order_dir.
				(!empty($search_categories) ? '&cid[]='.implode('&cid[]=', $search_categories) : '');

				$this->assignRef('items', $return['quizzes']);
				$this->assignRef('state', $return['state']);
				$this->assignRef('pagination', $return['pagination']);

				if($this->params->get('display_cat_list', 1) == 1){

					$this->assign('categories', array());
				}

				$this->assign('task', null);
				$this->assign('brand', JText::_('LBL_SEARCH'));
				$this->assign('brand_url', '#');
				$this->assign('page_url', 'index.php?option='.Q_APP_NAME.'&view=answers&task=search'.$query.$itemid);

				$page_heading = JText::_('LBL_SEARCH');
				break;
		}

		if(!in_array($this->action, array('search', 'tagged_quizzes'))){

			if($user->authorise('quiz.subscrcat', Q_APP_NAME)){

				$subscribed = $model->check_subscription(array(2, 3), $user->id, $catid);
				$this->assignRef('subscribed', $subscribed);
			}

			// breadcrumbs
			if($catid){

				$app->input->set('cqcatid', $catid); //set this only if it is not tags or search
				$breadcrumbs = $model->get_breadcrumbs($catid);

				if(!empty($breadcrumbs)){

					foreach($breadcrumbs as $id=>$breadcrumb){

						if($breadcrumb['parent_id'] > 0){

							$pathway->addItem($breadcrumb['title'], JRoute::_($this->page_url.'&id='.$breadcrumb['id'].':'.$breadcrumb['alias'].$itemid));
						}
					}
				}
			}
		}

		// set browser title
		$this->params->set('page_heading', $this->params->get('page_heading', $page_heading));

		// add to pathway
		$pathway->addItem($page_heading);

		if($catid > 0 && !in_array($this->action, array('search', 'tagged_quizzes'))){

			$category = $model->get_category($catid);

			$this->assignRef('category', $category);

			if(!empty($category)){
					
				$page_heading = $page_heading . ' - '. $category['title'];
			}
		}

		$title = $this->params->get('page_title', $app->getCfg('sitename'));

		if ($app->getCfg('sitename_pagetitles', 0) == 1) {

			$document->setTitle(JText::sprintf('JPAGETITLE', $title, $page_heading));
		} elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {

			$document->setTitle(JText::sprintf('JPAGETITLE', $page_heading, $title));
		} else {

			$document->setTitle($page_heading);
		}

		// set meta description
		if ($this->params->get('menu-meta_description')){

			$document->setDescription($this->params->get('menu-meta_description'));
		}

		// set meta keywords
		if ($this->params->get('menu-meta_keywords')){

			$document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		// set robots
		if ($this->params->get('robots')){

			$document->setMetadata('robots', $this->params->get('robots'));
		}

		// set nofollow if it is print
		if ($this->print){

			$document->setMetaData('robots', 'noindex, nofollow');
		}
		$this->assign('page_id', 4);
		parent::display($tpl);
	}

	private function load_users($quizzes){

		if(empty($quizzes)) return;

		$ids = array();

		foreach($quizzes as $quiz){

			$ids[] = $quiz->created_by;
		}

		if(!empty($ids)){

			CJFunctions::load_users($this->params->get('user_avatar'), $ids);
		}
	}
}