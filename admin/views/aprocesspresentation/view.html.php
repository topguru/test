<?php
/**
 * @version		$Id: view.html.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quizzes
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );
jimport('joomla.html.pagination');

//require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
class AwardpackageViewAprocesspresentation extends JViewLegacy {	
	var $presentation_ids;
	
	function display($tpl = null) {	
		//CommunitySurveysHelper::initiate();			
		JToolBarHelper::title(JText::_('Create Process Presentation'), 'logo.png');
		$document = JFactory::getDocument();
        $document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js');
		$app = JFactory::getApplication();	
		/*$document->addStyleSheet(JURI::base().'/components/com_awardpackage/assets/css/jquery-ui.css');
		$document->addStyleSheet(JURI::base().'/components/com_awardpackage/assets/css/jquery.ui.all.css');
		$document->addScript(JURI::base().'/components/com_awardpackage/assets/js/jquery-ui.js');
		$document->addScript(JURI::base().'/components/com_awardpackage/assets/js/make-tabs.js');*/
		$document->addStyleSheet(JURI::base().'/components/com_awardpackage/assets/css/make-tabs.css');
		$total = 10;
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
	/*	
		$document->addScript("http://code.jquery.com/jquery-1.9.1.js");
		$document->addScript("http://code.jquery.com/ui/1.10.3/jquery-ui.js");*/
		
		/* Code Added By Sushil on 01-12-2015 */
		$idUserGroupsId = JRequest::getVar('idUserGroupsId');
		$index = JRequest::getVar('index');
		$processPresentation = JRequest::getVar('processPresentation');
		$var_id = JRequest::getVar('var_id');
		
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		switch ($this->action){
			case 'showProcessPresentation' :
			if ( JRequest::getVar('command')== '1'){
				}
				
			if(JRequest::getVar('process_id') != null){
     			$id = JRequest::getVar('process_id');
				$presentations = $model->getProcessPresentationDetail($id,JRequest::getVar('package_id'));
				$list_presentations = $model->getDetailPresentationProcess_List($id,JRequest::getVar('package_id'));
				$valueFrom = $model->getProcessPresentationDetailValueFrom($id,JRequest::getVar('package_id'));
				foreach ($valueFrom as $row){
					$valuePrizeFrom = $row->selected_prize;
				}
				$valueTo = $model->getProcessPresentationDetailValueTo($id,JRequest::getVar('package_id'));
				foreach ($valueTo as $rows){
					$valuePrizeTo = $rows->selected_prize;
				}
				$PrizeSelected = $model->getProcessPresentationDetailPrize($id,JRequest::getVar('package_id'));
				$this->assignRef('presentations', $presentations);	
				$detail = $model->getDetailPresentationProcess(JRequest::getVar('package_id'));
				$this->assignRef('detail', $detail);
				foreach ( $presentations as $rows) {
					$selectedPresentations = $rows->selected_presentation;
					}
				$groupId = JRequest::getVar('idUserGroupsId');
				$index = JRequest::getVar('index');
				$groupId = empty($groupId) || $groupId == '' ? '0' : $groupId;
				$index = empty($index) || $index == '' ? '0' : $index;
				$this->assignRef('groupId', $groupId);				
				$this->assignRef('index', $index);
				$this->assignRef('selectedPresentation', $selectedPresentations);
				}
				
				
				$symbolfilled = $model->getSymbolFilled();
				$symbols = $model->getSymbols(JRequest::getVar('package_id'));
				$prizes = $model->getPrizes(JRequest::getVar('package_id'));
				$fundprize = $model->getFundPrizes(JRequest::getVar('package_id'));
				$startfundprize = $model->getStartFundPrizes($id,JRequest::getVar('package_id'));
				$awardfund = $model->getAwardFundPlan(JRequest::getVar('package_id'));
				$receiver = $model->getFundReceiverList(JRequest::getVar('package_id'));				
				//$symbolPrizes = $model->getSymbolSymbolPrize(JRequest::getVar('package_id'));
				$prizeSelected = $model->getPrizeById(JRequest::getVar('idPrizeId'), JRequest::getVar('package_id'));
				$symbolSelected = $model->getSymbolById(JRequest::getVar('idSymbolId'), JRequest::getVar('package_id'));
				$symbolPrizes = $model->getSymbolSymbolPrize_2(JRequest::getVar('package_id')); 
				$symbolqueuegroup = $model->getsymbolQueueGroup(JRequest::getVar('package_id')); 
				$this->assignRef('symbolfilled', $symbolfilled);				
				$this->assignRef('symbols', $symbols);
				$this->assignRef('prizes', $prizes);
				$this->assignRef('fundprize', $fundprize);
				$this->assignRef('startfundprize', $startfundprize);
				$this->assignRef('list_presentations', $list_presentations);
				$this->assignRef('awardfund', $awardfund);	
				$this->assignRef('receiver', $receiver);												
				$this->assignRef('symbolPrizes', $symbolPrizes);
				$this->assignRef('prizeSelected', $prizeSelected);
				$this->assignRef('symbolSelected', $symbolSelected);
				$this->assignRef('symbolqueuegroup', $symbolqueuegroup);
				$this->assignRef('valuePrizeFrom', $valuePrizeFrom);				
				$this->assignRef('valuePrizeTo', $valuePrizeTo);	
				$this->assignRef('PrizeSelected', $PrizeSelected);											
				$this->assignRef('pagination', $pagination);


				$result = $model->getProcessPresentationDetailList($id,$package_id,$limitstart,$limit);
				$this->assignRef('result', $result);
				$result2 = $model->getProcessPresentationDetailList($id,$package_id,$limitstart,$limit);
				$this->assignRef('result2', $result2);
				JToolBarHelper::custom('processpresentationlist.get_processpresentationlist', 'copy', 'copy', 'Save & Close', false);

				JToolbarHelper::back('Close', 'index.php?option=com_awardpackage&view=processpresentationlist&task=processpresentationlist.get_processpresentationlist&package_id='.JRequest::getVar('package_id').'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&processPresentation='.$processPresentation.'&var_id='.JRequest::getVar('var_id'));
				break;
			case 'doSelectPresentations' :
				$symbolPrizes = $model->getSymbolSymbolPrize_2(JRequest::getVar('package_id')); 
				if(!empty($this->presentation_ids)){
					$ids = explode(',', $this->presentation_ids);					
					$this->assignRef('ids', $ids);					
				}				
				$this->assignRef('symbolPrizes', $symbolPrizes);
				$this->assignRef('selectedPresentation', JRequest::getVar('selectedPresentation'));
				JToolBarHelper::custom( 'aprocesspresentation.saveSelectedPresentations', 'copy', 'copy', 'Save & Close', true, false );
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.JRequest::getVar('package_id').'&idUserGroupsId='.JRequest::getVar('idUserGroupsId').'&index='.JRequest::getVar('index').'&processPresentation='.JRequest::getVar('processPresentation').'&var_id='.JRequest::getVar('var_id'));
				break;	
			case 'onPrizeQueue':
				$package_id = JRequest::getVar('package_id');
				$index = JRequest::getVar('index');
				$idUserGroupsId = JRequest::getVar('idUserGroupsId');
				$position = JRequest::getVar('position');
				$processPresentation = JRequest::getVar('processPresentation');
				$var_id = JRequest::getVar('var_id');
				$selectedPresentation = JRequest::getVar('selectedPresentation');
				$presentation_id = JRequest::getVar('presentation_id');
				$prizeStatus = JRequest::getVar('prizeStatus');
				
				$symbols = $this->model->getSymbolPresentation($presentation_id, $selectedPresentation);				
				$symbol = $symbols[0];
				
				$result = $this->model->getDistributePrizeQueue($package_id, $processPresentation, $selectedPresentation);
				$this->rows = $result['data'];
				$this->pagination = $result['pagination'];
				$this->lists = $result['lists'];
				
				JToolBarHelper::title(JText::_('Distribute prize queue for user group'), 'logo.png');
				$this->assignRef('prize_value', $symbol->prize_value);
				$this->assignRef('prizeStatus', $prizeStatus);				
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.$package_id.'&idUserGroupsId='.$idUserGroupsId.'&index='.$index.'&processPresentation='.$processPresentation.'&var_id='.$var_id);
				break;	
			case 'onDistributionPrizeQueueHistory':
				$package_id = JRequest::getVar('package_id');
				JToolBarHelper::title(JText::_('Distribute prize queue history - Peter C'), 'logo.png');				
				break;	
			case 'showFundPrizeHistory':
				$package_id = JRequest::getVar('package_id');
				$valuefrom = JRequest::getVar('valuefrom');
				$valueto = JRequest::getVar('valueto');
				$i=1;
				
				$this->assignRef('valuefrom', $valuefrom);				
				$this->assignRef('valueto', $valueto);				
				$this->assignRef('i', $i);				
				JToolBarHelper::title(JText::_('Fund Prize Plan History. Funding value '.$valuefrom.' - '.$valueto));	

				JToolbarHelper::back('Close', 'index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.$package_id.'&idUserGroupsId='.$idUserGroupsId.'&index='.$index.'&processPresentation='.$processPresentation.'&var_id='.$var_id);			
				break;	
			case 'onFundQueue':
				$id = JRequest::getVar('process_id');
				$package_id = JRequest::getVar('package_id');
				$presentations = $model->getProcessPresentationDetail($id,JRequest::getVar('package_id'));
				$receiver = $model->getFundReceiverProcess($id, $package_id);//getFundReceiverList(JRequest::getVar('package_id'));				
				$fundprize = $model->getFundReceiver(JRequest::getVar('package_id'));
				$awardfund = $model->getAwardFundPlan(JRequest::getVar('package_id'));
				
				$this->assignRef('receiver', $receiver);
				$this->assignRef('fundprize', $fundprize);		
				$this->assignRef('awardfund', $awardfund);				
				$this->assignRef('presentations', $presentations);				

				$prize_value = 	JRequest::getVar('value');	
				JToolBarHelper::title('Prize Value $'.$prize_value);	
			
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=aprocesspresentation&task=aprocesspresentation.showProcessPresentation&package_id='.$package_id.'&idUserGroupsId='.$idUserGroupsId.'&index='.$index.'&processPresentation='.$processPresentation.'&var_id='.$var_id);
				break;		
			case 'fund_receiver_list':
			
				if(JRequest::getVar('process_id') != null){
     				$id = JRequest::getVar('process_id');		
				}	
				
				if(JRequest::getVar('cbfilter') != null){
     				$cbfilter = JRequest::getVar('cbfilter');		
				}	
				$package_id = JRequest::getVar('package_id');
				
				$result = $model->getFundReceiverListQueue($id,$package_id,$cbfilter,$limitstart,$limit);
				
				$startfundprize = $model->getStartFundPrizes($id,$package_id );
				//$receiver = $model->getFundReceiverList($package_id);		
				//$awardfund = $model->getAwardFundPlan(JRequest::getVar('package_id'));				
						
				$this->assignRef('result', $result);
				$this->assignRef('startfundprize', $startfundprize);
				$this->assignRef('receiver', $receiver);		
				$this->assignRef('awardfund', $awardfund);				
						
				JToolBarHelper::title(JText::_('Fund Receiver List Queue'), 'logo.png');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id=' . $package_id);
				break;	
			case 'fund_prize_plan':				
				if(JRequest::getVar('process_id') != null){
     				$id = JRequest::getVar('process_id');		
				}	
				$package_id = JRequest::getVar('package_id');
				$result = $model->getProcessPresentationDetailList($id,$package_id,$limitstart,$limit);
				$startfundprize = $model->getStartFundPrizes($id,$package_id );
				$this->assignRef('result', $result);
				$this->assignRef('startfundprize', $startfundprize);

				JToolBarHelper::title(JText::_('Fund Prize Plan'), 'logo.png');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id=' . $package_id);
				break;	
		}
		$this->model = $model;
		parent::display($tpl);
	}
}