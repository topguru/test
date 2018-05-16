<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
class AwardpackageControllerAward_schedule extends JControllerLegacy
{

	function __construct(){
		parent::__construct();	
		require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
	}
	 
	function save() 
	{
    $award_schedule_model =& JModelLegacy::getInstance('Award_schedule','AwardpackageModel');	
    $save_award_schedule = $award_schedule_model->save(
      $_POST['sunday'], $_POST['monday'], $_POST['tuesday'], $_POST['wednesday'], $_POST['thursday'], 
      $_POST['friday'], $_POST['saturday'], JRequest::getVar("package_id"), $_POST['category_id']
    ); 
    $this->setRedirect('index.php?option=com_awardpackage&view=award_category&layout=free&package_id='.JRequest::getVar("package_id"), "Award Schedule Successfully Created");		                  
	}
  
  function update()
  {
    $award_schedule_model =& JModelLegacy::getInstance('Award_schedule','AwardpackageModel');	
    $save_award_schedule = $award_schedule_model->update(
      $_POST['sunday'], $_POST['monday'], $_POST['tuesday'], $_POST['wednesday'], $_POST['thursday'], 
      $_POST['friday'], $_POST['saturday'], JRequest::getVar("package_id"), $_POST['category_id']
    ); 
    $this->setRedirect('index.php?option=com_awardpackage&view=award_category&layout=free&package_id='.JRequest::getVar("package_id"), "Award Schedule Successfully Updated");		                  
  }

}
