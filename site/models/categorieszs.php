<?php
/**
 * @version		$Id: categories.php 01 2012-09-20 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.modellist');
jimport('joomla.application.categories');

class AwardpackageUsersModelCategorieszs extends JModelList {

	protected $_items;
	protected $_item;
	private $_parent = null;

	function __construct() {

		parent::__construct ();
	}

	function get_categories1($parent = 0, $recursive = false, $packageId){

		if (!count($this->_items)) {
			
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
			$active = $menu->getActive();
			$params = new JRegistry();
             $users = AwardPackageHelper::getUserData();
			 $package_id = $users->package_id;
			if ($active) {
				$params->loadString($active->params);
			}
			$options = array();
			$options['countItems'] = $params->get('show_cat_num_surveys_cat', 1) || !$params->get('show_empty_surveys_cat', 0);
			$options['statefield'] = 'published';
			$options['packageId'] = $package_id;
			
			$categories = JCategories::getInstance('Awardpackage', $options);
			$this->_parent = $categories->get($parent);
			if (is_object($this->_parent)) {		
				$this->_items = $this->_parent->getChildren($recursive);
			}else {				
				$this->_items = false;
			}
		}
		return $this->_items;
	}
	
	function get_category($catid){
		
		if (!is_object($this->_item)) {
			
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
			$active = $menu->getActive();
			$params = new JRegistry();

			if ($active) {
				
				$params->loadString($active->params);
			}

			$options = array();
			$options['countItems'] = $params->get('show_cat_num_articles_cat', 1) || !$params->get('show_empty_categories_cat', 0);

			$catid = $catid > 0 ? $catid : 'root';
			$categories = JCategories::getInstance('CommunitySurveys', $options);
			$this->_item = $categories->get($catid);
			
			if (is_object($this->_item)) {
				
				$user	= JFactory::getUser();
				$userId	= $user->get('id');
				$asset	= 'com_content.category.'.$this->_item->id;
			
				if ($user->authorise('core.create', $asset)) {
					
					$this->_item->getParams()->set('access-create', true);
				}
			}
		}
		
		return $this->_item;
	}
	
	function get_categories($package_id) {
        
            $query = 'SELECT * FROM #__survey_categories WHERE package_id = '.$package_id;
        $this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		return $result;  
      }
	  
	 function get_survey_category($packageId){
	 	 
		$query = '
    			SELECT
        			count(*) as jml, a.id, a.title, a.alias, a.created_by, a.created, a.catid, a.published, a.responses, a.skip_intro, a.ip_address, 
    				a.survey_key, a.publish_up, a.publish_down, a.private_survey, a.max_responses, a.anonymous, a.public_permissions, 
    				a.display_template, a.introtext, a.endtext, a.custom_header, a.redirect_url,a.package_id,
        			c.title as category, c.alias as calias
        		FROM
        			#__survey a
        		LEFT JOIN
        			#__survey_categories c ON a.catid = c.id     
    			WHERE
    				a.package_id='.$packageId.'
			GROUP BY a.catid '   			
					;
			
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		return $results;
	}
	
}
?>