<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );
 
class AwardpackageModelAward_package_users extends JModelLegacy
{

  var $_all;
  
  function save($type, $param_1, $param_2, $category_id) {
    $package_id = JRequest::getVar("package_id");
  	$db =& JFactory::getDBO();		
    if ($type == "name") {
      $query = "INSERT INTO `#__ap_user_packages` (category_id, package_id, firstname, lastname) 
                  VALUES ('$category_id', '$package_id', '$param_1', '$param_2')";      
    }
		$db->setQuery($query);
		$db->query();	              
  }
  
  function delete($id) {
      $db =& JFactory::getDBO();		
      $query = "DELETE FROM `#__ap_user_packages` 
                WHERE `id` = '$id'";      
		$db->setQuery($query);
		$db->query();	              
  }  

  function update($id, $data) {
  	$db =& JFactory::getDBO();		
    $query = "UPDATE `#__ap_user_packages` 
              SET `firstname` = '".$data["firstname"]."',
                  `lastname` = '".$data["lastname"]."'
              WHERE `id` = '$id' ";      
		$db->setQuery($query);
		$db->query();	              
  }
  
  function all() {
    $package_id = JRequest::getVar("package_id");
    $category_id = JRequest::getVar("category_id");
    if (empty($this->_all)) {
      $this->_all = $this->_getList("SELECT * FROM #__ap_user_packages
                                            WHERE package_id = '$package_id'
                                            AND category_id = '$category_id' ");
    }
    return $this->_all;        
  }
  
}