<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageControllerAprocesspresentation extends JControllerLegacy {
	function __construct() {
		parent::__construct();
	}
	
	function addSelectedPresentation(){
		$package_id = JRequest::getVar('package_id');
		$groupId = JRequest::getVar('idUserGroupsId');
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$model->addNewSelectedPresentation($package_id);
		$this->setRedirect('index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.$package_id.'&idUserGroupsId='.$groupId.'&index='.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"),JTEXT::_('Select new presentation'));
	}
	
	function new_process_title(){
		$package_id = JRequest::getVar('package_id');
		$idProses = JRequest::getVar('idProcess');
		$idProsesValue = JRequest::getVar('idProcessValue');
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$model->insertNewProcessPresentationTitle($idProses,$idProsesValue,$package_id);
		
 $dataId = $model->getProcessId();
				 foreach ($dataId as $row) {
					$process_id = $row->id;
				 }
				 
		$this->setRedirect('index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.$package_id.'&idUserGroupsId='.$groupId.'&index='.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.$process_id.'&idProcess='.$idProses.'&idProcessValue='.$idProsesValue.'' ,JTEXT::_('Select new presentation'));
	}
	
	function new_process_1(){
		$presentation_id = '';
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$process = JRequest::getVar('idProcess');
		$value = JRequest::getVar('idProcessValue');
		$presentation_id = JRequest::getVar('idPresID');

		if($process == ''){
			$message = 'Please select New Process Presentation first';
			$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id"), $message);
				
		} else {
			$ret = $model->UpdatePresentationByProcess_1($process, $value, $presentation_id, JRequest::getVar('package_id'),JRequest::getVar('process_id'));
			$ret2 = $model->InputPresentationByProcess_1($process, $value, $presentation_id, JRequest::getVar('package_id'),JRequest::getVar('process_id'));
			$message = "Success created new presentation";
			if($ret == 0){
				$presentation_id = '';
			} else {
				$presentation_id = $ret;
			}			
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id").'&command_id=1&process_id='.JRequest::getVar("process_id"), $message);	
	}	
	
	public function delete_startfundprizeplan(){
		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));

		if(!empty($ids['cid'])){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
			JArrayHelper::toInteger($ids['cid']);
			if($model->delete_startfundprizeplan($ids['cid'])){
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id").'&process_id='.JRequest::getVar('process_id'), false), JText::_('MSG_SUCCESS'));
			} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id").'&process_id='.JRequest::getVar('process_id'), false), JText::_('MSG_ERROR'));
			}
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id").'&process_id='.JRequest::getVar('process_id'), false), JText::_('MSG_NO_ITEM_SELECTED'));
		}
	}
	
	
	
		function new_process_2(){
		$presentation_id = '';
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$process = JRequest::getVar('idProcess');
		$value = JRequest::getVar('idProcessValue');
		$idFundPrizePlan = JRequest::getVar('idFundPrizePlan');	
		
		$fundprizeId = $model->getFundPrizesId($idFundPrizePlan,JRequest::getVar("package_id"));

		foreach ($fundprizeId as $row){
		$sname = $row->name;
		$value_from =$row->value_from;
		$value_to = $row->value_to;
		}
		if($process == ''){
			$message = 'Please select New Process Presentation first';
			$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id"), $message);
				
		} else {
			$ret = $model->UpdatePresentationByProcess_2($process, $value, $idFundPrizePlan, JRequest::getVar('package_id'),JRequest::getVar('process_id'));
						$ret2 = $model->save_fund_prize_plan(JRequest::getVar("package_id"), $sname,JRequest::getVar('process_id'),$value_from,$value_to,$id);

			$message = "Success created new presentation";
			if($ret == 0){
				$presentation_id = '';
			} else {
			//UpdatePresentationByProcess_2($process, $value, $idFundPrizePlan, JRequest::getVar('package_id'),JRequest::getVar('process_id'));
				$presentation_id = $ret;
			}			
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id").'&process_id='.JRequest::getVar("process_id"), $message);
	}	
	
	function new_process_3(){
		$presentation_id = '';
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$process = JRequest::getVar('idProcess');
		$value = JRequest::getVar('idProcessValue');
		$AwardFundPlan = JRequest::getVar('AwardFundPlan');	
		
		if($process == ''){
			$message = 'Please select New Process Presentation first';
			$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id"), $message);
				
		} else {
			$ret = $model->UpdatePresentationByProcess_3($process, $value, $AwardFundPlan, JRequest::getVar('package_id'),JRequest::getVar('process_id'));
		    $award=$model->UpdateSelectedBy(JRequest::getVar('process_id'),$AwardFundPlan);
			$message = "Success created new presentation";
			if($ret == 0){
				$presentation_id = '';
			} else {
				$presentation_id = $ret;
			}			
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id").'&process_id='.JRequest::getVar("process_id"), $message);
	}	
	
	function new_process_4(){
		$presentation_id = '';
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$process = JRequest::getVar('idProcess');
		$value = JRequest::getVar('idProcessValue');
		$idReceiverID = JRequest::getVar('idReceiverID');	
		if($process == ''){
			$message = 'Please select New Process Presentation first';
			$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id"), $message);
				
		} else {
			$ret = $model->UpdatePresentationByProcess_4($process, $value, $idReceiverID, JRequest::getVar('package_id'),JRequest::getVar('process_id'));
			$message = "Success created new presentation";
			if($ret == 0){
				$presentation_id = '';
			} else {
				$presentation_id = $ret;
			}			
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id").'&process_id='.JRequest::getVar("process_id"), $message);
	}	
	
	function new_process_5(){
		$presentation_id = '';
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$process = JRequest::getVar('idProcess');
		$value = JRequest::getVar('idProcessValue');
		$idSymbolFilledID = JRequest::getVar('idSymbolFilledID');	
		if($process == ''){
			$message = 'Please select New Process Presentation first';
			$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id"), $message);
				
		} else {
			$ret = $model->UpdatePresentationByProcess_5($process, $value, $idSymbolFilledID, JRequest::getVar('package_id'),JRequest::getVar('process_id'));
			$message = "Success created new presentation";
			if($ret == 0){
				$presentation_id = '';
			} else {
				$presentation_id = $ret;
			}			
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id").'&process_id='.JRequest::getVar("process_id"), $message);	
	}	
	
	function showProcessPresentation(){
		$view = $this->getView('aprocesspresentation', 'html');
		$view->assign('action', 'showProcessPresentation');
		$view->display();
	}
	
	function showFundPrizeHistory(){
		$view = $this->getView('aprocesspresentation', 'html');
		$view->assign('action', 'showFundPrizeHistory');
		$view->display('fund_prize_history');
	}
	
	function doSelectPresentations(){
		$presentation = null;
		$params = JRequest::getVar('presentations');
		$presentation = $params[(int) JRequest::getVar('index')];
		$view = $this->getView('aprocesspresentation', 'html');
		$view->presentation_ids = $presentation;
		$view->assign('action', 'doSelectPresentations');
		$view->display('presentations');
	}
	function saveSelectedPresentations(){
		$package_id = JRequest::getVar('package_id');
		$selectedPresentation = JRequest::getVar('selectedPresentation');
		$groupId = JRequest::getVar('idUserGroupsId');
		$cid = JRequest::getVar('cid');
		$presentationIds = implode(',', $cid);
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$model->updateSelectedPresentationsSetPresentations($selectedPresentation, $presentationIds);
		$this->setRedirect('index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.$package_id.'&idUserGroupsId='.$groupId.'&index='.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"));
	}
	function updatePresentation() {
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$model->updatePresentationStatus(JRequest::getVar('presentationId'));
		$view = $this->getView('aprocesspresentation', 'html');
		$view->assign('action', 'showProcessPresentation');
		$view->display();
	}
	function addNewProcessSymbol($presentation_id, $selectedPresentation){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$model->insertNewProcessSymbol($presentation_id, $selectedPresentation);
	}
	
	function addExtractPieces(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 

		$presentation = null;
		$params = JRequest::getVar('presentations'); 
		$presentations = JRequest::getVar('cid1'.(int) JRequest::getVar('index'));
		
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}

				$params = JRequest::getVar('extract_pieces');
				$extract_pieces = $params[(int) JRequest::getVar('index')];
				$process_symbol_id = $processSymbol->id;
				$package_id = JRequest::getVar('package_id');
				if((int)$extract_pieces > 0) {
					$prizes	= $model->check_prize($presentation_id);
					$i=0;
					foreach($prizes as $prize){
						$extractPieces = array();
						$val = $extract_to;
						$extracteds = $model->getExtract_2($process_symbol_id, $prize->prize_id);
						if(!empty($extracteds)) {
							$extracted = $extracteds[0];
							//do delete for extract detail
							$model->delete_extract_detail($extracted->id);
							//do delete from extract
							$model->deleteExtract($extracted->id);
							//update all is_locked status to 0
							//$model->activeStatusAll($prize->symbol_id);
						}
						$save = $model->saveExtract($process_symbol_id,$prize->prize_id);
						$pieces = $model->getPiecesAll($prize->symbol_id);
						foreach ($pieces as $i=>$piece) {
							$extractPieces[$i] = $piece;
							if($i == ($extract_pieces-1)) break;
						}
						foreach ($extractPieces as $piece) {
							$save_detail = $model->save_extract_detail($save,$piece->symbol_pieces_id);
							//$model->updateStatus($piece->symbol_pieces_id);
						}
						$model->saveUpdateExtractData($extract_pieces, $process_symbol_id);
						$data = $model->getUpdateSymbol($process_symbol_id);
						foreach ($data as $row) {
							//$extract_pieces = $row->extra_from;
							$value_pieces  = $row->extra_to;
							$clone_vpc  = $row->clone_from;
							$clone_fpc = $row->clone_to;
						}
						$model->saveUpdateSymbol($extract_pieces, $value_pieces, $clone_vpc ,$clone_fpc ,$process_symbol_id);						$msg="Add Extract Pieces (EP) success";
					}
				} else {
					$msg="Add Extract Pieces (EP) fail";
				}
			}

		} else {
			$msg = 'Select presentation first';
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.
		JRequest::getVar("package_id").'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&position=processSymbol'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}
	
	function addExtractPieces1(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		
		$presentation = null;
		$params = JRequest::getVar('presentations');
		

		$presentations = JRequest::getVar('cid1'.(int) JRequest::getVar('index'));
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
			var_dump($presentation_id );
break;
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}
				$params = JRequest::getVar('extract_pieces');
				$extract_pieces = $params[(int) JRequest::getVar('index')];
				$process_symbol_id = $processSymbol->id;
				$package_id = JRequest::getVar('package_id');
				if((int)$extract_pieces > 0) {
					$prizes	= $model->check_prize($presentation_id);
					$i=0;
					foreach($prizes as $prize){
						$extractPieces = array();
						$val = $extract_to;
						$extracteds = $model->getExtract_2($process_symbol_id, $prize->prize_id);
						if(!empty($extracteds)) {
							$extracted = $extracteds[0];
							//do delete for extract detail
							$model->delete_extract_detail($extracted->id);
							//do delete from extract
							$model->deleteExtract($extracted->id);
							//update all is_locked status to 0
							//$model->activeStatusAll($prize->symbol_id);
						}
						$save = $model->saveExtract($process_symbol_id,$prize->prize_id);
						$pieces = $model->getPiecesAll($prize->symbol_id);
						foreach ($pieces as $i=>$piece) {
							$extractPieces[$i] = $piece;
							if($i == ($extract_pieces-1)) break;
						}
						foreach ($extractPieces as $piece) {
							$save_detail = $model->save_extract_detail($save,$piece->symbol_pieces_id);
							//$model->updateStatus($piece->symbol_pieces_id);
						}
						$model->saveUpdateExtractData($extract_pieces, $process_symbol_id);
						$data = $model->getUpdateSymbol($process_symbol_id);
						foreach ($data as $row) {
							//$extract_pieces = $row->extra_from;
							$value_pieces  = $row->extra_to;
							$clone_vpc  = $row->clone_from;
							$clone_fpc = $row->clone_to;
						}
						$model->saveUpdateSymbol($extract_pieces, $value_pieces, $clone_vpc ,$clone_fpc ,$process_symbol_id);						$msg="Add Value Pieces (VP) success";
					}
				} else {
					$msg="add Value Pieces (VP) fail";
				}
			}
		} else {
			$msg = "Select presentation first";
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id").
				'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&position=processSymbol'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id").'&process_id='.JRequest::getVar("process_id"), $msg);
	}

     function AddValuePieces(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		

		$presentation = null;
		$params = JRequest::getVar('presentations');
		$presentations = JRequest::getVar('cid1'.(int) JRequest::getVar('index'));
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}
				$params = JRequest::getVar('value_pieces');
				$value_pieces = $params[(int) JRequest::getVar('index')];
				$process_symbol_id = $processSymbol->id;
				$package_id = JRequest::getVar('package_id');
				if((int)$value_pieces > 0) {
					$prizes	= $model->check_prize($presentation_id);
					$i=0;
					foreach($prizes as $prize){
						$extractPieces = array();
						$val = $extract_to;
						$extracteds = $model->getExtract_2($process_symbol_id, $prize->prize_id);
						if(!empty($extracteds)) {
							$extracted = $extracteds[0];
							//do delete for extract detail
							$model->delete_extract_detail($extracted->id);
							//do delete from extract
							$model->deleteExtract($extracted->id);
							//update all is_locked status to 0
							//$model->activeStatusAll($prize->symbol_id);
						}
						$save = $model->saveExtract($process_symbol_id,$prize->prize_id);
						$pieces = $model->getPiecesAll($prize->symbol_id);
						foreach ($pieces as $i=>$piece) {
							$extractPieces[$i] = $piece;
							if($i == ($value_pieces-1)) break;
						}
						foreach ($extractPieces as $piece) {
							$save_detail = $model->save_extract_detail($save,$piece->symbol_pieces_id);
							//$model->updateStatus($piece->symbol_pieces_id);
						}
						//$model->saveUpdateExtractData($value_pieces, $process_symbol_id);
						$data = $model->getUpdateSymbol($process_symbol_id);
						foreach ($data as $rows) {
							$extract_pieces = $rows->extra_from;
							//$value_pieces  = $row->extra_to;
							$clone_vpc  = $rows->clone_from;
							$clone_fpc = $rows->clone_to;
						}
						$model->saveUpdateSymbol($extract_pieces, $value_pieces, $clone_vpc ,$clone_fpc ,$process_symbol_id);						$msg="Add Value Pieces (VP) success";
					}
				} else {
					$msg="Add Value Pieces (VP) fail";
				}
			}
		} else {
			$msg = "Select presentation first";
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id").
				'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&position=processSymbol'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}

	function AddNumCloneVal(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		

		$presentation = null;
		$params = JRequest::getVar('presentations');
		//$presentation = $params[(int) JRequest::getVar('index')];
		//$presentations = explode(',', $presentation);
		$presentations = JRequest::getVar('cid1'.(int) JRequest::getVar('index'));
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}

				$params = JRequest::getVar('num_clone_val');
				$num_clone_val = $params[(int) JRequest::getVar('index')];
				$process_symbol_id = $processSymbol->id;
				$package_id = JRequest::getVar('package_id');
				if((int)$num_clone_val > 0) {
					$prizes	= $model->check_prize($presentation_id);
					if($prizes){
						foreach($prizes as $prize){
							$val = $num_clone_val;
							$clonedPrizes = $model->get_clone_prize($prize->prize_id, $process_symbol_id, $prize->symbol_id);
							if(!empty($clonedPrizes)) {
								$clonedPrize = $clonedPrizes[0];
								//delete clone pieces
								//$model->delete_clone_pieces($prize->symbol_id, $clonedPrize->id);
								//delete detail clone pieces
								$model->delete_clone_detail($clonedPrize->id);
								//delete master clone
								$model->delete_clone_prize($prize->prize_id, $process_symbol_id, $prize->symbol_id);
							}

							$save = $model->save_clone_prize($prize->prize_id,$process_symbol_id,$prize->symbol_id);

							$extracteds = $model->get_extracted_pieces($presentation_id, $prize->symbol_id, $process_symbol_id);

							$extracts = '-88,';
							foreach ($extracteds as $extracted){
								$extracts .= $extracted->symbol_pieces_id . ',';
							}
							$extracts .= substr($extracts, 0, strlen($extracts)-1);

							$pieces = $model->getPieces_2($prize->symbol_id, $extracts);

							for($j=0;$j<$val;$j++){
								foreach($pieces as $piece){
									$save_detail = $model->save_clone_detail($save,$piece->symbol_pieces_id);
								}
							}
						}
						//$model->saveUpdateClonData($num_clone_val, $process_symbol_id);
						$data = $model->getUpdateSymbol($process_symbol_id);
						foreach ($data as $rows) {
							$extract_pieces = $rows->extra_from;
							$value_pieces  = $rows->extra_to;
							//$clone_vpc  = $rows->clone_from;
							$clone_fpc = $rows->clone_to;
						}
						$model->saveUpdateSymbol($extract_pieces, $value_pieces, $num_clone_val ,$clone_fpc ,$process_symbol_id);							
						$msg="Set each VP success";
					}
				} else {
					$msg="Set each VP fail";
				}
			}

		} else {
			$msg = 'Select presentation first';
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.
		JRequest::getVar("package_id").'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&position=processSymbol'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}
	
	function AddNumCloneFree(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		

		$presentation = null;
		$params = JRequest::getVar('presentations');
		//$presentation = $params[(int) JRequest::getVar('index')];
		//$presentations = explode(',', $presentation);
		$presentations = JRequest::getVar('cid1'.(int) JRequest::getVar('index'));
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}

				$params = JRequest::getVar('num_clone_free');
				$num_clone_free = $params[(int) JRequest::getVar('index')];
				$process_symbol_id = $processSymbol->id;
				$package_id = JRequest::getVar('package_id');
				if((int)$num_clone_free > 0) {
					$prizes	= $model->check_prize($presentation_id);
					if($prizes){
						foreach($prizes as $prize){
							$val = $num_clone_free;
							$clonedPrizes = $model->get_clone_prize($prize->prize_id, $process_symbol_id, $prize->symbol_id);
							if(!empty($clonedPrizes)) {
								$clonedPrize = $clonedPrizes[0];
								//delete clone pieces
								//$model->delete_clone_pieces($prize->symbol_id, $clonedPrize->id);
								//delete detail clone pieces
								$model->delete_clone_detail($clonedPrize->id);
								//delete master clone
								$model->delete_clone_prize($prize->prize_id, $process_symbol_id, $prize->symbol_id);
							}

							$save = $model->save_clone_prize($prize->prize_id,$process_symbol_id,$prize->symbol_id);

							$extracteds = $model->get_extracted_pieces($presentation_id, $prize->symbol_id, $process_symbol_id);

							$extracts = '-88,';
							foreach ($extracteds as $extracted){
								$extracts .= $extracted->symbol_pieces_id . ',';
							}
							$extracts .= substr($extracts, 0, strlen($extracts)-1);

							$pieces = $model->getPieces_2($prize->symbol_id, $extracts);

							for($j=0;$j<$val;$j++){
								foreach($pieces as $piece){
									$save_detail = $model->save_clone_detail($save,$piece->symbol_pieces_id);
								}
							}
						}
						//$model->saveUpdateClonData($num_clone_val, $process_symbol_id);
						$data = $model->getUpdateSymbol($process_symbol_id);
						foreach ($data as $rows) {
							$extract_pieces = $rows->extra_from;
							$value_pieces  = $rows->extra_to;
							$clone_vpc  = $rows->clone_from;
							//$clone_fpc = $rows->clone_to;
						}
						$model->saveUpdateSymbol($extract_pieces, $value_pieces, $clone_vpc ,$num_clone_free ,$process_symbol_id);							
						$msg="Set each FP success";
					}
				} else {
					$msg="Set each FP fail";
				}
			}

		} else {
			$msg = 'Select presentation first';
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.
		JRequest::getVar("package_id").'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&position=processSymbol'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}
	
		function priceOfExtractedPieces(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		

		$presentation = null;
		$params = JRequest::getVar('presentations');
		//$presentation = $params[(int) JRequest::getVar('index')];
		//$presentations = explode(',', $presentation);
		$presentations = JRequest::getVar('cid2'.(int) JRequest::getVar('index'));
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}

				$params = JRequest::getVar('price_of_each_extracted_pieces');
				$price_pieces = $params[(int) JRequest::getVar('index')];
				$process_symbol_id = $processSymbol->id;
				$package_id = JRequest::getVar('package_id');
				if((int)$price_pieces > 0) {
					$prizes	= $model->check_prize($presentation_id);
					if($prizes){
						
				$symbol_pricing_id = $model->saveSymbolPricing($presentation_id, $cidd);
				$detail = $model->getPricingDetailsByPricingId($symbol_pricing_id);
				$model->addPricingDetail($price_pieces,(null != $detail ? $detail->details_id : null),$symbol_pricing_id,$presentation_id, $process_symbol_id);							
				$model->saveUpdatePricePiecesData($price_pieces, $process_symbol_id);

						$msg="set Price of each EP success";
					}
				} else {
					$msg="set Price of each EP fail";
				}
			}

		} else {
			$msg = 'Select presentation first';
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.
		JRequest::getVar("package_id").'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&position=processSymbol'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}
	
		function priceOfSelectedRPC(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		

		$presentation = null;
		$params = JRequest::getVar('presentations');
		//$presentation = $params[(int) JRequest::getVar('index')];
		//$presentations = explode(',', $presentation);
		$presentations = JRequest::getVar('cid2'.(int) JRequest::getVar('index'));
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}

				$params = JRequest::getVar('price_of_selected_rpc');
				$price_pieces = $params[(int) JRequest::getVar('index')];
				$process_symbol_id = $processSymbol->id;
				$package_id = JRequest::getVar('package_id');
				if((int)$price_pieces > 0) {
					$prizes	= $model->check_prize($presentation_id);
					if($prizes){
						
				$symbol_pricing_id = $model->saveSymbolPricing($presentation_id, $cidd);
				$detail = $model->getPricingDetailsByPricingId($symbol_pricing_id);
				$model->addPricingDetail($price_pieces,(null != $detail ? $detail->details_id : null),$symbol_pricing_id,$presentation_id, $process_symbol_id);							
				$model->saveUpdatePriceValuesData($price_pieces, $process_symbol_id);

						$msg="set Price of each VP success";
					}
				} else {
					$msg="set Price of each VP fail";
				}
			}

		} else {
			$msg = 'Select presentation first';
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.
		JRequest::getVar("package_id").'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&position=processSymbol'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}
	
	function priceOfExtractedPieces1(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		
						
		$presentation = null;
		$params = JRequest::getVar('presentations');
		$presentations = JRequest::getVar('cid2'.(int) JRequest::getVar('index'));

		if(!empty($presentations)){						
			foreach ($presentations as $presentation_id){
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}
				$params = JRequest::getVar('price_of_each_extracted_pieces');
				$price_pieces = $params[(int) JRequest::getVar('index')];
				$process_symbol_id = $processSymbol->id;
				$package_id = JRequest::getVar('package_id');
				$symbol_pricing_id = $model->saveSymbolPricing($presentation_id, $cidd);
				$detail = $model->getPricingDetailsByPricingId($symbol_pricing_id);
				$model->addPricingDetail($price_pieces,(null != $detail ? $detail->details_id : null),$symbol_pricing_id,$presentation_id, $process_symbol_id);
			}
		} else {
			$msg = "Select presentation first";
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='
		.JRequest::getVar("package_id").'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&position=priceSymbol'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}
	function rpcSelectedForPricing(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		

		$presentation = null;
		$params = JRequest::getVar('presentations');

		$presentations = JRequest::getVar('cid2'.(int) JRequest::getVar('index'));
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}
				$process_symbol_id = $processSymbol->id;
				$package_id = JRequest::getVar('package_id');
				$params = JRequest::getVar('rpc_selected_for_pricing');
				$rpc_selected_for_pricing = $params[(int) JRequest::getVar('index')];

				if((int)$rpc_selected_for_pricing > 0) {
					$prizes	= $model->check_prize($presentation_id);
					if($prizes){
						foreach($prizes as $prize){
							$clonedPrizes = $model->get_clone_prize($prize->prize_id, $process_symbol_id, $prize->symbol_id);
							if(!empty($clonedPrizes)) {
								$model->onSelectedPricingRPC($prize->prize_id, $process_symbol_id, $prize->symbol_id, $rpc_selected_for_pricing);
								$clones = $model->get_clone_detail($prize->prize_id, $process_symbol_id, $prize->symbol_id);
								$model->updateCloneDetailForLock($prize->prize_id, $process_symbol_id, $prize->symbol_id, count($clones), '0');
								//update is_lock = 1 from presentation
								$clonesToUpdate = 0;
								if(!empty($clones ) && count($clones) > 0) {
									$clonesToUpdate = ceil(((int)$rpc_selected_for_pricing / 100) * count($clones)) ;
								}
								$model->updateCloneDetailForLock($prize->prize_id, $process_symbol_id, $prize->symbol_id, $clonesToUpdate, '1');
							} else {
								$msg= "error process";
							}
						}
					}
				} else {
					$msg="error process";
				}
			}

		} else {
			$msg = 'Select presentation first';
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='
		.JRequest::getVar("package_id").'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&position=priceSymbol'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}
	
	function priceOfSelectedRPC1(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		

		$presentation = null;
		$params = JRequest::getVar('presentations');
		$presentations = JRequest::getVar('cid2'.(int) JRequest::getVar('index'));
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}
				$params = JRequest::getVar('price_of_selected_rpc');
				$price_pieces = $params[(int) JRequest::getVar('index')];
				$process_symbol_id = $processSymbol->id;
				$package_id = JRequest::getVar('package_id');
				$symbol_pricing_id = $model->saveSymbolPricing($presentation_id, $cidd);
				$detail = $model->getPricingDetailsByPricingId($symbol_pricing_id);
				$model->addPricingDetail2($price_pieces,(null != $detail ? $detail->details_id : null),$symbol_pricing_id,$presentation_id, $process_symbol_id);
			}
		} else {
			$msg = 'Select presentation first';
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='
		.JRequest::getVar("package_id").'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&position=priceSymbol'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}
	function insertOfPricedRPC(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		

		$presentation = null;
		$params = JRequest::getVar('presentations');

		$presentations = JRequest::getVar('cid3'.(int) JRequest::getVar('index'));
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}
				$process_symbol_id = $processSymbol->id;
				$package_id = JRequest::getVar('package_id');
				$params = JRequest::getVar('insert_of_priced_rpc');
				$insert_of_priced_rpc = $params[(int) JRequest::getVar('index')];

				if((int) $insert_of_priced_rpc > 0){
					$prizes	= $model->check_prize($presentation_id);
					if($prizes){
						foreach($prizes as $prize){
							$clonedPrizes = $model->get_clone_prize($prize->prize_id, $process_symbol_id, $prize->symbol_id);
							if(!empty($clonedPrizes)) {
								$model->onInsertOfPricedRPC($prize->prize_id,$process_symbol_id,$prize->symbol_id,$insert_of_priced_rpc);
								$clones = $model->get_clone_detail($prize->prize_id, $process_symbol_id, $prize->symbol_id);
								$clonesToUpdate = 0;
								$selectedClones = array();
								foreach ($clones as $clone){
									$model->updateInsertOfPricedRPC($clone->id,'0');
									if($clone->is_lock == '1'){
										$selectedClones[] = $clone;
									}
								}
								$clonesToUpdate = ceil(((int)$insert_of_priced_rpc / 100) * count($selectedClones));
								for($i = 0; $i < $clonesToUpdate; $i++){
									$selectedClone = $selectedClones[$i];
									$model->updateInsertOfPricedRPC($selectedClone->id,'1');
								}
							}
						}
					}
				} else {
					$msg="error process";
				}
			}

		} else {
			$msg = 'Select presentation first';
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='
		.JRequest::getVar("package_id").'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&position=distributeSymbol'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}
	function insertOfFreeRPC(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		

		$presentation = null;
		$params = JRequest::getVar('presentations');
		//$presentation = $params[(int) JRequest::getVar('index')];
		//$presentations = explode(',', $presentation);
		$presentations = JRequest::getVar('cid3'.(int) JRequest::getVar('index'));
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}
				$process_symbol_id = $processSymbol->id;
				$package_id = JRequest::getVar('package_id');
				$params = JRequest::getVar('insert_of_free_rpc');
				$insert_of_free_rpc = $params[(int) JRequest::getVar('index')];
				if((int) $insert_of_free_rpc > 0){
					$prizes	= $model->check_prize($presentation_id);
					if($prizes){
						foreach($prizes as $prize){
							$clonedPrizes = $model->get_clone_prize($prize->prize_id, $process_symbol_id, $prize->symbol_id);
							if(!empty($clonedPrizes)) {
								$model->onInsertOfFreePRC($prize->prize_id,$process_symbol_id,$prize->symbol_id,$insert_of_free_rpc);
								$clones = $model->get_clone_detail($prize->prize_id, $process_symbol_id, $prize->symbol_id);
								$clonesToUpdate = 0;
								$selectedClones = array();
								foreach ($clones as $clone){
									$model->updateInsertOfFreeRPC($clone->id, '0');
									if($clone->is_lock == null || $clone->is_lock == '0'){
										$selectedClones[] = $clone;
									}
								}
								$clonesToUpdate = ceil(((int)$insert_of_free_rpc / 100) * count($selectedClones));
								for($i = 0; $i < $clonesToUpdate; $i++){
									$selectedClone = $selectedClones[$i];
									$model->updateInsertOfFreeRPC($selectedClone->id, '1');
								}
							}
						}
					}
				} else {
					$msg="error process";
				}
			}
		} else {
			$msg = 'Select presentation first';
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='
		.JRequest::getVar("package_id").'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&position=distributeSymbol'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}
	function shufflePieceIntoEachSymbolQueue(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		

		$presentation = null;
		$params = JRequest::getVar('presentations');
		$presentations = JRequest::getVar('cid3'.(int) JRequest::getVar('index'));
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}
				$process_symbol_id = $processSymbol->id;
					
				$processSymbolModel	= & JModelLegacy::getInstance('processsymbol', 'AwardpackageModel');
				$awardProgressModel	= & JModelLegacy::getInstance('awardprogress', 'AwardpackageModel');
				$archiveModel 		= & JModelLegacy::getInstance('archive', 'AwardPackageModel');

				$package_id = JRequest::getVar('package_id');
				$params = JRequest::getVar('shuffle_piece');
				$shuffle_piece = $params[(int) JRequest::getVar('index')];
				if((int) $shuffle_piece > 0){
					$prizes	= $model->check_prize($presentation_id);
					if($prizes){
						foreach($prizes as $prize){
							$clonedPrizes = $model->get_clone_prize($prize->prize_id, $process_symbol_id, $prize->symbol_id);
							if(!empty($clonedPrizes)) {
								$clones = $model->get_clone_detail($prize->prize_id, $process_symbol_id, $prize->symbol_id);
								for($i = 0; $i < (int) $shuffle_piece; $i++){
									shuffle($clones);
								}
								$user = JFactory::getUser();
								if(empty($user)){
									JError::raiseWarning(100, 'NO select user in awardpackage');
									$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id").'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
								}else{
									if(!empty($user)) {
										$awardProgressModel->delete_quee($user->id, $cidd);
										if(!empty($clones)) {
											$awardProgressModel->delete_quee_details($presentation_id, $cidd);
										}
										$quee_id = $awardProgressModel->save_quee($user->id, $cidd);
										foreach($clones as $piece){
											$data['queue_id'] = $quee_id;
											$data['symbol_pieces_id'] = $piece->id;
											$data['symbol_prize_id'] = $prize->prize_id;
											$data['presentation_id'] = $presentation_id;
											$save_details = $awardProgressModel->save_quee_details($data, $cidd);
										}
										$model->saveUpdateShuffleData($shuffle_piece, $process_symbol_id);
										$msg="shuffle success";
									}
								}
							}
						}
					}
				} else {
					$msg="error process";
				}
			}

		} else {
			$msg = 'Select presentation first';
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='
		.JRequest::getVar("package_id").'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&position=distributeSymbol'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}
	function percentEachFromAllUserGroupsToFundPrize(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		

		$rs = $model->getPresentionsFromProcessPresentations(JRequest::getVar('package_id'), JRequest::getVar('processPresentation'));
		$pct = 0;
		$presentations = '';

		foreach ($rs as $r){
			if(!empty($r->presentations)){
				$presentations .= $r->presentations . ',';
			}
		}

		$presentations = substr($presentations, 0, strlen($presentations) -1 );

		$fundings = $model->getFundingPresentation(JRequest::getVar('package_id'), $presentations);

		foreach ($fundings as $funding){
			$pct = $pct + (int) $funding->pct_funded;
		}

		$params = JRequest::getVar('presentations');

		$presentations = JRequest::getVar('cid4'.(int) JRequest::getVar('index'));
		$fundPrizes = 0;
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
				$params = JRequest::getVar('fundPrizes');
				$fundPrizes += $params[(int) JRequest::getVar('index')];
			}
		}
		if($fundPrizes <= 100) {
			if(!empty($presentations)){
				foreach ($presentations as $presentation_id){
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
					if($processSymbol == null){
						$this->addNewProcessSymbol($presentation_id, $cidd);
						$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
					}
					$process_symbol_id = $processSymbol->id;
					$package_id = JRequest::getVar('package_id');
					$params = JRequest::getVar('fundPrizes');
					$fundPrizes = $params[(int) JRequest::getVar('index')];
					if(((int) $fundPrizes > 0) && ($pct + $fundPrizes) <= 100)
					{
						$prizes	= $model->check_prize($presentation_id);
						if($prizes){
							foreach($prizes as $prize){
								$model->insertUpdateFunding($presentation_id, $package_id, $prize->prize_id, $fundPrizes, null, $cidd);
								$msg = "success update funding data";
							}
						}
					} else {
						if(($pct + $fundPrizes) > 100){
							$msg = "Percent funded should not be more than 100%";
						} else {
							$msg="Error process";
						}

					}
				}

			} else {
				$msg = 'Select presentation first';
			}
		} else {
			$msg = "Percent funded should not be more than 100%";
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='
		.JRequest::getVar("package_id").'&index='.JRequest::getVar('index').'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&position=fundPrize'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}
	function fundingQueue(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );

		$index = JRequest::getVar('index');
		$cidds = JRequest::getVar('cidd');
		$cidd = $cidds[$index];
		$cidd = JRequest::getVar('presentationId'); 		

		$presentation = null;
		$params = JRequest::getVar('presentations');

		$presentations = JRequest::getVar('cid4'.(int) JRequest::getVar('index'));
		if(!empty($presentations)){
			foreach ($presentations as $presentation_id){
				$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				if($processSymbol == null){
					$this->addNewProcessSymbol($presentation_id, $cidd);
					$processSymbol =  $model->getProcessSymbolByPresentationId($presentation_id, $cidd);
				}
				$process_symbol_id = $processSymbol->id;
				$package_id = JRequest::getVar('package_id');
				$params = JRequest::getVar('funding_queue');
				$fundingQueue = $params[(int) JRequest::getVar('index')];				
				$prizes	= $model->check_prize($presentation_id);
				if(!empty($prizes)){					
					foreach($prizes as $prize){
						$model->insertUpdateFunding($presentation_id, $package_id, $prize->prize_id, null, $fundingQueue, $cidd);
						$msg = "success update funding data";
					}
				}

			}

		} else {
			$msg = 'Select presentation first';
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='
		.JRequest::getVar("package_id").'&index='.JRequest::getVar('index').'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&position=fundPrize'.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), $msg);
	}
	function save(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$presentations = '';
		$cids = JRequest::getVar('cidd');
		foreach ($cids as $id){
			$datas = $model->getSelectedPresentationsById($id);
			foreach ($datas as $data) {
				if(!empty($data->presentations)){
					if(!empty($data->presentations)){
						$presentations .= $data->presentations . ',';
					}
				}
			}
		}

		$presentations = substr($presentations, 0, strlen($presentations)-1);
		$cid = implode(',', $cids);
		$model->updateProcessPresentation(JRequest::getVar('processPresentation'), JRequest::getVar('package_id'), $cid);
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=apresentationlist&task=apresentationlist.initiate&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&process_symbol_id='.JRequest::getVar('process_symbol_id').'&package_id='.JRequest::getVar("package_id").'&processSymbolId='.$cid.'&pids='.$presentations.'&processPresentation='.JRequest::getVar('processPresentation').'&prizename='.JRequest::getVar('prizename').'&symbolqueue='.JRequest::getVar('symbolqueue').'&funding='.JRequest::getVar('funding').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"));
	}
	function deleteSelectedPresentation(){
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$cids = JRequest::getVar('cid');
		foreach ($cids as $cid) {
			$model->deleteSelectedPresentations($cid);
		}
		$this->setRedirect('index.php?option='.Q_APP_NAME.'&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar("package_id").'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id').'&process_id='.JRequest::getVar("process_id"), 'Delete successful');
	}
	
	function onFundQueue(){
		$view = $this->getView('aprocesspresentation', 'html');
		$view->assign('action', 'onFundQueue');
		$view->display('fund_queue');		
	}
	
	function onPrizeQueue(){
		$view = $this->getView('aprocesspresentation', 'html');
		$view->assign('action', 'onPrizeQueue');
		$view->display('prize_queue');		
	}
	function onDistributionPrizeQueueHistory(){
		$view = $this->getView('aprocesspresentation', 'html');
		$view->assign('action', 'onDistributionPrizeQueueHistory');
		$view->display('prize_queue_history');	
	}
	function fundReceiverList(){
		$package_id = JRequest::getVar('package_id');
		$view = $this->getView('aprocesspresentation', 'html');
		$view->assign('action', 'fund_receiver_list');
		$view->display('fund_receiver_list');
	}
	
	function fundPrizePlan(){
		$package_id = JRequest::getVar('package_id');
		$view = $this->getView('aprocesspresentation', 'html');
		$view->assign('action', 'fund_prize_plan');
		$view->display('fund_prize_plan');
	}
}