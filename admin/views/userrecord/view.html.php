<?php
//restricted
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/awardpackages.php';

class AwardPackageViewuserrecord extends JViewLegacy
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
	public $items;
	
	public function __construct($config = array()) {
		$this->package_id 		= JRequest::getInt('package_id');
		$this->presentation_id 	= JRequest::getInt('presentation_id');
		$this->process_id		= JRequest::getInt('process_id');
		parent::__construct($config);
	}

	function display($tpl = null)
	{
		CommunitySurveysHelper::initiate();
		// Load the submenu.
		AwardpackagesHelper::addSubmenuAward('presentation');
		$archiveModel 		= & JModelLegacy::getInstance('archive', 'AwardPackageModel');
		$model				=	& JModelLegacy::getInstance('processsymbol', 'AwardPackageModel');
		$this->users		= $archiveModel->getApUser($this->package_id);
		$this->prizes		= $model->getProcessCloned($this->process_id);
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		$this->addToolBar();
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */


	protected function getUserPieces($user_id){
		$awardModel 		= & JModelLegacy::getInstance('awardprogress', 'AwardPackageModel');
		return $awardModel->getUserQueue($user_id,$this->presentation_id);
	}

	public function loadProcess(){
		$process_id		= JRequest::getInt('process_id');
		$model 			= & JModelLegacy::getInstance('processsymbol', 'AwardPackageModel');
		return $model->getItem($process_id);
	}

	protected  function addToolbar() {
		$layout 	= JRequest::getVar('layout');
		if(!$layout){
			JToolBarHelper::title(JText::_('User Symbol Queue List'));
			JToolBarHelper::cancel('helperqueue.close','Close');
		}else if($layout=='prize_extracted'){
			JToolBarHelper::title(JText::_('Prize With Extracted Pieces'));
			JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=processsymbol&package_id='.JRequest::getVar('package_id').'&presentation_id='.JRequest::getVar('presentation_id'));			
		}else if($layout=='prize_cloned'){
			JToolBarHelper::title(JText::_('Prize With Cloned'));
			JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=processsymbol&package_id='.JRequest::getVar('package_id').'&presentation_id='.JRequest::getVar('presentation_id'));
		}else if($layout=='helper_queue') {
			JToolBarHelper::title(JText::_('Helper Queue'));
			JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=processsymbol&package_id='.JRequest::getVar('package_id').'&presentation_id='.JRequest::getVar('presentation_id'));
		}else if($layout=='symbol_queue'){
			JToolBarHelper::title(JText::_('Symbol Queue'));
			JToolBarHelper::cancel('processsymbol.close_symbol','Close');
		}else if($layout=='user_symbol'){
			JToolBarHelper::title(JText::_('User Symbol Queue List'));
			JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=processsymbol&package_id='.JRequest::getVar('package_id').'&presentation_id='.JRequest::getVar('presentation_id'));
		}
	}

	protected function getPieces($symbol_id){
		$model 			= & JModelLegacy::getInstance('processsymbol', 'AwardPackageModel');

		return $model->getPieces($symbol_id);
	}

	protected function getClone($clone_id){
		$model 			= & JModelLegacy::getInstance('processsymbol', 'AwardPackageModel');
		return $model->getClonesAdd($clone_id);
	}
}