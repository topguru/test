<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageControllerProcesspresentationnew extends JControllerLegacy {
	
	function __construct() {
		parent::__construct();		
	}

	function show_process_presentation(){
		$view = $this->getView('processpresentationnew', 'html');		
		$view->assign('action', 'show_process_presentation');
		$view->display();
	}	
	
	function add_extract_pieces(){		
		$model = & JModelLegacy::getInstance( 'createpresentationnew', 'AwardpackageModel' );
		$extract_pieces = JRequest::getVar('extract_pieces');
		$process_symbol_id = JRequest::getVar('process_symbol_id');
		$presentation_id = JRequest::getVar('presentation_id');
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
					$model->activeStatusAll($prize->symbol_id);						
				}
				
				$save = $model->saveExtract($process_symbol_id,$prize->prize_id);
				$pieces = $model->getPiecesAll($prize->symbol_id);
				foreach ($pieces as $i=>$piece) {
					$extractPieces[$i] = $piece;
					if($i == ($extract_pieces-1)) break;					
				}						
				foreach ($extractPieces as $piece) {
					$save_detail = $model->save_extract_detail($save,$piece->symbol_pieces_id);
					$model->updateStatus($piece->symbol_pieces_id);
				}
				$model->saveUpdateExtractData($extract_pieces, $prize->process_symbol);					
			}
		}		
		$view = $this->getView('processpresentationnew', 'html');		
		$view->assign('action', 'show_process_presentation');
		$view->display();
	}
	
	function add_num_clone(){
		$model = & JModelLegacy::getInstance( 'createpresentationnew', 'AwardpackageModel' );
		$num_clone = JRequest::getVar('num_clone');
		$process_symbol_id = JRequest::getVar('process_symbol_id');
		$presentation_id = JRequest::getVar('presentation_id');
		$package_id = JRequest::getVar('package_id');
		if((int)$num_clone > 0) {
			$prizes	= $model->check_prize($presentation_id);
			if($prizes){
				foreach($prizes as $prize){
					$val = $num_clone;
					$clonedPrizes = $model->get_clone_prize($prize->prize_id, $process_symbol_id, $prize->symbol_id);
					if(!empty($clonedPrizes)) {
						$clonedPrize = $clonedPrizes[0];
						//delete clone pieces 
						$model->delete_clone_pieces($prize->symbol_id, $clonedPrize->id);
						//delete detail clone pieces
						$model->delete_clone_detail($clonedPrize->id);
						//delete master clone
						$model->delete_clone_prize($prize->prize_id, $process_symbol_id, $prize->symbol_id);
					}					
					$save = $model->save_clone_prize($prize->prize_id,$process_symbol_id,$prize->symbol_id);
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
		}
		$view = $this->getView('processpresentationnew', 'html');		
		$view->assign('action', 'show_process_presentation');
		$view->display();
	}
}