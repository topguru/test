<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Giftcode View
 */
class AwardpackageViewGiftcode extends JViewLegacy
{
	/**
	 * Donation view display method
	 * @return void
	 */
    function __construct($config = array()) {
        parent::__construct($config);
        $this->package_id = JRequest::getVar('package_id');
		$this->category_id = JRequest::getVar('category_id');
    }
	
	function display($tpl = null) 
	{
		// Load the submenu.
		AwardpackagesHelper::addSubmenu(JRequest::getCmd('view', 'referral'), 'donation');
		
		//set variable
		$this->package =& JModelLegacy::getInstance('Main','AwardpackageModel');
		
		$model =& JModelLegacy::getInstance('giftcode','AwardpackageModel');
		
		$this->giftcodes = $model->getGiftCode(JRequest::getVar('package_id'));
		
		$layout = JRequest::getVar('layout');
		
		$this->model = $model;
		
		if(!$layout){
			$this->showToolBar();
		}elseif($layout=='send_schedule'){
			$this->sendToolBar();
		}
		
		parent::display($tpl);
		
	}
 	
	function showToolBar(){
		JToolBarHelper::title('Award Category - Giftcode');
		/*	
		foreach($this->giftcodes as $d){
			
			if($d->package_id==JRequest::getVar('package_id')):
				if ($d->locked == 1) {
						JToolBarHelper::editList();                    
				} else {
						JToolBarHelper::save();                                        
				}
				break;
			   endif;
		}
		
		JToolbarHelper::publishList();
		
		JToolbarHelper::unpublishList();
		*/
	}
	
	function sendToolBar(){
		JToolBarHelper::title('Send giftcode schedule - '.$this->package->info(JRequest::getVar('package_id'))->package_name);
		JToolBarHelper::save('save_schedule','Save & Close');
		JToolBarHelper::cancel('cancel_schedule','Close');
	}
}
