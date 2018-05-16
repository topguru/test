<?php
/**
 * @version		$Id: view.html.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );

class AwardpackageViewUserquiz extends JViewLegacy {
	
	protected $params;
	protected $print;
	protected $state;
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$active = $app->getMenu()->getActive();
		$pathway = $app->getPathway();
		$this->print = $app->input->getBool('print');
		
		/********************************** PARAMS *****************************/
		$appparams = JComponentHelper::getParams(Q_APP_NAME);
		$menuParams = new JRegistry;
		
		if ($active) {
		
			$menuParams->loadString($active->params);
		}
		
		$this->params = clone $menuParams;
		$this->params->merge($appparams);
		/********************************** PARAMS *****************************/

		$limit = $this->params->get('list_length', $app->getCfg('list_limit', 20));
		$limitstart = $app->input->getInt('start', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$itemid = CJFunctions::get_active_menu_id();
		$quizzes_itemid = CJFunctions::get_active_menu_id(true, 'index.php?option='.Q_APP_NAME.'&view=quiz');
		$user = JFactory::getUser();
		
		switch($this->action){
	
			case 'quizzes':
				
				$options = array('userid'=>$user->id, 'limit'=>$limit, 'limitstart'=>$limitstart, 'order'=>'a.created', 'order_dir'=>'desc');
				$return = $model->get_quizzes(1, $options, $this->params);
				
				$this->assignRef('items', $return['quizzes']);
				$this->assignRef('state', $return['state']);
				$this->assignRef('pagination', $return['pagination']);
				$this->assign('task', null);
				$this->assign('brand', JText::_('LBL_HOME'));
				$this->assign('brand_url', JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$quizzes_itemid));
				$this->assign('page_url', 'index.php?option='.Q_APP_NAME.'&view=userquiz&task=userquiz.get_my_quizzes'.$itemid);
				break;
				
			case 'responses':
				
				$return  = $model->get_responses(0, $user->id);
				
				$this->assignRef('items', $return->rows);
				$this->assignRef('pagination', $return->pagination);
				$this->assignRef('lists', $return->lists);
				$this->assign('task', null);
				$this->assign('brand', JText::_('LBL_HOME'));
				$this->assign('brand_url', JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$quizzes_itemid));
				$this->assign('page_url', 'index.php?option='.Q_APP_NAME.'&view=userquiz&task=userquiz.get_my_responses'.$itemid);
				break;
				
			case 'subscriptions':

				$subscriptions = $model->get_user_subscriptions($user->id);
				$this->assignRef('items', $subscriptions);
				
				$this->assign('task', null);
				$this->assign('brand', JText::_('LBL_HOME'));
				$this->assign('brand_url', JRoute::_('index.php?option='.Q_APP_NAME.'&view=quiz'.$quizzes_itemid));
				$this->assign('page_url', 'index.php?option='.Q_APP_NAME.'&view=userquiz&task=userquiz.get_my_subscriptions'.$itemid);
				break;
		}
		
		$pathway->addItem($this->brand);
		$this->assign('page_id', 4);		
		parent::display($tpl);
	}
}