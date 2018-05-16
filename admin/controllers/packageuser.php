<?php
//resdirect
defined('_JEXEC') or die('Restricted access');
//import
jimport('joomla.application.component.controller');
//helper require
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';

class AwardpackageControllerPackageUser extends AwardpackageController {

	function __construct($config = array()) {
		//set variable
		$this->package_id 		= JRequest::getInt('package_id');
		$this->category_id     	= JRequest::getInt('category_id');
		$this->field	    	= JRequest::getVar('field');
		$this->id				= JRequest::getInt('id');
		parent::__construct($config);
	}

	function display() {
		JRequest::setVar('view', 'packageuser');
		parent::display();
	}

	function save_packageuser(){
		//get data
		$data 		= JRequest::getVar('jform', array(), 'post', 'array');
		//set model
		$model 		= JModelLegacy::getInstance('packageuser','AwardpackageModel');
		//set link
		$link 		= 'index.php?option=com_awardpackage&view=packageuser&package_id='.$data['package_id'].'&category_id='.$data['category_id'].'&field='.$data['field'].'&act='.JRequest::getVar('act');
//test if exist
		$firstname = $data['firstname'];
		$lastname = $data['lastname'];
		$from_age =$data['from_age'];
		$to_age =$data['to_age'];
		$email =$data['email'];
		$state =$data['state'];
		$city=$data['city'];
		$street=$data['street'];
		$post_code=$data['post_code'];
		$country=$data['country'];

	$db =& JFactory::getDBO();
$query = "
    SELECT COUNT(*)
        FROM `#__ap_useraccounts`
        WHERE `email` = '$email' OR 
		`firstname` = '$firstname' OR 
		`lastname` = '$lastname'  OR
		`state` = '$state'  OR
		`city` = '$city' OR 
		`street` = '$street'  OR
		`post_code` = '$post_code'  OR
		`country` = '$country' 
		;
";
$db->setQuery($query);
$count = $db->loadResult();

if ( $count ) {
		if($model->addItem($data)){
			$msg="Success : adding data success";
		}else{
			JError::raiseWarning( 100, 'Error : saving data error' );
		}
		} 
		$this->setRedirect($link,$msg);
	}
	public function save(){
		$model 		= JModelLegacy::getInstance('packageusers','AwardpackageModel');
		$awuser= JRequest::getVar('awuser');
		$firstname = JRequest::getVar('firstname');
		$lastname = JRequest::getVar('lastname');
		$email = JRequest::getVar('email');
		$category_id = JRequest::getVar('category_id');
		$id = JRequest::getVar('package_id');
		for($i = 0; $i < count($awuser); $i++) {
			$data['awuser']=$awuser[$i];
			$data['firstname']=$firstname[$i];
			$data['lastname']=$lastname[$i];
			$data['email']=$email[$i];
			$model->update($data);

		}
		$msg="save succed";
		if(JRequest::getVar('act') == 'nonawardpackageuser') {
			$link = 'index.php?option=com_awardpackage&view=packageuser&layout=non_award&package_id='.$this->package_id.'&category_id='.$category_id.'&act=nonawardpackageuser';
		} else {
			$link = 'index.php?option=com_awardpackage&view=award_category&layout=free&package_id='.$this->package_id;
		}
		$this->setRedirect($link,$msg);
	}
	public function sync_non_award_package()	{
		echo "bbbb";
		die();
	}
	public function delete(){
		//get model
		$model		= $this->getModel('packageusers');
		$model->delete(JRequest::getVar('category_id'), JRequest::getVar('package_id'), JRequest::getVar('id'));

		$msg = "Success : delete successfully";
		$link = 'index.php?option=com_awardpackage&view=packageuser&package_id='.$this->package_id.'&category_id='.$this->category_id.'&field='.$this->field.'&act='.JRequest::getVar('act');
		$this->setRedirect($link,$msg);
	}

	public function nonapuser(){
		//set variable

		$firstnames  	= JRequest::getVar('firstname',array(0), 'post', 'array');
		$lastnames   	= JRequest::getVar('lastname',array(0), 'post', 'array');
		$emails      	= JRequest::getVar('email',array(0), 'post', 'array');
		$varFirstname	="";
		$varLastname	="";
		$varEmail	="";
		$i          	= 0;

		foreach($firstnames as $firstname){
			if($firstname!=""){
				$varFirstname.="&firstname[]=".$firstname;
				$varLastname.="&lastname[]=".$lastnames[$i];
				$varEmail.="&email[]=".$emails[$i];
			}
			$i++;
		}

		$model = JModelLegacy::getInstance('packageusers','AwardpackageModel');
		if($model->addNewUser()){

			//die();
			$this->setRedirect('index.php?option=com_awardpackage&view=packageuser&layout=non_award'.$varFirstname.$varLastname.$varEmail.'&package_id='.$this->package_id.'&category_id='.$this->category_id . '&act=' . JRequest::getVar('act'),$message);
		}else{

			//die();
			$this->setRedirect('index.php?option=com_awardpackage&view=packageuser&layout=non_award&act=' . JRequest::getVar('act'),$message);
		}

	}

	public function nonapuserdelete(){
		//set variable
		$firstnames  	= JRequest::getVar('firstname',array(0), 'post', 'array');
		$lastnames   	= JRequest::getVar('lastname',array(0), 'post', 'array');
		$emails      	= JRequest::getVar('email',array(0), 'post', 'array');
		$varFirstname	="";
		$varLastname	="";
		$varEmail	="";
		$i          	= 0;

		foreach($firstnames as $firstname){
			if($firstname!=""){
				$varFirstname.="&firstname[]=".$firstname;
				$varLastname.="&lastname[]=".$lastnames[$i];
				$varEmail.="&email[]=".$emails[$i];
			}
			$i++;
		}

		$model = JModelLegacy::getInstance('packageuser','AwardpackageModel');

		//get ids
		$cids 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		foreach($cids as $cid){
			if($model->deletenonapuser($cid)){
				$msg ="Success : Deleted success ";
			}else{
				JError::raiseWarning( 100, 'Error : Error delete ' );
			}
		}

		$link = 'index.php?option=com_awardpackage&view=packageuser&layout=non_award'.$varFirstname.$varLastname.$varEmail.'&package_id='.$this->package_id.'&category_id='.$this->category_id . '&act=' . JRequest::getVar('act');
		$this->setRedirect($link,$msg);
	}


	public function messagesave(){
		$model  			=  JModelLegacy::getInstance('packageusers','AwardpackageModel');
		$data['subject'] 	= JRequest::getVar('subject');
		$data['body'] 	 	= JRequest::getVar('body');
		$data['user_id'] 	= JRequest::getVar('user_id');

		$firstnames  		= JRequest::getVar('firstname',array(0), 'post', 'array');
		$lastnames   		= JRequest::getVar('lastname',array(0), 'post', 'array');
		$emails      		= JRequest::getVar('email',array(0), 'post', 'array');
		$varFirstname		="";
		$varLastname		="";
		$varEmail			="";
		$i          		= 0;

		foreach($firstnames as $firstname){
			if($firstname!=""){
				$varFirstname.="&firstname[]=".$firstname;
				$varLastname.="&lastname[]=".$lastnames[$i];
				$varEmail.="&email[]=".$emails[$i];
			}
			$i++;
		}

		if($data['subject']==""){
			JError::raiseWarning( 100, 'Subject is required' );
			$link='index.php?option=com_awardpackage&view=packageuser&layout=new_message_non_award'.$varFirstname.$varLastname.$varEmail.'&package_id='.$this->package_id.'&category_id='.$this->category_id.'&act='.JRequest::getVar('act');
			$this->setRedirect($link,$msg);
		}elseif($data['body']==""){
			JError::raiseWarning(100,'Message is required');
		}else{
			if($model->saveMessage($data)){
				$msg  ='Success : user message has been saved';
				$link ='index.php?option=com_awardpackage&view=packageuser&layout=non_award&package_id='.JRequest::getVar('package_id').'&category_id='.JRequest::getVar('category_id').'&user_id='.JRequest::getVar('user_id').$varFirstname.$varLastname.$varEmail.'&act='.JRequest::getVar('act');
				$this->setRedirect($link);
			}
		}

	}


	public function messageclose(){
		//set variable
		$data['subject'] = JRequest::getVar('subject');
		$data['body'] 	 = JRequest::getVar('body');
		$data['user_id'] = JRequest::getVar('user_id');

		//get array
		$firstnames  	= JRequest::getVar('firstname',array(0), 'post', 'array');
		$lastnames   	= JRequest::getVar('lastname',array(0), 'post', 'array');
		$emails      	= JRequest::getVar('email',array(0), 'post', 'array');
		$varFirstname	="";
		$varLastname	="";
		$varEmail	="";
		$i          	= 0;

		foreach($firstnames as $firstname){
			if($firstname!=""){
				$varFirstname.="&firstname[]=".$firstname;
				$varLastname.="&lastname[]=".$lastnames[$i];
				$varEmail.="&email[]=".$emails[$i];
			}
			$i++;
		}
		$link ='index.php?option=com_awardpackage&view=packageuser&layout=non_award&package_id='.$this->package_id.'&category_id='.$this->category_id.$varFirstname.$varLastname.$varEmail.'&act='.JRequest::getVar('act');
		$this->setRedirect($link);
	}

	public function back_to_message(){
		$firstnames  	= JRequest::getVar('firstname',array(0), 'post', 'array');
		$lastnames   	= JRequest::getVar('lastname',array(0), 'post', 'array');
		$emails      	= JRequest::getVar('email',array(0), 'post', 'array');
		$varFirstname	="";
		$varLastname	="";
		$varEmail	="";
		$i          	= 0;

		foreach($firstnames as $firstname){
			if($firstname!=""){
				$varFirstname.="&firstname[]=".$firstname;
				$varLastname.="&lastname[]=".$lastnames[$i];
				$varEmail.="&email[]=".$emails[$i];
			}
			$i++;
		}
		$link ='index.php?option=com_awardpackage&view=packageuser&layout=non_award&package_id='.JRequest::getVar('package_id').'&category_id='.JRequest::getVar('category_id').'&user_id='.JRequest::getVar('user_id').$varFirstname.$varLastname.$varEmail.'&act='.JRequest::getVar('act');
		$this->setRedirect($link);
	}

	public function nonapsynmessage(){
		$model  			=  JModelLegacy::getInstance('packageusers','AwardpackageModel');
		//get array
		$firstnames  	= JRequest::getVar('firstname',array(0), 'post', 'array');
		$lastnames   	= JRequest::getVar('lastname',array(0), 'post', 'array');
		$emails      	= JRequest::getVar('email',array(0), 'post', 'array');

		$varFirstname	="";
		$varLastname	="";
		$varEmail		="";
		$i          	= 0;

		foreach($firstnames as $firstname){
			if($firstname!=""){
				$varFirstname.="&firstname[]=".$firstname;
				$varLastname.="&lastname[]=".$lastnames[$i];
				$varEmail.="&email[]=".$emails[$i];
			}
			$i++;
		}
		$cids 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		if(count($cids)>1){
			$msg = 'Select one non user award package';
		}else{
			foreach($cids as $cid){
				$sync = $model->getNonApUser($cid);
			}
		}
		//if($sync){
		$msg ='Success : Sync Message success';
		//}
		$link ='index.php?option=com_awardpackage&view=packageuser&layout=non_award&package_id='.$this->package_id.'&category_id='.$this->category_id.$varFirstname.$varLastname.$varEmail . '&act='. JRequest::getVar('act');

		$this->setRedirect($link,$msg);
	}

	function nonapuserclose(){
		$this->setRedirect('index.php?option=com_awardpackage&view=category&package_id='.$this->package_id);
	}

	function addNewUserEmail(){
		$package_id = JRequest::getVar('package_id');
		$account_id = JRequest::getVar('user_selected');
		$category_id = JRequest::getVar('category_id');
		$model = JModelLegacy::getInstance('packageusers','AwardpackageModel');
		$model->updatePackageForUserAccounts($package_id, substr($account_id, 0, strlen($account_id)-1), $category_id);
		$msg = "Successfull update package account";
		$this->setRedirect('index.php?option=com_awardpackage&view=packageuser&package_id='.JRequest::getVar('package_id').'&field=email&category_id='.JRequest::getVar('category_id'), $msg);
	}

	function addNewUserName(){
		$package_id = JRequest::getVar('package_id');
		$account_id = JRequest::getVar('user_selected');
		$category_id = JRequest::getVar('category_id');
		$model = JModelLegacy::getInstance('packageusers','AwardpackageModel');
		$model->updatePackageForUserAccounts($package_id, substr($account_id, 0, strlen($account_id)-1), $category_id);
		$msg = "Successfull update package account";
		$this->setRedirect('index.php?option=com_awardpackage&view=packageuser&package_id='.JRequest::getVar('package_id').'&field=name&category_id='.JRequest::getVar('category_id'), $msg);
	}

}
?>