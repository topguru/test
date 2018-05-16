<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
 
class AwardpackageViewModules extends JViewLegacy
{
	function display($tpl = null) 
	{		
		$model =& JModelLegacy::getInstance('main','AwardpackageModel');
		
		$this->actionModel = & JModelLegacy::getInstance('action','AwardpackageModel');
		
		$package = $model->info(JRequest::getVar('package_id'),1);
		
		$this->model = & JModelLegacy::getInstance('main','AwardpackageModel');
		
			foreach($package as $k => $v){
				${$k} = $v;
				$this->assignRef("$k",${$k});	
			}	
		
		parent::display($tpl);
	}
}
