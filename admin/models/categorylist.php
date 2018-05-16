<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

class AwardpackageModelCategorylist extends JModelList
{
	protected function getListQuery(){
		
		$db = JFactory::getDBO();
		
		$package_id = JRequest::getVar('package_id');
		
		JRequest::setVar('limit',10);
		
		$query = $db->getQuery(true);
		
		$query->select('*');
		
		$query->from('#__ap_categories')->where("package_id = '$package_id'")->order_by("category_id");
		
		//echo $package_id;
		return $query;	
	}
}