<?php

/*
 * @version 1.0.0
 * @package com_awardpackage
 * author kadeyasa<kadeyasa@gmail.com>
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
/*
 * selectwinner controller
 */

Class AwardPackageControllerSelectWinner extends JControllerLegacy {

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->package_id = JRequest::getVar('package_id');
        $this->option = 'com_awardpackage';
        $this->view = 'selectwinner';
        $this->db = & JModelLegacy::getInstance('potentialwinner', 'AwardPackageModel');
        $this->data = JRequest::getVar('jform');
        $this->id = JRequest::getVar('id');
        $this->field = JRequest::getVar('field');
        $this->presentation_id = JRequest::getVar('presentation_id');
        $this->mainmodel = & JModelLegacy::getInstance('main', 'AwardpackageModel');
		$this->model 	 = & JModelLegacy::getInstance('selectwinner', 'AwardpackageModel');
    }

    public function closewinner() {
        $link = 'index.php?option=' . $this->option . '&view=' . $this->view . '&package_id=' . $this->package_id. '&presentation_id=' . $this->presentation_id;
        $this->setRedirect($link);
    }

    public function closeunlocked() {
         $link = 'index.php?option=' . $this->option . '&view=' . $this->view . '&package_id=' . $this->package_id. '&presentation_id=' . $this->presentation_id;
        $this->setRedirect($link);
    }
	
	public function closeactualwinner(){
		$link = 'index.php?option=' . $this->option . '&view=' . $this->view . '&package_id=' . $this->package_id . '&presentation_id=' . $this->presentation_id;
        $this->setRedirect($link);

	}
	
    public function save_potential() {
        $link = 'index.php?option=' . $this->option . '&view=potentialwinner&package_id=' . $this->data['package_id'] . '&field=' . $this->data['field'] . '&presentation_id=' . $this->data['presentation_id'];
        $checkPackageExpired = $this->mainmodel->CheckPackageExpired($this->data['package_id']);
        if (!$checkPackageExpired) {
            JError::raiseWarning(100, 'Awardpackage expired');
            $this->setRedirect($link);
        } else {
            if ($this->data['field'] == 'name') {
                if ($this->data['firstname'] == "" || $this->data['lastname'] == "") {
                    JError::raiseWarning(100, 'Firstname or lastname not be empty');
                    $this->setRedirect($link);
                } else {
                    $save = $this->db->save($this->data);
                }
            } else {
                $save = $this->db->save($this->data);
            }
        }
        if ($save) {
            $msg = 'Data has been saved';
            $this->setRedirect($link, $msg);
        }
    }

    public function delete_potential() {
        $delete = $this->db->delete_potential_winner($this->id);
        $link = 'index.php?option=' . $this->option . '&view=potentialwinner&package_id=' . $this->package_id . '&field=' . $this->field . '&presentation_id=' . $this->presentation_id;
        if ($delete) {
            $msg = 'Data has been deleted';
            $this->setRedirect($link, $msg);
        } else {
            JError::raiseWarning(100, 'Delete data unsuccessfull');
            $this->setRedirect($link);
        }
    }
    
    public function potential_cancel(){
		$link = 'index.php?option=com_awardpackage&view=selectwinner&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id;
        $this->setRedirect($link);
    }
	
	public function potential_save(){
		$msg  = 'Data has been saved';
		$link = 'index.php?option=com_awardpackage&view=selectwinner&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id;
		$this->setRedirect($link);
	}
	
    public function edit_potential() {
        $link = 'index.php?option=com_awardpackage&view=potentialwinner&presentation_id='.$this->presentation_id.'&package_id=' . JRequest::getVar('package_id') . '&field=' . $this->field . '&id=' . $this->id;
        $this->setRedirect($link);
    }
	
	public function save(){
		$data = JRequest::getVar('jform');
		$presentation_id = JRequest::getVar('presentation_id');
		$package_id = JRequest::getVar('package_id');
		if($data['is_same_person']){
			$is_same_person ='1';
		}else{
			$is_same_person ='0';
		}
		if($this->model->saveSetting($presentation_id,$is_same_person)){
			$link ='index.php?option=com_awardpackage&view=selectwinner&package_id='.$package_id.'&presentation_id='.$presentation_id;
			$this->setRedirect($link);
		}
		
	}
	public function addwinner(){
		$error = array();
        $valid = false;
		if($_POST){
			$startunlocked = JRequest::getVar('startunlocked');
			$endunlocked   = JRequest::getVar('endunlocked');
			$presentation_id = JRequest::getVar('presentation_id');
			if($startunlocked =="" || $endunlocked==""){
				$error[]='Start unlocked prize value or end unlocked prize value not be empty';
			}else{
				$data['startunlocked']=$startunlocked;
				$data['endunlocked']=$endunlocked;
				$ap_winner_id = $this->model->addWinner($data);
				if($ap_winner_id){
					$rows = $this->model->checkFunding($startunlocked,$endunlocked);
					foreach($rows as $row){
						if($row->value>=$startunlocked && $row->value<=$endunlocked){
							$prizes = $this->model->checkPrizeWinnings($row->user_id,$startunlocked,$endunlocked);
							if(count($prizes)>0){
								foreach($prizes as $prize){
									$data['prize_id']=$prize->prize_id;
									$data['user_id']=$row->user_id;
									$data['ap_winner_id']=$ap_winner_id;
									$data['prize']=$prize->prize_value;
									$data['prize_value']=$prize->prize_value;
									$data['symbol_id']=$prize->symbol_id;
									$data['pieces_id']=$prize->symbol_pieces_id;
									$data['presentation_id']=$presentation_id;
									$this->model->addUserWinner($data);
									$i++;
								}
							}
							if($i<1){
								$this->model->deleteWinner($ap_winner_id);
							}else{
								$this->model->lockPrize($data['prize_id']);
							}
						}
					}
					
					echo'<script type="text/javascript">';
        			echo'
               			 function close(){
                    		window.parent.location.reload();
                   		 	window.parent.SqueezeBox.close();
                		}';
        			echo'</script>';
					echo'<body onLoad="close();"></body>';
				}
			}
		}
        if ($valid == false) {
            if (count($error) > 0) {
                JFactory::getApplication()->enqueueMessage(JText::_(implode("<br>", $error)), 'error');
            }
            JRequest::setVar('view', 'selectwinner');
            JRequest::setVar('layout', 'settingwinner');
            parent::display($cachable);
        } 
	}
	
	function delete(){
		$cids 	= JRequest::getVar('cid');
		$link	= 'index.php?option=com_awardpackage&view=selectwinner&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id;
		foreach($cids as $cid){
			if($this->model->deleteWinner($cid)){
				$msg='Data has been deleted';
				$this->setRedirect($link,$msg);
			}
		}
	}
	
	function close(){
		$link 	='index.php?option=com_awardpackage&view=presentationList&package_id='.$this->package_id;
		$this->setRedirect($link);
	}
	
	function symboldetailclose(){
		$winner_id = JRequest::getVar('winner_id');
		$back 	   = JRequest::getVar('back');
		$link 	   = 'index.php?option=com_awardpackage&view=selectwinner&layout='.$back.'&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&winner_id='.$winner_id;
		$this->setRedirect($link);
	}
	function symbolreturnclose(){
		$winner_id = JRequest::getVar('winner_id');
		$back 	   = JRequest::getVar('back');
		$link 	= 'index.php?option=com_awardpackage&view=selectwinner&layout='.$back.'&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&winner_id='.$winner_id;
		$this->setRedirect($link);
	}
}

?>
