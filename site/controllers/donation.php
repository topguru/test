<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * General Controller of Donation component
 */
class AwardpackageControllerDonation extends AwardpackageController
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function __construct(){
		parent::__construct();	
		$task = JRequest::getVar('task');
		if($task!='listen'){
			$user =& JFactory::getUser();
			if ($user->get('guest')) {
				$this->setRedirect('index.php?option=com_users&view=login');
			}else{
					$model =& JModelLegacy::getInstance('address','AwardpackageModel');
					if(!$model->info($user->id)->id){
						$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&controller=donation&task=edit'),'Please fill out the form before continue');
					}				
			}
		}
	}
	 
	function debug(){
        $db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$model =& JModelLegacy::getInstance('address','AwardpackageModel');
		$country = $model->info($user->get('id'))->country;
		$query = "SELECT * FROM #__ap_usergroup WHERE country = '". $country."' GROUP BY package_id"  ;
		$db->setQuery($query);
		$rs = $db->loadObjectList();
		$num = count($rs);
			if($num>1){
				echo "error, please contact admin, there are duplicate countries in the usergroup";
			}elseif($num==1){
				$pack_id = $rs[0]->package_id;
				$query = "SELECT * FROM `#__ap_useraccounts` t, `#__users` u ".$this->filter($pack_id);
				$db->setQuery($query);
				$rs = $db->loadObjectList();
				foreach($rs as $k => $v){
					$usr[$k] =  $rs[$k]->id;
				}
				if(in_array($user->get('id'),$usr)){
					echo "Congratulation...!";
					echo $pack_id;
				}else{
					echo "You are not one of the selected users";
				}
			}else{
				echo "You are not selected user";
			}
	}
	
	function filter($package_id){
        $db =& JFactory::getDBO();
		$query = "SELECT * FROM #__ap_usergroup WHERE package_id = '". $package_id."'"  ;
			$db->setQuery($query);
			$rs = $db->loadObjectList();
			if(count($rs)>0){	
				foreach($rs as $k => $v){
					$qry[] = "t.id = u.id AND email LIKE '%$v->email%' AND gender LIKE '%$v->gender%' AND city LIKE '%$v->city%' AND state LIKE '%$v->state%' AND country = '$v->country'"; 
				}
				return "WHERE ".implode(" OR ",$qry);
			}
	}	
	
	 
	function display($cachable = false) 
	{

		JRequest::setVar('view', JRequest::getCmd('view', 'Donation'));
		
		// call parent behavior
		parent::display($cachable);
	}
	
	function confirm(){
		
		$user =& JFactory::getUser();
		
		$subtotal = 0;
		
		foreach($_POST['category_id'] as $k => $v){
			
			$subtotal = $subtotal+($_POST['donation_amount'][$k]*$_POST['quantity'][$k]);
		
		}
		$_POST['credit'] = $subtotal;
		
		$_POST['status'] = 'in progress';
		
		$_POST['user_id'] = $user->get('id');
		
		$_POST['package_id'] = JRequest::getVar('package_id');
		
		$model =& JModelLegacy::getInstance('action','AwardpackageModel');
		
		$donatemodel =& JModelLegacy::getInstance('donation','AwardpackageModel');
		
		$id = $model->save_transaction($_POST);
		
		if($id>0){
		
			$payment_gateway = JRequest::getVar('payment_gateway');
		
			switch($payment_gateway){
		
				default :
					$donatemodel->SelectGiftCode();
					JRequest::setVar('id',$id);
					$pg =& JModelLegacy::getInstance($payment_gateway,'AwardpackageModel');
					$amount = $pg->details_info($id)->amount;
					JRequest::setVar('business',$pg->details_info($id)->business);
					JRequest::setVar('user_id',$_POST['user_id']);
					JRequest::setVar('amount',$amount);
					JRequest::setVar('view',$payment_gateway);
				break;
			}
					parent::display($cachable);	
		}
		//echo $id;
	}
	
	function make(){
		$user =& JFactory::getUser();
		if ($user->get('guest')) {
			$this->setRedirect('index.php?option=com_users&view=login');
		}else{	
			JRequest::setVar('view','categories');		
		}
			parent::display($cachable);	
		
	}

	function preview(){
		foreach(JRequest::getVar('quantity') as $k => $v){
			if($v != ''){
				if(preg_match("/^\d+$/",$v)){
					$valid = true;
				}else{
					$error .= $v.' is not valid value<br>';
				}
			}
		}
		
			if($error){
				JFactory::getApplication()->enqueueMessage( JText::_($error), 'error' );
				$this->setRedirect('index.php?option=com_awardpackage&controller=donation&task=make');				
			}else{
				if($valid){
					JRequest::setVar('view','categories');	
					JRequest::setVar('layout','preview');				
					parent::display($cachable);	
				}else{
					JFactory::getApplication()->enqueueMessage( JText::_("Please put numeric value at least 1 field"), 'error' );
					$this->setRedirect('index.php?option=com_awardpackage&controller=donation&task=make');					
				}
			}
	}

	
	
	function edit(){
		JRequest::setVar('layout','form');		
		parent::display($cachable);	
	}
	
	function view(){
		$user =& JFactory::getUser();
		$model =& JModelLegacy::getInstance('action','AwardpackageModel');
		if($user->get('id')==$model->transaction_info(JRequest::getVar('transaction_id'))->user_id){
			JRequest::setVar('view', 'Donation');		
			JRequest::setVar('layout','view');
			parent::display($cachable);		
		}else{
			JFactory::getApplication()->enqueueMessage( JText::_("You have no right to view the page"), 'error' );
			///$this->setRedirect('index.php?option=com_awardpackage');
		}
	}
	
	function delete(){
		$model =& JModelLegacy::getInstance('action','AwardpackageModel');
		$model->delete($_POST['transaction_id']);
	}

	function listen(){
		$model = JModelLegacy::getInstance('paypal','AwardpackageModel');
		$listener = JModelLegacy::getInstance('IpnListener','AwardpackageModel');
		$paypal = $listener->paypal();
		switch($paypal[post_method]) { 
			case "libCurl": //php compiled with libCurl support
				$result=$listener->libCurlPost($paypal[url],$_POST); 
			break;

			case "curl": //cURL via command line
				$result=$listener->curlPost($paypal[url],$_POST); 
			break; 

			case "fso": //php fsockopen(); 
				$result=$listener->fsockPost($paypal[url],$_POST); 
			break; 

			default: //use the fsockopen method as default post method
				$result=$listener->fsockPost($paypal[url],$_POST);
			break;
		}
			foreach($_POST as $k => $v){
			 $log .= 'POST '.$k.' => '.$v."\n";
			}
			$model->update($_POST);
			/*
		if(eregi("VERIFIED",$result)) { 
			if(isset($paypal['business']))
			{
				$model->update($_POST);
			}
			else
			{
			mail("netfriend@gmail.com","test TIDAK sukses",$log,"From: admin@primadita.net");
			}
		}else{ 
			//include_once('./ipn_error.php'); 
		} 
		*/
	}
}
