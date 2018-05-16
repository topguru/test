<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * General Controller of Donation component
 */
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
class AwardpackageControllerDonation extends JControllerLegacy
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function __construct(){
		parent::__construct();	
	}
	 
	function display($cachable = false) 
	{

		JRequest::setVar('view', JRequest::getCmd('view', 'Donationlist'));
		parent::display($cachable);
	}
	
	function publish(){ 
		
		$model =& JModelLegacy::getInstance('donation','AwardpackageModel');
		
		$model->update_category(JRequest::getVar('cid'),'publish');
		
		$this->setRedirect('index.php?option=com_awardpackage&view=donationlist&package_id='.JRequest::getVar('package_id'),JTEXT::_(PUBLISHED));	
	}	
	
	function unpublish(){ 
		
		$model =& JModelLegacy::getInstance('donation','AwardpackageModel');
		
		$model->update_category(JRequest::getVar('cid'),'unpublish');
		
		$this->setRedirect('index.php?option=com_awardpackage&view=donationlist&package_id='.JRequest::getVar('package_id'),JTEXT::_(UNPUBLISHED));	
	}

	function unlock(){
		$db =& JFactory::getDBO();
		
		$model =& JModelLegacy::getInstance('donation','AwardpackageModel');
		
		$post['unlock'] = 1;
		
		$model->save_settings($post);
		
		$cids 	= JRequest::getVar('cid');
		
		foreach($cids as $cid){
			
			if($model->unlock($cid)){
				
				$return = true;
			}
		}
		if($return){
			
			$message = JTEXT::_('DATA_UNLOCKED');
			
		}
		
		$this->setRedirect('index.php?option=com_awardpackage&view=donationlist&package_id='.JRequest::getVar('package_id'),$message);	
	}
	
	function save_categories(){
		
		$model =& JModelLegacy::getInstance('donation','AwardpackageModel');
		
		if($model->invar('unlock','')==1){
			
			$i			= 0;
			
			$cids 			= JRequest::getVar('cid');
			
			$donation_amount 	= JRequest::getVar('donation_amount');
			
			foreach($cids as $cid){
				
				if($model->invar('currency_unit',0)==1){
					
					$data['amount'] = $donation_amount[$i]/100;
					
				}else{
					
					$data['amount'] = $donation_amount[$i];
				}
				
				if($model->save_categories($cid,$data)){
					
					$return = true;
					
				}
				
				$i++;
			
			}
			
			$message = JTEXT::_('DATA_SAVED_AND_LOCKED');
			
			$data['unlock'] = 0;
			
			$model->save_settings($data);
			
		}else{
			$message = JTEXT::_('DATA_LOCKED');
		}
		
		$this->setRedirect('index.php?option=com_awardpackage&view=donationlist&package_id='.JRequest::getVar('package_id'),$message);	
	}	
	
	function settings(){
		JRequest::setVar('view','settings');		
		parent::display($cachable);	
	}		
	
	function paypal(){
		JRequest::setVar('view','paypal');		
		parent::display($cachable);	
	}	

	function donor(){
		JRequest::setVar('view','donor');		
		parent::display($cachable);	
	}	
	
	
	function view(){
		$model =& JModelLegacy::getInstance('action','AwardpackageModel');
		JRequest::setVar('view','Donation');		
		JRequest::setVar('layout','view');
		parent::display($cachable);		
	}
	
	function delete(){
		$model =& JModelLegacy::getInstance('action','AwardpackageModel');
		$model->delete($_POST['transaction_id']);
		$message = JTEXT::_('DATA_DELETED');
		$this->setRedirect('index.php?option=com_awardpackage', $message);			
	}
	
	
	function save_status(){
		$model =& JModelLegacy::getInstance('action','AwardpackageModel');
		$transaction_id = JRequest::getVar('transaction_id');
		$status = JRequest::getVar('status');		
		$model->save_status($transaction_id,$status);
		$message = JTEXT::_('DATA_SAVED');
		$this->setRedirect('index.php?option=com_awardpackage', $message);			
	}
	
	
	function save_paypal(){
		$db =& JFactory::getDBO();
		$model =& JModel::getInstance('settings','AwardpackageModel');
		$test_mode = JRequest::getVar('test_mode');		
		$business = JRequest::getVar('business');
		$sandbox = JRequest::getVar('sandbox');
		$return_url = JRequest::getVar('return_url');
		$notify_url = JRequest::getVar('notify_url');
	
		if($test_mode){
			$post['test_mode'] = 1;
		}else{
			$post['test_mode'] = 0;
		}	
	
		if($business){
			$post['business'] = $business;
		}else{
			$post['business'] = '';
		}
		
		if($sandbox){
			$post['sandbox'] = $sandbox;
		}else{
			$post['sandbox'] = 'seller_1315922650_biz@gmail.com';
		}

		if($return_url){
			$post['return_url'] = $return_url;
		}else{
			$post['return_url'] = preg_replace("/\/administrator\/$/","/",JURI::base()).'index.php?option=com_awardpackage';	
		}
		
		if($notify_url){
			$post['notify_url'] = $notify_url;
		}else{
			$post['notify_url'] = preg_replace("/\/administrator\/$/","/",JURI::base()).'index.php?option=com_awardpackage&task=listen';	
		}
		
		$model->save_settings($post);
		$message = JTEXT::_('DATA_SAVED');
		$this->setRedirect('index.php?option=com_awardpackage&task=paypal', $message);
	}	

	function edit(){
		JRequest::setVar('layout','form');		
		parent::display($cachable);	
	}
	
	function save_settings(){
		$db =& JFactory::getDBO();
		$model =& JModel::getInstance('settings','AwardpackageModel');

		$payment_gateway = JRequest::getVar('payment_gateway');
		$currency_unit = JRequest::getVar('currency_unit');
		$currency_code = JRequest::getVar('currency_code');
	
		if($payment_gateway){
			$post['payment_gateway'] = implode(",",$payment_gateway);
		}else{
			$post['payment_gateway'] = '';
		}
		
		if($currency_unit){
			$post['currency_unit'] = $currency_unit;
		}else{
			$post['currency_unit'] = 0;
		}

		if($currency_code){
			$post['currency_code'] = $currency_code;
		}
		$model->save_settings($post);
		$message = JTEXT::_('DATA_SAVED');
		$this->setRedirect('index.php?option=com_awardpackage&task=settings', $message);
	}
}
