<?php
/**
 * @version		$Id: view.html.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components.views
 * @copyright	Copyright (C) 2009 - 2010 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageViewDashboardz extends JViewLegacy {

    function display($tpl = null) {
    	CommunitySurveysHelper::initiate();

        JToolBarHelper::title(JText::_('COM_COMMUNITYQUIZ_MENU').': <small><small>[ ' . JText::_('COM_COMMUNITYQUIZ_DASHBOARD') .' ]</small></small>', 'logo.png');

        $model = & JModelLegacy::getInstance( 'quiz', 'AwardpackageModel' );
        $app = JFactory::getApplication();
        
        $latest_quizzes = $model->get_quizzes(array(), 5, 0);
        $this->assignRef('latest_quizzes', $latest_quizzes['quizzes']);

        $app->input->post->set('state', 2);
        $pending_quizzes = $model->get_quizzes(array(), 10, 0);
        $this->assignRef('pending_quizzes', $pending_quizzes['quizzes']);
        
        $version = $app->getUserState(Q_APP_NAME.'.VERSION');
        
        if(!$version){
        		
        	$version = CJFunctions::get_component_update_check(Q_APP_NAME, CQ_CURR_VERSION);
        	$v = array();
        		
        	if(!empty($version)){
        
        		$v['connect'] = (int)$version['connect'];
        		$v['version'] = (string)$version['version'];
        		$v['released'] = (string)$version['released'];
        		$v['changelog'] = (string)$version['changelog'];
        		$v['status'] = (string)$version['status'];
        
        		$app->setUserState(Q_APP_NAME.'.VERSION', $v);
        	}
        }
        
        $this->assignRef('version', $version);
        JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));
        parent::display($tpl);
    }
}