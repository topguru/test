<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Donation View
 */
class AwardpackageViewCategories extends JViewLegacy
{
	/**
	 * Donation view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		$task = JRequest::getVar('task');
		$model =& JModelLegacy::getInstance('action','AwardpackageModel');
		$user =& JFactory::getUser();
		$document= &JFactory::getDocument();	
		$document->addStyleSheet(JURI::base(true).'/components/com_awardpackage/asset/style.css');
		if($user->get('id')){		
		switch($task){
			default:
				$items = $this->get('Items');
				if(count($items)>0){
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
				}else{
					//JFactory::getApplication()->enqueueMessage( JText::_("All categories unpublished by admin"), 'error' );
					//return '';
				}
				//jimport('joomla.html.toolbar');
			break;
			case 'preview':
					$qty = JRequest::getVar('quantity');
					$item_id = JRequest::getVar('id_item');
					foreach(JRequest::getVar('category_id') as $k => $v){
						if($qty[$k]>0){
							//echo $qty[$k].'<br>';
							$row[$v] = array('donation_amount' => $model->info($v)->donation_amount, 'colour_code' => $model->info($v)->colour_code, 'quantity' => $qty[$k],'setting_id'=>$item_id[$k]);
						}
					}
				$payment_gateway = JRequest::getVar('payment_gateway');
				$this->assignRef('payment_gateway',$payment_gateway);
				$this->assignRef('row',$row);
			break;
			}

			parent::display($tpl);
		}else{
			$app = &JFactory::getApplication();
			$app->redirect('index.php?option=com_users&task=login', $msg);	
		}
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
			case 'preview':
				$bar->appendButton( 'Standard', 'apply', 'Confirm', 'confirm', false );		
				$bar->appendButton( 'Standard', 'cancel', 'Cancel', 'cancel', false );		
			break;
			case 'make':
				$bar->appendButton( 'Standard', 'preview', 'Preview', 'preview', false );					
			break;
		}
		return $bar->render();
	}
	
	function iscent($value){
		$model =& JModelLegacy::getInstance('settings','AwardpackageModel');
		return $model->iscent($value);
	}
	
	function show_category_name($id){
 		$model =& JModelLegacy::getInstance('action','AwardpackageModel');
		return $model->info($id)->category_name;
	}
	
 }
