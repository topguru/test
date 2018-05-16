<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class AwardpackageModelApresentationcategory extends JModelLegacy {

	function __construct() {
		parent::__construct();
	}

	public function getPresentationCategories($ids = array(), $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest('com_awardpackage.apresentationcategory.filter_order', 'filter_order', 'category_id', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest('com_awardpackage.apresentationcategory.filter_order_dir', 'filter_order_Dir', 'ASC', 'word' );
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.apresentationcategory.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

		$query = "
				SELECT pac.`category_id`, pac.`colour_code`, pac.`category_name`, ppc.`date_modify`,
				COUNT(ppc.`presentation_id`) as presentations
				FROM #__ap_categories pac
				LEFT JOIN #__presentation_category ppc ON ppc.`category_id` = pac.`category_id` AND ppc.`package_id` = '" .JRequest::getVar('package_id'). "'
				WHERE pac.`package_id` = '" .JRequest::getVar('package_id'). "'
				GROUP BY pac.`category_id`, pac.`colour_code`, pac.`category_name`, ppc.`date_modify`				
			" . $order;
		
		$this->_db->setQuery($query, $limitstart, $limit);
		$return['categories'] = $this->_db->loadObjectList();

		$query = "
				SELECT COUNT(*) FROM (
					SELECT pac.`category_id`, pac.`colour_code`, pac.`category_name`, ppc.`date_modify`,
					COUNT(ppc.`presentation_id`)
					FROM #__ap_categories pac
					LEFT JOIN #__presentation_category ppc ON ppc.`category_id` = pac.`category_id` AND ppc.`package_id` = '" .JRequest::getVar('package_id'). "'
					WHERE pac.`package_id` = '" .JRequest::getVar('package_id'). "'
					GROUP BY pac.`category_id`, pac.`colour_code`, pac.`category_name`, ppc.`date_modify`
				) alias
			";					

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
		$return['lists'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit,
    			'order'=>$filter_order,
    			'order_dir'=>$filter_order_dir
		);
		return $return;
	}

	public function getAllCategories(){
		$query = "select * from #__ap_categories where package_id = '" .JRequest::getVar('package_id'). "' ";
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		return $results;
	}

	public function getPresentations($ids = array(), $limit = 20, $limitstart = 0){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.apresentationcategory.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$query = "
				SELECT psp.`presentation_id`, pss.`symbol_image`, pss.`pieces`, pss.`rows`, pss.`cols`,
				ps.`prize_image`
				FROM #__symbol_presentation psp
				INNER JOIN #__symbol_symbol_prize pssp ON pssp.`presentation_id` = psp.`presentation_id`
				INNER JOIN #__symbol_symbol pss ON pss.`symbol_id` = pssp.`symbol_id`
				INNER JOIN #__symbol_prize ps ON ps.`id` = pssp.`id`
				WHERE psp.`package_id` = '" .JRequest::getVar('package_id'). "'								
			";	


		$this->_db->setQuery($query, $limitstart, $limit);
		$return['presentations'] = $this->_db->loadObjectList();

		$query = "
				SELECT COUNT(*)
				FROM #__symbol_presentation psp
				INNER JOIN #__symbol_symbol_prize pssp ON pssp.`presentation_id` = psp.`presentation_id`
				INNER JOIN #__symbol_symbol pss ON pss.`symbol_id` = pssp.`symbol_id`
				INNER JOIN #__symbol_prize ps ON ps.`id` = pssp.`id`
				WHERE psp.`package_id` = '" .JRequest::getVar('package_id'). "'			
			";					

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
		$return['lists'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit,
    			'order'=>$filter_order,
    			'order_dir'=>$filter_order_dir
		);
		return $return;
	}

	public function insertPresentationCategory($data){
		$date = JFactory::getDate()->toSql();
		if($this->deletePresentationCategoryByCategory($category, $package_id)) {
			$query = "
					insert into #__presentation_category (category_id, presentation_id, package_id, date_created, date_modify, description)
					values ('" .$data->category. "', '" .$data->presentation_id. "', '" .$data->package_id. "', 
							'" .$date. "', '" .$date . "', '" .$data->description. "')
				";
			$this->_db->setQuery($query);
			if(!$this->_db->query()){
				return false;
			}
			return true;
		} 
		return false;
	}

	public function deletePresentationCategoryByCategory($category, $package_id){
		$query = "
				delete from #__presentation_category where category_id = '" .$category. "' and package_id = '" .$package_id. "'				
			";
		$this->_db->setQuery($query);
		if(!$this->_db->query()){
			return false;
		}
		return true;
	}
	
	public function getPresentationCategoryByCategory($category, $package_id){
		$query = "
				select * from #__presentation_category where category_id = '" .$category. "' and package_id = '" .$package_id. "'
			";
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();		
		return $results; 
	}
}
