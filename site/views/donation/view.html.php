<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Donation View
 */
 
class AwardpackageViewDonation extends JViewLegacy
{
	/**
	 * Donation view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		$user =& JFactory::getUser();
		$task = JRequest::getVar('task');
	
		switch($task){
			default:
				// Get data from the model
				$items = $this->get('Items');
				$pagination = $this->get('Pagination');
		 
				// Check for errors.
				if (count($errors = $this->get('Errors'))) 
				{
					JError::raiseError(500, implode('<br />', $errors));
					return false;
				}
				// Assign data to the view
				$this->items = $items;
				$this->pagination = $pagination;
		 
				$document    = & JFactory::getDocument();
				$document->addStyleSheet('administrator/templates/system/css/system.css');
				//now we add the necessary stylesheets from the administrator template
				//in this case i make reference to the bluestork default administrator template in joomla 1.6
				$document->addCustomTag(
					'<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />'."\n\n".
					'<!--[if IE 7]>'."\n".
					'<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />'."\n".
					'<![endif]-->'."\n".
					'<!--[if gte IE 8]>'."\n\n".
					'<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />'."\n".
					'<![endif]-->'."\n".
					'<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />'."\n"
					);
			
			break;
			case 'edit':
			case 'view':
				if(JRequest::getVar('transaction_id')){
					$model =& JModelLegacy::getInstance('action','AwardpackageModel');
					$info = $model->view(JRequest::getVar('transaction_id'));
						$dated = $model->transaction_info(JRequest::getVar('transaction_id'))->dated;
						$this->assignRef('transaction_id',JRequest::getVar('transaction_id'));					
						$this->assignRef('dated',$dated);
						$this->assignRef('info',$info);
				}
			break;
			}
			parent::display($tpl);
	}
 
	
	function view_buttons() {
		// add required stylesheets from admin template
		$task = JREquest::setVar('task');
		$document    = & JFactory::getDocument();
		$document->addStyleSheet('administrator/templates/system/css/system.css');
		//now we add the necessary stylesheets from the administrator template
		//in this case i make reference to the bluestork default administrator template in joomla 1.6
		$document->addCustomTag(
			'<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />'."\n\n".
			'<!--[if IE 7]>'."\n".
			'<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />'."\n".
			'<![endif]-->'."\n".
			'<!--[if gte IE 8]>'."\n\n".
			'<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />'."\n".
			'<![endif]-->'."\n".
			'<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />'."\n"
			);
		//load the JToolBar library and create a toolbar
		jimport('joomla.html.toolbar');
		$bar =& new JToolBar( 'toolbar' );
		switch($task){
			case 'history':
				$bar->appendButton( 'Standard', 'new', 'Make donation', 'make', false );					
			break;
			case 'edit':
				$bar->appendButton( 'Standard', 'save', 'Save', 'save', false );			
				$bar->appendButton( 'Standard', 'delete', 'Delete', 'delete', false );
			break;
		}
		return $bar->render();
	}
	
	function iscent($value,$digit=0){
		$model =& JModelLegacy::getInstance('settings','AwardpackageModel');
		return $model->iscent($value,$digit);
	}
 
	function show_category_name($id){
 		$model =& JModelLegacy::getInstance('action','AwardpackageModel');
		return $model->info($id)->category_name;
	}
}
