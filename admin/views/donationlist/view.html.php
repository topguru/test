<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class awardpackageViewDonationlist extends JViewLegacy {
    
    function __construct($config = array()) {
        
        parent::__construct($config);
        
        $this->model = & JModelLegacy::getInstance('Main', 'AwardpackageModel');
        
        $this->package_id = JRequest::getVar('package_id');
        
    }
    function display($tpl = null) {
        // Load the submenu.
        AwardpackagesHelper::addSubmenu(JRequest::getCmd('view', 'referral'), 'donation');

        $poll_model =& JModelLegacy::getInstance('Poll','AwardpackageModel');
		
	    $this->poll_model = $poll_model;

        $task = JRequest::getVar('task');
        
        switch ($task) {

            default:

                $items = $this->get('Items');

                $pagination = $this->get('Pagination');

                if (count($errors = $this->get('Errors'))) {
                    
                    JError::raiseError(500, implode('<br />', $errors));

                    return false;
                }

                $this->items = $items;

                $this->pagination = $pagination;

                JToolBarHelper::title('Award Category - Donation');

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

                JToolBarHelper::unpublishList();

                JToolBarHelper::publishList();
      JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));

                break;
        }

        if ($this->model->invar('currency_unit', '') == 1) {

            $unit = ' cents (' . $this->model->invar('currency_code', '') . ')';
        } else {

            $unit = ' dollars (' . $this->model->invar('currency_code', '') . ')';
        }

        $this->assignRef('readonly', $readonly);

        $this->assignRef('unit', $unit);

        parent::display($tpl);
    }

    function iscent($value) {

        $model = & JModelLegacy::getInstance('helper', 'AwardpackageModel');

        return $model->iscent($value);
    }

    function iscent_raw($value) {

        $model = & JModelLegacy::getInstance('helper', 'AwardpackageModel');

        return $model->iscent_raw($value);
    }

}
