<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Donation View
 */
class AwardpackageViewSettings extends JViewLegacy
{
	/**
	 * Donation view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
	$model =& JModelLegacy::getInstance('settings','AwardpackageModel');
		if($model->invar('payment_gateway','')){
			$payment_gateway = explode(",",$model->invar('payment_gateway',''));
			
		}else{
			$payment_gateway = '';
		}
		
		if($model->invar('currency_unit',0)==1){		
			$checked = ' checked';
		}else{						
			$checked = '';
		}
		$this->assignRef('payment_gateway',$payment_gateway);			
		$this->assignRef('checked',$checked);
		$this->assignRef('currency_code',$model->invar('currency_code','USD'));	
		
		JToolBarHelper::title('Donation settings');
		
		//$bar = & JToolBar::getInstance('toolbar');
		//$url = JRoute::_('index.php?option=com_awardpackage&controller=donation&task=save_settings&format=raw');
		//$bar->appendButton( 'Link', 'save', 'Save', $url);	
		JToolBarHelper::save('save_settings','Save');
    include_once (JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_awardpackage'.
                    DS.'shared'.DS.'submenu.php');
		parent::display($tpl);
	}
}
