<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class AwardPackageViewGiftcode extends JViewLegacy
{

	function display($tpl = null)
	{
          $model 					=& JModelLegacy::getInstance('address','AwardPackageModel');
          $this->model 				= & JModelLegacy::getInstance('Giftcode','AwardPackageModel');
		  
		  //set variable
          $this->user_info 			= $model->info($user->id);
	 	  $this->items 				= $this->get('Items');
	 	  $this->pagination 		= $this->get('Pagination');
          
          // Checking user
          $user_data 				= $this->model->check_user($user->email); 
		    
          // Get all giftcode collection
          $giftcodes 				= $this->model->getGiftcodeCollection();  
		      
          $page = JRequest::getVar("page") != "" ? JRequest::getVar("page") : 0;
          $giftcodes_pagination	 	= $this->model->getGiftcodes(JRequest::getVar("color"), $page);         
          $all_giftcodes 			= $this->model->getAllGiftcodes(JRequest::getVar("color")); 
		  
          $gc = array();
          foreach ($giftcodes as $giftcode) {
            $gc[] = $giftcode->id; 
          } 
		       
          $giftcode_category = $this->model->getGiftcodeCategory();
          $this->assignRef('all_giftcodes', $all_giftcodes);                     
          $this->assignRef('giftcode_category', $giftcode_category);               
          $this->assignRef('user_data', $user_data);                     
          $this->assignRef('giftcodes_pagination', $giftcodes_pagination);   
		  $this->checkSchedule();                        

		  // Check for errors.
		  if (count($errors = $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
		  }
		  
		  //add stylesheet 
		  
		  $this->addStyleSheet();
		  
          parent::display($tpl);        
        }
		
		function checkSchedule(){
			$schedules = $this->model->checkSchedule();
			$day = strtolower(date('l'));
			foreach($schedules as $schedule){
					if($schedule->$day){
						if(!$this->model->checkGiftcode()){
							$this->model->saveGiftCode($schedule->setting_id);
						}
					}
			}
		}
		
		function addStyleSheet(){
			
          $document= &JFactory::getDocument();
          
          $document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/style.css');
          
          $document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/layout.css');
          
          $document->addScript(JURI::root().'/media/system/js/modal.js');
          
          $document->addStyleSheet(JURI::root().'media/system/css/modal.css');
          
		}
}