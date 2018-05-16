<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardpackageViewAward_category extends JViewLegacy
{

    function __construct($config = array()) {
        parent::__construct($config);
		
        $this->package_id = JRequest::getVar('package_id');
    }
	
	function display($tpl = null){
		
		// Load the submenu.
		AwardpackagesHelper::addSubmenu(JRequest::getCmd('view', 'referral'), 'donation');

		$layout = JRequest::getVar("layout");	   
    
		//if ($layout == "free") {
		
			JToolBarHelper::title("Award Category - Free");  
			
			$poll_model =& JModelLegacy::getInstance('Poll','AwardpackageModel');
			
			//$this->categories = $poll_model->get_categories($package_id);
			
			$this->packageuser_model = & JModelLegacy::getInstance('PackageUsers','AwardpackageModel');
			
			$package_id = JRequest::getVar("package_id");	 
			
			$categories = $poll_model->get_categories($package_id);
			
			$this->model = $poll_model;
			
			$this->assignRef("categories", $categories);     
			$pagination = $this->get('Pagination');
			$this->items = $categories;
			$this->pagination = $pagination;
			//print_r($categories);
			if (count($errors = $this->get('Errors'))) 
			{
				JError::raiseError(500, implode('<br />', $errors));
				return false;
			}
			$is_unlocked = false;
			foreach ($categories as $item) {
				if ($item->unlocked == 1) {
					$is_unlocked = true;
				}
			}
			
			if ($is_unlocked) {
				JToolBarHelper::title("Award Category - Free Giftcode");  
				JToolBarHelper::save('save_categories_2','Save');	
				$readonly = '';
			} else {
				JToolBarHelper::title("Award Category - Free Giftcode");
				JToolbarHelper::editList('unlock_2');
			}  
			//JToolBarHelper::title("Award Category - Free");  
			
			//JToolbarHelper::custom("edit","edit","copy","Edit"); 
			
			JToolbarHelper::divider();             
			
			JToolbarHelper::custom("sync_award_schedule","copy","copy","Sync Award Schedule", false);             
			
			JToolbarHelper::custom("sync_award_package","copy","copy","Sync Award Package Users", false);             
			
			JToolbarHelper::custom("sync_non_award_package","copy","copy","Sync Non Award Package Users", false);             
      JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));

			//$this->setLayout('free');            

		//} 
		/*else {
	
			JToolBarHelper::title("Award Category - Donation");
			JToolbarHelper::editList();       
		}*/

	parent::display($tpl);                                        
	}          
}