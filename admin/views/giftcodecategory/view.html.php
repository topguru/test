<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
class AwardpackageViewGiftcodecategory extends JViewLegacy
{
	function display($tpl = null)
	{
		// Load the submenu.
		AwardpackagesHelper::addSubmenuGiftcode(JRequest::getCmd('view', 'referral'));

		$document= &JFactory::getDocument();

		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/style.css');

		$act = JRequest::getVar('layout');
		$task = JRequest::getVar('task');

		if ($task =='edit') {
			$cid = JRequest::getVar('cid');
			JRequest::setVar( 'hidemainmenu', 1 );
			$model =& $this->getModel();
			JToolBarHelper::title(JText::_('Edit Gift Code Category'),'generic.png');
			$data = $model->getDataDetail($cid[0]);
			JToolBarHelper::save();
			JToolBarHelper::cancel();
			$this->assignRef('data',$data[0]);
			$this->setLayout('create');
		} else {
			//$this->add_submenu();
			$model =& $this->getModel();
				
			$data = $model->getData();
				
			$locked = JRequest::getVar('locked');
				
			if ($locked!='false') {
				JToolBarHelper::editList();
			} else {
				JToolBarHelper::save();
			}
			 
			JToolBarHelper::title(JText::_('Giftcode Category'),'generic.png');
			JToolBarHelper::publish();
			JToolBarHelper::unpublish();
			$this->assignRef('data',$data);
		}
		parent::display($tpl);

	}

	function add_submenu(){
		JSubMenuHelper::addEntry(JText::_('Giftcode Collection List'), 'index.php?option=com_awardpackage');
		JSubMenuHelper::addEntry(JText::_('Giftcode Queue List'), 'index.php?option=com_awardpackage&view=queue' );
		JSubMenuHelper::addEntry(JText::_('Giftcode Category'), 'index.php?option=com_awardpackage&view=category' );
	}

}