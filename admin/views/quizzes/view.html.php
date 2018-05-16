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
class AwardpackageViewQuizzes extends JViewLegacy {
	
	protected $params;
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		JToolBarHelper::title(JText::_('COM_COMMUNITYQUIZ_MENU').': <small><small>[ ' . JText::_('COM_COMMUNITYQUIZ_QUIZZES') .' ]</small></small>', 'logo.png');
		
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$categories_model = $this->getModel('categories');
		
		/********************************** PARAMS *****************************/
		$this->params = JComponentHelper::getParams(Q_APP_NAME);
		/********************************** PARAMS *****************************/
		
		switch ($this->action){
			
			case 'list':
				$result = $model->get_quizzes();
				$quizzes = !empty($result['quizzes']) ? $result['quizzes'] : array();
				$categories = $categories_model->get_qcategories(JRequest::getVar('package_id'));
		        //$categories = $model->get_categories_tree(JRequest::getVar('package_id'));
				$users_model = $this->getModel('users');
				$users = $users_model->get_all_active_users();
				
				$this->assignRef('quizzes', $quizzes);
				$this->assignRef('categories', $categories);
				$this->assignRef('users', $users);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				
				JToolBarHelper::custom('quizzes.refresh', 'refresh.png', 'refresh.png', JText::_('LBL_REFRESH'), false, false);
				JToolBarHelper::divider();
				JToolBarHelper::addNew('quizzes.add_quiz');
				JToolBarHelper::custom( 'quizzes.copy_quiz', 'copy', 'copy', 'Copy', true, false );
				JToolBarHelper::divider();
				JToolBarHelper::publish('quizzes.publish_list');
				JToolBarHelper::unpublish('quizzes.unpublish_list');
				JToolBarHelper::deleteList('', 'quizzes.delete');
						JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));		

				break;
				
			case 'form':
				
				$id = $app->input->getInt('id', 0);
				if($id <= 0) return CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTH'), 403);
				
				if(empty($this->item)){
					
					$question = $model->get_quiz_details($id);
					if(empty($question)) return CJFunctions::throw_error(JText::_('JERROR_ALERTNOAUTH'), 403);
					
					$this->assignRef('item', $question);
				}
				
				$categories = $categories_model->get_categories_tree();
				$this->assignRef('categories', $categories);
				
				JToolBarHelper::save();
				JToolBarHelper::cancel();
				
				break;
		}
		
		parent::display($tpl);
	}	
}