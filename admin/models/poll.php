<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.model');

class AwardpackageModelPoll extends JModelLegacy
{
  
      var $_categories;
    
      function get_categories($package_id) {
        
            if (empty($this->_categories)) {
              $this->_categories = $this->_getList("SELECT * FROM #__ap_categories
                                                    WHERE package_id = '$package_id' ORDER BY category_id ASC");
            }            
            return $this->_categories;    
      }
  	
	  function getGiftCode($categoryID){
	  		$db	= &JFactory::getDbo();
			$query = $db->getQuery(TRUE);
			$query->select('count(id) AS total');
			$query->from($db->QuoteName('#__giftcode_collection'));
			$query->where("color_id='".$categoryID."'");
			$db->setQuery($query);
			$row = $db->loadObject();
			return $row;
	  }
	  
	  function getAwardSymbol($categoryID){
	  		$db = &JFactory::getDbo();
			$query = $db->getQuery(TRUE);
			$query->select('count(id) AS total');
			$query->from($db->QuoteName('#__gc_recieve_user'));
			$query->where("category_id='".$categoryID."'");
			$query->where("status='1'");
			$db->setQuery($query);
			$row = $db->loadObject();
			return $row;
	  }
}
