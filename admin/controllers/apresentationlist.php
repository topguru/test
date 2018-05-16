<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageControllerApresentationlist extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}
	
	function initiate(){
		$view = $this->getView('apresentationlist', 'html');
		$view->assign('action', 'initiate');		
		$view->display();		
	}
	
	
	
	function addrow(){
			$packageId = JRequest::getVar('package_id');
			$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
			$ret = $model->InsertNewRow($packageId);
		$this->setRedirect('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id'),JTEXT::_('Add New Row'));
	}
	
	function addusergroup(){
		$var_id = '';		
		$package_id = JRequest::getVar('package_id');
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$userGroup = JRequest::getVar('idUserGroupsId');			
		$processPresentation = JRequest::getVar('processPresentation');	
		$presentationid	= JRequest::getVar('processSymbolId');	
		if($userGroup == ''){
			$message = 'Please select user group first';
		} else {			
			$ret = $model->createPresentationByUserGroup($userGroup, $package_id, $presentationid);
			$message = "Success created new presentation";
			if($ret == 0){
				$var_id = '';
				$this->setRedirect('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id'));
			} else {
				$var_id = $ret;
			}			
		}		
		$view = $this->getView('apresentationlist', 'html');
		$view->assign('action', 'initiate');	
		$view->assignRef('var_id', $var_id);
		$view->display();	
		/*
		if($model->addPresentationUserGroup($package_id)){
			$this->setRedirect('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id'),JTEXT::_('Success create new user group'));
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id'),JTEXT::_('Error insert user group'));	
		}
		*/
	}
	function addPresentation(){
		$var_id = '';		
		$package_id = JRequest::getVar('package_id');
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		
		$processPresentation = JRequest::getVar('idPresentationID');
		$prizename = JRequest::getVar('idPrizeID');
		$symbolqueue = JRequest::getVar('idSymbolGroupID');
		$funding = JRequest::getVar('idFundID');
		if($processPresentation == ''){
			$message = 'Please select process presentation first';
		} else {
			$ret = $model->createPresentationByProcessSymbol($processPresentation,$prizename,$symbolqueue,$funding, JRequest::getVar('package_id'));
			$message = "Success created new process presentation";
			if($ret == 0){
				$var_id = '';
				$this->setRedirect('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id'));
			} else {
				$var_id = $ret;
			}			
		}
		$view = $this->getView('apresentationlist', 'html');
		$view->assign('action', 'initiate');	
		$view->assignRef('var_id', $var_id);
		$view->display();
	}	
	
	function deleteSelectedPresentation(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$cids = JRequest::getVar('cid');

		foreach ($cids as $cid) {
			$model->deleteUserGroupPresentation($cid);
		}
		$this->setRedirect('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id'),JTEXT::_('Success delete user group'));
	}
	
	function deleteusergroup(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$cids = JRequest::getVar('cid');
		foreach ($cids as $cid) {
			$model->deleteUserGroupPresentation($cid);
		}
		$this->setRedirect('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id'),JTEXT::_('Success delete user group'));
	}
	
	function save(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$cids = JRequest::getVar('cid');

		foreach ($cids as $cid) {
		$idUserGroupsId= JRequest::getVar('idUserGroupsId_'.$cid);
		$idAwardFundID = JRequest::getVar('idAwardFundID_'.$cid);
		$idSymbolGroupID = JRequest::getVar('idSymbolGroupID_'.$cid);
		$idSymbolQueue = JRequest::getVar('idSymbolQueue_'.$cid);
		$idAssignSymbolQueue = JRequest::getVar('idAssignSymbolQueue_'.$cid);
		$idPresentationID= JRequest::getVar('idPresentationID_'.$cid);
		$idSymbolQueueID= JRequest::getVar('idSymbolQueueID_'.$cid);
		$idPrizeValueID= JRequest::getVar('idPrizeValue_'.$cid);
		

	$model->SaveUserGroupPresentation($cid,$idAwardFundID,$idSymbolGroupID,$idSymbolQueue,$idAssignSymbolQueue,$idUserGroupsId,$idPresentationID,$idSymbolQueueID,$idPrizeValueID);
	$model->UpdateUserGroupSymbol($cid,$idPresentationID,$idSymbolGroupID);
	$model->UpdateAwardFundPlan($cid,$idAwardFundID,$idUserGroupsId);
		}
		
		$groupName = $model->getUserGroupId($idUserGroupsId);
		$jumlah = count($groupName);
		$Symbolqueues = $model->getSymbolqueues($idSymbolGroupID);	
		$i = 1;
					foreach ($Symbolqueues as $rows){
					$queue_id = $rows->queue_id ;
						}	
			     foreach ($groupName as $row){
					$userid = $row->useraccount_id ;
					$model->UpdateUserAssigned($userid,$idSymbolGroupID,$queue_id,$i,$idPresentationID);	
					$queue_id++;
					$i++;
						}	
		$this->setRedirect('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id'),JTEXT::_('Success Update Presentation Usergroup'));
	}
	
		function symbolSetStatus(){
		$package_id = JRequest::getVar('package_id');
		$view = $this->getView('apresentationlist', 'html');
		$view->assign('action', 'symbol_set_status');
		$view->display('symbol_set_status');
	}
	
	function fundPrizeHistory(){
		$package_id = JRequest::getVar('package_id');
		$view = $this->getView('apresentationlist', 'html');
		$view->assign('action', 'fund_prize_history');
		$view->display('fund_prize_history');
	}
	
	function fundReceiverList(){
		$package_id = JRequest::getVar('package_id');
		$view = $this->getView('apresentationlist', 'html');
		$view->assign('action', 'fund_receiver_list');
		$view->display('fund_receiver_list');
	}
	
	function fundPrizePlan(){
		$package_id = JRequest::getVar('package_id');
		$view = $this->getView('apresentationlist', 'html');
		$view->assign('action', 'fund_prize_plan');
		$view->display('fund_prize_plan');
	}
	
	function prizeStatusHistory(){
		$package_id = JRequest::getVar('package_id');
		$view = $this->getView('apresentationlist', 'html');
		$view->assign('action', 'prize_status_history');
		$view->display('prize_status_history');
	}
	
	function get_usergroup(){
		$package_id = JRequest::getVar('package_id');
		$criteria_id = JRequest::getVar('criteria_id');
		$this->setRedirect('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id='.JRequest::getVar('package_id'),JTEXT::_('Success add user group'));
	}
	
	public function save_create(){
		$package_id = JRequest::getVar('package_id');
		$title = JRequest::getVar('title');
		$idUserGroupsId= JRequest::getVar('criteria_id');
		$var_id= JRequest::getVar('var_id');				     
			$this->setRedirect('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id=' .$package_id . '&title=' . $title . '&idUserGroupsId=' . $idUserGroupsId . '&var_id=' . $var_id. '&command=1', JText::_('MSG_SUCCESS'));
	}
}