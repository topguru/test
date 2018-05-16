<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT . '/helpers/awardpackages.php';

class AwardPackageControllerProcessSymbol extends JControllerLegacy {

	function __construct($config = array()) {
		parent::__construct($config);
		$this->package_id = JRequest::getInt('package_id');
		$this->presentation_id = JRequest::getInt('presentation_id');
	}

	public function add(){
		//set data array
		$data		= array();

		//get model
		$model		= $this->getModel('processsymbol');

		$data['prize_value_from']='';

		$data['presentation_id'] = $this->presentation_id;

		if($model->addItem($data)){
			$this->setRedirect('index.php?option=com_awardpackage&view=processsymbol&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id,'Data has been added');
		}

		$this->setRedirect('index.php?option=com_awardpackage&view=processsymbol&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id);
	}

	public function prizerange(){
		$model				= $this->getModel('processsymbol');
		$prizevalue_from	= JRequest::getVar('prizerange_from');
		$prizevalue_to		= JRequest::getVar('prizerange_to');
		$id					= JRequest::getVar('process_id');
		$presenation_id		= JRequest::getVar('presentation_id');
		if($model->checkProcessSymbolPrizeRange($prizevalue_from,$prizevalue_to)){
			$msg	='Prize range need out of exist range value';
		}else{
			if($model->checkProcessSymbolPrizeRangeTo($prizevalue_to)){
				$msg	='Prize range need out of exist range value';
			}else{
				if($model->saveProcessPrizeRange($prizevalue_from,$prizevalue_to,$id)){
					//$prizes	= $model->CheckPrize($prizevalue_from,$prizevalue_to,$presenation_id);
					$prizes = $model->CheckPrize2($presenation_id);
					if($prizes){
						foreach($prizes as $prize){
							$model->saveProcessPrize($id,$prize->symbol_prize_id,$prize->symbol_id);
						}
					}
					$msg ='Data has been saved';
				}
			}
		}
		echo $msg; die();
		//$this->setRedirect('index.php?option=com_awardpackage&view=processsymbol&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&layout=prizerange&tmpl=component',$msg);
	}

	public function cloned(){
		$model				= $this->getModel('processsymbol');
		$prizevalue_from	= JRequest::getVar('prize_from');
		$prizevalue_to		= JRequest::getVar('prize_to');
		$clone_from			= JRequest::getInt('clone_from');
		$clone_to			= JRequest::getInt('clone_to');
		$presenation_id		= JRequest::getInt('presentation_id');
		$process_id			= JRequest::getInt('process_id');
		if($clone_from){
			$prizes	= $model->CheckPrize2($presenation_id);
			if($prizes){
				foreach($prizes as $prize){
					$val = rand($clone_from, $clone_to);
					$clonedPrizes = $model->get_clone_prize($prize->prize_id,$process_id,$prize->symbol_id);
					if(!empty($clonedPrizes)) {
						$clonedPrize = $clonedPrizes[0];
						//delete clone pieces 
						$model->delete_clone_pieces($prize->symbol_id, $clonedPrize->id);
						//delete detail clone pieces
						$model->delete_clone_detail($clonedPrize->id);
						//delete master clone
						$model->delete_clone_prize($prize->prize_id,$process_id,$prize->symbol_id);
					}
					$save = $model->save_clone_prize($prize->prize_id,$process_id,$prize->symbol_id);
					$pieces = $model->getPieces($prize->symbol_id);
					foreach($pieces as $piece){
						for($j=0;$j<$val;$j++){
							$clone = $model->save_clone_pieces($piece->symbol_id,$piece->symbol_pieces_image);
							$save_detail = $model->save_clone_detail($save,$piece->symbol_pieces_id);
							$j++;							
						}						
					}					
				}
			}
			$data['clone_from']=$clone_from;
			$data['clone_to']=$clone_to;
			$data['id']=$process_id;
			$model->addItem($data);			
		}
		//$this->setRedirect('index.php?option=com_awardpackage&view=userrecord&layout=clone&tmpl=component&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&process_id='.$process_id,'Data has been saved');

	}

	public function shuffeled(){
		$model				= $this->getModel('processsymbol');
		$awardmodel			= $this->getModel('awardprogress');
		$archiveModel 		= & JModelLegacy::getInstance('archive', 'AwardPackageModel');
		$shuffle_from		= JRequest::getInt('shuffle_from');
		$shuffle_to			= JRequest::getInt('shuffle_to');
		$presentation_id	= JRequest::getInt('presentation_id');		
		$package_id = JRequest::getInt('package_id');
		$id	= JRequest::getInt('process_id');
		
		if($shuffle_from){
			if($shuffle_from<$shuffle_to){
				$val = rand($shuffle_from, $shuffle_to);
				for($i=0;$i<$val;$i++){
					$pieces = $model->shuffled_2($presentation_id);
				}
			}
		}
		$rows = $archiveModel->getApUser($this->package_id);
		$user = JFactory::getUser();
		if(empty($user)){
			JError::raiseWarning(100, 'NO select user in awardpackage');
			$this->setRedirect('index.php?option=com_awardpackage&view=processsymbol&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id);
		}else{
			if(!empty($user)) {
			//foreach ($rows as $row) {
				$awardmodel->delete_quee($user->id);
				if(!empty($pieces)) {
					$piece = $pieces[0];
					$awardmodel->delete_quee_details($piece);
				}
				
				$quee_id = $awardmodel->save_quee($user->id);
				foreach($pieces as $i=>$piece){
					$data['queue_id'] = $quee_id;
					$data['symbol_pieces_id'] = $piece->symbol_pieces_id;
					$data['symbol_prize_id'] = $piece->symbol_prize_id;
					$data['presentation_id'] = $piece->presentation_id;
					$save_details = $awardmodel->save_quee_details($data);
				}
			}
		}
		if($save_details){
			$data['id']=$id;
			$data['shuffle_from']=$shuffle_from;
			$data['shuffle_to']=$shuffle_to;
			//update
			$model->addItem($data);
			//$this->setRedirect('index.php?option=com_awardpackage&view=userrecord&layout=shuffle&tmpl=component&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id,'Data has been saved');
		}
	}

	public function close_symbol(){

		$process_id	= JRequest::getInt('process_id');

		$link		= 'index.php?option=com_awardpackage&view=userrecord&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&process_id='.$process_id;

		$this->setRedirect($link);
	}

	public function extractpieces(){
		$model				= $this->getModel('processsymbol');
		$prizevalue_from	= JRequest::getVar('prize_from');
		$prizevalue_to		= JRequest::getVar('prize_to');
		$extract_from		= JRequest::getVar('extract_from');
		$extract_to			= JRequest::getVar('extract_to');
		$presenation_id		= JRequest::getInt('presentation_id');
		$process_id			= JRequest::getInt('process_id');
		if($extract_from){
			$prizes	= $model->CheckPrize2($presenation_id);
			$i=0;
			if($prizes){
				foreach($prizes as $prize){
					$extractPieces = array();
					$val = rand($extract_from, $extract_to);
					$extracteds = $model->getExtract_2($process_id, $prize->prize_id);
					if(!empty($extracteds)) {
						$extracted = $extracteds[0];
						//do delete for extract detail
						$model->delete_extract_detail($extracted->id);
						//do delete from extract
						$model->deleteExtract($extracted->id);
						//update all is_locked status to 0
						$model->activeStatusAll($prize->symbol_id);						
					}
					$save = $model->saveExtract($process_id,$prize->prize_id);
					$pieces = $model->getPiecesAll($prize->symbol_id);
					$i=0;	
					foreach ($pieces as $piece) {
						$extractPieces[$i] = $piece;
						if($i == ($val-1)) break;
						$i++;
					}
					
					foreach ($extractPieces as $piece) {
						$save_detail = $model->save_extract_detail($save,$piece->symbol_pieces_id);
						$model->updateStatus($piece->symbol_pieces_id);
					}					
				}
			}
		}
		$data['extra_from']=$extract_from;
		$data['extra_to']=$extract_to;
		$data['id']=$process_id;
		$model->addItem($data);
		//$this->setRedirect('index.php?option=com_awardpackage&view=userrecord&layout=extract&tmpl=component&package_id='.$this->package_id.'&presentation_id='.$this->presentation_id.'&process_id='.$process_id,'Data has been saved');
	}
}
?>