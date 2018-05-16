<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );
 
class AwardpackageModelAward_schedule extends JModelLegacy
{

	function __construct(){	 
	}

  function save($sunday, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, 
    $package_id, $category_id) {

    $sunday = $sunday == "sunday" ? true : false;
    $monday = $monday == "monday" ? true : false;
    $tuesday = $tuesday == "tuesday" ? true : false;
    $wednesday = $wednesday == "wednesday" ? true : false;
    $thursday = $thursday == "thursday" ? true : false;
    $friday = $friday == "friday" ? true : false;
    $saturday = $saturday == "saturday" ? true : false;
    
  	$db =& JFactory::getDBO();		
    $query = "INSERT INTO `#__ap_award_schedule` (sunday, monday, tuesday, wednesday,
                thursday, friday, saturday, package_id, category_id) VALUES ('$sunday', '$monday',
                '$tuesday', '$wednesday', '$thursday', '$friday', '$saturday', '$package_id', '$category_id')";
		$db->setQuery($query);
		$db->query();	          
  }

  function update($sunday, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, 
    $package_id, $category_id) {

    $sunday = $sunday == "sunday" ? true : false;
    $monday = $monday == "monday" ? true : false;
    $tuesday = $tuesday == "tuesday" ? true : false;
    $wednesday = $wednesday == "wednesday" ? true : false;
    $thursday = $thursday == "thursday" ? true : false;
    $friday = $friday == "friday" ? true : false;
    $saturday = $saturday == "saturday" ? true : false;
    
  	$db =& JFactory::getDBO();		
    $query = "UPDATE `#__ap_award_schedule` 
                SET sunday = '$sunday',
                    tuesday = '$tuesday',
                    wednesday = '$wednesday',
                    thursday = '$thursday',
                    friday = '$friday',
                    monday = '$monday',
                    saturday = '$saturday'
                WHERE category_id = '$category_id'
                AND package_id = '$package_id'                                    
                ";
		$db->setQuery($query);
		$db->query();	          
  }
  
}