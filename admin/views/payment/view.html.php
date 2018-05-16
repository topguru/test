<?php 
//redirect
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AwardpackageViewPayment extends JViewLegacy {

	protected $form;
	protected $item;
	protected $state;
	
	function display($tpl = null) {
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		//$this->addToolbar();
		
		parent::display($tpl);
	}
}
?>