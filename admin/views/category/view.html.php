<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

jimport('joomla.application.component.view');
 
class AwardpackageViewCategory extends JViewLegacy
{
	function display($tpl = null) 
	{
		// Load the submenu.
		AwardpackagesHelper::addSubmenu(JRequest::getCmd('view', 'referral'), 'donation');

		$task = JRequest::getVar('task');
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
		JToolBarHelper::title('Award Category - Category Settings');
		
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
        
		$this->assignRef('readonly',$readonly);	
		$this->assignRef('unit',$unit);		
      JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));

		parent::display($tpl);

	}
 
 }
