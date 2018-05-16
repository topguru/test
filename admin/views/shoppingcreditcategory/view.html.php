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
class AwardpackageViewShoppingCreditCategory extends JViewLegacy {
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		JToolBarHelper::title(JText::_('Shopping Credit Plan Category'), 'logo.png');
		$app = JFactory::getApplication();		
		$model = & JModelLegacy::getInstance( 'shoppingcreditcategory', 'AwardpackageModel' );
		$package_id = JRequest::getVar("package_id");	
		switch ($this->action){
			case 'list':
				$result = $model->get_shopping_credit_category($package_id);
				$shoppings = array();
				if(!empty($result['shoppings'])) {
					$shoppings = $result['shoppings'];
				}
				$this->assignRef('shoppings', $shoppings);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				
				JToolBarHelper::addNew('shoppingcreditcategory.add_shopping_credit_category');
				JToolBarHelper::custom( 'shoppingcreditcategory.save_and_close', 'copy', 'copy', 'Save & Close', false);
				JToolBarHelper::divider();
				JToolBarHelper::publish('shoppingcreditcategory.publish_list');
				JToolBarHelper::unpublish('shoppingcreditcategory.unpublish_list');				
				JToolBarHelper::deleteList('', 'shoppingcreditcategory.delete_shopping_credit_category');
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));
				break;
			case 'create':
				$id = '0';
				if(JRequest::getVar('id') != null) {
					$id = JRequest::getVar('id');
				}
				$result = $model->get_shopping_credit_category_byid($id);
				$this->assignRef('shopping', $result);
				JToolBarHelper::custom('shoppingcreditcategory.save_create', 'copy', 'copy', 'Save & Close', false);
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=shoppingcreditcategory&task=shoppingcreditcategory.get_shopping_credit_category_list&package_id='.JRequest::getVar('package_id'));
				break;				
		}
		parent::display($tpl);
	}	
}