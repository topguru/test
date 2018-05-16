<?php
/**
 * @version		$Id: view.html.php 01 2011-11-08 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.admin
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport( 'joomla.application.component.view');

class AwardpackageViewResponses extends JViewLegacy {
	
	protected $items;
	protected $pagination;
	protected $canDo;
	protected $lists;
	
	function display($tpl = null) {
		
		$model = $this->getModel();
		$this->canDo = CommunitySurveysHelper::getActions();
		
		$id = JRequest::getInt('id', 0);
		$result = $model->get_survey_responses($id);
		
		$this->assignRef ( 'survey', $result->survey );
		$this->assignRef ( 'responses', $result->responses );
		$this->assignRef ( 'pagination', $result->pagination );
		$this->assignRef ( 'lists', $result->lists );
		
		$this->addToolBar();
		
		parent::display($tpl);
	}
	
	protected function addToolBar(){
		
		JToolBarHelper::title(JText::_('COM_COMMUNITYSURVEYS').': <small><small>[ ' . JText::_('COM_COMMUNITYSURVEYS_SURVEYS') .' ]</small></small>', 'logo.png');
		
		if ($this->canDo->get('core.delete')){
			
			JToolBarHelper::deleteList('', 'responses.delete', 'JTOOLBAR_DELETE');
		}
		
		if ($this->canDo->get('core.admin')){
			
			JToolBarHelper::divider();
			JToolBarHelper::preferences(S_APP_NAME);
		}
	}
}
?>