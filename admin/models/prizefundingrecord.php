<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class AwardPackageModelPrizefundingrecord extends JModelList
{
	public function __construct($config = array())
	{
		// Load the submenu.
		AwardpackagesHelper::addSubmenuFunding(JRequest::getCmd('view', 'referral'));
		
		if (empty($config['filter_fields'])) {
			
		    $config['filter_fields'] = array('id','prize_funding_session_id', 'prize_id','value','funding','shortfall','pct_funded','created','unlocked_date');
		
		}
		parent::__construct($config);
	}
	
	public function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('*');

		// From the hello table
		$query->from('#__funding_presentations a');
		
		$query->innerjoin('#__symbol_prize b ON a.prize_id=b.id');
		
		$query->leftjoin("#__funding c ON c.funding_id=a.prize_funding_session_id");
		
		$query->where('b.package_id='.JRequest::getVar('package_id').' AND c.presentation_id='.JRequest::getVar('presentation_id'));
		
		$query->order($this->getState('list.ordering','a.id').' '.$this->getState('list.direction', 'ASC'));
		
		return $query;
	}
}
