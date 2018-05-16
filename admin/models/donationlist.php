<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library

jimport('joomla.application.component.modellist');

class AwardpackageModelDonationlist extends JModelList
{
	protected function getListQuery(){
		
		$user =& JFactory::getUser();		
		
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true);
		
		$query->select('*');
		
		$query->from('#__ap_categories')->where("package_id = '".JRequest::getVar('package_id')."'");
		
		$query->order($this->getState('list.ordering','category_id').' '.$this->getState('list.direction', 'ASC'));
		
		return $query;
	}
}
