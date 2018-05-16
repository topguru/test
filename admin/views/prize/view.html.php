<?php
//restricted
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardPackageViewprize extends JViewLegacy
{
	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   1.6
	 */
	 
    function __construct($config = array()) {
        parent::__construct($config);
        $this->package_id = JRequest::getVar('package_id');
		$this->prize_model = & JModelLegacy::getInstance('Prize','AwardPackageModel');
		$this->setting_model = & JModelLegacy::getInstance('settings', 'AwardpackageModel');
    }
	
	function display($tpl = null)
	{
		// Load the submenu.
		AwardpackagesHelper::addSubmenuAward('prize');
		$app = JFactory::getApplication();		
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$total = 10;//$model->getDonationHistoryTotal($userId, $packageId);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
		
		$model =& JModelLegacy::getInstance('Prize','AwardPackageModel');
		$this->items 	= $model->getListItems($limit,$limitstart);

		//$this->pagination 	= $this->get('pagination'); 
		
		// Check for errors.
        if (count($errors = $this->get('Errors'))) 
		{
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
		
		$this->addStyleSheet();
		$this->addToolBar();
		parent::display($tpl);
	}
	
	protected function addStyleSheet(){
		$document= &JFactory::getDocument();
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/style.css');
		$document->addScript(JURI::base(true).'/components/com_awardpackage/asset/js/jquery-1.2.6.js');
	}
	
	function addToolBar(){
		$app = JFactory::getApplication();		
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$total = 10;//$model->getDonationHistoryTotal($userId, $packageId);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
		
		$task = JRequest::getVar('task');
		$act = JRequest::getVar('act');
		$cid = JRequest::getVar('cid');
		$id  = JRequest::getVar('id');
		
		if($task =='edit' || $task=='add'){
			$model =& JModelLegacy::getInstance('Prize','AwardPackageModel');
			if($task =='add'){
				JToolBarHelper::title(JText::_('Create Prize'),'generic.png');
			}else{
				JToolBarHelper::title(JText::_('Edit Prize'),'generic.png');
			}
			if($cid !=''){
			//set variable
				$data = $model->getDataDetail($cid[0],$limit,$limitstart);
				$this->assignRef('data',$data[0]);
			}
			
			JRequest::setVar( 'hidemainmenu', 1 );
			JToolBarHelper::apply();
			JToolBarHelper::save();
			$comm=JRequest::getVar('command');
		  	if (!empty($comm)&& $comm=='1'){
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&package_id='.JRequest::getVar('package_id'));	
			} else {
				JToolBarHelper::cancel();	
			}			
			$this->setLayout('create');
			
		}elseif($act == 'view'){
			
			$gcid = JRequest::getVar('gcid');
			
			if($gcid !=''){
				$model =& JModelLegacy::getInstance('Prize','AwardPackageModel');
				$data = $model->getDataDetail($gcid,$limit,$limitstart);
			
				$this->assignRef('data',$data[0]);
			}
			
			JRequest::setVar('tmpl', 'component');
			
			$this->setLayout('show');
		}else{
			$layout = JRequest::getVar('layout');
			if(!$layout){
				$package_id = JRequest::getVar('package_id');			
				JToolBarHelper::title(JText::_('Prize List'),'generic.png');
				JToolBarHelper::addNew();
				//JToolBarHelper::publishList();
				JToolBarHelper::editList();
				JToolBarHelper::deleteList();
				if($package_id!=""){
					JToolBarHelper::custom('prizeclose','cancel','toolbar-cancel','Cancel',false,false);
				}
				$model =& JModelLegacy::getInstance('Prize','AwardPackageModel');
				$data = $model->getItems();
				$this->assignRef('data',$data);
			}
		}
	}
}