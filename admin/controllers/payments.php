<?php 
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class AwardPackageControllerPayments extends JControllerLegacy {
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function get_payment_list(){
		$view = $this->getView('payments', 'html');
		$view->assign('action', 'list');
		$view->display();
	}
	
	public function getModel($name = 'payment', $prefix = 'AwardPackageModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	public function delete(){
		$model	= $this->getModel('payments');
		$ids	=	JRequest::getVar('cid');
		if($ids){
			foreach($ids as $id){
				$delete = $model->delete($id);
			}
		}
		if($delete){
			$msg	= 'Delete success';
			$this->setRedirect('index.php?option=com_awardpackage&view=payments&task=payments.get_payment_list',$msg);
		}
	}
	
	public function create_update(){		
		$view = $this->getView('payments', 'html');		
		$view->assign('action', 'create');
		$view->display('create');
	}
	
	public function save_create(){
		$option = JRequest::getVar('opt');
		if( $option <> '') {
			$id = JRequest::getVar('id');
			$model = & JModelLegacy::getInstance( 'payments', 'AwardpackageModel' );
			if($model->save_update_payment($option, $id)) {
				$this->setRedirect('index.php?option=com_awardpackage&view=payments&task=payments.get_payment_list&package_id=' . JRequest::getVar('package_id'), JText::_('MSG_SUCCESS'));
			} else {
				$this->setRedirect('index.php?option=com_awardpackage&view=payments&task=payments.get_payment_list&package_id=' . JRequest::getVar('package_id'), JText::_('Error'));
			}			
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=payments&task=payments.get_payment_list&package_id=' . JRequest::getVar('package_id'), 'Payment name should not empty');
		}
		
	}
}

?>