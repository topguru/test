<?php
//restricted
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardPackageViewProcessSymbol extends JViewLegacy
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
		$this->package_id = JRequest::getInt('package_id');
		$this->presentation_id = JRequest::getInt('presentation_id');
	}

	function display($tpl = null)
	{
		//set variable
		$this->items		= $this->get('items');
				
		// Load the submenu.
		AwardpackagesHelper::addSubmenuAward('presentation');

		$this->addStyleSheet();

		$this->addToolBar();

		parent::display($tpl);
	}

	function addToolBar(){
		$task = JRequest::getVar('task');
		$act = JRequest::getVar('act');
		$cid = JRequest::getVar('cid');	

		$presentation_id = JRequest::getVar('presentation_id');
		$package_id = JRequest::getVar('package_id');
		
		if($task =='add' || $task =='edit'){
			JToolBarHelper::save();
			$this->setLayout('create');
		}elseif($act == 'view'){
			$gcid = JRequest::getVar('gcid');
			if($gcid !=''){
				$model =& JModelLegacy::getInstance('Presentation','AwardPackageModel');
				$data = $model->getDataDetail($gcid);
				$this->assignRef('data',$data[0]);
			}
			JRequest::setVar('tmpl', 'component');
			$this->setLayout('show');
				
		}elseif($task == 'findsymbol'){
				
			$model =& JModelLegacy::getInstance('Awardsymbol','AwardPackageModel');
				
			$data = $model->getData();
				
			$this->assignRef('data',$data);
				
			JRequest::setVar('tmpl', 'component');
				
			$this->setLayout('findsymbol');
				
		}	elseif($task == 'extractsymbol'){
				
			JRequest::setVar('tmpl', 'component');
				
			$this->setLayout('extractsymbol');
				
		} elseif($task == 'clonesymbol'){

			JRequest::setVar('tmpl', 'component');
				
			$this->setLayout('clonesymbol');
				
		} elseif($task == 'shufflesymbol'){

			JRequest::setVar('tmpl', 'component');

			$this->setLayout('shufflesymbol');

		} elseif($task == 'symbolqueue'){

			JRequest::setVar('tmpl', 'component');
				
			$this->setLayout('symbolqueue');
				
		} elseif($task == 'processsymbolqueue'){
				
			JRequest::setVar('tmpl', 'component');
				
			$this->setLayout('processsymbolqueue');
				
		} elseif($task == 'findprize'){

			$model =& JModelLegacy::getInstance('Prize','AwardPackageModel');

			$data = $model->getItems();

			$this->assignRef('data',$data);

			JRequest::setVar('tmpl', 'component');

			$this->setLayout('findprize');
		}else{
			JToolBarHelper::title(JText::_('Process Symbol List'),'generic.png');				
			
			JToolBarHelper::addNew("processsymbol.add","New");
				
			JToolBarHelper::DeleteList("are you sure ?","processsymbol.delete");
				
			JToolBarHelper::cancel("processsymbol.close");
				
			$model =& JModelLegacy::getInstance('PresentationList','AwardPackageModel');
			
			$presentations = $model->getPresentationDetails($presentation_id, $package_id);
			if(!empty($presentations)) {
				$presentation= $presentations[0];
			}			
			$data = $model->getItems();	
			$this->assignRef('data',$data);
			$this->assignRef('presentation', $presentation);	
		}
	}

	public function getCloned($process_id){
		$model =& JModelLegacy::getInstance('processsymbol','AwardPackageModel');
		return $model->getProcessCloned($process_id);
	}
	public function getExtract($process_id){
		$model =& JModelLegacy::getInstance('processsymbol','AwardPackageModel');
		return $model->getExtract($process_id);
	}
	protected function addStyleSheet(){
		$document= &JFactory::getDocument();
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/style.css');
		$document->addScript(JURI::base(true).'/components/com_giftcode/asset/js/jquery-1.2.6.js');
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/thickbox.css');
		$document->addScript(JURI::base(true).'/components/com_awardpackage/asset/js/thickbox.js');
	}

}