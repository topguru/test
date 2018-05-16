<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT . '/helpers/awardpackages.php';
class AwardPackageControllerprize extends AwardPackageController
{
	function add() {
		
		JRequest::setVar('view', 'prize');
		
		JRequest::setVar('layout', 'create');
		
		JRequest::setVar('package_id',JRequest::getVar('package_id'));
		
		parent::display();
	}
	
	function upload(){
	  
	   $result = 0;
	   
	   $photo = basename( $_FILES['prize_image']['name']);
	   
	   $c = strrpos($photo,".");
	   
	   $name =  date("Ymdhis");
	   
	   $ext = strtolower(substr($photo,$c+1,strlen($photo)-$c));
	   
	   $target_path = "./components/com_awardpackage/asset/prize/".$name.".".$ext;

	   if(@move_uploaded_file($_FILES['prize_image']['tmp_name'], $target_path)) {
	      
	      $result = 1;
	   
	   }
	   
	   sleep(1);
	   
	   echo "<script language=\"javascript\" type=\"text/javascript\">window.top.window.stopUpload('".$result."','".$target_path."','".$name.".".$ext."');</script>";     
	   exit();
	}
	
	function edit() {
		
		JRequest::setVar('view', 'prize');
		
		JRequest::setVar('layout', 'create');
		
		parent::display();
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
		
		$model =& JModelLegacy::getInstance('Prize','AwardPackageModel');
		
		$bool=$model->delete($ids);	
			
			if($bool==true)
				
				$msg = 'Prize Deleted';
			
			else $msg = 'Prize Delete false';
				
				$this->setRedirect('index.php?option=com_awardpackage&view=prize&package_id='.$package_id, $msg);		
	}
	function prizeclose(){
		
		$link = 'index.php?option=com_awardpackage';
		
		$this->setRedirect($link);
	
	}
	function save(){
		
		if(JRequest::getVar('id')==''){
			
			if($this->do_save(false)){
				
				$msg = 'Prize Created';
				
			}
		
		}else{
			if($this->do_save(true)){
				
				$msg = 'Prize Edited';
			}
			
		}
		
		if(JRequest::getVar('command') == '1') {
			$this->setRedirect('index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&package_id='.JRequest::getVar('package_id'), 'Update prize successful');
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=prize&package_id='.JRequest::getVar('package_id'), $msg);	
		}				
	}
	
	function apply(){
		
		if(JRequest::getVar('id')==''){
			$ngewa = $this->do_save(false);
			
			$msg = 'Prize Created';
		}else{
			$ngewa = $this->do_save(true);
			
			$msg = 'Prize Edited';
		}
		
		$this->setRedirect('index.php?option=com_awardpackage&controller=prize&task=edit&cid[0]='.$ngewa.'&package_id='.JRequest::getVar('package_id'),$msg);		
	}
	
	function do_save($edit){
		
		$data = array(
			
			'id' => JRequest::getVar('id'),
			
			'date_created' => date('Y-m-d'),
			
			'package_id' => JRequest::getVar('package_id'),
			
			'prize_name' => JRequest::getVar('prize_name'),
			
			'prize_value' => JRequest::getVar('prize_value'),
			
			'prize_image' => JRequest::getVar('prize_image_name'),
			
			'created_by' => JRequest::getVar('created_by'),
			
			'desc' => JRequest::getVar('desc'),
         
			
		);
		
		
		$model =& JModelLegacy::getInstance('Prize','AwardPackageModel');
			
		$ngewi = $model->saveData($data);
			
		return $ngewi;
	}
	
	public function prizewinnerclose(){
		$package_id = JRequest::getVar('package_id');
		$link = 'index.php?option=com_awardpackage&view=prize&package_id='.$package_id;
		$this->setRedirect($link);		
	}
}

?>