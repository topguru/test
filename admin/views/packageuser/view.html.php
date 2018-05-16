<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardpackageViewPackageUser extends JViewLegacy {
	function __construct($config = array()) {
		//set variable
		$this->package_id 	= JRequest::getInt('package_id');
		$this->category_id 	= JRequest::getInt('category_id');
		$this->model 		= JModelLegacy::getInstance('main', 'AwardpackageModel');
		$this->ug 			=  JModelLegacy::getInstance('PackageUsers', 'AwardpackageModel');
		$this->id 			= JRequest::getInt('id');
		$this->field 		= JRequest::getVar('field');
		$this->class       	= ' class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"';
		$editor 			= & JFactory::getEditor();
		$this->editor		= $editor;
			
		parent::__construct($config);
	}

	 function display($tpl = null) {
		// Load the submenu.
		//AwardpackagesHelper::addSubmenu(JRequest::getCmd('view', 'referral'), 'donation');
		//get model
		$model 					= JModelLegacy::getInstance('packageusers', 'AwardpackageModel');
		//set variable
		$this->form				= $this->get('Form');
		$this->itemmodel		= $model;
		$this->search_result 	= $this->ug->search_result($this->package_id);
		$this->item				= $this->get('item');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		if ($this->id) {
			$rss = $this->ug->criteria_info($this->id);
			if ($rss) {
				$rs = $rss[0];
				foreach ($rs as $k => $v) {
					${$k} = $rs->{$k};
					$this->assignRef($k, ${$k});
				}
			}
		}

		//add toolbar
		$this->addToolBar();
		//add stylesheet
		$this->addStyleSheet();
		$this->assignRef('act', JRequest::getVar('act'));
		
		if(JRequest::getVar('layout') != 'email_sent' &&
			JRequest::getVar('layout') != 'new_message_non_award') {
			JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=award_category&layout=free&package_id='.JRequest::getVar('package_id'));
		}
		parent::display($tpl);
	}

	 function addToolBar(){
		$package = $this->model->info($this->package_id)->package_name;
		$countries = $this->ug->selectCountryForUserGroup($this->package_id);
		$genders = $this->ug->selectGenderForUserGroup($this->package_id);
		$users = $this->ug->getAllUsers($this->package_id);
		$country_list = AwardpackagesHelper::Countries_list();

		$this->assignRef('countries', $countries);
		$this->assignRef('genders', $genders);
		$this->assignRef('users', $users);

		if(JRequest::getVar('act') == 'awardpackageuser') {
			JToolBarHelper::title(JText::_('Award Package Users'), 'generic.png');
		} else
		if(JRequest::getVar('act') == 'nonawardpackageuser') {
			JToolBarHelper::title(JText::_('Non Award Package Users'), 'generic.png');
		} else {
			JToolBarHelper::title(JText::_('Award Package Users'), 'generic.png');
		}
	}

	function addStyleSheet(){
		//add other script needed
		$document = &JFactory::getDocument();
		$document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.min.js');
		$document->addStyleSheet(JURI::base() . 'components/com_awardpackage/assets/css/jquery.ui.all.css');
		$document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.core.js');
		$document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.widget.js');
		$document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.tabs.js');
		$document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/tabs.js');
	}
}
