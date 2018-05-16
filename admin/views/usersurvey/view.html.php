<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
class AwardpackageViewUsersurvey extends JViewLegacy
{

	function display($tpl = null) 
	{
		// Load the submenu.
		AwardpackagesHelper::addSubmenu(JRequest::getCmd('view', 'referral'),'donation');
		
		$poll_model =& JModelLegacy::getInstance('Poll','AwardpackageModel');	 
		
		$package_id = JRequest::getVar("package_id");	 
		
		$categories = $poll_model->get_categories($package_id);
		
		$this->model = $poll_model;
		
		$this->assignRef("categories", $categories);	 

		JToolBarHelper::title('Survey - Admin');		
		
		//category
		$model =& JModelLegacy::getInstance('Main','AwardpackageModel');
		$setting =& JModelLegacy::getInstance('Main','AwardpackageModel');
		$this->model=& JModelLegacy::getInstance('Category','AwardpackageModel');
		
		//setting variable
		$items = $this->model->getCategory(JRequest::getVar('package_id'));
		
		$pagination = $this->get('Pagination');
		$this->items = $items;
		$this->pagination = $pagination;
		
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$package =& JModelLegacy::getInstance('Main','AwardpackageModel');
		JToolBarHelper::title('Survey - Frontend Admin');		
		
		$is_unlocked = false;
		foreach ($items as $item) {
			if ($item->unlocked == 1) {
				$is_unlocked = true;
			}
		}
		
		if ($is_unlocked) {
			JToolBarHelper::save('save_categories','Save');	
			$readonly = '';
		} else {
			JToolbarHelper::editList('unlock');
		}
		
		/*if($setting->invar('unlock','')==0){					
			JToolBarHelper::save('save_categories','Save');	
			$readonly = '';
		}else{
			JToolbarHelper::editList('unlock');			 
		}*/
		
		JToolbarHelper::publishList();
		
		JToolbarHelper::unpublishList();
              JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));

		$this->assignRef('readonly',$readonly);	
		$this->assignRef('unit',$unit);
		//add toolbar
		$this->addToolBar();
		//display template
		parent::display($tpl);
	}
	
	function addToolBar() {
		$document = JFactory::getDocument();

		$document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.min.js');

		$document->addStyleSheet(JURI::base() . 'components/com_awardpackage/assets/css/jquery.ui.all.css');

		$document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.core.js');

		$document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.widget.js');

		$document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.tabs.js');

		$document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/tabs.js');
		}
}
