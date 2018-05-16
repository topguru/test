<?php
/**
 * @version		$Id: view.html.php 01 2013-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.surveys
 * @subpackage	Components.views
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageViewDashboard extends JViewLegacy {
	
	protected $items;
	protected $pagination;
	protected $canDo;
	protected $lists;
	
    function display($tpl = null) {
    	CommunitySurveysHelper::initiate();
		
    	
       	$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
       	$app = JFactory::getApplication();
       
        $latest_surveys = $model->get_surveys(array(), 5, 0);       
        
        $this->assignRef('latest_surveys', $latest_surveys['surveys']);

        $pending_surveys = $model->get_surveys(array(), 10, 0, 2);
        $this->assignRef('pending_surveys', $pending_surveys['surveys']);
        
        $version = $app->getUserState(S_APP_NAME.'.VERSION');
        
        if(!$version){
        		
        	$version = CJFunctions::get_component_update_check(S_APP_NAME, S_CURR_VERSION);
        	$v = array();
        		
        	if(!empty($version)){
        
        		$v['connect'] = (int)$version['connect'];
        		$v['version'] = (string)$version['version'];
        		$v['released'] = (string)$version['released'];
        		$v['changelog'] = (string)$version['changelog'];
        		$v['status'] = (string)$version['status'];
        
        		$app->setUserState(S_APP_NAME.'.VERSION', $v);
        	}
        }        
        $this->assignRef('version', $version);        
        $this->addToolBar();        
        parent::display($tpl);
        
    }
	
	protected function addToolBar(){
		/*		
		$state	= $this->get('State');
		$this->canDo = CommunitySurveysHelper::getActions($state->get('filter.category_id'));
		*/		
		JToolBarHelper::title(JText::_('COM_COMMUNITYSURVEYS').': <small><small>[ ' . JText::_('COM_COMMUNITYSURVEYS_DASHBOARD') .' ]</small></small>', 'logo.png');
		JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));		
		/*if ($this->canDo->get('core.admin')){*/			
		JToolBarHelper::preferences(S_APP_NAME);
		/*}*/
	}
	
}