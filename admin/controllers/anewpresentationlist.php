<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageControllerAnewpresentationlist extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}		
	function initiate(){
		$view = $this->getView('anewpresentationlist', 'html');
		$view->assign('action', 'initiate');		
		$view->display();
	}	
	function new_prize(){
		$presentation_id = '';
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$prize = JRequest::getVar('idPrizeId');
		if($prize == ''){
			$message = 'Please select prize first';
		} else {
			$ret = $model->createPresentationByPrize($prize, JRequest::getVar('package_id'));
			$message = "Success created new presentation";
			if($ret == 0){
				$presentation_id = '';
			} else {
				$presentation_id = $ret;
			}			
		}
		$view = $this->getView('anewpresentationlist', 'html');
		$view->assign('action', 'initiate');	
		$view->assignRef('presentation_id', $presentation_id);	
		$view->display();
	}	
	
	function new_symbolset(){		
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$symbol = JRequest::getVar('idSymbolId');
		if($symbol == ''){
			$message = 'Please select symbol first';
		} else {
			$ret = $model->createPresentationBySymbol($symbol, JRequest::getVar('package_id'));
			$message = "Success created new presentation";
			if($ret == 0){
				$presentation_id = '';
			} else {
				$presentation_id = $ret;
			}	
		}
		$view = $this->getView('anewpresentationlist', 'html');
		$view->assign('action', 'initiate');
		$view->assignRef('presentation_id', $presentation_id);		
		$view->display();	
	}	
	
	function create(){
		$package_id = JRequest::getVar('package_id');
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$cids = JRequest::getVar('cid');
		if(!empty($cids)){
			$message = "Success";
			$strCid = implode(',', $cids);
			$model->updateSymbolPresentation($strCid);
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&package_id='.JRequest::getVar("package_id"), false), JText::_($message));
		} else {
			$message = "Please select presentation first";
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&presentation_id='.$presentation_id.'&package_id='.JRequest::getVar("package_id"), false), JText::_($message));							
		}
		
	}
	function delete(){
		$cid = JRequest::getVar('cidd');
		$package_id = JRequest::getVar('package_id');
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		foreach ($cid as $id) {
			$model->deletePresentation($id);
		}		
		$this->setRedirect('index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&package_id='.JRequest::getVar('package_id'),JTEXT::_('Success delete presentation'));		
	}
}