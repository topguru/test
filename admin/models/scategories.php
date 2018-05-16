<?php
/**
 * @version		$Id: categories.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
require_once JPATH_SITE.'/components/com_cjlib/tree/nestedtree.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageModelSCategories extends JModelLegacy {
	
	function __construct() {
		 
		parent::__construct();
	}
	
	
	function get_scategories($package_id) {
        
            $query = 'SELECT * FROM #__survey_categories WHERE package_id = '.$package_id;
        $this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		return $result;  
      }
	  
	function get_categories($node=0){
	
		$tree = new CjNestedTree($this->_db, '#__survey_categories');
		return $tree->get_tree($node);
	}
	
	function get_categories_tree($node = 0, $include_root=false,$packageId){
		 
		$tree = new CjNestedTree($this->_db, '#__survey_categories');
		$cat =  $tree->get_selectables($node, '.... ', $include_root);
		$db=JFactory::getDBO();
		$q="select id from #__survey_categories where package_id=$packageId";
		$db->setQuery($q);
		$catids=$db->loadObjectList();
		$categories = array();
		array_push($categories , $cat[1]);
		foreach($catids as $c){
			array_push($categories , $cat[$c->id]);
		}
		return $categories;
	}
	
	function get_category($id){
		 
		$tree = new CjNestedTree($this->_db, '#__survey_categories');
		return $tree->get_node($id);
	}
	
	function delete($id){
		 
		$tree = new CjNestedTree($this->_db, '#__survey_categories');
		return $tree->delete($id);
	}
	
	function save(){
		 
		$id = JRequest::getVar('id',0,'post','int');
		$title = trim(JRequest::getVar('title',null,'post','string'));
		$alias = trim(JRequest::getVar('alias',null,'post','string'));
		$parent_id = JRequest::getVar('category', 0, 'post', 'int');
		$locked = JRequest::getInt('locked', 0);
	
		if(!$alias){			 
			$alias = $title;
		}else{			 
			$alias = $alias;
		}	
		if(!empty($title)){
			$tree = new CjNestedTree($this->_db, '#__survey_categories', array('alias'));			 
			if($id > 0){	
				$query = 'update #__survey_categories set title = '.$this->_db->quote($title).', alias='.$this->_db->quote($alias).' where id = '.$id;
				$this->_db->setQuery($query);	
				if($this->_db->query()){					 
					$category = $tree->get_node($id);					 
					if($parent_id != $category['parent_id'] && $id != $parent_id){	
						$tree->move($id, $parent_id);
					}					 
					return true;
				}
			} else {	
				$nodeId = $tree->add($parent_id, $title, false, array($alias));
				
				$query = 'update #__survey_categories set package_id = '.JRequest::getVar('package_id').' where id = '.$nodeId.' ';
				
				$this->_db->setQuery($query);
				
				$this->_db->query();
				
				if(true){					 
					return true;
				}
			}
		}	
		die();
		return false;
	}
	
	function set_locked($status, $ids){		 
		$query = 'update #__survey_categories set locked='.$status.' where id in ('.$ids.')';
		$this->_db->setQuery($query);
		 
		if($this->_db->query()){
	
			return true;
		}
		 
		return false;
	}
	
	function movedown($id){
			 
		$query = 'select id, parent_id, norder from #__survey_categories where id='.$id;
		$this->_db->setQuery($query);
		$source = $this->_db->loadObject();
		 
		$query = 'select id, parent_id, norder from #__survey_categories'
		. ' where parent_id='.$source->parent_id.' and norder>'.$source->norder.' order by norder limit 1';
		$this->_db->setQuery($query);
		$target = $this->_db->loadObject();
		 
		if($target){
	
			$query = 'update #__survey_categories set norder='.$source->norder.' where id='.$target->id;
			$this->_db->setQuery($query);
	
			if($this->_db->query()){
				 
				$query = 'update #__survey_categories set norder='.$target->norder.' where id='.$source->id;
				$this->_db->setQuery($query);
				 
				if(!$this->_db->query()){
	
					$this->setError($this->_db->getErrorMsg());
				}
				 
				$tree = new CjNestedTree($this->_db, '#__survey_categories');
				return $tree->rebuild();
			}else{
				 
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}else{
	
			$this->setError($this->_db->getErrorMsg());
	
			return false;
		}
	}
	
	function moveup($id){
		 
		$query = 'select id, parent_id, norder from #__survey_categories where id='.$id;
		$this->_db->setQuery($query);
		$source = $this->_db->loadObject();
		 
		$query = 'select id, parent_id, norder from #__survey_categories'
		. ' where parent_id='.$source->parent_id.' and norder<'.$source->norder.' order by norder desc limit 1';
		$this->_db->setQuery($query);
		$target = $this->_db->loadObject();
		 
		if($target){
	
			$query = 'update #__survey_categories set norder='.$source->norder.' where id='.$target->id;
			$this->_db->setQuery($query);
	
			if($this->_db->query()){
				 
				$query = 'update #__survey_categories set norder='.$target->norder.' where id='.$source->id;
				$this->_db->setQuery($query);
				 
				if(!$this->_db->query()){
	
					$this->setError($this->_db->getErrorMsg());
				}
				 
				$tree = new CjNestedTree($this->_db, '#__survey_categories');
				return $tree->rebuild();
			}else{
				 
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}else{
	
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	}
	
	function sort($id, $new_parent){
		 
		$query = "select nleft, nright from #__survey_categories where id=" . $new_parent;
		$this->_db->setQuery($query);
		$parent = $this->_db->loadObject();
	
		if($parent->nleft && $parent->nright){
			 
			$query = '
					update 
						#__survey_categories 
					set 
						parent_id = '.$new_parent.' 
					where 
						id='.$id.' and not(' . $parent->nleft . ' between nleft and nright) and not(' . $parent->nright . ' between nleft and nright)';
		}else{
			 
			$query = 'update #__survey_categories set parent_id=' . $new_parent . ' where id='.$id;
		}
	
		$this->_db->setQuery($query);
	
		if($this->_db->query()){
			 
			$tree = new CjNestedTree($this->_db, '#__survey_categories');
			return $tree->rebuild();
		}else{
			 
			return false;
		}
	}
	
	function rebuild_categories(){	
		$this->refresh_categories();		 
		$tree = new CjNestedTree($this->_db, '#__survey_categories', array('alias'));
		return $tree->rebuild();
	}
	
	function refresh_categories(){		 
		$query = '
			UPDATE 
				#__survey_categories a 
			SET 
				a.surveys = (SELECT COUNT(*) FROM #__survey q WHERE q.catid = a.id and q.published = 1)';
		$this->_db->setQuery($query);		 
		if(!$this->_db->query()){			 
			return false;
		}		 
		$tree = new CjNestedTree($this->_db, '#__survey_categories', array('alias'));
		$tree->update_category_counts('#__survey', 'surveys');		
		$query = 'update #__survey a set responses = (select count(*) from #__survey_responses r where r.survey_id = a.id and r.completed = 1)';			
		$this->_db->setQuery($query);		 
		if(!$this->_db->query()){
			 
			return false;
		}
		
		return true;
	}
	
	function update_category_counts($nodes, $num_surveys = 0){
		 
		foreach ( $nodes as $id => $node ) {
	
			if( !empty($node['children'] ) ) {
				 
				$this->update_category_counts($node['children']);
			}
	
			if( !empty($catids) ) {
				 
				$query = '
					update 
						#__survey_categories 
					set 
						surveys = ( select count(*) from #__survey 
					where 
						catid in ( '.implode(',', $catids).' ) )';
				
				$this->_db->setQuery($query);
				$this->_db->query();
			}
	
			$catids[] = $node['id'];
		}
	}
}
?>