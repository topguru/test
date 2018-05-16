<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
class AwardpackageViewAwardpackage extends JViewLegacy
{
	protected $pagination;
	
	function display($tpl = null) 
	{
		// Load the submenu.
		//AwardpackagesHelper::addSubmenu(JRequest::getCmd('view', 'referral'), 'donation');
		
		// Initialise variables.
		$items 				= 	$this->get('items');
		$this->pagination 	= 	$this->get('Pagination');
		$this->items 		= 	$items;
		$package_id 		= 	JRequest::getInt('package_id');
		$this->mrd			=	JRequest::getVar('action');
		$this->package_id	= 	$package_id;
		$model				=	$this->getModelName('awardpackage');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$layout 			= 	JRequest::getVar('layout');
		$this->package		=	$model->getAwardPackage($this->package_id);
			
		if(!$layout){
			$this->AddToolbar();
		}
		$this->addStyleSheet();
		
		$user = JFactory::getUser();

		$model = JModelLegacy::getInstance('action','AwardpackageModel');

		$info = $model->view(JRequest::getVar('transaction_id'));
		
		$this->actionModel = JModelLegacy::getInstance('action','AwardpackageModel');
		
		$this->depositModel = JModelLegacy::getInstance('deposit','AwardpackageModel');
				
		parent::display($tpl);
	}
	
	function AddToolbar(){
		JToolBarHelper::title(JTEXT::_('COM_AWARDPACKAGE'));
		$alt = "New";
		$bar = JToolBar::getInstance( 'toolbar' );
		$bar->appendButton('Popup', 'preview', $alt, 'index.php?option=com_awardpackage&tmpl=component&param=scUpdate&task=entry');				
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();	
		JToolBarHelper::deleteList("Are you sure?");									
	}
	
	function iscent($value,$digit){
		$model = JModelLegacy::getInstance('settings','AwardpackageModel');
		return $model->iscent($value,$digit);
	}
 
 	function show_username($value){
		$user = JFactory::getUser($value);
		return '<a href="'.JRoute::_('index.php?option=com_users&view=user&layout=edit&id='.$value).'">'.$user->username.'</a>';
	}	
	
	function show_category_info($id){
 		$model = JModelLegacy::getInstance('action','AwardpackageModel');
		return $model->info($id);
	}	
	
	function registered_users($package_id){
		$model = $this->getModelName('awardpackage');
		return $model->registered_users($package_id);
	}
	
	function registered_quiz($package_id){
		$model = $this->getModelName('quiz');
		return $model->registered_quiz($package_id);
	}
	
	function registered_survey($package_id){
		$model = $this->getModelName('surveys');
		return $model->registered_survey($package_id);
	}
	
	function registered_donation($package_id) {
		$model = $this->getModelName('donation');
		return $model->registered_donation($package_id);
	}
	
	function registered_prize($package_id) {
		$model = $this->getModelName('prize');		
		return $model->registered_prize($package_id);
	}
	
	function getModelName($name){
		$model = JModelList::getInstance($name,'AwardpackageModel');
		return $model;
	}
	
    protected function addStyleSheet() {    	
        $doc = JFactory::getDocument();      
        $url = JURI::base() . 'components/com_awardpackage/assets/';
        $doc->addStyleSheet($url . 'css/jquery-ui.min.css');
        $doc->addScript($url . 'js/jquery-1.8.3.js');
        $doc->addScript($url . 'js/jquery.ui.core.min.js');
        $doc->addScript($url . 'js/jquery.ui.widget.min.js');
        $doc->addScript($url . 'js/jquery.ui.tabs.min.js');
		$doc->addScript($url . 'js/jquery_003.js');
		$doc->addScript($url . 'js/jquery_002.js');
		$doc->addScript($url . 'js/highlight.js');
		$doc->addScript($url . 'js/responsive-tables.js');		
		$doc->addStyleSheet($url . 'css/style.css');
		$doc->addStyleSheet($url . 'css/collapsible.css');
		$doc->addStyleSheet($url . 'css/responsive-tables.css');
    }
}
