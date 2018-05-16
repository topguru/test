<?php
//restricted
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class AwardPackageViewawardsymbol extends JViewLegacy
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
    }

	function display($tpl = null)
	{	
		// Load the submenu.
		AwardpackagesHelper::addSubmenuAward('award');
		
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
	
	function addStyleSheet(){
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
		$package_id =JRequest::getVar('package_id')	;
		$task = JRequest::getVar('task');
		$act = JRequest::getVar('act');
		$cid = JRequest::getVar('cid');		
		if($task =='add' || $task =='edit'){
			if($task =='add'){
				JToolBarHelper::title(JText::_('Create Award Symbol'),'generic.png');
			}else{
				JToolBarHelper::title(JText::_('Edit Award Symbol'),'generic.png');
			}
			if($cid !=''){
				$model =& JModelLegacy::getInstance('Awardsymbol','AwardPackageModel');
				$data = $model->getDataDetail($cid[0],$limit, $limitstart);
				$this->assignRef('data',$data[0]);
			}
          
			JRequest::setVar( 'hidemainmenu', 1 );
			JToolBarHelper::apply();
			JToolBarHelper::save();
			
			$command = JRequest::getVar('command');
			
			if(!empty($command) && $command == '1') {
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&package_id='.JRequest::getVar('package_id'));
			} else {
				JToolBarHelper::cancel();	
			}			
			$this->setLayout('create');
		}elseif($act == 'view'){
			$gcid = JRequest::getVar('gcid');
			if($gcid !=''){
				$model =& JModelLegacy::getInstance('Awardsymbol','AwardPackageModel');
				$data = $model->getDataDetail($gcid,$limit, $limitstart);
				$this->assignRef('data',$data[0]);
			}
			JRequest::setVar('tmpl', 'component');
			
			$this->setLayout('show');	
		}else{
			JToolBarHelper::title(JText::_('Award Symbol List'),'generic.png');
			JToolBarHelper::addNew();
			JToolBarHelper::editList();
			JToolBarHelper::deleteList();
			if(JRequest::getVar('package_id')!=""){
				JToolBarHelper::custom('awardsymbolclose','cancel','toolbar-cancel','Cancel',false,false);
			}
			$model =& JModelLegacy::getInstance('Awardsymbol','AwardPackageModel');
			$data = $model->getData($package_id,$limit, $limitstart);
			$this->assignRef('data',$data);

		}
	}
}