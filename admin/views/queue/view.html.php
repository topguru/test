<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardpackageViewQueue extends JViewLegacy
{
	function display($tpl = null)
	{
	// Load the submenu.
	AwardpackagesHelper::addSubmenuGiftcode(JRequest::getCmd('view', 'giftcodecollection'), 'queue');
	
	$layout = JRequest::getVar('layout');
	$this->model =& JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
        if ($layout == "profile") {
            JToolBarHelper::title(JText::_('Giftcode Queue Profile'),'generic.png');
            JToolBarHelper::back();	         
            $model =& $this->getModel();
            $categoryModel =& JModelLegacy::getInstance('GiftCodeCategory','AwardPackageModel');
            $categoryData = $categoryModel->getData();                              
            $this->assignRef('categoryData', $categoryData);                            
            $giftcodeModel =& JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
            $gcid = JRequest::getVar('gcid');
            $giftcode_data = $giftcodeModel->getGiftcode($gcid);
            $giftcodeOwner = $giftcodeModel->getGiftcodeOwner($gcid);
            foreach ($giftcodeOwner as $gO) {
              $user_id = $gO->user_id;
            }
            $user = $giftcodeModel->getUser($user_id);                              
            $this->assignRef('giftcode_data', $giftcode_data);   
            $this->assignRef('user', $user);                                        
           // $this->add_submenu();            
        } else {
            JToolBarHelper::title(JText::_('Giftcode Queue List'),'generic.png');
            JToolBarHelper::back();	   
        		$model =& $this->getModel();                        
            $giftcodeModel =& JModelLegacy::getInstance('Giftcodecode','AwardPackageModel');
            $giftcode_collection = $giftcodeModel->getDataGiftCollection();               
            $this->assignRef('giftcode_collection', $giftcode_collection);    
           // $this->add_submenu();
        }
		
		parent::display($tpl);                            
	}

    function add_submenu(){
        JSubMenuHelper::addEntry(JText::_('Giftcode Collection List'), 'index.php?option=com_giftcode');        
        JSubMenuHelper::addEntry(JText::_('Giftcode Queue List'), 'index.php?option=com_giftcode&view=queue' );        
        JSubMenuHelper::addEntry(JText::_('Giftcode Category'), 'index.php?option=com_giftcode&view=category' );
	}

}