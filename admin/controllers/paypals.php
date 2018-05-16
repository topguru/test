<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');
class AwardPackageControllerPaypals extends JControllerLegacy {
	public function __construct($config = array()){
		parent::__construct($config);
	}

	public function getPaypalConfigurationList(){
		$view = $this->getView('paypals', 'html');
		$view->assign('action', 'list');
		$view->display();
	}

	public function getModel($name = 'paypals', $prefix = 'AwardPackageModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function delete(){
		$model	= $this->getModel('paypals');
		$ids	=	JRequest::getVar('cid');
		if($ids){
			foreach($ids as $id){
				$delete = $model->deletePaypalConfiguration($id);
			}
		}
		if($delete){
			$msg	= 'Delete success';
			$this->setRedirect('index.php?option=com_awardpackage&view=paypals&task=paypals.getPaypalConfigurationList&package_id='.JRequest::getVar('package_id'),$msg);
		}
	}

	public function createUpdate(){
		$view = $this->getView('paypals', 'html');
		$view->assign('action', 'create');
		$view->display('create');
	}

	public function saveCreate(){
		$model	= $this->getModel('paypals');
		$id = JRequest::getVar('id');
		$package_id = JRequest::getVar('package_id');
		$business = JRequest::getVar('business');
		$currency_code = JRequest::getVar('currency_code');
		$lc = JRequest::getVar('lc');
		$is_active = JRequest::getVar('is_active');
		
		$data = array();
		$data['package_id'] = $package_id;
		$data['business'] = $business;
		$data['currency_code'] = $currency_code;		
		$data['lc'] = $lc;
		$data['is_active'] = $is_active;
		if($model->saveUpdatePaypalConfiguration($data, $id)) {
			$this->setRedirect('index.php?option=com_awardpackage&view=paypals&task=paypals.getPaypalConfigurationList&package_id=' . JRequest::getVar('package_id'), JText::_('MSG_SUCCESS'));
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=paypals&task=paypals.getPaypalConfigurationList&package_id=' . JRequest::getVar('package_id'), JText::_('Error'));
		}
	}
}