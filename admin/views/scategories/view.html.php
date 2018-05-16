<?php
/**
 * @version		$Id: view.html.php 01 2012-06-30 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport( 'joomla.application.component.view');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageViewSCategories extends JViewLegacy {
	
	function display($tpl = null) {		
		CommunitySurveysHelper::initiate();
		
		JToolBarHelper::title(JText::_('COM_COMMUNITYSURVEYS').': <small><small>[ ' . JText::_('CATEGORIES') .' ]</small></small>', 'logo.png');
		$model = $this->getModel('scategories');		
		$packageId = JRequest::getVar("package_id");	 
				
        if($this->getLayout() == 'list') {
        	
            JToolBarHelper::custom('scategories.refresh', 'refresh.png', 'refresh.png', JText::_('LBL_REFRESH'), false, false);
            JToolBarHelper::addNew('scategories.add');
            
            $categories = $model->get_scategories($packageId);
//			 $categories = $model->get_categories();
            $this->assignRef('categories',$categories);
            
        }else if($this->getLayout() == 'add') {
        	
            JToolBarHelper::save('scategories.save');
            JToolBarHelper::cancel('scategories.cancel');
        	
            $id = JRequest::getVar('id', 0, '', 'int');
            $category = array();
            
            if($id){            	
                $category = $model->get_category($id);
            } else{
            	
            	$category['id'] = $category['surveys'] = $category['parent_id'] = 0;
            	$category['title'] = $category['alias'] = '';
            }

            $this->assignRef('category', $category);
            $categories = $model->get_categories_tree(0, true,$packageId);
            $this->assignRef('categories', $categories);
        }
                						JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));		

		
		parent::display($tpl);
	}
}
?>