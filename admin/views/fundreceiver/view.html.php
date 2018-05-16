<?php 
//redirect
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
class awardpackageViewfundreceiver extends JViewLegacy {
	
	function display($tpl = null) {
		CommunitySurveysHelper::initiate();
		$app = JFactory::getApplication();		
		$model = & JModelLegacy::getInstance( 'fundreceiver', 'AwardpackageModel' );
		$package_id = JRequest::getVar("package_id");	
		switch ($this->action){
			case 'list':
			
				$list_category = $model->get_fund_receiver1($package_id );
				$this->assignRef('list_category', $list_category);
				JToolBarHelper::title(JText::_('Fund Receiver Plan List'), 'logo.png');
				JToolbarHelper::back('Close', 'index.php?option=com_awardpackage&package_id=' . JRequest::getVar('package_id'));

				break;
			case 'create':
				JToolBarHelper::title(JText::_('New Fund Receiver Plan'), 'logo.png');
				//$title = '';
				if(JRequest::getVar('id') != null) {
				       $id=JRequest::getVar('id');
				}
				$this->ug          = JModelLegacy::getInstance('usergroup', 'AwardpackageModel');
      			$country_list = $this->ug->selectCountryForUserGroup(JRequest::getVar('package_id'));
				$this->assignRef('countries', $country_list);
		
				$result = $model->get_fund_receiver_byid($id);
				foreach ($result as $rows){
				 $title = $rows->title;
				 $filter = $rows->filter;
				 $randoma = $rows->randoma;
				 $randomb = $rows->randomb;
				 $randomc = $rows->randomc;
				}
				
				$listfundreceiver = $model->get_fund_receiver_list_byid($id);
				$this->assignRef('result', $listfundreceiver);
				$this->assignRef('title', $title);
				$this->assignRef('filter', $filter);
				$this->assignRef('randoma', $randoma);
				$this->assignRef('randomb', $randomb);
				$this->assignRef('randomc', $randomc);

				JToolBarHelper::custom('fundreceiver.save_create', 'copy', 'copy', 'Save & Close', false);
//JToolbarHelper::back('Save & Close', 'index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.save_create&package_id='.JRequest::getVar('package_id'));
				JToolbarHelper::back('Cancel', 'index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.get_fundreceiver&package_id='.JRequest::getVar('package_id'));
				 $document = JFactory::getDocument();

        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.min.js');
        $document->addStyleSheet(JURI::base() . 'components/com_awardpackage/assets/css/jquery.ui.all.css');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.core.js');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.widget.js');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/jquery.ui.tabs.js');
        $document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/tabs.js');
				break;				
		}
       
		parent::display($tpl);
		
	}	
}