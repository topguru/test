<?php
/**
 * @version		$Id: view.html.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quizzes
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageViewApresentationcategory extends JViewLegacy {	
	
	function display($tpl = null) {	
		CommunitySurveysHelper::initiate();
		
		$model = & JModelLegacy::getInstance( 'apresentationcategory', 'AwardpackageModel' );
		switch ($this->action){
			case 'list':
				$result = $model->getPresentationCategories();
				$categories = array();
				if(!empty($result['categories'])) {
					$categories = $result['categories'];
				}
				$this->assignRef('categories', $categories);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				
				JToolBarHelper::title(JText::_('Presentation Category List'), 'logo.png');
				JToolBarHelper::addNew('apresentationcategory.createUpdate');
				JToolBarHelper::custom('apresentationcategory.edit', 'copy', 'copy', 'Edit', true);
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));
				break;
			case 'create':
				$categories = $model->getAllCategories();
				$this->assignRef('categories', $categories);
				
				$result = $model->getPresentations();
				$presentations = array();
				if(!empty($result['presentations'])) {
					$presentations = $result['presentations'];
				}
				JToolBarHelper::title(JText::_('Create Presentation Category'), 'logo.png');
				JToolBarHelper::custom('apresentationcategory.saveCreateUpdate', 'copy', 'copy', 'Save & Close', true);
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=apresentationcategory&task=apresentationcategory.getListCategory&package_id=' . JRequest::getVar('package_id'));
				$this->assignRef('presentations', $presentations);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				break;				
		}
		
		parent::display($tpl);
	}
}