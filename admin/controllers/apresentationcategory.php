<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageControllerApresentationcategory extends JControllerLegacy {

	function __construct(){
		parent::__construct();
	}

	function getListCategory(){
		$view = $this->getView('apresentationcategory', 'html');
		$view->assign('action', 'list');
		$view->display();
	}
	
	function createUpdate(){
		$view = $this->getView('apresentationcategory', 'html');		
		$view->assign('action', 'create');
		$view->display('create');
	}
	
	function edit(){
		$package_id = JRequest::getVar('package_id');
		$cids = JRequest::getVar('cid');
		$category = $cids[0];
		
		$model = & JModelLegacy::getInstance( 'apresentationcategory', 'AwardpackageModel' );
		$ppc = $model->getPresentationCategoryByCategory($category, $package_id);
		$view = $this->getView('apresentationcategory', 'html');		
		$view->assignRef('ppc', $ppc);
		$view->assign('action', 'create');
		$view->display('create');
	}
	
	function saveCreateUpdate(){
		$package_id = JRequest::getVar('package_id');
		$category = JRequest::getVar('category');
		$description = JRequest::getVar('description');
		$cids = JRequest::getVar('cid');
		
		$model = & JModelLegacy::getInstance( 'apresentationcategory', 'AwardpackageModel' );
		foreach ($cids as $cid) {
			$data = new stdClass();
			$data->package_id = $package_id;
			$data->category = $category;
			$data->description = $description;
			$data->presentation_id = $cid;
			$model->insertPresentationCategory($data);
		}
		$this->setRedirect('index.php?option=com_awardpackage&view=apresentationcategory&task=apresentationcategory.getListCategory&package_id='.$package_id,JTEXT::_('Success add new presentation category'));
	}
}