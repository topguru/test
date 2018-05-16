<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
class AwardPackageControllerpresentationlist extends AwardPackageController
{
	function display() {
		parent::display();
	}
	
	function add() {
		$package_id = JRequest::getVar('package_id');
		
		$model =& JModelLegacy::getInstance('listpersentation','AwardpackageModel');	
		
		$bool=$model->create();
		
		if($bool==true)
		
			$msg = 'New Presentation Created';
		
		else $msg = 'New Presentation Failed to Create';
		
			$this->setRedirect('index.php?option=com_awardpackage&view=presentationList&package_id='.$package_id, $msg);	
	}
	
		
	function edit() {
		$ids = JRequest::getVar('cid');
		
		if(count($ids)>0) {
		JRequest::setVar('gcid', $ids[0]);
		JRequest::setVar('view', 'presentation');
		JRequest::setVar('layout', 'default');
		}
		
		parent::display();
	}
	
	function publish() {
		
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		
		$model =& JModelLegacy::getInstance('listpersentation','AwardpackageModel');	
		
		if(count($ids)>0)
		{
			foreach($ids as $id){
				
				if($model->publish(true,$id)){
					
					$msg=JText::_('COM_SYMBOL_PERSENTATION_PUBLISHED');
				}
                                
                 $this->CreateSymbolHelper($id);
                               
			}
		}else{
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		
		$link = 'index.php?option=com_awardpackage&view=presentationList&package_id='.JRequest::getVar('package_id');
		
		$this->setRedirect($link,$msg);
	}
	
	function unpublish(){
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		
		$model =& JModelLegacy::getInstance('listpersentation','AwardpackageModel');	
                
        $presentationModel = $this->getModel('presentationlist');
		
		if(count($ids)>0)
		{
			foreach($ids as $id){
				
				if($model->publish(false,$id)){
					
					$msg=JText::_('COM_SYMBOL_PERSENTATION_UN_PUBLISHED');
				}
                  $presentationModel->deleteSymbolHelper($id);
                  $presentationModel->deletesymbolQueue($id);
			}
		}else{
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		
		$link = 'index.php?option=com_awardpackage&view=presentationList&package_id='.JRequest::getVar('package_id');
		
		$this->setRedirect($link,$msg);
	}
	
	function preview() {
		JRequest::setVar('layout', 'create');
		parent::display();
	}
	
	function viewgiftcode() {
		JRequest::setVar('view', 'viewgiftcode');
		JRequest::setVar('layout', 'default');
		parent::display();
	}
	
	function remove() {
		
		$package_id = JRequest::getVar('package_id');
		
		$ids = JRequest::getVar('cid');
		
		$model =& JModelLegacy::getInstance('listpersentation','AwardpackageModel');	
		
		$bool=$model->delete($ids);	
		
		if($bool==true)
		
			$msg = 'Presentation Deleted';
		
		else $msg = 'Presentation Delete false';
		
			$this->setRedirect('index.php?option=com_awardpackage&view=presentationList&package_id='.$package_id, $msg);		
	}
	
		
	function save(){
		
		if(JRequest::getVar('id')==''){			
		
			$this->do_save(false);
			
			$msg = 'Presentation Created';
		}else{
		
			$this->do_save(true);
		
			$msg = 'Presentation Edited';
		}
		
		
		//echo 2; exit();
		$this->setRedirect('index.php?option=com_awardpackage&view=presentation', $msg);		
	}
	
	function apply(){
				
	}
	
	function do_save($edit){
		
		$data = array(
			'symbol_prize_id' => JRequest::getVar('symbol_prize_id'),
			'id' => JRequest::getVar('id'),
			'symbol_id' => JRequest::getVar('symbol_id'),
			
		);
		
		$model =& JModelLegacy::getInstance('Presentation','AwardpackageModel');
		
		$ngewi = $model->saveData($data);
		
		return $ngewi;
	}
	
	function do_queue($dt){
		
		$data = array(
			'symbol_id' => JRequest::getVar('symbol_id'),
			'symbol_prize_id' => $dt
			);
		
		$model =& JModelLegacy::getInstance('Shuffle','ShuffleModel');
		
		$q = $model->queue($data);
		
		return $q;
	}
	
	public function select_persentation(){
		
		$db 	= &JFactory::getDBO();
		
		$cids	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
		foreach($cids as $cid):
		
			if($cid!=""){
				echo JRequest::getVar('package_id');
			}
			
		endforeach;
	}
	
	public function persentationlistclose(){
		
		$link = 'index.php?option=com_awardpackage';
		
		$this->setRedirect($link);
	}
        
    public function CreateSymbolHelper($presentation_id){
       $model          = $this->getModel('presentationlist');
       $symbol_info    = $model->getSymbolPrize($presentation_id);
       foreach ($symbol_info as $k=>$symbol){
                $data['symbol_id']=$symbol->symbol_id;
                $data['presentation_id']=$presentation_id;
                $helper_save = $model->saveSymbolHelper($data,  JRequest::getVar('package_id'));
           }
            if($helper_save){
               return $helper_save;
         }
     }
}

?>