<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
class AwardpackageControllerSurvey extends JControllerLegacy {

    function __construct() {
        parent::__construct();
    }

    function save() {
        $poll_model = & JModelLegacy::getInstance('Category', 'AwardpackageModel');
		$ids = JRequest::getVar('cid');
		if(!$ids){
			$this->setRedirect('index.php?option=com_awardpackage&view=survey&package_id='.JRequest::getVar('package_id'),JTEXT::_('Select data first'));
		}
		foreach($ids as $id){
			$price = JRequest::getVar('survey_price_'.$id);
			$save = $poll_model->save_price($price,$id,'survey');
		}	 
		if($save){
			$this->setRedirect('index.php?option=com_awardpackage&view=survey&package_id='.JRequest::getVar('package_id'),JTEXT::_('Data has updated'));
		}
    }
	
	function save_categories(){
		
		$model =& JModelLegacy::getInstance('surveys','AwardpackageModel');
		
		if($model->invar('unlock','')==1){
			
			$i			= 0;
			
			$cids 			= JRequest::getVar('cid');
			$survey_price	= JRequest::getVar('survey_price');
			$user_survey_price	= JRequest::getVar('user_survey_price');
			
			foreach($cids as $cid){
				
				if($model->invar('currency_unit',0)==1){
					
					$data['survey_price'] = $survey_price[$i]/100;
					$data['user_survey_price'] = $user_survey_pricee[$i]/100;
					
				}else{
					
					$data['survey_price'] = $survey_price[$i];
					$data['user_survey_price'] = $user_survey_price[$i];					
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
		
		$this->setRedirect('index.php?option=com_awardpackage&view=survey&package_id='.JRequest::getVar('package_id'),$message);	
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
		
		$this->setRedirect('index.php?option=com_awardpackage&view=survey&package_id='.JRequest::getVar('package_id'),$message);	
	}
	
	
	function publish(){
	
		$model =& JModelLegacy::getInstance('surveys','AwardpackageModel');
		
		$model->update_category(JRequest::getVar('cid'),'publish');
		
		$this->setRedirect('index.php?option=com_awardpackage&view=survey&package_id='.JRequest::getVar('package_id'),JTEXT::_(PUBLISHED));	
	}
	
	function unpublish(){ 
		
		$model =& JModelLegacy::getInstance('surveys','AwardpackageModel');
		
		$model->update_category(JRequest::getVar('cid'),'unpublish');
		
		$this->setRedirect('index.php?option=com_awardpackage&view=survey&package_id='.JRequest::getVar('package_id'),JTEXT::_(UNPUBLISHED));	
	}
}

