<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardPackageViewshowgiftcode extends JViewLegacy
{
	function display($tpl = null)
	{
	    // Load the submenu.
	    AwardpackagesHelper::addSubmenuGiftcode(JRequest::getCmd('view', 'referral'), 'giftcodecollection');
	    
	    JToolBarHelper::title(JText::_('View Giftcode Collection List'),'generic.png');
        
	    JToolBarHelper::back();	   
            
	    $categoryModel =& JModelLegacy::getInstance('GiftcodeCategory','AwardPackageModel');
            
	    $categoryData = $categoryModel->getData();        
		$document= &JFactory::getDocument();	
		$gcid = JRequest::getVar('gcid');
		$model =& JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
			$this->assignRef('categoryData', $categoryData);        	
		$data = $model->getGiftcode($gcid);
		$this->assignRef('data',$data);		
		parent::display($tpl);        
	}

    function add_submenu(){
        JSubMenuHelper::addEntry(JText::_('Giftcode Collection List'), 'index.php?option=com_giftcode');        
        JSubMenuHelper::addEntry(JText::_('Giftcode Queue List'), 'index.php?option=com_giftcode&view=queue' );        
        JSubMenuHelper::addEntry(JText::_('Giftcode Category'), 'index.php?option=com_giftcode&view=category' );
	}

}