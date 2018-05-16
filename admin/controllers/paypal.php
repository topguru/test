<?php 
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class AwardPackageControllerPaypal extends JControllerForm {
	public function __construct($config = array()){
		parent::__construct($config);
	}
	
	public function save(){
		$model		= $this->getModel();
		$data		= JRequest::getVar('jform');
		$msg		= 'Data saved';
		
		//add data to table
		if($model->addItem($data)){
			$this->setRedirect('index.php?option=com_awardpackage&view=payments',$msg);
		}
	}
	
	public function cancel(){
		$this->setRedirect('index.php?option=com_awardpackage&view=payments');
	}
}

?>