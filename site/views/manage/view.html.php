<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardPackageViewManage extends JViewLegacy
{

	function display($tpl = null)
	{
		$document= &JFactory::getDocument();		
		
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/style.css');
		
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/thickbox.css');
		
		$document->addScript(JURI::base(true).'/components/com_awardpackage/asset/js/jquery-1.2.6.js');
		
		$document->addScript(JURI::base(true).'/components/com_awardpackage/asset/js/thickbox.js');
		
		$act 			= JRequest::getVar('act');
		
		$model 			=& JModelLegacy::getInstance('manage','AwardPackageModel');
		
		$usermodel		= & JModelLegacy::getInstance('address','AwardPackageModel');
		
		$this->model 		= & JModelLegacy::getInstance('manage','AwardPackageModel');
		
		$this->giftcodeModel 	= & JModelLegacy::getInstance('giftcode','AwardPackageModel');
		
		$user =& JFactory::getUser();
		
		$this->user_info 	= $usermodel->info($user->id);
		
		$this->items = $this->get('Items');
		
		$this->pagination = $this->get('Pagination');
			
		$data = $this->items;
            
		$this->assignRef('data',$data);
		
		parent::display($tpl);

	}

}