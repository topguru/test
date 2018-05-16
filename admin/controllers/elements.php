<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
 
/**
 * General Controller of Donation component
 */
class AwardpackageControllerElements extends JControllerLegacy
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function __construct(){
		parent::__construct();	
	}
	 
	function display($cachable = false) 
	{
		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'Categories'));
 
		// call parent behavior
		parent::display($cachable);
	}
	
	function elements(){
		$setting =& JModelLegacy::getInstance('Donation','AwardpackageModel');
		JRequest::setVar('view','elements');
		parent::display($cachable);		
	}

	function categories(){
		JRequest::setVar('view','categories');		
		parent::display($cachable);	
	}	
	
	function settings(){
		JRequest::setVar('view','settings');		
		parent::display($cachable);	
	}		
	

	function publish(){
		$model =& JModelLegacy::getInstance('elements','AwardpackageModel');
		$model->update_category(JRequest::getVar('cid'),'publish');
		JRequest::setVar('view','categories');		
		$this->setRedirect('index.php?option=com_awardpackage&task=elements&package_id='.JRequest::getVar('package_id'),JText::_('PUBLISHED'));		
	}
	
	function unpublish(){
		$model =& JModelLegacy::getInstance('elements','AwardpackageModel');
		$model->update_category(JRequest::getVar('cid'),'unpublish');
		JRequest::setVar('view','categories');
		$this->setRedirect('index.php?option=com_awardpackage&task=elements&package_id='.JRequest::getVar('package_id'),JText::_('UNPUBLISHED'));				
	}
	
	function edit(){
		JRequest::setVar('layout','form');		
		parent::display($cachable);	
	}
	
	
	function unlock(){
		$db =& JFactory::getDBO();
		$model =& JModelLegacy::getInstance('settings','AwardpackageModel');
		$post['unlock'] = 1;
		$model->save_settings($post);
		$message = JTEXT::_('DATA_UNLOCKED');
		$this->setRedirect('index.php?option=com_awardpackage&controller=categories', $message);		
	}
	
	
	function save_categories(){
		$setting =& JModelLegacy::getInstance('settings','AwardpackageModel');
		if($setting->invar('unlock','')==1){
			$model =& JModelLegacy::getInstance('action','AwardpackageModel');
			$model->save_categories();
			$msg = JTEXT::_('DATA_SAVED_AND_LOCKED');
			JFactory::getApplication()->enqueueMessage($msg);
			$data['unlock'] = 0;
			$setting->save_settings($data);
		}else{
			$message = JTEXT::_('DATA_LOCKED');
			JFactory::getApplication()->enqueueMessage($message, 'error' );
		}
		$this->setRedirect('index.php?option=com_awardpackage&controller=categories');
		}
	

}
