<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class AwardpackageViewPoll extends JViewLegacy
{

  function display($tpl = null) 
  {
       // Load the submenu.
      AwardpackagesHelper::addSubmenu(JRequest::getCmd('view', 'referral'), 'donation');
      
      $poll_model =& JModelLegacy::getInstance('Poll','AwardpackageModel');
      
      $package_id = JRequest::getVar("package_id");	 
      
      $categories = $poll_model->get_categories($package_id);
	  
	  $this->model = $poll_model;
      
      $this->assignRef("categories", $categories);
      
      JToolbarHelper::EditList("save", "update");           
      
      JToolBarHelper::title('Poll List');		
      
      parent::display($tpl);
  }

}