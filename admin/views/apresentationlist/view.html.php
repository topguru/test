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

class AwardpackageViewApresentationlist extends JViewLegacy {	
	function display($tpl = null) {		
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();							
		JToolBarHelper::title(JText::_('Presentation User Group List'), 'logo.png');
		$model = & JModelLegacy::getInstance( 'apresentationlist', 'AwardpackageModel' );
		$package_id = JRequest::getVar('package_id');		
		$total = 10;
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;


		switch ($this->action){
			case 'initiate' :	 			

				$id =0;
				$var_id =0;
				$title =0;
				if(JRequest::getVar('var_id') != null) {
					$id = JRequest::getVar('var_id');
					if(JRequest::getVar('title') != null) {
						$title = JRequest::getVar('title');
						$groupsId = $model->getGroupId($title);
							if($groupsId != null){
								$usergroup_id = $groupsId->id;
							}
						$usergroup = $model->getUserGroup($id,$title,$usergroup_id);
						$unlock = 1;
						//$app->redirect('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id=' . JRequest::getVar('package_id').'&command=1');
						}
				}			

				$usergrouplist = $model->SelectUserGroupPresentations($package_id,$limitstart,$limit);				
                $processSymbolId = JRequest::getVar('processSymbolId');
				$userGroupsPresentation = $model->getUserGroupPresentations(JRequest::getVar('package_id'));
				$awardfundplan = $model->getAwardFundPlan(JRequest::getVar('package_id'));
				$proses = $model->getProcessPresentation(JRequest::getVar('package_id'));
				$symbolgroup = $model->getSymbolGroupQueue(JRequest::getVar('package_id'));
				$symbolqueue = $model->getSymbolQueue(JRequest::getVar('package_id'));
				
				$this->assignRef('awardfundplan', $awardfundplan);	
				$this->assignRef('userGroupsPresentation', $userGroupsPresentation);
				$this->assignRef('processPresentation', $processPresentation);
				$this->assignRef('jml_pres', $jml_pres);
				$this->assignRef('presentations', $presentations);
				$this->assignRef('usergroups', $usergroups);
				$this->assignRef('presentationUserGroups', $presentationUserGroups);

				$this->assignRef('selectedGroups', $selectedGroups);
				$this->assignRef('processSymbolId', $processSymbolId);

				$this->assignRef('symbolgroup', $symbolgroup);
				$this->assignRef('symbolqueue', $symbolqueue);
				
				$this->assignRef('proses', $proses);
				$this->assignRef('usergrouplist', $usergrouplist);
				
				$this->assignRef('unlock', $unlock);
				$this->assignRef('usergroupname', $usergroupname);
				$this->assignRef('package_id', $package_id);
				$this->assignRef('title', $title);

				
			
				
				JToolbarHelper::back('Close', 'index.php?option=com_awardpackage&package_id=' . JRequest::getVar('package_id'));
				
				break;
			case 'fund_prize_history':
			    $id = JRequest::getVar('processId');
				$groupId = JRequest::getVar('groupId');
				$usergrouplistId = $model->getProcessPresentationDetail($id,$package_id);//SelectUserGroupPresentationsID($groupId, $package_id,$limitstart,$limit);
				
				$result = $model->getFundPrizeHistory($package_id);
				$this->assignRef('usergrouplistId', $usergrouplistId);
				JToolBarHelper::title(JText::_('Fund Prize History'), 'logo.png');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id=' . $package_id);
				break;
			case 'fund_receiver_list':
				$package_id = JRequest::getVar('package_id');
				$result = $model->getProcessPresentationDetailList($id,$package_id,$limit,$limitstart);
				$this->assignRef('result', $result);
				JToolBarHelper::title(JText::_('Fund Receiver List Queue'), 'logo.png');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id=' . $package_id);
				break;	
				case 'fund_prize_plan':							
				$result = $model->getProcessPresentationDetailList($id,$package_id,$limit,$limitstart);
				$this->assignRef('result', $result);

				JToolBarHelper::title(JText::_('Fund Prize Plan'), 'logo.png');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id=' . $package_id);
				break;	
				case 'prize_status_history':
     			$id = JRequest::getVar('process_id');
				$result = $model->getProcessPresentationDetailList($id,$package_id,$limit,$limitstart);
				$this->assignRef('result', $result);
				JToolBarHelper::title(JText::_('Prize Status History'), 'logo.png');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id=' . $package_id);
				break;	
				case 'symbol_set_status':
				$result = $model->getProcessPresentationDetailList($id,$package_id,$limit,$limitstart);
				$this->assignRef('result', $result);
				JToolBarHelper::title(JText::_('Symbol Set Status History'), 'logo.png');
				JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id=' . $package_id);
				break;	
		}
		$this->model = $model;			
		parent::display($tpl);
	}		
}