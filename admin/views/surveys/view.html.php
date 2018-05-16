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

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageViewSurveys extends JViewLegacy {

	protected $items;
	protected $pagination;
	protected $canDo;
	protected $lists;
	protected $state;

	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$model = $this->getModel();
		//$model = & JModelLegacy::getInstance( 'surveys', 'AwardpackageModel' );
		
		$result = $model->get_surveys(array(), 20, 0, -1);
		$this->items = !empty($result['surveys']) ? $result['surveys'] : array();
		$this->pagination = $result['pagination'];
		$this->category = $model->get_categories(JRequest::getVar('package_id'));
		$this->lists = $result['lists'];
		$this->addToolBar();
		parent::display($tpl);
	}		
	protected function addToolBar(){

		$user	= JFactory::getUser();
		$this->state	= $this->get('State');
		$this->canDo = CommunitySurveysHelper::getActions($this->state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_COMMUNITYSURVEYS').': <small><small>[ ' . JText::_('COM_COMMUNITYSURVEYS_SURVEYS') .' ]</small></small>', 'logo.png');
		JToolBarHelper::divider();
		JToolBarHelper::addNew('surveys.add_survey');
		JToolBarHelper::custom( 'surveys.copy_surveys', 'copy', 'copy', 'Copy', true, false );
		JToolBarHelper::custom( 'refresh', 'default', 'default', 'Refresh Hits', false, false );
		JToolBarHelper::divider();
		JToolBarHelper::publish('surveys.publish_list', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('surveys.unpublish_list', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::deleteList('', 'surveys.delete_surveys', 'JTOOLBAR_DELETE');		
		JToolBarHelper::divider();
		        						JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));		

	}
}
?>