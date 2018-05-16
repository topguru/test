<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class AwardpackageControllerFundreceiver extends JControllerLegacy {

	function __construct(){
		parent::__construct();
	}

	public function get_fundreceiver(){	
		$view = $this->getView('fundreceiver', 'html');
		$view->assign('action', 'list');
		$view->display();
	}
	
	public function create_update(){
		$view = $this->getView('fundreceiver', 'html');		
		$view->assign('action', 'create');
		$view->display('create');
	}
	
	public function save_create(){
		$package_id = JRequest::getVar('package_id');
		$id = JRequest::getVar('id');
		$title = JRequest::getVar('title');
		$filter = JRequest::getVar('filter');	
		$randoma = JRequest::getVar('randoma');	
		$randomb = JRequest::getVar('randomb');	
		$randomc = JRequest::getVar('randomc');	
		$randomd = JRequest::getVar('randomd');	
		$randome = JRequest::getVar('randome');	

		$selecta = JRequest::getVar('selecta');	
		$selectb = JRequest::getVar('selectb');	
		$selectc = JRequest::getVar('selectc');	
		$selectd = JRequest::getVar('selectd');	
		$selecte = JRequest::getVar('selecte');	
		
		$model = & JModelLegacy::getInstance( 'fundreceiver', 'AwardpackageModel' );
		if($model->save_create_fund ($id, $package_id, $title, $filter, $receiver, $gender, $randoma, $randomb, $randomc, $randomd, $randome)) {
		//$model->save_update_category($id, $package_id, $title, $filter, $receiver, $from_year, $to_year, $from_month, $to_month, $from_day, $to_day, $gender, $randoma, $randomb, $randomc, $randomd, $randome);
			$this->setRedirect('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.get_fundreceiver&package_id='.JRequest::getVar('package_id'), JText::_('MSG_SUCCESS'));
		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.get_fundreceiver&package_id='.JRequest::getVar('package_id'), JText::_('Error'));
		}
	}
	
	public function onAddAge(){
		$package_id = JRequest::getVar('package_id');
		$title = JRequest::getVar('title');
		$filter = JRequest::getVar('filter');	
		$receiver = JRequest::getVar('receiver');	
		$from_year =JRequest::getVar('from_year');	
		$to_year =JRequest::getVar('to_year');	
		$from_month =JRequest::getVar('from_month');	
		$to_month =JRequest::getVar('to_month');	
		$from_day =JRequest::getVar('from_day');	
		$to_day =JRequest::getVar('to_day');
		$gender = JRequest::getVar('gender');
		$randoma = JRequest::getVar('randoma');	
		$randomb = JRequest::getVar('randomb');	
		$randomc = JRequest::getVar('randomc');	
		$randomd = JRequest::getVar('randomd');	
		$randome = JRequest::getVar('randome');	

		$selecta = JRequest::getVar('selecta');	
		$selectb = JRequest::getVar('selectb');	
		$selectc = JRequest::getVar('selectc');	
		$selectd = JRequest::getVar('selectd');	
		$selecte = JRequest::getVar('selecte');	

		$model = & JModelLegacy::getInstance( 'fundreceiver', 'AwardpackageModel' );

		$id = JRequest::getVar('id');	

		
//$k = array_rand($array);
//$v = $array[$k];
if ($randomc == '1'){
		$fromday = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,24,26,27,28,29,30,31);
		$from_day = $fromday[array_rand($fromday, 1)];
		$todays = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,24,26,27,28,29,30,31);
		$to_day = $todays[array_rand($todays, 1)];
	}
if ($randomb == '1'){		
		$tomonth = array(1,2,3,4,5,6,7,8,9,10,11,12);
		$to_month = $tomonth[array_rand($tomonth, 1)];
		$frommonth = array(1,2,3,4,5,6,7,8,9,10,11,12);
		$from_month = $frommonth[array_rand($frommonth, 1)];
		}
if ($randoma == '1'){		
		$toyear = array(2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015);
		$to_year = $toyear[array_rand($toyear, 1)];
		$fromyear = array(2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015);
		$from_year = $fromyear[array_rand($fromyear, 1)];
		}
		
if ($title ==''){
$this->setRedirect('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.create_update&package_id='.JRequest::getVar("package_id").'&id='.JRequest::getVar("id"),  JText::_('Empty Title'));
}else {

		if($model->save_create_fund ($id, $package_id, $title, $filter, $receiver, $gender, $randoma, $randomb, $randomc, $randomd, $randome)){
		if (empty($id))
		{
			$rows = $model->get_fundreceiver_id();
			foreach ($rows as $row){
			$id = $row->criteria_id;
						}
		}

$model->save_update_fundreceiver($id, $package_id, $title, $filter, $receiver, $from_year, $to_year, $from_month, $to_month, $from_day, $to_day, $gender, $randoma, $randomb, $randomc, $randomd, $randome);

if ( ($selecta == '2') || ($selectb == '2') || ($selectc == '2') ){


	if ($randomc == '1'){
		$fromday = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,24,26,27,28,29,30,31);
		$from_day = $fromday[array_rand($fromday, 1)];
		$todays = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,24,26,27,28,29,30,31);
		$to_day = $todays[array_rand($todays, 1)];
	}
	if ($randomb == '1'){		
		$tomonth = array(1,2,3,4,5,6,7,8,9,10,11,12);
		$to_month = $tomonth[array_rand($tomonth, 1)];
		$frommonth = array(1,2,3,4,5,6,7,8,9,10,11,12);
		$from_month = $frommonth[array_rand($frommonth, 1)];
		}
	if ($randoma == '1'){		
		$toyear = array(2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015);
		$to_year = $toyear[array_rand($toyear, 1)];
		$fromyear = array(2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015);
		$from_year = $fromyear[array_rand($fromyear, 1)];
		}
					$model->save_update_fundreceiver($id, $package_id, $title, $filter, $receiver, $from_year, $to_year, $from_month, $to_month, $from_day, $to_day, $gender, $randoma, $randomb, $randomc, $randomd, $randome);
					}
$id =(!empty(JRequest::getVar("id")) ? JRequest::getVar("id") : $id);	
if ( ($randoma == '1') || ($randomb == '1') || ($randomc == '1') ){
	$this->setRedirect('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.create_update&title='.JRequest::getVar("title").'&filter='.JRequest::getVar("filter").'&from_year='.JRequest::getVar("from_year").'&to_year='.JRequest::getVar("to_year").'&from_month='.JRequest::getVar("from_month").'&to_month='.JRequest::getVar("to_month").'&from_day='.JRequest::getVar("from_day").'&to_day='.JRequest::getVar("to_day").'&id='.$id.'&package_id='.JRequest::getVar("package_id"),  JText::_('MSG_SUCCESS'));
}else
	{		
	$this->setRedirect('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.create_update&title='.JRequest::getVar("title").'&filter='.JRequest::getVar("filter").'&from_year='.JRequest::getVar("from_year").'&to_year='.JRequest::getVar("to_year").'&from_month='.JRequest::getVar("from_month").'&to_month='.JRequest::getVar("to_month").'&from_day='.JRequest::getVar("from_day").'&to_day='.JRequest::getVar("to_day").'&id='.$id.'&package_id='.JRequest::getVar("package_id"),  JText::_('MSG_SUCCESS'));
	}

		} else {
			$this->setRedirect('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.create_update&title='.JRequest::getVar("title").'&filter='.JRequest::getVar("filter").'&from_year='.JRequest::getVar("from_year").'&to_year='.JRequest::getVar("to_year").'&from_month='.JRequest::getVar("from_month").'&to_month='.JRequest::getVar("to_month").'&from_day='.JRequest::getVar("from_day").'&to_day='.JRequest::getVar("to_day").'&id='.$id.'&package_id='.JRequest::getVar("package_id"),  JText::_('Error'));
		}
		}
	}
	
	public function publish_list(){
		$return = $this->change_state(1);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.get_fundreceiver&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function unpublish_list(){
		$return = $this->change_state(0);
		$msg = $return == 1 ? JText::_('MSG_SUCCESS') : ($return == 0 ? JText::_('MSG_ERROR') : JText::_('MSG_NO_ITEM_SELECTED'));		
		$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.get_fundreceiver&package_id='.JRequest::getVar("package_id"), false), $msg);
	}
	
	public function onDeleteAge(){
		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		if(!empty($ids['cid'])){
			$model = & JModelLegacy::getInstance( 'fundreceiver', 'AwardpackageModel' );
			JArrayHelper::toInteger($ids['cid']);
			if($model->delete_categories2($ids['cid'])){
						$this->setRedirect('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.create_update&title='.JRequest::getVar("title").'&filter='.JRequest::getVar("filter").'&from_year='.JRequest::getVar("from_year").'&to_year='.JRequest::getVar("to_year").'&from_month='.JRequest::getVar("from_month").'&to_month='.JRequest::getVar("to_month").'&from_day='.JRequest::getVar("from_day").'&to_day='.JRequest::getVar("to_day").'&id='.JRequest::getVar("id").'&package_id='.JRequest::getVar("package_id"),  JText::_('MSG_SUCCESS'));
			} else {
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.create_update&title='.JRequest::getVar("title").'&filter='.JRequest::getVar("filter").'&from_year='.JRequest::getVar("from_year").'&to_year='.JRequest::getVar("to_year").'&from_month='.JRequest::getVar("from_month").'&to_month='.JRequest::getVar("to_month").'&from_day='.JRequest::getVar("from_day").'&to_day='.JRequest::getVar("to_day").'&id='.JRequest::getVar("id").'&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_ERROR'));
			}
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.create_update&title='.JRequest::getVar("title").'&filter='.JRequest::getVar("filter").'&from_year='.JRequest::getVar("from_year").'&to_year='.JRequest::getVar("to_year").'&from_month='.JRequest::getVar("from_month").'&to_month='.JRequest::getVar("to_month").'&from_day='.JRequest::getVar("from_day").'&to_day='.JRequest::getVar("to_day").'&id='.JRequest::getVar("id").'&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_NO_ITEM_SELECTED'));
		}
	}
	
	public function delete_fundreceiver(){
		$app = JFactory::getApplication();
		$ids = $app->input->post->getArray(array('cid'=>'array'));
		if(!empty($ids['cid'])){
			$model = & JModelLegacy::getInstance( 'fundreceiver', 'AwardpackageModel' );
			JArrayHelper::toInteger($ids['cid']);
			if($model->delete_categories($ids['cid'])){
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.get_fundreceiver&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_SUCCESS'));
			} else {
				$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.get_fundreceiver&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_ERROR'));
			}
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.get_fundreceiver&package_id='.JRequest::getVar("package_id"), false), JText::_('MSG_NO_ITEM_SELECTED'));
		}
	}
	
	public function add_fundreceiver(){
		$this->setRedirect('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.create_update&package_id='.JRequest::getVar("package_id"), null);
	}
	
	public function change_state($state){
		$app = JFactory::getApplication();
		$ids = $app->input->getArray(array('cid'=>'array'));		
		if(!empty($ids['cid'])){				
			$model = & JModelLegacy::getInstance( 'fundreceiver', 'AwardpackageModel' );
			JArrayHelper::toInteger($ids['cid']);
			$id = implode(',', $ids['cid']);
			$ret = $model->set_status($id, $state);
			if($model->set_status($id, $state)){		
				return 1;
			} else {
				return 0;
			}
		}
		
		return -1;
	}
	
	public function save_and_close(){
		$this->setRedirect('index.php?option=com_awardpackage&package_id='.JRequest::getVar("package_id"), null);
	}
	
	public function onaddyear(){
		$this->setRedirect('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.create_update&title='.JRequest::getVar("title").'&filter='.JRequest::getVar("filter").'&from_year='.JRequest::getVar("from_year").'&to_year='.JRequest::getVar("to_year").'&package_id='.JRequest::getVar("package_id").'&id='.JRequest::getVar("id"), null);
	}
	
	public function onaddmonth(){
		$this->setRedirect('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.create_update&title='.JRequest::getVar("title").'&filter='.JRequest::getVar("filter").'&from_year='.JRequest::getVar("from_year").'&to_year='.JRequest::getVar("to_year").'&from_month='.JRequest::getVar("from_month").'&to_month='.JRequest::getVar("to_month").'&package_id='.JRequest::getVar("package_id").'&id='.JRequest::getVar("id"), null);
	}
	public function onaddday(){
		$this->setRedirect('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.create_update&title='.JRequest::getVar("title").'&filter='.JRequest::getVar("filter").'&from_year='.JRequest::getVar("from_year").'&to_year='.JRequest::getVar("to_year").'&from_month='.JRequest::getVar("from_month").'&to_month='.JRequest::getVar("to_month").'&from_day='.JRequest::getVar("from_day").'&to_day='.JRequest::getVar("to_day").'&package_id='.JRequest::getVar("package_id").'&id='.JRequest::getVar("id"), null);
	}
	
		public function ongender(){
		$this->setRedirect('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.create_update&title='.JRequest::getVar("title").'&filter='.JRequest::getVar("filter").'&gender='.JRequest::getVar("gender").'&package_id='.JRequest::getVar("package_id").'&id='.JRequest::getVar("id"), null);
	}
}