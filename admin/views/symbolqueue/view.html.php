<?php 
//redirect
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class awardpackageViewsymbolqueue extends JViewLegacy {
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();		
		$total = 20;
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
				
		$model = & JModelLegacy::getInstance( 'symbolqueue', 'AwardpackageModel' );
		$package_id = JRequest::getVar("package_id");	
		$criteria_id = JRequest::getVar("criteria_id");	
		$total = 50;//$model->getDonationHistoryTotal($userId, $packageId);

				
		switch ($this->action){
			case 'list':
			$group = JRequest::getVar('groupId');
			    $result = $model->get_symbolqueue($group, $limit,$limitstart);
				//var_dump($result);
				$symbols = array();
				if(!empty($result['symbols'])) {
					$symbols = $result['symbols'];
				}
				$this->assignRef('symbols', $symbols);
				
				$list_users = $model->get_users($package_id);
				$this->assignRef('users', $list_users);
				JToolBarHelper::title(JText::_('Symbol Queue List'), 'logo.png');
				
				JToolBarHelper::addNew('symbolqueue.create_update');			
				JToolBarHelper::deleteList('','symbolqueue.delete_symbolqueue');
				JToolBarHelper::custom('symbolqueue.save_create', 'copy', 'copy', 'Save & Close', false);
				break;
			case 'userlist':
			    $result = $model->get_symbolqueue();
				$symbols = array();
				if(!empty($result['symbols'])) {
					$symbols = $result['symbols'];
				}
				$this->assignRef('symbols', $symbols);
				
				$list_users = $model->get_users($package_id);
				$this->assignRef('users', $list_users);
				JToolBarHelper::title(JText::_('User List'), 'logo.png');
				
				JToolBarHelper::custom('symbolqueue.save_userlist', 'copy', 'copy', 'Save & Close', false);

				break;	
			case 'create':
				if(JRequest::getVar('processId') != null) {
					$processId = JRequest::getVar('processId');
				}
				JToolBarHelper::title(JText::_('New Symbol Queue'), 'logo.png');				
				JToolBarHelper::custom('symbolqueue.save_create', 'copy', 'copy', 'Save & Close', false);
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.get_symbolqueue&groupId='.JRequest::getVar('groupId').'&package_id='.JRequest::getVar('package_id'));
				break;			
			case 'view':
   		        $id = '0';
				if(JRequest::getVar('id') != null) {
					$id = JRequest::getVar('id');
				}
				$result = $model->get_symbolqueue_byid($id,$limit,$limitstart);
				foreach ($result as $row){
					$name = $row->symbol_name;				
				}
				$jumlah = count($result);
				$this->assignRef('fundprizes', $result);
				$this->assignRef('jumlah', $jumlah);
			    JToolBarHelper::title(JText::_('Symbol Queue of '.$name), 'logo.png');				
				JToolbarHelper::back('Close', 'index.php?option=com_awardpackage&view=symbolqueue&task=symbolqueue.get_symbolqueue&groupId='.JRequest::getVar('groupId').'&package_id='.JRequest::getVar('package_id'));
				break;			
		}
		parent::display($tpl);
	}	
}