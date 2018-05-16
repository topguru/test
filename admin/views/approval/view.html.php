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
require_once JPATH_SITE.'/components/com_cjlib/framework/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageViewApproval extends JViewLegacy {
	
	protected $params;
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageModel' );
		JToolBarHelper::title(JText::_('COM_COMMUNITYQUIZ_MENU').': <small><small>[ ' . JText::_('COM_COMMUNITYQUIZ_QUIZZES') .' ]</small></small>', 'logo.png');
		
		$model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageModel' );
		$app = JFactory::getApplication();
		//$model = $this->getModel();		
		$categories_model = $this->getModel('categories');
		
		/********************************** PARAMS *****************************/
		$this->params = JComponentHelper::getParams(Q_APP_NAME);
		/********************************** PARAMS *****************************/
		
		switch ($this->action){
			
			case 'list':
				
				$app->input->post->set('state', 2);
				$result = $model->get_quizzes();
				$quizzes = !empty($result['quizzes']) ? $result['quizzes'] : array();
				$categories = $categories_model->get_categories_tree();
				
				$users_model = $this->getModel('users');
				$users = $users_model->get_all_active_users();
				
				$this->assignRef('quizzes', $quizzes);
				$this->assignRef('categories', $categories);
				$this->assignRef('users', $users);
				$this->assignRef('pagination', $result['pagination']);
				$this->assignRef('lists', $result['lists']);
				
				JToolBarHelper::custom('approval.get_quizzes_list', 'refresh.png', 'refresh.png', JText::_('LBL_REFRESH'), false, false);
				JToolBarHelper::divider();
				JToolBarHelper::publish('approval.publish_list');
				JToolBarHelper::unpublish('approval.unpublish_list');
				JToolBarHelper::deleteList();
										JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));		

				break;
				
			case 'preview':
				
				$id = $app->input->getInt('id');
				$quiz = $model->get_quiz_details($id, true);
				$this->assignRef('quiz', $quiz);
				
				JToolBarHelper::publish('approval.publish_list');
				JToolBarHelper::unpublish('approval.unpublish_list');
				JToolBarHelper::cancel('approval.cancel');
				
				break;
		}
		
		parent::display($tpl);
	}	
}