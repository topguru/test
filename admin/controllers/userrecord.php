<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.controlleradmin');
class AwardPackageControllerUserrecord extends JControllerLegacy {
	function __construct() {
		parent::__construct();
	}
	
	public function prize_extracted() {
		$dataArray = array();
		$package_id = JRequest::getVar('package_id');
		$presentation_id = JRequest::getVar('presentation_id');
		$process_id = JRequest::getVar('process_id');
		$model =& JModelLegacy::getInstance('PresentationList','AwardPackageModel');
		$model_symbol = & JModelLegacy::getInstance('Processsymbol','AwardPackageModel');			
		$presentations = $model->getPresentationDetails($presentation_id, $package_id);
		if(!empty($presentations)) {
			foreach ($presentations as $presentation) {
				$process_symbols = $model_symbol->CheckPrize2($presentation_id);
				if(!empty($process_symbols)) {
					$i = 0;
					foreach ($process_symbols as $process_symbol) {
						$extracts = null;
						$extracted = null;
						if($process_symbol->process_symbol == $process_id) {
							$extracteds = $model_symbol->getExtract_2($process_symbol->process_symbol, $process_symbol->prize_id);
							if(!empty($extracteds)) {							
								$extracted = $extracteds[0];
								$extracts = $model_symbol->getExtractPieces($extracted->id);																					 	
							}
							$data = new stdClass();
							$data->prizeValueRange = $process_symbol->prize_value_from . ' to ' . $process_symbol->prize_value_to;
							$data->prizeValue = $presentation->prize_value;
							$data->prizeImage = $presentation->prize_image;
							$data->symbolSet = $presentation->symbol_image;
							$data->extractId = ($extracted == null ? 'nop' : $extracted->id);
							$data->extractedPieces = ($extracts == null ? 0 : count($extracts));						
							$data->detailExtractedPieces = $extracts;
							$dataArray[$i] = $data;	
						}
						$i++;							
					}					
				}
			}
		}
		$view = $this->getView('userrecord', 'html');
		$view->items = $dataArray;
		$view->display('prize_extracted');
	}
	
	public function prize_cloned() {
		$dataArray = array();
		$package_id = JRequest::getVar('package_id');
		$presentation_id = JRequest::getVar('presentation_id');
		$process_id =  JRequest::getVar('process_id');
		$model =& JModelLegacy::getInstance('PresentationList','AwardPackageModel');
		$model_symbol = & JModelLegacy::getInstance('Processsymbol','AwardPackageModel');			
		$presentations = $model->getPresentationDetails($presentation_id, $package_id);
		if(!empty($presentations)) {
			foreach ($presentations as $presentation) {
				$process_symbols = $model_symbol->CheckPrize2($presentation_id);
				if(!empty($process_symbols)) {
					$i = 0;
					foreach ($process_symbols as $process_symbol) {
						$cloneds = null;
						$clones = null;
						$pieces = null;						
						if($process_symbol->process_symbol == $process_id) {
							$cloneds = $model_symbol->get_clone_prize($process_symbol->prize_id, $process_symbol->process_symbol, $process_symbol->symbol_id);
							if(!empty($cloneds)) {
								$cloned = $cloneds[0];
								$totalClone = count($model_symbol->get_clone_pieces($process_symbol->symbol_id, $cloned->id));
								$clones = $model_symbol->getClonesAdd($cloned->id);
								$clonesAdd = $totalClone / count($clones); 
								foreach ($clones as $clone) {
									$pieces = $model_symbol->getPiecesByPiecesId($clone->pieces_id);
									$piece = $pieces[0];
									$data = new stdClass();
									$data->prizeValue = $presentation->prize_value;
									$data->prizeImage = $presentation->prize_image;
									$data->symbolPiece = $piece->symbol_pieces_image;
									$data->clonesAdd = $clonesAdd;
									$data->totalSymbolPieces = ($clonesAdd+1);									
									$dataArray[$i] = $data;
									$i++;		
								}													
							}
						}
					}
				}		
			}
			
		}	
		$view = $this->getView('userrecord', 'html');
		$view->items = $dataArray;
		$view->display('prize_cloned');	
	}
	
	public function helper_queue() {
		$dataArray = array();
		$package_id = JRequest::getVar('package_id');
		$presentation_id = JRequest::getVar('presentation_id');
		$process_id =  JRequest::getVar('process_id');
		$model =& JModelLegacy::getInstance('PresentationList','AwardPackageModel');
		$model_symbol = & JModelLegacy::getInstance('Processsymbol','AwardPackageModel');			
		$model_award = & JModelLegacy::getInstance('Awardprogress','AwardPackageModel');
		$presentations = $model->getPresentationDetails($presentation_id, $package_id);
		if(!empty($presentations)) {
			foreach ($presentations as $presentation) {
				$process_symbols = $model_symbol->CheckPrize2($presentation_id);
				if(!empty($process_symbols)) {
					$i = 0;
					foreach ($process_symbols as $process_symbol) {
						if($process_symbol->process_symbol == $process_id) {
							$queues = $model_award->getQueueDetail($process_symbol->symbol_id, $presentation_id, $process_symbol->prize_id);
							if(!empty($queues)){
								$queue = $queues[0];
								$data = new stdClass();
								$data->prizeValue = $presentation->prize_value;
								$data->prizeImage = $presentation->prize_image;
								$data->symbolSet = $queue->symbol_image;
								$data->symbolPieces = $queue->total;
								$dataArray[$i] = $data;
								$i++;
							}
						}
					}
				}
			}
		}
		$view = $this->getView('userrecord', 'html');
		$view->items = $dataArray;
		$view->display('helper_queue');	
	}
	
	public function user_symbol() {
		$dataArray = array();
		$package_id = JRequest::getVar('package_id');
		$presentation_id = JRequest::getVar('presentation_id');
		$process_id =  JRequest::getVar('process_id');
		$model =& JModelLegacy::getInstance('PresentationList','AwardPackageModel');
		$model_symbol = & JModelLegacy::getInstance('Processsymbol','AwardPackageModel');		
		$model_award = & JModelLegacy::getInstance('Awardprogress','AwardPackageModel');
		$presentations = $model->getPresentationDetails($presentation_id, $package_id);
		if(!empty($presentations)) {
			foreach ($presentations as $presentation) {
				$process_symbols = $model_symbol->CheckPrize2($presentation_id);
				if(!empty($process_symbols)) {
					$i = 0;
					foreach ($process_symbols as $process_symbol) {						
						if($process_symbol->process_symbol == $process_id) {
							$userSymbols = $model_award->getUserSymbolQueueList($process_symbol->symbol_id, $presentation_id, $process_symbol->prize_id);
							if(!empty($userSymbols)) {
								$userSymbol = $userSymbols[0]; 
								$data = new stdClass();
								$data->selectedUser = $userSymbol->username;
								$data->total = $userSymbol->total;
								$data->prizeValueRange = '$'.number_format($process_symbol->prize_value_from,2) . ' to ' . '$'.number_format($process_symbol->prize_value_to,2);
								$dataArray[$i] = $data;
								$i++;	 		
							}							 
						}
					}
				}
			}
		}
		$view = $this->getView('userrecord', 'html');
		$view->items = $dataArray;
		$view->display('user_symbol');	
	}
	
	public function user_symbol_detail(){
		$dataArray = array();
		$package_id = JRequest::getVar('package_id');
		$presentation_id = JRequest::getVar('presentation_id');
		$process_id =  JRequest::getVar('process_id');
		$model =& JModelLegacy::getInstance('PresentationList','AwardPackageModel');
		$model_symbol = & JModelLegacy::getInstance('Processsymbol','AwardPackageModel');		
		$model_award = & JModelLegacy::getInstance('Awardprogress','AwardPackageModel');
		$presentations = $model->getPresentationDetails($presentation_id, $package_id);
		if(!empty($presentations)) {
			foreach ($presentations as $presentation) {
				$process_symbols = $model_symbol->CheckPrize2($presentation_id);
				if(!empty($process_symbols)) {
					if($process_symbol->process_symbol == $process_id) {
						$i = 0;
						foreach ($process_symbols as $process_symbol) {		
							$details = $model_award->getUserSymbolQueueListDetail($process_symbol->symbol_id, $presentation_id, $process_symbol->prize_id);
							foreach ($details as $detail) {
								print_r($detail);
								echo '<br>';
								
							}
							die();
						}
					}
				}
			}
		}
	}
	
	public function prize_extracted_detail() {		
		$extract_id = JRequest::getVar('extract_id');
		$model_symbol = & JModelLegacy::getInstance('Processsymbol','AwardPackageModel');
		$extracts = $model_symbol->getExtractPieces($extract_id);
		$str = '';
		foreach ($extracts as $extract) {
			$str .= '<tr>';
			$str .= '<td width="100%">';
			$str .= '<img
						src="./components/com_awardpackage/asset/symbol/pieces/'.$extract->symbol_pieces_image.'"
						width="50px" height="50px" />';
			$str .= '</td>';
			$str .= '</tr>';
		}
		echo $str;
		die();
	}
}