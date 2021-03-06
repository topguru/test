<?php
/**
 * @version		$Id: dashboard.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2010 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

class AwardpackageControllerDashboardz extends JControllerLegacy {

	function __construct() {
		parent::__construct();
		$this->registerDefaultTask('get_dashboard');
	}

	function get_dashboard() {
		 
		$view = $this->getView('dashboard', 'html');
		$model = $this->getModel('quiz');
		$view->setModel($model, true);
		$view->display();
	}

	public function publish_list(){
		
		$return = $this->change_state(1);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=dashboardz&package_id='.JRequest::getVar('package_id'), false), 'Success publish quiz');		
	}
	
	public function unpublish_list(){
		
		$return = $this->change_state(0);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));
		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=dashboardz&package_id='.JRequest::getVar('package_id'), false), 'Success unpublish quiz');
	}
	
	public function change_state($state){

		$app = JFactory::getApplication();
		$ids = $app->input->getArray(array('cid'=>'array'));
		
		if(!empty($ids['cid'])){
				
			$model = $this->getModel('quiz');
			JArrayHelper::toInteger($ids['cid']);
			$id = implode(',', $ids['cid']);

			if($model->set_status($id, $state)){
		
				return 1;
			} else {

				return 0;
			}
		}
		
		return -1;
	}
}
?>
