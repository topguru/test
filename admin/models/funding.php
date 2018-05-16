<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class AwardpackageModelFunding extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
		    $config['filter_fields'] = array('funding_id', 'funding_session', 'funding_created','funding_modify','funding_published');
		}
		parent::__construct($config);
	}
	
	public function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('funding_id,funding_session,funding_created,funding_modify,funding_published,package_id,presentation_id');

		// From the hello table
		$query->from('#__funding');
		$query->order($this->getState('list.ordering', 'funding_id').' '.$this->getState('list.direction', 'ASC'));
		return $query;
	}
	
	public function addFund($data) 
	{
		$db = JFactory::getDBO();
		$timenow = date('Y-m-d',time());
		$sql = "INSERT INTO `#__funding` 
		(
			`funding_session`, 
			`funding_desc`, 
			`funding_published`, 
			`funding_created`,
			`funding_modify`,
			`package_id`,
                        `presentation_id`
		) VALUES 
		(
		 	'-', 
			'-', 
			'0', 
			'".$timenow."',
			'".$timenow."',
			'".$data['package_id']."',
                        '".$data['presentation_id']."'
		)";
		$db->setQuery($sql);
		if($db->query())
		{
			return true;
		}else
		{
			return false;
		}
	}
	
	public function delete($id){
		
		$db		= &JFactory::getDBO();
		
		$_Query 	= "DELETE FROM #__funding WHERE ".$db->QuoteName('funding_id')."='".$id."'";
		
		$db->setQuery($_Query);
		
		if($db->query()){
			
			return true;
		
		}else{
			
			return false;
		
		}
	}
	
	public function publish($id){
		
		$db		= &JFactory::getDBO();
		
		$_Query 	= "UPDATE #__funding SET ".$db->QuoteName('funding_published')."='1' WHERE funding_id='".$id."'";
		
		$db->setQuery($_Query);
		
		if($db->query()){
			
			return true;
		
		}else{
			
			return false;
		
		}
	}
	
	public function unpublish($id){
		
		$db		= &JFactory::getDBO();
		
		$_Query 	= "UPDATE #__funding SET ".$db->QuoteName('funding_published')."='0' WHERE funding_id='".$id."'";
		
		$db->setQuery($_Query);
		
		if($db->query()){
			
			return true;
		
		}else{
			
			return false;
		
		}
	}
	
	public function CheckFunding($id){
		
		$db	=	&JFactory::getDBO();
		
		$query 	= 	"SELECT * FROM #__funding WHERE ".$db->QuoteName('funding_id')."='".$id."'";
		
		$db->setQuery($query);
		
		$rows	= $db->loadObject();
		
		return $rows;
	}
}
