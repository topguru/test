<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';

class AwardpackageControllerFunding extends JControllerForm
{
	public function add()
	{
		$model	= $this->getModel('funding');
		
		$data['package_id'] = JRequest::getVar('package_id');
                $data['presentation_id'] = JRequest::getVar('presentation_id');
		
		if($model->addFund($data))
		{
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=funding&package_id='.JRequest::getVar('package_id').'&presentation_id='.JRequest::getVar('presentation_id'), false));
		}else
		{
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=funding&package_id='.JRequest::getVar('package_id').'&presentation_id='.JRequest::getVar('presentation_id'), false),'Error while process New data');
		}
	}
	
	public function save()
	{
		$model	= $this->getModel('funding');
	}
	
	public function edit()
	{
		$model	= &$this->getModel('fundingadd');
		
		$document = JFactory::getDocument();
		
		$vLayout = JRequest::getCmd('layout', 'default');
		
		$vName = 'fundingedit';
		
		$vType = $document->getType();
		
		$view = $this->getView($vName, $vType);
		
		// Set the layout
		$view->setLayout($vLayout);
		
		// Display the view
		$view->display();
	}
	
	public function cancel()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=funding&package_id='.JRequest::getVar('package_id').'&presentation_id='.JRequest::getVar('presentation_id'), false));
	}
	
	public function delete()
	{
		$model	= $this->getModel('funding');
		
		$cids 	= JRequest::getVar('cid');
		
		foreach($cids as $cid){
			
			$return = $model->delete($cid);
			
		}
		
		if($return){
			
			$msg 	= count($cids)." data deleted";
			
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=funding&presentation_id='.JRequest::getVar('presentation_id').'&package_id='.JRequest::getVar('package_id'), false),$msg);
		
		}
		
	}
	
	public function publish()
	{
		$model	= $this->getModel('funding');
		
		$cids	= JRequest::getVar('cid');
		
		foreach($cids as $cid){
			
			$return 	= $model->publish($cid);
			
		}
		
		if($return){
			
			$msg		= count($cids)." Data published";
			
		}else{
			
			$msg		="No data published";
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=funding&presentation_id='.JRequest::getVar('presentation_id').'&package_id='.JRequest::getVar('package_id'), false),$msg);
	}
	
	public function unpublish()
	{
		$model	= $this->getModel('funding');
		
		$cids	= JRequest::getVar('cid');
		
		foreach($cids as $cid){
			
			$return 	= $model->unpublish($cid);
			
		}
		
		if($return){
			
			$msg		= count($cids)." Data Unpublished";
			
		}else{
			
			$msg		="No data published";
		}
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=funding&presentation_id='.JRequest::getVar('presentation_id').'&package_id='.JRequest::getVar('package_id'), false),$msg);
	}
	
	public function fundingadd_edit()
	{
		$model 	= &$this->getModel('fundingadd');
		
		$cids 	= JRequest::getVar('cid');
		
		foreach($cids as $cid){
			
			if($model->UnlockRevenue($cid)){
				
				$return =1;
			
			}
		}
		if($return){
			
			$msg='Edit unlocked';
			
		}else{
			
			$msg='Cant to process edit';
			
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=funding&view=fundingadd&presentation_id='.JRequest::getVar('presentation_id').'&editdata=1&funding_id='.JRequest::getVar('funding_id').'&package_id='.JRequest::getVar('package_id'), false),$msg);
	}
	
	public function fundingadd_apply()
	{
		$model				= 	$this->getModel('fundingadd');
		
		$data['session']		=	JRequest::getVar('funding_session');
		
		$data['funding_desc']		=	JRequest::getVar('funding_desc');
		
		$data['funding_id']		= 	JRequest::getVar('funding_id');
		
		$revenue_percentage		=	JRequest::getVar('revenue_percentage');
		
		$revenue_fromprize		=	JRequest::getVar('revenue_fromprize');
		
		$revenue_toprize		=	JRequest::getVar('revenue_toprize');
		
		$revenue_strategy		=	JRequest::getVar('revenue_strategy');
		
		$revenue_id			=	JRequest::getVar('revenue_id');
		
		$j				= 	0;
		
		if($data['session']!=""){
			
			if($model->updateFunding($data)){
				$msg='Funding updated';
			}
		
		}
		
		if(count($revenue_percentage)>0):
		
			foreach($revenue_percentage as $percentage){
				
				$data['revenue_percentage'] 	= $percentage;
				
				$data['revenue_fromprize']	= $revenue_fromprize[$j];
				
				$data['revenue_toprize']	= $revenue_toprize[$j];
				
				$data['revenue_strategy']	= $revenue_strategy[$j];
				
				$data['revenue_id']		= $revenue_id[$j];
				
				$return = $model->saveRevenue($data);
				
				//echo $data['revenue_id'];
				
				$j++;
			}
		endif;
		
		if($return){
			
			$msg	= "The data are open now for editing";
			
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=fundingadd&funding_id='.JRequest::getVar('funding_id').'&presentation_id='.JRequest::getVar('presentation_id').'&package_id='.JRequest::getVar('package_id'), false),$msg);
		
		}else{
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=fundingadd&funding_id='.JRequest::getVar('funding_id').'&presentation_id='.JRequest::getVar('presentation_id').'&package_id='.JRequest::getVar('package_id'), false),$msg);
		}
	}
	
	public function fundingadd_save()
	{
		$model				= 	$this->getModel('fundingadd');
		
		$data['session']		=	JRequest::getVar('funding_session');
		
		$data['funding_desc']		=	JRequest::getVar('funding_desc');
		
		$data['funding_id']		= 	JRequest::getVar('funding_id');
		
		$revenue_percentage		=	JRequest::getVar('revenue_percentage');
		
		$revenue_fromprize		=	JRequest::getVar('revenue_fromprize');
		
		$revenue_toprize		=	JRequest::getVar('revenue_toprize');
		
		$revenue_strategy		=	JRequest::getVar('revenue_strategy');
		
		$revenue_id			=	JRequest::getVar('revenue_id');
		
		$data['package_id']		= 	JRequest::getVar('package_id');
		
		$j				= 	0;
		
		if($data['session']!=""){
			
			if($model->updateFunding($data)){
				$msg	= 'Funding edited';
			}
		
		}
		
		if(count($revenue_percentage)>0):
		
			foreach($revenue_percentage as $percentage){
				
				$data['revenue_percentage'] 	= $percentage;
				
				$data['revenue_fromprize']	= $revenue_fromprize[$j];
				
				$data['revenue_toprize']	= $revenue_toprize[$j];
				
				$data['revenue_strategy']	= $revenue_strategy[$j];
				
				$data['revenue_id']		= $revenue_id[$j];
				
				$return = $model->saveRevenue($data);
				
				$j++;
			}
		endif;
		
		if($return){
			
			$msg	= "Revenue has been created";
			
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=funding&presentation_id='.JRequest::getVar('presentation_id').'&package_id='.JRequest::getVar('package_id'), false),$msg);
		
		}else{
			
			$msg	= "Error revenue created";
			
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=funding&presentation_id='.JRequest::getVar('presentation_id').'&package_id='.JRequest::getVar('package_id'), false),$msg);
		}
	}
	
	public function fundingadd_delete(){
		
		$model	= $this->getModel('fundingadd');
		
		$cids 	= JRequest::getVar('cid');
		
		foreach($cids as $cid){
			
			$return = $model->DeleteRevenue($cid);
		
		}
		if($return){
			$msg 	= count($cids)." Data has been deleted";
			
			$link	= 'index.php?option=com_awardpackage&view=funding&view=fundingadd&presentation_id='.JRequest::getVar('presentation_id').'&funding_id='.JRequest::getVar('funding_id').'&package_id='.JRequest::getVar('package_id');
		}else{
			$msg 	= 'No data to delete';
			
			$link	= 'index.php?option=com_awardpackage&view=funding&view=fundingadd&presentation_id='.JRequest::getVar('presentation_id').'&funding_id='.JRequest::getVar('funding_id').'&package_id='.JRequest::getVar('package_id');
		}
		
		$this->setRedirect($link,$msg);
	}
	
	public function fundingadd_add()
	{
		$model		= $this->getModel('fundingadd');
		
		$_funding_id	= JRequest::getVar('funding_id'); 
		
		if($model->addRevenue($_funding_id)){
			
			$link 	= 'index.php?option=com_awardpackage&view=fundingadd&presentation_id='.JRequest::getVar('presentation_id').'&editdata=1&funding_id='.$_funding_id.'&package_id='.JRequest::getVar('package_id');	
		
		}
		
		$this->setRedirect($link);
	}
}