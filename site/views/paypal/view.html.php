<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Donation View
 */

class AwardpackageViewPaypal extends JViewLegacy
{
	/**
	 * Donation view display method
	 * @return void
	 */
	 
	function display($tpl = null) 
	{	
		$amount						= JRequest::getVar('amount');
		$currency_code				= JRequest::getVar('currency_code');
		$topup						= JRequest::getVar('topup');
		$days						= JRequest::getVar('days');
		$data						= array();
		
		$model 						= &JModelLegacy::getInstance('paypal','AwardpackageModel');
		
		$deposit_number				= count($model->get_deposit())+1;
		
		$this->item					= $this->get('item');
		$data['deposit_amount']		= $amount;
		$data['payment_gateway']	= 'paypal';
		$data['deposit_number']		= $deposit_number;
		$data['top_amount']			= $topup;
		$data['top_every']			= $days;
		$data['currency_code']		= $currency_code; 
		$date 						= JFactory::getDate();
		$data['dated']				= $date->toFormat();
		$model->save_deposit($data);
		if($this->item->is_test){
			$destination 			= 'https://www.sandbox.paypal.com/cgi-bin/webscr';
			$account				= $this->item->sandbox_account; 	
		}else{
			$destination 			= 'https://www.paypal.com/cgi-bin/webscr';
			$account				= $this->item->paypal_account; 	
		}
		$this->destination			= $destination;
		$this->account				= $account;
		$this->deposit_number		= $deposit_number;
		$this->currency_code		= $currency_code;
		$this->amount				= $amount;
		$this->return_url			= $this->item->return_url.'&deposit_number='.$this->deposit_number;
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		parent::display($tpl);
	}
}
