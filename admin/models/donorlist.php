<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * DonationList Model
 */
class AwardpackageModelDonorlist extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.	
		$user =& JFactory::getUser();		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('*');
		$query->from('#__ap_donation_transactions as t')->from('#__ap_useraccounts as u')->where('t.user_id = u.id')->where("t.package_id = '".JRequest::getVar('package_id')."'")->group('u.id');
		return $query;
	}
	
}
