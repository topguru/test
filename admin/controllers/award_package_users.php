<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
class AwardpackageControllerAward_package_users extends JControllerLegacy
{

	function __construct(){
		parent::__construct();	
		require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
	}

  function display() {
    
    $filter = JRequest::getVar("filter");
    $task = JRequest::getVar("task");
    $id = JRequest::getVar("id");
    $category_id = JRequest::getVar("category_id");

    if ($task == "delete") {
      $award_package_users_model =& JModelLegacy::getInstance('Award_package_users','AwardpackageModel');	
      $award_package_users_model->delete($id);
      $this->setRedirect('index.php?option=com_awardpackage&view=package_users&package_id='.JRequest::getVar("package_id").'&filter='.$filter.'&category_id='.$category_id, "Award package was successfully deleted");		                                                
    } else if ($task == "update") {
      $award_package_users_model =& JModelLegacy::getInstance('Award_package_users','AwardpackageModel');	
      $data = array(
        "firstname" => $_POST['first'],
        "lastname" => $_POST['last'],
        "id" => $_POST['id']
      );
      echo $data["id"];
      $award_package_users_model->update($id, $data);      
      $this->setRedirect('index.php?option=com_awardpackage&view=package_users&package_id='.JRequest::getVar("package_id").'&filter='.$filter.'&category_id='.$category_id, "Award package was successfully updated");		                                                
    } else {
      if ($filter == "name") {
        if (($_POST['first'] != "") || ($_POST['last'] != "")) {
          $first_name = $_POST['first'];
          $last_name = $_POST['last'];
          $award_package_users_model =& JModelLegacy::getInstance('Award_package_users','AwardpackageModel');	
          $save_award_package_users = $award_package_users_model->save("name", $first_name, $last_name, $category_id);
          $this->setRedirect('index.php?option=com_awardpackage&view=package_users&package_id='.JRequest::getVar("package_id").'&filter='.$filter.'&category_id='.$category_id, "Award package users created");		                                          
        } else {        
          $this->setRedirect('index.php?option=com_awardpackage&view=package_users&package_id='.JRequest::getVar("package_id").'&filter='.$filter.'&category_id='.$category_id, "First name or last name is empty");		                                  
        }
      }      
    }
    
  }
	 
}